@extends('layouts.app')

@section('title', 'Edit Kamar')

@section('content')

<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('kamar.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-xl font-semibold text-gray-800">Edit Kamar {{ $kamar->nomor_kamar }}</h1>
    </div>

    <form method="POST" action="{{ route('kamar.update', $kamar) }}" class="card p-6 space-y-5">
        @csrf @method('PUT')

        {{-- Nomor kamar (readonly) --}}
        <div>
            <label class="form-label">Nomor kamar</label>
            <input type="text" value="{{ $kamar->nomor_kamar }}" readonly
                   class="form-input bg-gray-50 text-gray-500 cursor-not-allowed">
            <p class="text-xs text-gray-400 mt-1">Nomor kamar tidak dapat diubah.</p>
        </div>

        {{-- Tipe kamar --}}
        <div>
            <label for="tipe_kamar_id" class="form-label">
                Tipe kamar <span class="text-red-500">*</span>
            </label>
            <select id="tipe_kamar_id" name="tipe_kamar_id" required class="form-input">
                @foreach($tipeKamar as $t)
                    <option value="{{ $t->id }}"
                        {{ old('tipe_kamar_id', $kamar->tipe_kamar_id) == $t->id ? 'selected' : '' }}>
                        {{ $t->nama }} — Rp {{ number_format($t->harga_per_malam, 0, ',', '.') }}/malam
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Lantai --}}
        <div>
            <label for="lantai" class="form-label">Lantai <span class="text-red-500">*</span></label>
            <input type="number" id="lantai" name="lantai"
                   value="{{ old('lantai', $kamar->lantai) }}" required min="1" max="50"
                   class="form-input w-32">
        </div>

        {{-- Status --}}
        <div>
            <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
            <select id="status" name="status" required class="form-input">
                @foreach(['tersedia','terisi','dibersihkan','maintenance'] as $s)
                    <option value="{{ $s }}"
                        {{ old('status', $kamar->status) === $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>
            @if($kamar->status === 'terisi')
            <p class="text-xs text-amber-600 mt-1">
                ⚠ Kamar sedang terisi. Ubah status hanya jika tamu sudah benar-benar keluar.
            </p>
            @endif
        </div>

        {{-- Keterangan --}}
        <div>
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea id="keterangan" name="keterangan" rows="2"
                      class="form-input">{{ old('keterangan', $kamar->keterangan) }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
            <a href="{{ route('kamar.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
