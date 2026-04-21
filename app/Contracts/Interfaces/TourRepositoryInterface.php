<?php

namespace App\Contracts\Interfaces;

use App\Models\Tour;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TourRepositoryInterface
{
    public function paginateFiltered(array $filters, int $perPage = 12): LengthAwarePaginator;

    public function getFeatured(int $limit = 4): Collection;

    public function findBySlugOrFail(string $slug): Tour;

    public function getRelated(int $tourId, int $destinationId, int $limit = 4): Collection;

    public function adminPaginate(int $perPage = 15): LengthAwarePaginator;

    public function adminCreate(array $data): Tour;

    public function adminUpdate(Tour $tour, array $data): Tour;

    public function adminDelete(Tour $tour): void;
}

