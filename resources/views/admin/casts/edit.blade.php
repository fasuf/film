@extends('admin.layout')

@section('title', 'Edit Cast: ' . $cast->name)
@section('header_title', 'Edit Data Cast / Aktor')
@section('header_subtitle', 'Perbarui informasi dan biografi aktor/aktris.')

@section('header_actions')
    <a href="{{ route('admin.casts.index') }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
@endsection

@section('content')
<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-user-pen" style="color: #38bdf8;"></i> Edit Cast: {{ $cast->name }}
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.casts.update', $cast->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="name">Nama Lengkap Aktor / Cast <span class="required">*</span></label>
                <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $cast->name) }}" maxlength="45" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="age">Usia (Tahun) <span class="required">*</span></label>
                <input type="number" name="age" id="age" class="form-input" value="{{ old('age', $cast->age) }}" min="1" max="120" required>
                @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="bio">Biografi Singkat <span class="required">*</span></label>
                <textarea name="bio" id="bio" rows="5" class="form-input" required>{{ old('bio', $cast->bio) }}</textarea>
                @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div style="border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 20px; display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('admin.casts.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
