<?php

namespace App\ViewModels;

use App\Models\Tour;

class TourCardViewModel
{
    public function __construct(
        public readonly Tour $tour,
    ) {
    }

    public function title(): string
    {
        return $this->tour->title;
    }

    public function slug(): string
    {
        return $this->tour->slug;
    }

    public function durationLabel(): string
    {
        $days = (int) $this->tour->duration;

        return $days === 1 ? __('1 day') : __(':count days', ['count' => $days]);
    }

    public function priceLabel(): string
    {
        return number_format((int) $this->tour->price).'₫';
    }

    public function destinationName(): ?string
    {
        return $this->tour->destination?->name;
    }

    public function thumbnailUrl(): string
    {
        if ($this->tour->thumbnail) {
            return $this->tour->thumbnail;
        }

        return 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1200&q=80';
    }
}

