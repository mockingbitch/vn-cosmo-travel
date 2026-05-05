<?php

namespace App\ViewModels;

use App\Models\HeroBanner;

class HomeHeroViewModel
{
    public function __construct(
        private readonly ?HeroBanner $banner,
        private readonly string $locale,
    ) {}

    public function imageUrl(): string
    {
        $url = $this->banner?->imageUrl();

        if (is_string($url) && $url !== '') {
            return $url;
        }

        return asset('images/default-banner.avif');
    }

    public function title(): string
    {
        $title = $this->banner?->getTitleForLocale($this->locale) ?? '';

        return $title !== '' ? $title : (string) __('ui.hero_title');
    }

    public function subtitle(): string
    {
        $subtitle = $this->banner?->getSubtitleForLocale($this->locale);

        return is_string($subtitle) && $subtitle !== ''
            ? $subtitle
            : (string) __('ui.hero_subtitle');
    }

    public function ctaText(): string
    {
        $cta = $this->banner?->getCtaTextForLocale($this->locale);

        return is_string($cta) && $cta !== ''
            ? $cta
            : (string) __('ui.explore_tours');
    }

    public function ctaLink(): string
    {
        $link = $this->banner?->cta_link;

        return is_string($link) && $link !== '' ? $link : route('tours.index');
    }
}
