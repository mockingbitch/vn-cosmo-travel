<?php

namespace App\Models;

use App\Support\CurrencyFormatter;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

#[Fillable([
    'title',
    'slug',
    'status',
    'is_featured',
    'featured_sort',
    'description',
    'services',
    'amenities',
    'price',
    'currency',
    'duration',
    'destination_id',
    'created_by',
    'updated_by',
    'thumbnail',
    'thumbnail_media_id',
])]
class Tour extends Model
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_DISABLED = 'disabled';

    public const CURRENCY_USD = 'USD';

    /**
     * @var array<string, array{symbol: string, symbol_before: bool}>
     */
    public const CURRENCIES = [
        self::CURRENCY_USD => ['symbol' => '$', 'symbol_before' => true],
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'duration' => 'integer',
            'is_featured' => 'boolean',
            'featured_sort' => 'integer',
            'services' => 'array',
            'amenities' => 'array',
        ];
    }

    public function currencyCode(): string
    {
        return self::CURRENCY_USD;
    }

    /**
     * Price with USD symbol, e.g. "$1,200".
     */
    public function formattedPrice(): string
    {
        return CurrencyFormatter::format((int) $this->price, $this->currencyCode());
    }

    /**
     * The price row shown wherever a single "headline" price is needed
     * (cards, hero badge, sticky bar). Mirrors the `price` column.
     */
    public function defaultPriceRow(): ?TourPrice
    {
        $rows = $this->prices;

        return $rows->firstWhere('is_default', true) ?? $rows->first();
    }

    /**
     * Admin-defined name of the headline price, e.g. "Price for 2 people".
     */
    public function defaultPriceTypeName(): ?string
    {
        $name = $this->defaultPriceRow()?->label();

        return filled($name) ? $name : null;
    }

    public function hasPriceOptions(): bool
    {
        return $this->prices->count() > 1;
    }

    /**
     * Price rows grouped by their type category, for the public price table.
     *
     * @return Collection<string, Collection<int, TourPrice>>
     */
    public function priceRowsByCategory(): Collection
    {
        return $this->prices
            ->filter(fn (TourPrice $row): bool => $row->priceType !== null)
            ->groupBy(fn (TourPrice $row): string => $row->categoryLabel());
    }

    /**
     * @param  list<string>  $items
     * @return list<string>
     */
    public static function labeledListItems(array $items, string $field): array
    {
        $catalog = $field === 'services'
            ? config('tour_catalog.services', [])
            : config('tour_catalog.amenities', []);
        $prefix = $field === 'services'
            ? 'tour.catalog.service.'
            : 'tour.catalog.amenity.';

        return array_values(array_filter(array_map(
            static function (mixed $item) use ($catalog, $prefix): string {
                if (! is_string($item) || $item === '') {
                    return '';
                }
                if (in_array($item, $catalog, true)) {
                    return __($prefix.$item);
                }

                return $item;
            },
            $items
        ), static fn (string $label): bool => $label !== ''));
    }

    /** @return list<string> */
    public function includedItems(): array
    {
        return is_array($this->services) ? array_values($this->services) : [];
    }

    /** @return list<string> */
    public function excludedItems(): array
    {
        return is_array($this->amenities) ? array_values($this->amenities) : [];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Primary destination — the first of {@see self::destinations()}, kept on the
     * tours table so filters and list columns stay on one indexed column.
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class)
            ->withPivot('sort_order')
            ->orderByPivot('sort_order')
            ->orderBy('destinations.name_en');
    }

    /**
     * Every destination of the tour, primary first; falls back to the primary
     * relation when the pivot has not been loaded/filled.
     *
     * @return Collection<int, Destination>
     */
    public function destinationList(): Collection
    {
        $list = $this->destinations;

        if ($list->isNotEmpty()) {
            return $list;
        }

        return $this->destination !== null ? collect([$this->destination]) : collect();
    }

    /** @return list<string> */
    public function destinationNames(): array
    {
        return $this->destinationList()
            ->map(fn (Destination $destination): string => $destination->localizedName())
            ->all();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function thumbnailMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'thumbnail_media_id');
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(TourItinerary::class)->orderBy('day');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(TourPrice::class)->orderBy('sort_order')->orderBy('id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(TourImage::class)->orderBy('sort_order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
