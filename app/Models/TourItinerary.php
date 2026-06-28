<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'tour_id',
    'day',
    'title',
    'description',
    'schedule',
])]
class TourItinerary extends Model
{
    protected function casts(): array
    {
        return [
            'day' => 'integer',
            'schedule' => 'array',
        ];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
