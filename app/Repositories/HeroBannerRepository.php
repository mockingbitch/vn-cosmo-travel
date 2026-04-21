<?php

namespace App\Repositories;

use App\Contracts\Interfaces\HeroBannerRepositoryInterface;
use App\Models\HeroBanner;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class HeroBannerRepository implements HeroBannerRepositoryInterface
{
    public function adminPaginate(int $perPage = 15): LengthAwarePaginator
    {
        return HeroBanner::query()
            ->latest('id')
            ->paginate($perPage);
    }

    public function adminCreate(array $data): HeroBanner
    {
        return HeroBanner::query()->create($data);
    }

    public function adminUpdate(HeroBanner $banner, array $data): HeroBanner
    {
        $banner->update($data);

        return $banner->fresh();
    }

    public function adminDelete(HeroBanner $banner): void
    {
        $banner->delete();
    }

    public function activeOrdered(): Collection
    {
        return HeroBanner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();
    }
}

