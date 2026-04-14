@extends('layout.template')

@section('title', 'Watchlist')

@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>    
@endif

<h1>My Watchlist</h1>
<div class="row">
    @if($movies->count() > 0)
        @foreach ($movies as $movie)
        <div class="col-lg-6">
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row g-0">
                  <div class="col-md-4">
                    <img src="{{ strpos($movie['foto_sampul'], 'http') === 0 ? $movie['foto_sampul'] : '/images/' . $movie['foto_sampul'] }}" class="img-fluid rounded-start" alt="{{ $movie['judul'] }}">
                </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $movie['judul'] }}</h5>
                            <p class="card-text">{{ $movie['sinopsis'] }}</p>
                            <div class="d-flex gap-2">
                                <a href="/movie/{{ $movie['id'] }}" class="btn btn-success btn-sm">Lihat Detail</a>
                                <a href="{{ route('movies.delete', ['id' => $movie->id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Hapus dari watchlist?')">Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <div class="d-flex justify-content-center">
            {{ $movies->links() }}
        </div>
    @else
        <div class="col-12">
            <div class="alert alert-info text-center" role="alert">
                <h5>Watchlist Kosong</h5>
                <p>Cari film dan tambahkan ke watchlist Anda</p>
                <a href="/search" class="btn btn-primary">🔍 Cari Film</a>
            </div>
        </div>
    @endif
</div>
@endsection
