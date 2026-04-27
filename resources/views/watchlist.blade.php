@extends('layout.template')

@section('title', 'Watchlist')

@section('content')
@include('partials.flash-success')

<h1>My Watchlist</h1>
<div class="row">
    @if($movies->count() > 0)
        @foreach ($movies as $movie)
        <div class="col-lg-6">
            @include('movies.partials.movie-card', [
                'movie' => $movie,
                'detailLabel' => 'Lihat Detail',
                'showActions' => true,
            ])
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
