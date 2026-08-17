<?php

namespace App\Models;

use App\Support\CurrencyFormatter;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
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

    public function images(): HasMany
    {
        return $this->hasMany(TourImage::class)->orderBy('sort_order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
