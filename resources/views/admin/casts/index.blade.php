@extends('admin.layout')

@section('title', 'Kelola Cast & Aktor')
@section('header_title', 'Kelola Data Cast & Aktor')
@section('header_subtitle', 'Manajemen daftar aktor/aktris dan relasi peran dalam film.')

@section('header_actions')
    <a href="{{ route('admin.casts.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Cast Baru
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-users" style="color: #38bdf8;"></i> Daftar Cast & Aktor ({{ $casts->total() }})
        </div>

        <!-- Filter & Search Bar -->
        <form action="{{ route('admin.casts.index') }}" method="GET" class="filter-bar">
            <div class="search-input-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" class="form-control" placeholder="Cari nama, umur, bio..." value="{{ $search ?? '' }}">
            </div>

            <button type="submit" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-filter"></i> Cari
            </button>

            @if(!empty($search))
                <a href="{{ route('admin.casts.index') }}" class="btn btn-danger btn-sm" title="Reset pencarian">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Nama Aktor / Cast</th>
                    <th>Usia</th>
                    <th>Biografi Singkat</th>
                    <th>Jumlah Film</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($casts as $index => $cast)
                    <tr>
                        <td>{{ $casts->firstItem() + $index }}</td>
                        <td>
                            <strong style="color: #fff; font-size: 15px;">{{ $cast->name }}</strong>
                        </td>
                        <td>
                            <span class="badge badge-year">{{ $cast->age }} Tahun</span>
                        </td>
                        <td>
                            <div style="font-size: 13px; color: var(--text-muted); max-width: 380px; line-height: 1.4;">
                                {{ Str::limit($cast->bio, 90) }}
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-genre">
                                <i class="fa-solid fa-clapperboard"></i> {{ $cast->films_count }} Film
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a href="{{ route('admin.casts.show', $cast->id) }}" class="btn btn-secondary btn-sm" title="Detail Cast">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.casts.edit', $cast->id) }}" class="btn btn-secondary btn-sm" title="Edit Cast">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" title="Hapus Cast"
                                    onclick="confirmDelete('{{ route('admin.casts.destroy', $cast->id) }}', '{{ addslashes($cast->name) }}')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            <i class="fa-solid fa-users" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
                            Tidak ada data cast/aktor yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($casts->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <div style="font-size: 13px; color: var(--text-muted);">
                Menampilkan {{ $casts->firstItem() }} - {{ $casts->lastItem() }} dari {{ $casts->total() }} cast
            </div>
            <div>
                {{ $casts->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
