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

        return $days === 1 ? __('ui.1_day') : __(':count days', ['count' => $days]);
    }

    public function priceLabel(): string
    {
        return $this->tour->formattedPrice();
    }

    /**
     * What the headline price covers, e.g. "Price for 2 people".
     */
    public function priceSuffix(): string
    {
        return $this->tour->defaultPriceTypeName() ?? __('ui.per_person');
    }

    public function priceOptionsLabel(): ?string
    {
        if (! $this->tour->hasPriceOptions()) {
            return null;
        }

        return __('tour.price_options_count', ['count' => $this->tour->prices->count()]);
    }

    public function destinationName(): ?string
    {
        return $this->tour->destination?->localizedName();
    }

    public function thumbnailUrl(): string
    {
        if ($this->tour->thumbnail) {
            return $this->tour->thumbnail;
        }

        return 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1200&q=80';
    }
}

