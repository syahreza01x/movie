<?php

namespace App\Services\Movie;

use App\Interfaces\Repositories\CategoryRepositoryInterface;
use App\Interfaces\Repositories\MovieRepositoryInterface;
use App\Models\Movie;
use App\Services\External\OMDbService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MovieService
{
    private MovieRepositoryInterface $movieRepository;
    private CategoryRepositoryInterface $categoryRepository;
    private OMDbService $omdbService;

    public function __construct(
        MovieRepositoryInterface $movieRepository,
        CategoryRepositoryInterface $categoryRepository,
        OMDbService $omdbService
    )
    {
        $this->movieRepository = $movieRepository;
        $this->categoryRepository = $categoryRepository;
        $this->omdbService = $omdbService;
    }

    public function getHomepageMovies(?string $search): LengthAwarePaginator
    {
        return $this->movieRepository->getHomepagePaginated($search);
    }

    public function getWatchlistMovies(): LengthAwarePaginator
    {
        return $this->movieRepository->getLatestPaginated(6);
    }

    public function getDataMovies(): LengthAwarePaginator
    {
        return $this->movieRepository->getLatestPaginated(10);
    }

    public function findMovie(string $id): ?Movie
    {
        return $this->movieRepository->findById($id);
    }

    public function getCategories(): Collection
    {
        return $this->categoryRepository->all();
    }

    public function store(Request $request): array
    {
        $validator = $this->validateStoreRequest($request);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator,
            ];
        }

        $coverImage = $request->input('poster_url');

        if ($request->hasFile('foto_sampul')) {
            $coverImage = $this->storeUploadedCover($request, 'jpg');
        }

        $this->movieRepository->create($this->buildMoviePayload($request, $coverImage));

        if ($request->has('poster_url')) {
            return $this->successResponse('/watchlist', 'Film berhasil ditambahkan ke Watchlist!');
        }

        return $this->successResponse('/', 'Data berhasil disimpan');
    }

    public function update(Request $request, string $id): array
    {
        $validator = $this->validateUpdateRequest($request);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator,
            ];
        }

        $movie = $this->movieRepository->findByIdOrFail($id);

        $coverImage = null;

        if ($request->hasFile('foto_sampul')) {
            $extension = $request->file('foto_sampul')->getClientOriginalExtension();
            $coverImage = $this->storeUploadedCover($request, $extension);

            $this->deleteCoverIfExists($movie->foto_sampul);
        }

        $this->movieRepository->update($movie, $this->buildMoviePayload($request, $coverImage, false));

        return $this->successResponse('/movies/data', 'Data berhasil diperbarui');
    }

    public function delete(string $id): void
    {
        $movie = $this->movieRepository->findByIdOrFail($id);

        $this->deleteCoverIfExists($movie->foto_sampul);
        $this->movieRepository->delete($movie);
    }

    public function searchFromOmdb(?string $query): array
    {
        if (!$query || strlen($query) < 2) {
            return [
                'success' => false,
                'message' => 'Query minimal 2 karakter',
                'results' => [],
            ];
        }

        $results = $this->omdbService->searchMovies($query);

        return [
            'success' => true,
            'results' => $results,
        ];
    }

    public function getOmdbDetail(?string $imdbId): array
    {
        if (!$imdbId) {
            return [
                'success' => false,
                'message' => 'IMDB ID tidak ditemukan',
            ];
        }

        $data = $this->omdbService->getMovieById($imdbId);

        if ($data) {
            return [
                'success' => true,
                'data' => $data,
            ];
        }

        return [
            'success' => false,
            'message' => 'Film tidak ditemukan',
        ];
    }

    private function validateStoreRequest(Request $request): ValidatorContract
    {
        return Validator::make($request->all(), [
            'id' => ['required', 'string', 'max:255', Rule::unique('movies', 'id')],
            'judul' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'sinopsis' => 'required|string',
            'tahun' => 'required|integer',
            'pemain' => 'required|string',
            'foto_sampul' => $request->has('poster_url')
                ? 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                : 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }

    private function validateUpdateRequest(Request $request): ValidatorContract
    {
        return Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'sinopsis' => 'required|string',
            'tahun' => 'required|integer',
            'pemain' => 'required|string',
            'foto_sampul' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }

    private function storeUploadedCover(Request $request, string $extension): string
    {
        $fileName = Str::uuid()->toString() . '.' . $extension;
        $request->file('foto_sampul')->move(public_path('images'), $fileName);

        return $fileName;
    }

    private function deleteCoverIfExists(?string $coverFileName): void
    {
        if (!$coverFileName) {
            return;
        }

        $coverPath = public_path('images/' . $coverFileName);

        if (File::exists($coverPath)) {
            File::delete($coverPath);
        }
    }

    private function buildMoviePayload(Request $request, ?string $coverImage = null, bool $includeId = true): array
    {
        $payload = [
            'judul' => $request->judul,
            'category_id' => $request->category_id,
            'sinopsis' => $request->sinopsis,
            'tahun' => $request->tahun,
            'pemain' => $request->pemain,
        ];

        if ($includeId) {
            $payload['id'] = $request->id;
        }

        if ($coverImage !== null) {
            $payload['foto_sampul'] = $coverImage;
        }

        return $payload;
    }

    private function successResponse(string $redirect, string $message): array
    {
        return [
            'success' => true,
            'redirect' => $redirect,
            'message' => $message,
        ];
    }
}
