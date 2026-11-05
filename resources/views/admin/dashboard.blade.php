@extends('admin.layout')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard Ringkasan')
@section('header_subtitle', 'Pantau statistik film, cast, komentar, dan aktivitas terkini.')

@section('header_actions')
    <a href="{{ route('admin.films.create') }}" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-plus"></i> Tambah Film
    </a>
@endsection

@section('content')
<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fa-solid fa-film"></i>
        </div>
        <div>
            <div class="stat-value">{{ $stats['total_films'] }}</div>
            <div class="stat-label">Total Film</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <div class="stat-value">{{ $stats['total_casts'] }}</div>
            <div class="stat-label">Total Cast / Aktor</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-tags"></i>
        </div>
        <div>
            <div class="stat-value">{{ $stats['total_genres'] }}</div>
            <div class="stat-label">Total Genre</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fa-solid fa-comments"></i>
        </div>
        <div>
            <div class="stat-value">{{ $stats['total_kritiks'] }}</div>
            <div class="stat-label">Total Ulasan/Komentar</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-user-group"></i>
        </div>
        <div>
            <div class="stat-value">{{ $stats['total_users'] }}</div>
            <div class="stat-label">Pengguna Terdaftar</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- Recent Films -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-clapperboard" style="color: var(--primary);"></i> Film Terbaru
            </div>
            <a href="{{ route('admin.films.index') }}" class="btn btn-secondary btn-sm">
                Lihat Semua ({{ $stats['total_films'] }})
            </a>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Poster</th>
                        <th>Judul</th>
                        <th>Genre</th>
                        <th>Tahun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentFilms as $film)
                        <tr>
                            <td>
                                <img src="{{ $film->poster_url }}" alt="{{ $film->title }}" class="thumbnail-img" onerror="this.src='{{ asset('images/m1.jpg') }}'">
                            </td>
                            <td>
                                <strong style="color: #fff; font-size: 14px;">{{ $film->title }}</strong>
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                                    {{ Str::limit($film->sinopsis, 60) }}
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-genre">{{ $film->genre->name ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-year">{{ $film->year }}</span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 6px;">
                                    <a href="{{ route('admin.films.edit', $film->id) }}" class="btn btn-secondary btn-sm" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="{{ route('movies.show', $film->id) }}" target="_blank" class="btn btn-secondary btn-sm" title="Lihat di web">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                Belum ada data film.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Comments -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-comments" style="color: #a855f7;"></i> Komentar Terbaru
            </div>
            <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary btn-sm">Semua</a>
        </div>
        <div class="card-body" style="padding: 16px;">
            @forelse($recentComments as $comment)
                <div style="padding: 12px; border-bottom: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 6px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <strong style="color: #fff; font-size: 13px;">{{ !empty($comment->user?->name) ? $comment->user->name : 'Anonim' }}</strong>
                        <span class="badge badge-rating"><i class="fa-solid fa-star"></i> {{ $comment->rating }}</span>
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted);">
                        pada film: <span style="color: var(--primary);">{{ $comment->film->title ?? '-' }}</span>
                    </div>
                    <p style="font-size: 13px; color: #cbd5e1; margin-top: 4px; font-style: italic;">
                        "{{ Str::limit($comment->comment, 80) }}"
                    </p>
                </div>
            @empty
                <div style="text-align: center; color: var(--text-muted); padding: 20px;">
                    Belum ada ulasan/komentar.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
