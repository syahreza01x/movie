@extends('layout.template')

@section('title', 'Homepage')

@section('content')
@include('partials.flash-success')

<h1>Popular Movie</h1>
<div class="row">
    @foreach ($movies as $movie)
    <div class="col-lg-6">
        @include('movies.partials.movie-card', [
            'movie' => $movie,
            'detailLabel' => 'Lihat Selanjutnya',
            'showActions' => false,
        ])
    </div>
    @endforeach
    <div class="d-flex justify-content-center">
        {{ $movies->links() }}
    </div>
</div>
@endsection