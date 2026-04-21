<?php

namespace App\Contracts\Interfaces;

use App\Models\HeroBanner;
use Illuminate\Support\Collection;

interface HeroBannerRepositoryInterface
{
    public function currentOrNull(): ?HeroBanner;

    /**
     * @return Collection<int, HeroBanner>
     */
    public function history(int $limit = 50): Collection;

    public function makeCurrent(array $data): HeroBanner;

    public function applyHistory(HeroBanner $historyBanner): HeroBanner;
}
