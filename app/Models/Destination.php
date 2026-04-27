<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'slug',
    'description',
])]
class Destination extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }
}
