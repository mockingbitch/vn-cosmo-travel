<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'tour_id',
    'created_by',
    'name',
    'email',
    'phone',
    'travel_date',
    'people_count',
    'note',
    'status',
    'updated_by',
])]
class Booking extends Model
{
    protected function casts(): array
    {
        return [
            'travel_date' => 'date',
            'people_count' => 'integer',
        ];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
