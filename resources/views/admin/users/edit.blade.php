@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')

<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.users.index') }}"
           class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-xl font-semibold text-gray-800">Edit Pengguna</h1>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}"
          class="card p-6 space-y-5">
        @csrf @method('PUT')

        {{-- Nama --}}
        <div>
            <label for="name" class="form-label">Nama lengkap <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name"
                   value="{{ old('name', $user->name) }}" required
                   class="form-input {{ $errors->has('name') ? 'error' : '' }}">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
            <input type="email" id="email" name="email"
                   value="{{ old('email', $user->email) }}" required
                   class="form-input {{ $errors->has('email') ? 'error' : '' }}">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password (opsional saat edit) --}}
        <div>
            <label for="password" class="form-label">
                Password baru
                <span class="text-gray-400 font-normal text-xs">(kosongkan jika tidak diubah)</span>
            </label>
            <input type="password" id="password" name="password"
                   placeholder="Minimal 8 karakter"
                   class="form-input {{ $errors->has('password') ? 'error' : '' }}">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div>
            <label for="password_confirmation" class="form-label">Konfirmasi password baru</label>
            <input type="password" id="password_confirmation"
                   name="password_confirmation"
                   placeholder="Ulangi password baru"
                   class="form-input">
        </div>

        {{-- Role --}}
        <div>
            <label for="role" class="form-label">Role <span class="text-red-500">*</span></label>
            <select id="role" name="role" required
                    class="form-input"
                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                <option value="resepsionis"
                    {{ old('role', $user->role_nama) === 'resepsionis' ? 'selected' : '' }}>
                    Resepsionis
                </option>
                <option value="admin"
                    {{ old('role', $user->role_nama) === 'admin' ? 'selected' : '' }}>
                    Admin
                </option>
            </select>
            @if($user->id === auth()->id())
                <input type="hidden" name="role" value="{{ $user->role_nama }}">
                <p class="text-xs text-gray-400 mt-1">Anda tidak bisa mengubah role akun sendiri.</p>
            @endif
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
