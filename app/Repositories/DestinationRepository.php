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
        return Destination::query()
            ->orderBy('name')
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
            ->orderBy('name')
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

