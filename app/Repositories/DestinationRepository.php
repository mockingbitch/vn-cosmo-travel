<?php

namespace App\Repositories;

use App\Contracts\Interfaces\DestinationRepositoryInterface;
use App\Models\Destination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DestinationRepository implements DestinationRepositoryInterface
{
    public function all(): Collection
    {
        $order = config('destination_regions.order', []);

        return Destination::query()
            ->get()
            ->sortBy(function (Destination $d) use ($order) {
                $i = array_search($d->region, $order, true);

                return [is_int($i) ? $i : 99, mb_strtolower($d->name_en ?? '')];
            })
            ->values();
    }

    public function mostPopularByTourCount(int $limit = 4): Collection
    {
        return Destination::query()
            ->withCount('tours')
            ->whereHas('tours')
            ->orderByDesc('tours_count')
            ->orderBy('name_en')
            ->limit($limit)
            ->get();
    }

    public function findBySlugOrFail(string $slug): Destination
    {
        return Destination::query()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function adminPaginate(int $perPage = 15): LengthAwarePaginator
    {
        return Destination::query()
            ->orderBy('region')
            ->orderBy('name_en')
            ->paginate($perPage);
    }

    public function adminCreate(array $data): Destination
    {
        return Destination::query()->create($data);
    }

    public function adminUpdate(Destination $destination, array $data): Destination
    {
        $destination->update($data);

        return $destination->fresh();
    }

    public function adminDelete(Destination $destination): void
    {
        $destination->delete();
    }
}

