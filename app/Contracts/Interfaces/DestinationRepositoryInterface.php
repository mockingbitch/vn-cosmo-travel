<?php

namespace App\Contracts\Interfaces;

use App\Models\Destination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface DestinationRepositoryInterface
{
    public function all(): Collection;

    /**
     * @return \Illuminate\Support\Collection<int, Destination>
     */
    public function mostPopularByTourCount(int $limit = 4): Collection;

    public function findBySlugOrFail(string $slug): Destination;

    public function adminPaginate(int $perPage = 15): LengthAwarePaginator;

    public function adminCreate(array $data): Destination;

    public function adminUpdate(Destination $destination, array $data): Destination;

    public function adminDelete(Destination $destination): void;
}

