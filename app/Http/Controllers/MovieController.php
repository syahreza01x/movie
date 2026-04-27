<?php

namespace App\Http\Controllers;

use App\Services\Movie\MovieService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    private MovieService $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function index()
    {
        $movies = $this->movieService->getHomepageMovies(request('search'));
        return view('homepage', compact('movies'));
    }

    /**
     * Search page for movies
     */
    public function search()
    {
        return view('search');
    }

    /**
     * Watchlist page
     */
    public function watchlist()
    {
        $movies = $this->movieService->getWatchlistMovies();
        return view('watchlist', compact('movies'));
    }

    public function detail($id)
    {
        $movie = $this->movieService->findMovie($id);
        return view('detail', compact('movie'));
    }

    public function create()
    {
        $categories = $this->movieService->getCategories();
        return view('input', compact('categories'));
    }

    public function store(Request $request)
    {
        $result = $this->movieService->store($request);

        if (!$result['success']) {
            return redirect('movies/create')
                ->withErrors($result['errors'])
                ->withInput();
        }

        return redirect($result['redirect'])->with('success', $result['message']);
    }

    public function data()
    {
        $movies = $this->movieService->getDataMovies();
        return view('data-movies', compact('movies'));
    }

    public function formEdit($id)
    {
        $movie = $this->movieService->findMovie($id);
        $categories = $this->movieService->getCategories();
        return view('form-edit', compact('movie', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $result = $this->movieService->update($request, $id);

        if (!$result['success']) {
            return redirect("/movies/edit/{$id}")
                ->withErrors($result['errors'])
                ->withInput();
        }

        return redirect($result['redirect'])->with('success', $result['message']);
    }

    public function delete($id)
    {
        $this->movieService->delete($id);

        return redirect('/movies/data')->with('success', 'Data berhasil dihapus');
    }

    /**
     * Search movies from OMDb API
     */
    public function searchOMDb(Request $request)
    {
        $result = $this->movieService->searchFromOmdb($request->get('q'));

        return response()->json($result);
    }

    /**
     * Get movie detail from OMDb API
     */
    public function getOMDbDetail(Request $request)
    {
        $result = $this->movieService->getOmdbDetail($request->get('imdbid'));

        return response()->json($result);
    }
}
