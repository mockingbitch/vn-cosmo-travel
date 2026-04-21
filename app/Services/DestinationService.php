<?php

namespace App\Services;

use App\Contracts\Interfaces\DestinationRepositoryInterface;
use App\Models\Destination;
use Illuminate\Support\Collection;

class DestinationService
{
    public function __construct(
        private readonly DestinationRepositoryInterface $destinations,
    ) {
    }

    public function all(): Collection
    {
        return $this->destinations->all();
    }

    public function detail(string $slug): Destination
    {
        return $this->destinations->findBySlugOrFail($slug);
    }
}

