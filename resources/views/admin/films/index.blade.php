@extends('admin.layout')

@section('title', 'Kelola Film')
@section('header_title', 'Kelola Data Film')
@section('header_subtitle', 'Daftar semua film di database OneMovies dengan fitur pencarian dan filter.')

@section('header_actions')
    <a href="{{ route('admin.films.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Film Baru
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-film" style="color: var(--primary);"></i> Daftar Film ({{ $films->total() }})
        </div>

        <!-- Filter & Search Bar -->
        <form action="{{ route('admin.films.index') }}" method="GET" class="filter-bar">
            <select name="genre_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- Semua Genre --</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}" {{ $genreId == $genre->id ? 'selected' : '' }}>
                        {{ $genre->name }}
                    </option>
                @endforeach
            </select>

            <div class="search-input-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" class="form-control" placeholder="Cari judul, sinopsis, tahun..." value="{{ $search ?? '' }}">
            </div>

            <button type="submit" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-filter"></i> Cari
            </button>

            @if(!empty($search) || !empty($genreId))
                <a href="{{ route('admin.films.index') }}" class="btn btn-danger btn-sm" title="Reset filter">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Poster</th>
                    <th>Judul Film</th>
                    <th>Genre</th>
                    <th>Tahun</th>
                    <th>Durasi</th>
                    <th>Rating</th>
                    <th>Cast / Aktor</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($films as $film)
                    <tr>
                        <td>
                            <img src="{{ $film->poster_url }}" alt="{{ $film->title }}" class="thumbnail-img" onerror="this.src='{{ asset('images/m1.jpg') }}'">
                        </td>
                        <td>
                            <strong style="color: #fff; font-size: 15px;">{{ $film->title }}</strong>
                            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px; max-width: 320px; line-height: 1.4;">
                                {{ Str::limit($film->sinopsis, 80) }}
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-genre">{{ $film->genre->name ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="badge badge-year">{{ $film->year }}</span>
                        </td>
                        <td>
                            @if($film->duration)
                                <span style="font-size: 13px; color: #cbd5e1;"><i class="fa-regular fa-clock"></i> {{ $film->duration }} mnt</span>
                            @else
                                <span style="color: var(--text-muted); font-size: 12px;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($film->rating)
                                <span class="badge badge-rating"><i class="fa-solid fa-star"></i> {{ number_format($film->rating, 1) }}</span>
                            @else
                                <span style="color: var(--text-muted); font-size: 12px;">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8; border: 1px solid rgba(14, 165, 233, 0.3);">
                                <i class="fa-solid fa-user-tag"></i> {{ $film->actors->count() }} Cast
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a href="{{ route('admin.films.show', $film->id) }}" class="btn btn-secondary btn-sm" title="Detail Film">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.films.edit', $film->id) }}" class="btn btn-secondary btn-sm" title="Edit Film">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" title="Hapus Film"
                                    onclick="confirmDelete('{{ route('admin.films.destroy', $film->id) }}', '{{ addslashes($film->title) }}')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            <i class="fa-solid fa-film" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
                            Tidak ada data film yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($films->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <div style="font-size: 13px; color: var(--text-muted);">
                Menampilkan {{ $films->firstItem() }} - {{ $films->lastItem() }} dari {{ $films->total() }} film
            </div>
            <div>
                {{ $films->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
