<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Services\OMDbService;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        // Database hanya untuk data baru input dari user
        // Data film sudah tersedia di OMDb API
    }
}
