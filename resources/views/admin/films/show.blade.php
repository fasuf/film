@extends('admin.layout')

@section('title', 'Detail Film: ' . $film->title)
@section('header_title', 'Detail Film')
@section('header_subtitle', 'Informasi lengkap film, pemeran, dan ulasan pengguna.')

@section('header_actions')
    <a href="{{ route('admin.films.index') }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
    <a href="{{ route('admin.films.edit', $film->id) }}" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-pen-to-square"></i> Edit Film
    </a>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: 280px 1fr; gap: 24px; align-items: start;">
    <!-- Left Column: Poster & Quick Info -->
    <div class="card">
        <div style="padding: 16px; text-align: center;">
            <img src="{{ $film->poster_url }}" alt="{{ $film->title }}" style="width: 100%; max-height: 380px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.4);" onerror="this.src='{{ asset('images/m1.jpg') }}'">
        </div>
        <div class="card-body" style="padding-top: 0;">
            <h3 style="font-size: 18px; color: #fff; margin-bottom: 8px;">{{ $film->title }}</h3>
            
            <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 16px;">
                <span class="badge badge-genre">{{ $film->genre->name ?? 'Tanpa Genre' }}</span>
                <span class="badge badge-year">{{ $film->year }}</span>
                @if($film->rating)
                    <span class="badge badge-rating"><i class="fa-solid fa-star"></i> {{ number_format($film->rating, 1) }}</span>
                @endif
                @if($film->duration)
                    <span class="badge" style="background:#334155; color:#fff;"><i class="fa-regular fa-clock"></i> {{ $film->duration }} mnt</span>
                @endif
            </div>

            <div style="border-top: 1px solid var(--border-color); padding-top: 14px; font-size: 13px; color: var(--text-muted); line-height: 1.6;">
                <div><strong>Dibuat:</strong> {{ $film->created_at ? $film->created_at->format('d M Y H:i') : '-' }}</div>
                <div><strong>Diperbarui:</strong> {{ $film->updated_at ? $film->updated_at->format('d M Y H:i') : '-' }}</div>
            </div>

            <div style="margin-top: 18px;">
                <button type="button" class="btn btn-danger btn-sm" style="width: 100%; justify-content: center;"
                    onclick="confirmDelete('{{ route('admin.films.destroy', $film->id) }}', '{{ addslashes($film->title) }}')">
                    <i class="fa-solid fa-trash"></i> Hapus Film Ini
                </button>
            </div>
        </div>
    </div>

    <!-- Right Column: Details, Casts, Reviews -->
    <div>
        <!-- Sinopsis -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-align-left" style="color: var(--primary);"></i> Sinopsis & Deskripsi
                </div>
            </div>
            <div class="card-body">
                <p style="font-size: 14px; line-height: 1.7; color: #e2e8f0; text-align: justify;">
                    {{ $film->sinopsis }}
                </p>
            </div>
        </div>

        <!-- Cast & Pemeran -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-users" style="color: #38bdf8;"></i> Daftar Cast & Karakter ({{ $film->perans->count() }})
                </div>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nama Cast/Aktor</th>
                            <th>Usia</th>
                            <th>Peran / Karakter</th>
                            <th>Bio Singkat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($film->perans as $peran)
                            <tr>
                                <td>
                                    <strong style="color: #fff;">{{ $peran->cast->name ?? 'Cast #' . $peran->cast_id }}</strong>
                                </td>
                                <td>
                                    {{ $peran->cast->age ?? '-' }} Tahun
                                </td>
                                <td>
                                    <span class="badge" style="background: rgba(14, 165, 233, 0.2); color: #38bdf8;">
                                        {{ $peran->actor ?: '-' }}
                                    </span>
                                </td>
                                <td style="color: var(--text-muted); font-size: 13px;">
                                    {{ Str::limit($peran->cast->bio ?? '-', 50) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 24px;">
                                    Belum ada pemeran/cast yang ditautkan ke film ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ulasan Pengguna (Kritik & Rating) -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-comments" style="color: #a855f7;"></i> Ulasan & Komentar Pengguna ({{ $film->kritiks->count() }})
                </div>
            </div>
            <div class="card-body" style="padding: 16px;">
                @forelse($film->kritiks as $kritik)
                    <div style="padding: 12px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: flex-start; gap: 16px;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <strong style="color: #fff; font-size: 14px;">{{ !empty($kritik->user?->name) ? $kritik->user->name : 'Anonim' }}</strong>
                                <span class="badge badge-rating"><i class="fa-solid fa-star"></i> {{ $kritik->rating }} / 10</span>
                                <span style="font-size: 12px; color: var(--text-muted);">{{ $kritik->created_at ? $kritik->created_at->format('d M Y, H:i') . ' • ' . $kritik->created_at->diffForHumans() : '-' }}</span>
                            </div>
                            <p style="font-size: 13px; color: #cbd5e1; margin-top: 6px; line-height: 1.5;">
                                "{{ $kritik->comment }}"
                            </p>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" title="Hapus komentar tidak pantas"
                            onclick="confirmDelete('{{ route('admin.comments.destroy', $kritik->id) }}', 'komentar dari {{ addslashes(!empty($kritik->user?->name) ? $kritik->user->name : 'Anonim') }}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                @empty
                    <div style="text-align: center; color: var(--text-muted); padding: 20px;">
                        Belum ada ulasan atau komentar untuk film ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
