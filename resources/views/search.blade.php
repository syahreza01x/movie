@extends('layout.template')

@section('title', 'Search Movie')

@section('content')

<h1>Cari Film</h1>

<div class="row mb-4">
    <div class="col-lg-8">
        <div class="input-group input-group-lg">
            <input type="text" class="form-control" id="search-input" placeholder="Cari film (misal: Spiderman)..." value="{{ request('q') }}">
            <button class="btn btn-primary" type="button" id="search-btn">Cari</button>
        </div>
    </div>
</div>

<div class="row">
    <!-- API Results -->
    <div class="col-lg-12">
        <h3>Hasil Pencarian</h3>
        <div id="api-results">
            @if(request('q'))
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            @else
                <p class="text-muted">Cari untuk melihat hasil...</p>
            @endif
        </div>
    </div>
</div>

<!-- Modal untuk detail film dari API -->
<div class="modal fade" id="movieDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="movieModalTitle">Detail Film</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="movieModalBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="addMovieBtn">Tambah ke Watchlist</button>
            </div>
        </div>
    </div>
</div>

<style>
    .movie-card {
        cursor: pointer;
        transition: transform 0.2s;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .movie-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }
    .movie-poster {
        height: 350px;
        object-fit: cover;
        background: #f0f0f0;
    }
</style>

<script>
document.getElementById('search-btn').addEventListener('click', searchMovies);
document.getElementById('search-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') searchMovies();
});

let currentMovieData = null;

function searchMovies() {
    const query = document.getElementById('search-input').value;
    if (!query || query.length < 2) {
        alert('Minimal 2 karakter');
        return;
    }

    // Update URL
    window.history.pushState({}, '', '?q=' + encodeURIComponent(query));

    // Search API
    searchAPI(query);
}

function searchAPI(query) {
    const resultsDiv = document.getElementById('api-results');
    resultsDiv.innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>';

    fetch(`/api/movies/search?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.results.length > 0) {
                let html = '<div class="row g-3">';
                data.results.forEach(movie => {
                    html += `
                        <div class="col-md-3">
                            <div class="card movie-card h-100" onclick="viewMovieDetail('${movie.imdbid}')">
                                <img src="${movie.poster}" class="card-img-top movie-poster" alt="${movie.judul}">
                                <div class="card-body p-2">
                                    <h6 class="card-title small">${movie.judul}</h6>
                                    <p class="card-text small text-muted">${movie.tahun}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                resultsDiv.innerHTML = html;
            } else {
                resultsDiv.innerHTML = '<p class="text-danger">Film tidak ditemukan di OMDb API</p>';
            }
        })
        .catch(err => {
            resultsDiv.innerHTML = '<p class="text-danger">Error: ' + err.message + '</p>';
        });
}

function viewMovieDetail(imdbid) {
    fetch(`/api/movies/omdb-detail?imdbid=${imdbid}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentMovieData = data.data;
                document.getElementById('movieModalTitle').textContent = data.data.judul;
                document.getElementById('movieModalBody').innerHTML = `
                    <div class="row">
                        <div class="col-md-4">
                            <img src="${data.data.poster}" class="img-fluid" alt="${data.data.judul}">
                        </div>
                        <div class="col-md-8">
                            <p><strong>Tahun:</strong> ${data.data.tahun}</p>
                            <p><strong>Rating IMDb:</strong> ${data.data.rating}/10</p>
                            <p><strong>Genre:</strong> ${data.data.genre}</p>
                            <p><strong>Director:</strong> ${data.data.director}</p>
                            <p><strong>Pemain:</strong> ${data.data.pemain}</p>
                            <p><strong>Sinopsis:</strong></p>
                            <p>${data.data.sinopsis}</p>
                        </div>
                    </div>
                `;
                new bootstrap.Modal(document.getElementById('movieDetailModal')).show();
            }
        })
        .catch(err => alert('Error: ' + err.message));
}

function addMovieToDatabase() {
    if (!currentMovieData) return;

    // Redirect to create form with data pre-filled
    const data = currentMovieData;
    window.location.href = `/movies/create?imdbid=${data.imdbid}&judul=${encodeURIComponent(data.judul)}&tahun=${data.tahun}&pemain=${encodeURIComponent(data.pemain)}&sinopsis=${encodeURIComponent(data.sinopsis)}&poster=${encodeURIComponent(data.poster)}`;
}

document.getElementById('addMovieBtn').addEventListener('click', addMovieToDatabase);

// Auto search jika ada query di URL
window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const query = urlParams.get('q');
    if (query) {
        document.getElementById('search-input').value = query;
        searchMovies();
    }
});
</script>

@endsection
