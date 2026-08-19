<?php

namespace App\Contracts\Interfaces;

use App\Models\Tour;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TourRepositoryInterface
{
    public function paginateFiltered(array $filters, int $perPage = 12): LengthAwarePaginator;

    public function getFeatured(int $limit = 4): Collection;

    public function adminFeaturedList(): Collection;

    public function adminPaginateNonFeatured(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function findBySlugOrFail(string $slug): Tour;

    /**
     * @param  list<int>  $ids
     * @return Collection<int, Tour>
     */
    public function activeByIds(array $ids): Collection;

    public function getRelated(int $tourId, int $destinationId, int $limit = 4): Collection;

    public function adminPaginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    public function adminCreate(array $data): Tour;

    public function adminUpdate(Tour $tour, array $data): Tour;

    public function adminDelete(Tour $tour): void;
}

