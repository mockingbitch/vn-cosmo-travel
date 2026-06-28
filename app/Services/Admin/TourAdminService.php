<?php

namespace App\Services\Admin;

use App\Contracts\Interfaces\TourRepositoryInterface;
use App\Models\Media;
use App\Models\Tour;
use App\Models\TourAttribute;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    public function create(array $data): Tour
    {
        $itineraryRows = $this->extractItineraryRows($data);
        $galleryPaths = $this->extractGalleryPaths($data);
        unset($data['itinerary'], $data['gallery']);

        $data['duration'] = max(1, count($itineraryRows));

        $data = $this->applyThumbnail($data);
        $data = $this->normalizeTourLists($data);
        $this->syncAttributes($data);
        $data['slug'] = $this->uniqueSlug(null, $data['title']);

        if (($uid = auth()->id()) !== null) {
            $data['created_by'] = $uid;
        }

        $tour = $this->tours->adminCreate($data);
        $this->replaceItineraries($tour, $itineraryRows);
        $this->replaceGalleryImages($tour, $galleryPaths);

        return $tour->fresh(['itineraries', 'images']);
    }

    public function updateStatus(Tour $tour, string $status): void
    {
        $data = ['status' => $status];

        if (($uid = auth()->id()) !== null) {
            $data['updated_by'] = $uid;
        }

        $this->tours->adminUpdate($tour, $data);
    }

    public function update(Tour $tour, array $data): Tour
    {
        $itineraryRows = $this->extractItineraryRows($data);
        $galleryPaths = $this->extractGalleryPaths($data);
        unset($data['itinerary'], $data['gallery']);

        $data['duration'] = max(1, count($itineraryRows));

        $data = $this->applyThumbnail($data);
        $data = $this->normalizeTourLists($data);
        $this->syncAttributes($data);
        $title = $data['title'] ?? $tour->title;
        $data['slug'] = $this->uniqueSlug(null, $title, $tour->id);

        if (($uid = auth()->id()) !== null) {
            $data['updated_by'] = $uid;
        }

        $this->tours->adminUpdate($tour, $data);
        $this->replaceItineraries($tour, $itineraryRows);
        $this->replaceGalleryImages($tour, $galleryPaths);

        return $tour->fresh(['itineraries', 'images']);
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
     * Persists any free-text services/amenities so they are reusable on other tours.
     *
     * @param  array<string, mixed>  $data
     */
    private function syncAttributes(array $data): void
    {
        $this->attributes->sync($data['services'] ?? [], TourAttribute::TYPE_SERVICE);
        $this->attributes->sync($data['amenities'] ?? [], TourAttribute::TYPE_AMENITY);
    }

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
