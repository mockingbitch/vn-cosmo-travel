<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tour extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'duration',
        'destination_id',
        'thumbnail',
    ];

    protected $casts = [
        'price' => 'integer',
        'duration' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
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
