<?php

namespace App\Services\External;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OMDbService
{
    private string $apiKey;
    private string $baseUrl = 'http://www.omdbapi.com/';

    public function __construct()
    {
        $this->apiKey = 'd23adc12';
    }

    /**
     * Search movies - return multiple results
     */
    public function searchMovies(string $title): array
    {
        try {
            $response = Http::get($this->baseUrl, [
                'apikey' => $this->apiKey,
                's' => $title,
                'type' => 'movie'
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['Response'] === 'True' && isset($data['Search'])) {
                    return array_map(function ($movie) {
                        return [
                            'imdbid' => $movie['imdbID'] ?? '',
                            'judul' => $movie['Title'] ?? '',
                            'tahun' => $movie['Year'] ?? '',
                            'poster' => $movie['Poster'] !== 'N/A' ? $movie['Poster'] : 'https://via.placeholder.com/300x450?text=No+Poster',
                            'type' => $movie['Type'] ?? 'movie'
                        ];
                    }, $data['Search']);
                }
            }

            return [];
        } catch (\Exception $e) {
            Log::error('OMDb Search Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get movie details by IMDB ID
     */
    public function getMovieById(string $imdbId): ?array
    {
        try {
            $response = Http::get($this->baseUrl, [
                'apikey' => $this->apiKey,
                'i' => $imdbId,
                'type' => 'movie'
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['Response'] === 'True') {
                    return [
                        'imdbid' => $data['imdbID'] ?? '',
                        'judul' => $data['Title'] ?? '',
                        'sinopsis' => $data['Plot'] ?? '',
                        'tahun' => (int)($data['Year'] ?? date('Y')),
                        'pemain' => $data['Actors'] ?? '',
                        'rating' => $data['imdbRating'] ?? '0',
                        'director' => $data['Director'] ?? '',
                        'genre' => $data['Genre'] ?? '',
                        'poster' => $data['Poster'] !== 'N/A' ? $data['Poster'] : 'default.jpg',
                    ];
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('OMDb Detail Error: ' . $e->getMessage());
            return null;
        }
    }
}
