<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function all(): Collection
    {
        return Category::all();
    }
}
