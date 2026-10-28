@extends('admin.layout')

@section('title', 'Kelola User')
@section('header_title', 'Kelola Data Pengguna')
@section('header_subtitle', 'Manajemen akun pengguna dan hak akses role.')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-users-gear" style="color: #60a5fa;"></i> Daftar Pengguna ({{ $users->total() }})
        </div>

        <!-- Filter & Search Bar -->
        <form action="{{ route('admin.users.index') }}" method="GET" class="filter-bar">
            <div class="search-input-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..." value="{{ $search ?? '' }}">
            </div>

            <button type="submit" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-filter"></i> Cari
            </button>

            @if(!empty($search))
                <a href="{{ route('admin.users.index') }}" class="btn btn-danger btn-sm" title="Reset filter">
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
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role Saat Ini</th>
                    <th>Ubah Role</th>
                    <th>Terdaftar Sejak</th>
                    <th style="width: 100px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $u)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>
                            <strong style="color: #fff; font-size: 14px;">{{ $u->name }}</strong>
                            @if($u->id === Auth::id())
                                <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #34d399; font-size: 10px; margin-left: 6px;">
                                    (Anda)
                                </span>
                            @endif
                        </td>
                        <td style="color: #cbd5e1;">{{ $u->email }}</td>
                        <td>
                            @if($u->role && $u->role->name === 'admin')
                                <span class="badge badge-role-admin">
                                    <i class="fa-solid fa-shield-halved"></i> Admin
                                </span>
                            @else
                                <span class="badge badge-role-user">
                                    <i class="fa-solid fa-user"></i> User
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($u->id !== Auth::id())
                                <form action="{{ route('admin.users.updateRole', $u->id) }}" method="POST" style="display: flex; gap: 6px; align-items: center;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role_id" class="form-control" style="padding: 4px 8px; font-size: 12px; height: 32px;" onchange="this.form.submit()">
                                        @foreach($roles as $r)
                                            <option value="{{ $r->id }}" {{ $u->role_id == $r->id ? 'selected' : '' }}>
                                                Set ke {{ ucfirst($r->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @else
                                <span style="font-size: 12px; color: var(--text-muted);">-</span>
                            @endif
                        </td>
                        <td style="font-size: 13px; color: var(--text-muted);">
                            {{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}
                        </td>
                        <td style="text-align: center;">
                            @if($u->id !== Auth::id())
                                <button type="button" class="btn btn-danger btn-sm" title="Hapus User"
                                    onclick="confirmDelete('{{ route('admin.users.destroy', $u->id) }}', 'pengguna {{ addslashes($u->name) }}')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            @else
                                <span style="font-size: 12px; color: var(--text-muted);">Terkunci</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            Tidak ada data pengguna.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <div style="font-size: 13px; color: var(--text-muted);">
                Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} user
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
