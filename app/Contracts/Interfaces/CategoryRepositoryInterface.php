<?php

namespace App\Contracts\Interfaces;

use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    public function all(): Collection;
}
