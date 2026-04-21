<?php

namespace App\Contracts\Interfaces;

use App\Models\HeroBanner;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface HeroBannerRepositoryInterface
{
    public function adminPaginate(int $perPage = 15): LengthAwarePaginator;

    public function adminCreate(array $data): HeroBanner;

    public function adminUpdate(HeroBanner $banner, array $data): HeroBanner;

    public function adminDelete(HeroBanner $banner): void;

    /**
     * @return Collection<int, HeroBanner>
     */
    public function activeOrdered(): Collection;
}

