<?php

namespace App\Services\Admin;

use App\Contracts\Interfaces\TourRepositoryInterface;
use App\Models\Media;
use App\Models\Tour;
use App\Models\TourAttribute;
use App\ViewModels\TourCardViewModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TourAdminService
{
    public function __construct(
        private readonly TourRepositoryInterface $tours,
        private readonly TourAttributeService $attributes,
    ) {}

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->tours->adminPaginate($perPage, $filters);
    }

    /**
     * How many tours exist at all — the settings form hides the picker at zero.
     */
    public function pickableCount(): int
    {
        return Tour::query()->count();
    }

    /**
     * One page of tours for the picker screen (search by title/slug).
     */
    public function pickerPage(int $perPage = 20, ?string $search = null): LengthAwarePaginator
    {
        return $this->tours->adminPaginate($perPage, ['q' => (string) $search]);
    }

    /**
     * Compact row used by the tour picker and by the "already picked" list.
     *
     * @return array{id: int, title: string, destination: string, thumbnail: string, price: string, is_active: bool}
     */
    public function pickerPayload(Tour $tour): array
    {
        return [
            'id' => (int) $tour->id,
            'title' => (string) $tour->title,
            'destination' => $tour->destination?->localizedName() ?? '',
            'thumbnail' => (new TourCardViewModel($tour))->thumbnailUrl(),
            'price' => $tour->formattedPrice(),
            'is_active' => $tour->status === Tour::STATUS_ACTIVE,
        ];
    }

    /**
     * Payloads keyed by id for the given tours, disabled ones included: an admin
     * must still see a tour they picked before it was switched off.
     *
     * @param  list<int>  $ids
     * @return array<int, array{id: int, title: string, destination: string, thumbnail: string, price: string, is_active: bool}>
     */
    public function pickerPayloadsById(array $ids): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids), fn (int $id): bool => $id > 0)));
        if ($ids === []) {
            return [];
        }

        return Tour::query()
            ->with(['destination', 'prices.priceType'])
            ->whereIn('id', $ids)
            ->get()
            ->mapWithKeys(fn (Tour $tour): array => [(int) $tour->id => $this->pickerPayload($tour)])
            ->all();
    }

    public function listFeatured(): Collection
    {
        return $this->tours->adminFeaturedList();
    }

    public function paginateNonFeatured(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->tours->adminPaginateNonFeatured($perPage, $filters);
    }

    /**
     * @param  list<array{tour_id: int|string, featured_sort?: int|string|null}>  $rows
     */
    public function updateFeaturedSorts(array $rows): void
    {
        foreach ($rows as $row) {
            $tourId = (int) ($row['tour_id'] ?? 0);
            if ($tourId <= 0) {
                continue;
            }

            $tour = Tour::query()->find($tourId);
            if ($tour === null || ! $tour->is_featured) {
                continue;
            }

            $sortRaw = $row['featured_sort'] ?? null;
            $sort = ($sortRaw === null || $sortRaw === '') ? null : max(0, (int) $sortRaw);

            $this->updateFeatured($tour, true, $sort);
        }
    }

    public function create(array $data): Tour
    {
        $itineraryRows = $this->extractItineraryRows($data);
        $galleryPaths = $this->extractGalleryPaths($data);
        $priceRows = $this->extractPriceRows($data);
        unset($data['itinerary'], $data['gallery'], $data['prices'], $data['default_price_index']);

        $data['duration'] = max(1, count($itineraryRows));

        $data = $this->applyThumbnail($data);
        $data = $this->normalizeTourLists($data);
        $data['currency'] = Tour::CURRENCY_USD;
        $data = $this->applyHeadlinePrice($data, $priceRows);
        $this->syncAttributes($data);
        $data['slug'] = $this->uniqueSlug(null, $data['title']);

        if (($uid = auth()->id()) !== null) {
            $data['created_by'] = $uid;
        }

        $tour = $this->tours->adminCreate($data);
        $this->replaceItineraries($tour, $itineraryRows);
        $this->replaceGalleryImages($tour, $galleryPaths);
        $this->replacePrices($tour, $priceRows);

        return $tour->fresh(['itineraries', 'images', 'prices.priceType']);
    }

    public function updateStatus(Tour $tour, string $status): void
    {
        $data = ['status' => $status];

        if (($uid = auth()->id()) !== null) {
            $data['updated_by'] = $uid;
        }

        $this->tours->adminUpdate($tour, $data);
    }

    public function updateFeatured(Tour $tour, bool $isFeatured, ?int $featuredSort = null): void
    {
        $data = [
            'is_featured' => $isFeatured,
            'featured_sort' => $isFeatured ? $featuredSort : null,
        ];

        if (($uid = auth()->id()) !== null) {
            $data['updated_by'] = $uid;
        }

        $this->tours->adminUpdate($tour, $data);
    }

    public function update(Tour $tour, array $data): Tour
    {
        $itineraryRows = $this->extractItineraryRows($data);
        $galleryPaths = $this->extractGalleryPaths($data);
        $priceRows = $this->extractPriceRows($data);
        unset($data['itinerary'], $data['gallery'], $data['prices'], $data['default_price_index']);

        $data['duration'] = max(1, count($itineraryRows));

        $data = $this->applyThumbnail($data);
        $data = $this->normalizeTourLists($data);
        $data['currency'] = Tour::CURRENCY_USD;
        $data = $this->applyHeadlinePrice($data, $priceRows);
        $this->syncAttributes($data);
        $title = $data['title'] ?? $tour->title;
        $data['slug'] = $this->uniqueSlug(null, $title, $tour->id);

        if (($uid = auth()->id()) !== null) {
            $data['updated_by'] = $uid;
        }

        $this->tours->adminUpdate($tour, $data);
        $this->replaceItineraries($tour, $itineraryRows);
        $this->replaceGalleryImages($tour, $galleryPaths);
        $this->replacePrices($tour, $priceRows);

        return $tour->fresh(['itineraries', 'images', 'prices.priceType']);
    }

    public function delete(Tour $tour): void
    {
        $this->tours->adminDelete($tour);
    }

    /**
     * Resolves thumbnail from library (priority) or manual image URL.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function applyThumbnail(array $data): array
    {
        $hasMediaKey = array_key_exists('thumbnail_media_id', $data);
        $hasUrlKey = array_key_exists('thumbnail', $data);

        if (! $hasMediaKey && ! $hasUrlKey) {
            return $data;
        }

        $mediaRaw = $hasMediaKey ? $data['thumbnail_media_id'] : null;
        $urlRaw = $hasUrlKey ? trim((string) ($data['thumbnail'] ?? '')) : '';

        if ($hasMediaKey) {
            unset($data['thumbnail_media_id']);
        }
        if ($hasUrlKey) {
            unset($data['thumbnail']);
        }

        if ($mediaRaw !== null && $mediaRaw !== '') {
            $media = Media::query()->find((int) $mediaRaw);
            if ($media && $media->isImage()) {
                $data['thumbnail'] = $media->url();
                $data['thumbnail_media_id'] = $media->id;

                return $data;
            }
        }

        $data['thumbnail_media_id'] = null;

        if ($urlRaw !== '') {
            $data['thumbnail'] = $urlRaw;

            return $data;
        }

        $data['thumbnail'] = null;

        return $data;
    }

    /**
     * Normalizes the repeatable price rows: one row per price type, exactly one
     * default. Blank or duplicate rows are dropped.
     *
     * @param  array<string, mixed>  $data
     * @return list<array{tour_price_type_id: int, amount: int, note: string|null, sort_order: int, is_default: bool}>
     */
    private function extractPriceRows(array $data): array
    {
        if (! isset($data['prices']) || ! is_array($data['prices'])) {
            return [];
        }

        $defaultIndex = isset($data['default_price_index']) ? (int) $data['default_price_index'] : 0;

        $rows = [];
        $seenTypes = [];
        foreach (array_values($data['prices']) as $index => $row) {
            if (! is_array($row)) {
                continue;
            }

            $typeId = (int) ($row['tour_price_type_id'] ?? 0);
            if ($typeId <= 0 || in_array($typeId, $seenTypes, true)) {
                continue;
            }
            $seenTypes[] = $typeId;

            $note = isset($row['note']) ? trim((string) $row['note']) : '';

            $rows[] = [
                'tour_price_type_id' => $typeId,
                'amount' => max(0, (int) ($row['amount'] ?? 0)),
                'note' => $note === '' ? null : $note,
                'sort_order' => count($rows),
                'is_default' => $index === $defaultIndex,
            ];
        }

        if ($rows === []) {
            return [];
        }

        $defaults = array_keys(array_filter($rows, static fn (array $row): bool => $row['is_default']));
        if ($defaults === []) {
            $rows[0]['is_default'] = true;
        } else {
            // Keep the first flagged row only, so the headline price is unambiguous.
            foreach (array_slice($defaults, 1) as $extra) {
                $rows[$extra]['is_default'] = false;
            }
        }

        return $rows;
    }

    /**
     * `tours.price` stays the headline amount, so price filters, sorting and
     * cards keep working off a single indexed column.
     *
     * @param  array<string, mixed>  $data
     * @param  list<array{amount: int, is_default: bool}>  $priceRows
     * @return array<string, mixed>
     */
    private function applyHeadlinePrice(array $data, array $priceRows): array
    {
        if ($priceRows === []) {
            return $data;
        }

        foreach ($priceRows as $row) {
            if ($row['is_default']) {
                $data['price'] = $row['amount'];

                return $data;
            }
        }

        $data['price'] = $priceRows[0]['amount'];

        return $data;
    }

    /**
     * @param  list<array{tour_price_type_id: int, amount: int, note: string|null, sort_order: int, is_default: bool}>  $rows
     */
    private function replacePrices(Tour $tour, array $rows): void
    {
        if ($rows === []) {
            return;
        }

        $tour->prices()->delete();
        foreach ($rows as $row) {
            $tour->prices()->create([
                'tour_price_type_id' => $row['tour_price_type_id'],
                'amount' => $row['amount'],
                'currency' => Tour::CURRENCY_USD,
                'note' => $row['note'],
                'sort_order' => $row['sort_order'],
                'is_default' => $row['is_default'],
            ]);
        }
    }

    /**
     * Persists any free-text services/amenities so they are reusable on other tours.
     *
     * @param  array<string, mixed>  $data
     */
    private function syncAttributes(array $data): void
    {
        $this->attributes->sync($data['services'] ?? [], TourAttribute::TYPE_SERVICE);
        $this->attributes->sync($data['amenities'] ?? [], TourAttribute::TYPE_AMENITY);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function normalizeTourLists(array $data): array
    {
        foreach (['services', 'amenities'] as $field) {
            if (! array_key_exists($field, $data)) {
                continue;
            }
            $raw = $data[$field];
            unset($data[$field]);

            if (is_array($raw)) {
                // Keep both canonical catalog keys and free-text custom items, trimmed & deduped.
                $items = array_values(array_unique(array_filter(
                    array_map(static fn ($v) => is_string($v) ? trim($v) : '', $raw),
                    static fn (string $v) => $v !== ''
                )));
                $data[$field] = $items === [] ? null : $items;
            } elseif (is_string($raw)) {
                $data[$field] = $this->linesToList($raw);
            } else {
                $data[$field] = null;
            }
        }

        return $data;
    }

    /**
     * @return list<string>|null
     */
    private function linesToList(?string $raw): ?array
    {
        if ($raw === null || trim($raw) === '') {
            return null;
        }

        $lines = preg_split('/\r\n|\r|\n/', $raw) ?: [];
        $trimmed = array_map(static fn (string $line): string => trim($line), $lines);
        $items = array_values(array_filter($trimmed, static fn (string $line): bool => $line !== ''));

        return $items === [] ? null : $items;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return list<array{day: int, title: string, description: string|null, schedule: list<array{time: string|null, title: string|null, description: string|null}>|null}>
     */
    private function extractItineraryRows(array $data): array
    {
        if (! isset($data['itinerary']) || ! is_array($data['itinerary'])) {
            return [];
        }

        $ordered = [];
        foreach ($data['itinerary'] as $row) {
            if (! is_array($row)) {
                continue;
            }
            $title = isset($row['title']) ? trim((string) $row['title']) : '';
            if ($title === '') {
                continue;
            }
            $desc = isset($row['description']) ? trim((string) $row['description']) : '';
            $ordered[] = [
                'title' => $title,
                'description' => $desc === '' ? null : $desc,
                'schedule' => $this->extractScheduleSlots($row['schedule'] ?? null),
            ];
        }

        $out = [];
        foreach ($ordered as $i => $row) {
            $out[] = [
                'day' => $i + 1,
                'title' => $row['title'],
                'description' => $row['description'],
                'schedule' => $row['schedule'],
            ];
        }

        return $out;
    }

    /**
     * Normalizes the per-day hourly schedule, dropping fully empty slots.
     *
     * @return list<array{time: string|null, title: string|null, description: string|null}>|null
     */
    private function extractScheduleSlots(mixed $raw): ?array
    {
        if (! is_array($raw)) {
            return null;
        }

        $slots = [];
        foreach ($raw as $slot) {
            if (! is_array($slot)) {
                continue;
            }
            $time = isset($slot['time']) ? trim((string) $slot['time']) : '';
            $title = isset($slot['title']) ? trim((string) $slot['title']) : '';
            $desc = isset($slot['description']) ? trim((string) $slot['description']) : '';

            if ($time === '' && $title === '' && $desc === '') {
                continue;
            }

            $slots[] = [
                'time' => $time === '' ? null : $time,
                'title' => $title === '' ? null : $title,
                'description' => $desc === '' ? null : $desc,
            ];
        }

        return $slots === [] ? null : $slots;
    }

    /**
     * @param  list<array{day: int, title: string, description: string|null, schedule: list<array<string, string|null>>|null}>  $rows
     */
    private function replaceItineraries(Tour $tour, array $rows): void
    {
        $tour->itineraries()->delete();
        foreach ($rows as $row) {
            $tour->itineraries()->create([
                'day' => $row['day'],
                'title' => $row['title'],
                'description' => $row['description'],
                'schedule' => $row['schedule'],
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return list<string>
     */
    private function extractGalleryPaths(array $data): array
    {
        $raw = $data['gallery'] ?? null;

        if (is_array($raw)) {
            $paths = [];
            foreach ($raw as $line) {
                $line = trim((string) $line);
                if ($line === '' || strlen($line) > 2048) {
                    continue;
                }
                $paths[] = $line;
            }

            return array_values($paths);
        }

        if (is_string($raw)) {
            $lines = preg_split('/\r\n|\r|\n/', $raw) ?: [];
            $paths = [];
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || strlen($line) > 2048) {
                    continue;
                }
                $paths[] = $line;
            }

            return $paths;
        }

        return [];
    }

    /**
     * @param  list<string>  $paths
     */
    private function replaceGalleryImages(Tour $tour, array $paths): void
    {
        $tour->images()->delete();
        foreach (array_values($paths) as $i => $path) {
            $tour->images()->create([
                'path' => $path,
                'sort_order' => $i,
            ]);
        }
    }

    private function uniqueSlug(?string $slug, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug !== null && $slug !== '' ? $slug : $title);
        if ($base === '') {
            $base = 'tour';
        }

        $candidate = $base;
        $i = 2;
        while (
            Tour::query()
                ->where('slug', $candidate)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $candidate = $base.'-'.$i;
            $i++;
        }

        return $candidate;
    }
}
