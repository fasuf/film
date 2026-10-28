@extends('admin.layout')

@section('title', 'Edit Film: ' . $film->title)
@section('header_title', 'Edit Data Film')
@section('header_subtitle', 'Perbarui informasi film, poster, genre, dan relasi peran cast.')

@section('header_actions')
    <a href="{{ route('admin.films.index') }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
@endsection

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-pen-to-square" style="color: var(--primary);"></i> Edit Film: {{ $film->title }}
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.films.update', $film->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <!-- Left column -->
                <div>
                    <div class="form-group">
                        <label class="form-label" for="title">Judul Film <span class="required">*</span></label>
                        <input type="text" name="title" id="title" class="form-input" value="{{ old('title', $film->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="sinopsis">Deskripsi / Sinopsis <span class="required">*</span></label>
                        <textarea name="sinopsis" id="sinopsis" rows="5" class="form-input" required>{{ old('sinopsis', $film->sinopsis) }}</textarea>
                        @error('sinopsis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Right column -->
                <div>
                    <div class="form-group">
                        <label class="form-label" for="genre_id">Genre <span class="required">*</span></label>
                        <select name="genre_id" id="genre_id" class="form-input" required>
                            <option value="">-- Pilih Genre --</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}" {{ old('genre_id', $film->genre_id) == $genre->id ? 'selected' : '' }}>
                                    {{ $genre->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('genre_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="year">Tahun Rilis <span class="required">*</span></label>
                        <input type="number" name="year" id="year" class="form-input" value="{{ old('year', $film->year) }}" min="1880" max="{{ date('Y') + 5 }}" required>
                        @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label class="form-label" for="duration">Durasi (Menit)</label>
                            <input type="number" name="duration" id="duration" class="form-input" value="{{ old('duration', $film->duration) }}" placeholder="120" min="1">
                            @error('duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="rating">Rating (0 - 10)</label>
                            <input type="number" step="0.1" name="rating" id="rating" class="form-input" value="{{ old('rating', $film->rating) }}" placeholder="8.5" min="0" max="10">
                            @error('rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Poster Section -->
            <div style="border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 10px;">
                <h4 style="font-size: 15px; color: #fff; margin-bottom: 12px;">
                    <i class="fa-solid fa-image" style="color: var(--primary);"></i> Poster Film
                </h4>
                <div style="display: flex; gap: 20px; align-items: flex-start;">
                    <div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 6px;">Poster Saat Ini:</div>
                        <img src="{{ asset($film->poster) }}" alt="{{ $film->title }}" style="width: 80px; height: 110px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);" onerror="this.src='{{ asset('images/m1.jpg') }}'">
                    </div>
                    <div style="flex-grow: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label" for="poster_file">Ganti Gambar Poster</label>
                            <input type="file" name="poster_file" id="poster_file" class="form-input" accept="image/png,image/jpeg,image/webp,image/jpg">
                            <div class="form-helper">Biarkan kosong jika tidak ingin mengubah poster file</div>
                            @error('poster_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="poster_url">Atau Ubah Path Poster</label>
                            <input type="text" name="poster_url" id="poster_url" class="form-input" value="{{ old('poster_url', $film->poster) }}">
                            <div class="form-helper">Path lokal atau URL gambar</div>
                            @error('poster_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cast & Role Section -->
            <div style="border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <h4 style="font-size: 15px; color: #fff;">
                        <i class="fa-solid fa-users" style="color: #38bdf8;"></i> Hubungkan Cast / Aktor (Relasi Many-to-Many)
                    </h4>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addCastRow()">
                        <i class="fa-solid fa-plus"></i> Tambah Cast
                    </button>
                </div>

                <div id="castRowsContainer">
                    @forelse($film->perans as $index => $peran)
                        <div class="cast-row" style="display: grid; grid-template-columns: 2fr 2fr auto; gap: 12px; margin-bottom: 10px; align-items: center;">
                            <div>
                                <select name="casts[{{ $index }}][cast_id]" class="form-input" required>
                                    <option value="">-- Pilih Aktor / Cast --</option>
                                    @foreach($casts as $c)
                                        <option value="{{ $c->id }}" {{ $peran->cast_id == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }} (Usia: {{ $c->age }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <input type="text" name="casts[{{ $index }}][actor]" class="form-input" value="{{ $peran->actor }}" placeholder="Nama Karakter / Peran">
                            </div>
                            <div>
                                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.cast-row').remove()" title="Hapus baris">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <!-- If no casts yet, will be added by script -->
                    @endforelse
                </div>
            </div>

            <!-- Submit Button -->
            <div style="border-top: 1px solid var(--border-color); padding-top: 24px; margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('admin.films.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<template id="castRowTemplate">
    <div class="cast-row" style="display: grid; grid-template-columns: 2fr 2fr auto; gap: 12px; margin-bottom: 10px; align-items: center;">
        <div>
            <select name="casts[INDEX][cast_id]" class="form-input" required>
                <option value="">-- Pilih Aktor / Cast --</option>
                @foreach($casts as $c)
                    <option value="{{ $c->id }}">{{ $c->name }} (Usia: {{ $c->age }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <input type="text" name="casts[INDEX][actor]" class="form-input" placeholder="Nama Karakter / Peran">
        </div>
        <div>
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.cast-row').remove()" title="Hapus baris">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    </div>
</template>

@push('scripts')
<script>
    let castIndex = {{ $film->perans->count() }};
    function addCastRow() {
        const container = document.getElementById('castRowsContainer');
        const template = document.getElementById('castRowTemplate').innerHTML;
        const html = template.replace(/INDEX/g, castIndex);
        container.insertAdjacentHTML('beforeend', html);
        castIndex++;
    }

    @if($film->perans->count() === 0)
    document.addEventListener('DOMContentLoaded', function() {
        addCastRow();
    });
    @endif
</script>
@endpush
@endsection
