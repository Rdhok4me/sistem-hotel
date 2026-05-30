@extends('layouts.app')

@section('title', 'Edit Data Tamu')

@section('content')

<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('tamu.show', $tamu) }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-xl font-semibold text-gray-800">Edit Data Tamu</h1>
    </div>

    <form method="POST" action="{{ route('tamu.update', $tamu) }}"
          class="card p-6 space-y-5">
        @csrf @method('PUT')

        <div class="grid grid-cols-2 gap-5">
            {{-- Nama Lengkap --}}
            <div class="col-span-2">
                <label for="nama_lengkap" class="form-label">
                    Nama lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama_lengkap" name="nama_lengkap"
                       value="{{ old('nama_lengkap', $tamu->nama_lengkap) }}" required
                       class="form-input {{ $errors->has('nama_lengkap') ? 'error' : '' }}">
                @error('nama_lengkap')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jenis Identitas --}}
            <div>
                <label for="jenis_identitas" class="form-label">Jenis identitas</label>
                <select id="jenis_identitas" name="jenis_identitas" class="form-input">
                    <option value="ktp"    {{ old('jenis_identitas', $tamu->jenis_identitas) === 'ktp'    ? 'selected' : '' }}>KTP</option>
                    <option value="sim"    {{ old('jenis_identitas', $tamu->jenis_identitas) === 'sim'    ? 'selected' : '' }}>SIM</option>
                    <option value="paspor" {{ old('jenis_identitas', $tamu->jenis_identitas) === 'paspor' ? 'selected' : '' }}>Paspor</option>
                </select>
            </div>

            {{-- NIK --}}
            <div>
                <label for="nik" class="form-label">
                    NIK <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nik" name="nik"
                       value="{{ old('nik', $tamu->nik) }}" required
                       maxlength="20"
                       class="form-input font-mono {{ $errors->has('nik') ? 'error' : '' }}">
                @error('nik')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jenis kelamin --}}
            <div>
                <label class="form-label">Jenis kelamin</label>
                <div class="flex gap-4 mt-2">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="jenis_kelamin" value="L"
                               {{ old('jenis_kelamin', $tamu->jenis_kelamin) === 'L' ? 'checked' : '' }}>
                        Laki-laki
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="jenis_kelamin" value="P"
                               {{ old('jenis_kelamin', $tamu->jenis_kelamin) === 'P' ? 'checked' : '' }}>
                        Perempuan
                    </label>
                </div>
            </div>

            {{-- Tanggal lahir --}}
            <div>
                <label for="tanggal_lahir" class="form-label">Tanggal lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                       value="{{ old('tanggal_lahir', $tamu->tanggal_lahir?->format('Y-m-d')) }}"
                       class="form-input">
            </div>

            {{-- No. telepon --}}
            <div>
                <label for="no_telepon" class="form-label">
                    No. telepon <span class="text-red-500">*</span>
                </label>
                <input type="text" id="no_telepon" name="no_telepon"
                       value="{{ old('no_telepon', $tamu->no_telepon) }}" required
                       class="form-input {{ $errors->has('no_telepon') ? 'error' : '' }}">
                @error('no_telepon')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email', $tamu->email) }}"
                       class="form-input {{ $errors->has('email') ? 'error' : '' }}">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kota asal --}}
            <div>
                <label for="kota_asal" class="form-label">Kota asal</label>
                <input type="text" id="kota_asal" name="kota_asal"
                       value="{{ old('kota_asal', $tamu->kota_asal) }}"
                       class="form-input">
            </div>

            {{-- Pekerjaan --}}
            <div>
                <label for="pekerjaan" class="form-label">Pekerjaan</label>
                <input type="text" id="pekerjaan" name="pekerjaan"
                       value="{{ old('pekerjaan', $tamu->pekerjaan) }}"
                       class="form-input">
            </div>

            {{-- Alamat --}}
            <div class="col-span-2">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea id="alamat" name="alamat" rows="2"
                          class="form-input">{{ old('alamat', $tamu->alamat) }}</textarea>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
            <a href="{{ route('tamu.show', $tamu) }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
