<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Lang;

#[Fillable([
    'name',
    'slug',
    'region',
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

    /**
     * Display name for the current locale: `lang/{locale}/destinations.php` → `name.{slug}` when set, else DB `name`.
     */
    public function localizedName(): string
    {
        $key = "destinations.name.{$this->slug}";

        if (Lang::has($key)) {
            return __($key);
        }

        return (string) $this->name;
    }
}
