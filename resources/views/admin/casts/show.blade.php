@extends('admin.layout')

@section('title', 'Detail Cast: ' . $cast->name)
@section('header_title', 'Detail Cast & Aktor')
@section('header_subtitle', 'Profil aktor dan daftar film yang dibintangi.')

@section('header_actions')
    <a href="{{ route('admin.casts.index') }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
    <a href="{{ route('admin.casts.edit', $cast->id) }}" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-user-pen"></i> Edit Cast
    </a>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: 320px 1fr; gap: 24px; align-items: start;">
    <!-- Profile Card -->
    <div class="card">
        <div style="padding: 24px; text-align: center; border-bottom: 1px solid var(--border-color);">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #38bdf8, #0284c7); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 32px; color: #fff; font-weight: 700;">
                {{ strtoupper(substr($cast->name, 0, 1)) }}
            </div>
            <h3 style="font-size: 20px; color: #fff; font-weight: 700;">{{ $cast->name }}</h3>
            <div style="margin-top: 6px;">
                <span class="badge badge-year">{{ $cast->age }} Tahun</span>
                <span class="badge badge-genre" style="margin-left: 4px;">{{ $cast->films->count() }} Film Dibintangi</span>
            </div>
        </div>

        <div class="card-body">
            <h4 style="font-size: 14px; color: #fff; margin-bottom: 8px;">
                <i class="fa-solid fa-book-open" style="color: var(--primary);"></i> Biografi
            </h4>
            <p style="font-size: 13px; color: #cbd5e1; line-height: 1.6; text-align: justify;">
                {{ $cast->bio }}
            </p>

            <div style="margin-top: 24px; border-top: 1px solid var(--border-color); padding-top: 16px;">
                <button type="button" class="btn btn-danger btn-sm" style="width: 100%; justify-content: center;"
                    onclick="confirmDelete('{{ route('admin.casts.destroy', $cast->id) }}', '{{ addslashes($cast->name) }}')">
                    <i class="fa-solid fa-trash"></i> Hapus Cast Ini
                </button>
            </div>
        </div>
    </div>

    <!-- Films starred in -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-film" style="color: var(--primary);"></i> Film yang Dibintangi ({{ $cast->perans->count() }})
            </div>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Poster</th>
                        <th>Judul Film</th>
                        <th>Genre</th>
                        <th>Tahun</th>
                        <th>Karakter / Peran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cast->perans as $peran)
                        @if($peran->film)
                            <tr>
                                <td>
                                    <img src="{{ $peran->film->poster_url }}" alt="{{ $peran->film->title }}" class="thumbnail-img" onerror="this.src='{{ asset('images/m1.jpg') }}'">
                                </td>
                                <td>
                                    <strong style="color: #fff;">{{ $peran->film->title }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-genre">{{ $peran->film->genre->name ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-year">{{ $peran->film->year }}</span>
                                </td>
                                <td>
                                    <span class="badge" style="background: rgba(14, 165, 233, 0.2); color: #38bdf8;">
                                        {{ $peran->actor ?: '-' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.films.show', $peran->film->id) }}" class="btn btn-secondary btn-sm" title="Detail Film">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                Aktor ini belum ditautkan ke film manapun.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
