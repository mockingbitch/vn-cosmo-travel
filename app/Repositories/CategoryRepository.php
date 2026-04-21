<?php

namespace App\Repositories;

use App\Contracts\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all(): Collection
    {
        return Category::query()->orderBy('name')->get();
    }
}
