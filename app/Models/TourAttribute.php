<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'type',
    'label',
])]
class TourAttribute extends Model
{
    public const TYPE_SERVICE = 'service';

    public const TYPE_AMENITY = 'amenity';
}
