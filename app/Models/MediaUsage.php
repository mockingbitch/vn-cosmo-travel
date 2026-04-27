<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'media_id',
    'model_type',
    'model_id',
    'field',
])]
class MediaUsage extends Model
{
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}

