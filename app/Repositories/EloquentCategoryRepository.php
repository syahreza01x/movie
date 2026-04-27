<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function all(): Collection
    {
        return Category::all();
    }
}
