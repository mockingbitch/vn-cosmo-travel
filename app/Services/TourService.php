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

    public function detail(string $slug): Tour
    {
        return $this->tours->findBySlugOrFail($slug);
    }

    /**
     * @param  list<int>  $ids
     * @return Collection<int, Tour>
     */
    public function byIds(array $ids): Collection
    {
        return $this->tours->activeByIds($ids);
    }

    public function related(Tour $tour, int $limit = 4): Collection
    {
        $destinationIds = $tour->destinationList()->pluck('id')->map(fn ($id): int => (int) $id)->all();

        return $this->tours->getRelated($tour->id, $destinationIds, $limit);
    }
}

