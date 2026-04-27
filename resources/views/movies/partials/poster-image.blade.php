@php
    $cover = $movie->foto_sampul ?? $movie['foto_sampul'] ?? '';
    $title = $movie->judul ?? $movie['judul'] ?? 'Movie';
    $posterSrc = \Illuminate\Support\Str::startsWith($cover, ['http://', 'https://']) ? $cover : '/images/' . $cover;
    $imgClass = $class ?? 'img-fluid rounded-start';
    $imgAlt = $alt ?? $title;
    $imgWidth = $width ?? null;
@endphp

<img src="{{ $posterSrc }}" class="{{ $imgClass }}" alt="{{ $imgAlt }}" @if ($imgWidth) width="{{ $imgWidth }}" @endif>
