@extends('admin.layout')

@section('title', 'Moderasi Komentar')
@section('header_title', 'Kelola Komentar & Ulasan')
@section('header_subtitle', 'Pantau dan hapus ulasan atau komentar yang tidak pantas dari pengguna.')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-comments" style="color: #a855f7;"></i> Daftar Komentar ({{ $comments->total() }})
        </div>

        <!-- Filter & Search Bar -->
        <form action="{{ route('admin.comments.index') }}" method="GET" class="filter-bar">
            <div class="search-input-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" class="form-control" placeholder="Cari teks komentar, user, judul film..." value="{{ $search ?? '' }}">
            </div>

            <button type="submit" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-filter"></i> Cari
            </button>

            @if(!empty($search))
                <a href="{{ route('admin.comments.index') }}" class="btn btn-danger btn-sm" title="Reset filter">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Pengguna</th>
                    <th>Film</th>
                    <th style="width: 100px;">Rating</th>
                    <th>Isi Komentar / Ulasan</th>
                    <th>Waktu</th>
                    <th style="width: 100px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comments as $index => $c)
                    <tr>
                        <td>{{ $comments->firstItem() + $index }}</td>
                        <td>
                            <strong style="color: #fff;">{{ !empty($c->user?->name) ? $c->user->name : 'Anonim' }}</strong>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $c->user->email ?? 'Akun telah dihapus' }}</div>
                        </td>
                        <td>
                            <a href="{{ route('admin.films.show', $c->film_id) }}" style="color: var(--primary); text-decoration: none; font-weight: 500;">
                                {{ $c->film->title ?? 'Film #' . $c->film_id }}
                            </a>
                        </td>
                        <td>
                            <span class="badge badge-rating">
                                <i class="fa-solid fa-star"></i> {{ $c->rating }} / 10
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 13px; color: #cbd5e1; max-width: 450px; line-height: 1.5;">
                                "{{ $c->comment }}"
                            </div>
                        </td>
                        <td>
                            <span style="font-size: 12px; color: var(--text-muted); white-space: nowrap;">
                                {{ $c->created_at ? $c->created_at->diffForHumans() : '-' }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <button type="button" class="btn btn-danger btn-sm" title="Hapus Komentar Tidak Pantas"
                                onclick="confirmDelete('{{ route('admin.comments.destroy', $c->id) }}', 'komentar dari {{ addslashes($c->user->name ?? 'user') }}')">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            <i class="fa-solid fa-comment-slash" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
                            Tidak ada komentar yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($comments->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <div style="font-size: 13px; color: var(--text-muted);">
                Menampilkan {{ $comments->firstItem() }} - {{ $comments->lastItem() }} dari {{ $comments->total() }} komentar
            </div>
            <div>
                {{ $comments->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
