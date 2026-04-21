<?php

namespace App\Services;

use App\Contracts\Interfaces\TourRepositoryInterface;
use App\Models\Tour;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TourService
{
    public function __construct(
        private readonly TourRepositoryInterface $tours,
    ) {
    }

    public function paginate(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        return $this->tours->paginateFiltered($filters, $perPage);
    }

    public function featured(int $limit = 4): Collection
    {
        return $this->tours->getFeatured($limit);
    }

    public function detail(string $slug): Tour
    {
        return $this->tours->findBySlugOrFail($slug);
    }

    public function related(Tour $tour, int $limit = 4): Collection
    {
        return $this->tours->getRelated($tour->id, $tour->destination_id, $limit);
    }
}

