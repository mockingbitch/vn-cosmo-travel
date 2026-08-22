<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Lang;

#[Fillable([
    'name_en',
    'name_vi',
    'slug',
    'region',
    'description',
    'created_by',
    'updated_by',
])]
class Destination extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Every tour attached to this destination (a tour may cover several).
     */
    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class)->withPivot('sort_order');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Public label: optional override in `lang/.../destinations.php` (`name.{slug}`), else `name_vi` / `name_en` from DB.
     */
    public function localizedName(): string
    {
        $key = "destinations.name.{$this->slug}";

        if (Lang::has($key)) {
            return __($key);
        }

        return (string) (filled($this->name_en) ? $this->name_en : $this->name_vi);
    }
}
