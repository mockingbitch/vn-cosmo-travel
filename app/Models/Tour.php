<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title',
    'slug',
    'status',
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

    public const CURRENCY_VND = 'VND';

    public const CURRENCY_USD = 'USD';

    /**
     * Supported price currencies. symbol_before = symbol shown before the amount.
     *
     * @var array<string, array{symbol: string, symbol_before: bool}>
     */
    public const CURRENCIES = [
        self::CURRENCY_VND => ['symbol' => '₫', 'symbol_before' => false],
        self::CURRENCY_USD => ['symbol' => '$', 'symbol_before' => true],
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'duration' => 'integer',
            'services' => 'array',
            'amenities' => 'array',
        ];
    }

    /**
     * Currency code, falling back to VND for legacy/empty rows.
     */
    public function currencyCode(): string
    {
        $code = (string) ($this->currency ?? '');

        return array_key_exists($code, self::CURRENCIES) ? $code : self::CURRENCY_VND;
    }

    /**
     * Price with its currency symbol, e.g. "8,990,000₫" or "$1,200".
     */
    public function formattedPrice(): string
    {
        $meta = self::CURRENCIES[$this->currencyCode()];
        $amount = number_format((int) $this->price);

        return $meta['symbol_before'] ? $meta['symbol'].$amount : $amount.$meta['symbol'];
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
