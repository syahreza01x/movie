<?php

namespace App\Interfaces;

use App\Models\Movie;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MovieRepositoryInterface
{
    public function getHomepagePaginated(?string $search): LengthAwarePaginator;

    public function getLatestPaginated(int $perPage): LengthAwarePaginator;

    public function findById(string $id): ?Movie;

    public function findByIdOrFail(string $id): Movie;

    public function create(array $attributes): Movie;

    public function update(Movie $movie, array $attributes): bool;

    public function delete(Movie $movie): bool;
}
