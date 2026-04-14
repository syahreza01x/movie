@extends('layout.template')  
@section('title', 'Input Data Movie')  
@section('content')
		<a href="/movies/data" class="btn btn-primary mt-4">List Movie</a>
		<h2 class="mb-4">{{ request('imdbid') ? 'Tambah ke Watchlist' : 'Tambah Movie Baru' }}</h2>
        <form action="/movies/store" method="POST" enctype="multipart/form-data">
			@csrf
			<div class="mb-3">
				<label for="id" class="form-label">ID Film (IMDB ID):</label>
				<input type="text" class="form-control" id="id" name="id" value="{{ request('imdbid', '') }}" required="">
			</div>
			<div class="mb-3">
				<label for="judul" class="form-label">Judul:</label>
				<input type="text" class="form-control" id="judul" name="judul" value="{{ request('judul', '') }}" required="">
			</div>
			<div class="mb-3">
				<label for="category_id" class="form-label">Kategori:</label>
				<select name="category_id" id="category_id" class="form-select" required>
					<option value="">Pilih Kategori</option>
					@foreach ($categories as $category)
						<option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
					@endforeach
				</select>
			</div>
			<div class="mb-3">
				<label for="sinopsis" class="form-label">Sinopsis:</label>
				<textarea class="form-control" id="sinopsis" name="sinopsis" rows="4" required="">{{ request('sinopsis', '') }}</textarea>
			</div>
			<div class="mb-3">
				<label for="tahun" class="form-label">Tahun:</label>
				<input type="number" class="form-control" id="tahun" name="tahun" value="{{ request('tahun', '') }}" required="">
			</div>
			<div class="mb-3">
				<label for="pemain" class="form-label">Pemain:</label>
				<input type="text" class="form-control" id="pemain" name="pemain" value="{{ request('pemain', '') }}" required="">
			</div>
			<div class="mb-3">
				<label for="foto_sampul" class="form-label">Foto Sampul:</label>
				@if(request('poster'))
					<p class="text-info mb-2">✓ Poster dari OMDb akan digunakan</p>
					<input type="hidden" name="poster_url" value="{{ request('poster', '') }}">
					<input type="file" class="form-control" id="foto_sampul" name="foto_sampul">
					<small class="text-muted">Atau upload file baru untuk mengganti</small>
				@else
					<input type="file" class="form-control" id="foto_sampul" name="foto_sampul" required="">
				@endif
			</div>
			<div class="mb-3">
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
		</form>
		@endsection