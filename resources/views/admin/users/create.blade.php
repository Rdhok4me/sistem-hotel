@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')

<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.users.index') }}"
           class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-xl font-semibold text-gray-800">Tambah Pengguna Baru</h1>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}"
          class="card p-6 space-y-5">
        @csrf

        {{-- Nama --}}
        <div>
            <label for="name" class="form-label">Nama lengkap <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name"
                   value="{{ old('name') }}" required autofocus
                   placeholder="Nama pengguna"
                   class="form-input {{ $errors->has('name') ? 'error' : '' }}">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}" required
                   placeholder="email@domain.com"
                   class="form-input {{ $errors->has('email') ? 'error' : '' }}">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="form-label">Password <span class="text-red-500">*</span></label>
            <input type="password" id="password" name="password" required
                   placeholder="Minimal 8 karakter"
                   class="form-input {{ $errors->has('password') ? 'error' : '' }}">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div>
            <label for="password_confirmation" class="form-label">
                Konfirmasi password <span class="text-red-500">*</span>
            </label>
            <input type="password" id="password_confirmation"
                   name="password_confirmation" required
                   placeholder="Ulangi password"
                   class="form-input">
        </div>

        {{-- Role --}}
        <div>
            <label for="role" class="form-label">Role <span class="text-red-500">*</span></label>
            <select id="role" name="role" required
                    class="form-input {{ $errors->has('role') ? 'error' : '' }}">
                <option value="">-- Pilih Role --</option>
                <option value="resepsionis" {{ old('role') === 'resepsionis' ? 'selected' : '' }}>
                    Resepsionis
                </option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                    Admin
                </option>
            </select>
            @error('role')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Simpan Pengguna</button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
