@extends('layouts.app')

@section('title', 'Tambah Kamar')

@section('content')

<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('kamar.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-xl font-semibold text-gray-800">Tambah Kamar Baru</h1>
    </div>

    <form method="POST" action="{{ route('kamar.store') }}" class="card p-6 space-y-5">
        @csrf

        {{-- Nomor kamar --}}
        <div>
            <label for="nomor_kamar" class="form-label">
                Nomor kamar <span class="text-red-500">*</span>
            </label>
            <input type="text" id="nomor_kamar" name="nomor_kamar"
                   value="{{ old('nomor_kamar') }}" required
                   placeholder="Contoh: 101, 205, 312"
                   class="form-input {{ $errors->has('nomor_kamar') ? 'error' : '' }}">
            @error('nomor_kamar')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tipe kamar --}}
        <div>
            <label for="tipe_kamar_id" class="form-label">
                Tipe kamar <span class="text-red-500">*</span>
            </label>
            <select id="tipe_kamar_id" name="tipe_kamar_id" required
                    class="form-input {{ $errors->has('tipe_kamar_id') ? 'error' : '' }}">
                <option value="">-- Pilih tipe --</option>
                @foreach($tipeKamar as $t)
                    <option value="{{ $t->id }}"
                        {{ old('tipe_kamar_id') == $t->id ? 'selected' : '' }}>
                        {{ $t->nama }} — Rp {{ number_format($t->harga_per_malam, 0, ',', '.') }}/malam
                    </option>
                @endforeach
            </select>
            @error('tipe_kamar_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Lantai --}}
        <div>
            <label for="lantai" class="form-label">
                Lantai <span class="text-red-500">*</span>
            </label>
            <input type="number" id="lantai" name="lantai"
                   value="{{ old('lantai', 1) }}" required min="1" max="50"
                   class="form-input w-32 {{ $errors->has('lantai') ? 'error' : '' }}">
            @error('lantai')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Keterangan --}}
        <div>
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea id="keterangan" name="keterangan" rows="2"
                      placeholder="Catatan khusus kamar (opsional)"
                      class="form-input">{{ old('keterangan') }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Simpan Kamar</button>
            <a href="{{ route('kamar.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
