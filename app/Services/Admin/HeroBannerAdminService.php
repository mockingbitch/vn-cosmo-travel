<?php

namespace App\Services\Admin;

use App\Contracts\Interfaces\HeroBannerRepositoryInterface;
use App\Models\HeroBanner;
use Illuminate\Support\Collection;

class HeroBannerAdminService
{
    public function __construct(
        private readonly HeroBannerRepositoryInterface $banners,
    ) {}

    public function currentOrNull(): ?HeroBanner
    {
        return $this->banners->currentOrNull();
    }

    /**
     * @return Collection<int, HeroBanner>
     */
    public function history(int $limit = 50): Collection
    {
        return $this->banners->history($limit);
    }

    public function applyHistory(HeroBanner $historyBanner): HeroBanner
    {
        return $this->banners->applyHistory($historyBanner);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateCurrent(array $data): HeroBanner
    {
        $data['title'] = (string) ($data['title_en'] ?? '');
        $data['subtitle'] = $data['subtitle_en'] ?? null;
        $data['cta_text'] = $data['cta_text_en'] ?? null;

        $data['title_translations'] = [
            'en' => (string) ($data['title_en'] ?? ''),
            'vi' => (string) ($data['title_vi'] ?? ''),
        ];
        $data['subtitle_translations'] = [
            'en' => $data['subtitle_en'] ?? null,
            'vi' => $data['subtitle_vi'] ?? null,
        ];
        $data['cta_text_translations'] = [
            'en' => $data['cta_text_en'] ?? null,
            'vi' => $data['cta_text_vi'] ?? null,
        ];

        unset(
            $data['title_en'],
            $data['title_vi'],
            $data['subtitle_en'],
            $data['subtitle_vi'],
            $data['cta_text_en'],
            $data['cta_text_vi'],
        );

        return $this->banners->makeCurrent($data);
    }
}
