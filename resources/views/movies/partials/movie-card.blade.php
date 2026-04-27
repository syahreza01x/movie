<div class="card mb-3" style="max-width: 540px;">
    <div class="row g-0">
        <div class="col-md-4">
            @include('movies.partials.poster-image', [
                'movie' => $movie,
                'class' => 'img-fluid rounded-start',
                'alt' => $movie->judul ?? $movie['judul'],
            ])
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title">{{ $movie->judul ?? $movie['judul'] }}</h5>
                <p class="card-text">{{ $movie->sinopsis ?? $movie['sinopsis'] }}</p>
                @if (!empty($showActions))
                    <div class="d-flex gap-2">
                        <a href="/movie/{{ $movie->id ?? $movie['id'] }}" class="btn btn-success btn-sm">{{ $detailLabel ?? 'Lihat Detail' }}</a>
                        <a href="{{ route('movies.delete', ['id' => $movie->id ?? $movie['id']]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Hapus dari watchlist?')">Hapus</a>
                    </div>
                @else
                    <a href="/movie/{{ $movie->id ?? $movie['id'] }}" class="btn btn-success">{{ $detailLabel ?? 'Lihat Selanjutnya' }}</a>
                @endif
            </div>
        </div>
    </div>
</div>
