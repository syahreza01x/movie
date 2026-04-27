<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\MovieRepositoryInterface;
use App\Models\Movie;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentMovieRepository implements MovieRepositoryInterface
{
    public function getHomepagePaginated(?string $search): LengthAwarePaginator
    {
        $query = Movie::latest();

        if ($search) {
            $query->where('judul', 'like', '%' . $search . '%')
                ->orWhere('sinopsis', 'like', '%' . $search . '%');
        }

        return $query->paginate(6)->withQueryString();
    }

    public function getLatestPaginated(int $perPage): LengthAwarePaginator
    {
        return Movie::latest()->paginate($perPage);
    }

    public function findById(string $id): ?Movie
    {
        return Movie::find($id);
    }

    public function findByIdOrFail(string $id): Movie
    {
        return Movie::findOrFail($id);
    }

    public function create(array $attributes): Movie
    {
        return Movie::create($attributes);
    }

    public function update(Movie $movie, array $attributes): bool
    {
        return $movie->update($attributes);
    }

    public function delete(Movie $movie): bool
    {
        return $movie->delete();
    }
}
