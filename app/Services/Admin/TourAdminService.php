<?php

namespace App\Services\Admin;

use App\Contracts\Interfaces\TourRepositoryInterface;
use App\Models\Tour;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class TourAdminService
{
    public function __construct(
        private readonly TourRepositoryInterface $tours,
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->tours->adminPaginate($perPage);
    }

    public function create(array $data): Tour
    {
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? null, $data['title']);

        return $this->tours->adminCreate($data);
    }

    public function update(Tour $tour, array $data): Tour
    {
        $title = $data['title'] ?? $tour->title;
        $slugInput = array_key_exists('slug', $data) ? $data['slug'] : $tour->slug;
        $data['slug'] = $this->uniqueSlug($slugInput, $title, $tour->id);

        return $this->tours->adminUpdate($tour, $data);
    }

    public function delete(Tour $tour): void
    {
        $this->tours->adminDelete($tour);
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
