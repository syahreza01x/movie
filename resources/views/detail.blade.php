@extends('layout.template')

@section('title', $movie['judul'])

@section('content')

<div class="card mb-3">
    <div class="row g-0">
      <div class="col-md-3">
        <img src="{{ strpos($movie['foto_sampul'], 'http') === 0 ? $movie['foto_sampul'] : '/images/' . $movie['foto_sampul'] }}" class="img-fluid rounded-start" alt="{{ $movie['judul'] }}">
      </div>
      <div class="col-md-9">
        <div class="card-body">
          <h2 class="card-title">{{ $movie['judul'] }}</h2>
          <p class="card-text">{{ $movie['sinopsis'] }}</p>
          <p class="card-text">Kategori : {{ $movie->category->nama_kategori }}</p>
          <p class="card-text">Tahun : {{ $movie['tahun'] }}</p>
          <p class="card-text">Pemain : {{ $movie['pemain'] }}</p>
          <a href="/" class="btn btn-success">Kembali</a>
        </div>
      </div>
    </div>
  </div>
    
@endsection