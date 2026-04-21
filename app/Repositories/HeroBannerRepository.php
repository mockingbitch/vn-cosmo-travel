<?php

namespace App\Repositories;

use App\Contracts\Interfaces\HeroBannerRepositoryInterface;
use App\Models\HeroBanner;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HeroBannerRepository implements HeroBannerRepositoryInterface
{
    private const HISTORY_LIMIT = 3;

    public function adminPaginate(int $perPage = 15): LengthAwarePaginator
    {
        return HeroBanner::query()
            ->latest('id')
            ->paginate($perPage);
    }

    public function activeOrdered(): Collection
    {
        return HeroBanner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();
    }

    public function currentOrNull(): ?HeroBanner
    {
        return HeroBanner::query()
            ->where('is_current', true)
            ->latest('id')
            ->first();
    }

    public function history(int $limit = 50): Collection
    {
        return HeroBanner::query()
            ->where('is_current', false)
            ->orderByDesc('archived_at')
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    public function makeCurrent(array $data): HeroBanner
    {
        return DB::transaction(function () use ($data): HeroBanner {
            $now = Carbon::now();

            HeroBanner::query()
                ->where('is_current', true)
                ->update([
                    'is_current' => false,
                    'archived_at' => $now,
                ]);

            $data['is_current'] = true;
            $data['archived_at'] = null;

            $current = HeroBanner::query()->create($data);

            $this->trimHistory(self::HISTORY_LIMIT);

            return $current;
        });
    }

    public function applyHistory(HeroBanner $historyBanner): HeroBanner
    {
        return DB::transaction(function () use ($historyBanner): HeroBanner {
            if ($historyBanner->is_current) {
                return $historyBanner;
            }

            $data = $historyBanner->only([
                'title',
                'subtitle',
                'title_translations',
                'subtitle_translations',
                'cta_text_translations',
                'image_path',
                'cta_text',
                'cta_link',
                'sort_order',
                'is_active',
            ]);

            $current = $this->makeCurrent($data);

            // makeCurrent already trims history
            return $current;
        });
    }

    private function trimHistory(int $keep): void
    {
        $idsToDelete = HeroBanner::query()
            ->where('is_current', false)
            ->orderByDesc('archived_at')
            ->orderByDesc('id')
            ->skip($keep)
            ->take(1000)
            ->pluck('id');

        if ($idsToDelete->isNotEmpty()) {
            HeroBanner::query()->whereIn('id', $idsToDelete)->delete();
        }
    }
}

