<?php

namespace App\Services\External;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OMDbService
{
    private $apiKey;
    private $baseUrl = 'http://www.omdbapi.com/';
    private $imageUrl = 'http://img.omdbapi.com/';

    public function __construct()
    {
        $this->apiKey = 'd23adc12';
    }

    /**
     * Search movies by title
     */
    public function searchByTitle($title)
    {
        try {
            $response = Http::get($this->baseUrl, [
                'apikey' => $this->apiKey,
                't' => $title,
                'type' => 'movie'
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['Response'] === 'True') {
                    return [
                        'judul' => $data['Title'] ?? '',
                        'sinopsis' => $data['Plot'] ?? '',
                        'tahun' => (int)($data['Year'] ?? date('Y')),
                        'pemain' => $data['Actors'] ?? '',
                        'imdbid' => $data['imdbID'] ?? '',
                        'poster' => $data['Poster'] !== 'N/A' ? $data['Poster'] : 'default.jpg',
                    ];
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('OMDb API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Search movies - return multiple results
     */
    public function searchMovies($title)
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
    public function getMovieById($imdbId)
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

    /**
     * Get poster image download link
     */
    public function getPosterUrl($title)
    {
        try {
            $response = Http::get($this->imageUrl, [
                'apikey' => $this->apiKey,
                't' => $title,
                'type' => 'movie'
            ]);

            if ($response->successful() && $response->headers()['content-type'][0] === 'image/jpeg') {
                return $this->imageUrl . '?apikey=' . $this->apiKey . '&t=' . urlencode($title);
            }

            return 'default.jpg';
        } catch (\Exception $e) {
            Log::error('OMDb Poster Error: ' . $e->getMessage());
            return 'default.jpg';
        }
    }
}
