<?php

namespace App\Services\Admin;

use App\Models\Tour;
use App\Models\TourPriceType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TourPriceTypeAdminService
{
    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return TourPriceType::query()
            ->ordered()
            ->withCount('prices')
            ->paginate($perPage);
    }

    /**
     * Types selectable on a tour form: every active type, plus any type the
     * tour already uses (so a deactivated type never silently drops a row).
     *
     * @return Collection<int, TourPriceType>
     */
    public function selectableFor(?Tour $tour = null): Collection
    {
        $usedIds = $tour?->exists
            ? $tour->prices()->pluck('tour_price_type_id')->all()
            : [];

        return TourPriceType::query()
            ->where(function ($query) use ($usedIds): void {
                $query->where('is_active', true);
                if ($usedIds !== []) {
                    $query->orWhereIn('id', $usedIds);
                }
            })
            ->ordered()
            ->get();
    }

    /**
     * Existing category names, offered as autocomplete on the price type form.
     *
     * @return list<string>
     */
    public function existingCategories(): array
    {
        return TourPriceType::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->all();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): TourPriceType
    {
        return TourPriceType::query()->create($this->normalize($data));
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(TourPriceType $type, array $data): TourPriceType
    {
        $type->update($this->normalize($data));

        return $type->refresh();
    }

    public function isInUse(TourPriceType $type): bool
    {
        return $type->prices()->exists();
    }

    /**
     * Types attached to a tour are kept: deleting one would drop a real price.
     */
    public function delete(TourPriceType $type): bool
    {
        if ($this->isInUse($type)) {
            return false;
        }

        $type->delete();

        return true;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function normalize(array $data): array
    {
        if (array_key_exists('name', $data)) {
            $data['name'] = trim((string) $data['name']);
        }

        if (array_key_exists('category', $data)) {
            $category = trim((string) ($data['category'] ?? ''));
            $data['category'] = $category === '' ? null : $category;
        }

        if (array_key_exists('sort_order', $data)) {
            $data['sort_order'] = max(0, (int) ($data['sort_order'] ?? 0));
        }

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        return $data;
    }
}
