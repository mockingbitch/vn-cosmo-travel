<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'tour_id',
        'name',
        'email',
        'phone',
        'travel_date',
        'people_count',
        'note',
        'status',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'people_count' => 'integer',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
