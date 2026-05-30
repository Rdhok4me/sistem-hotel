@extends('layouts.app')
@section('title', 'Tambah Tamu Baru')
@section('content')

<div class="max-w-2xl">
    <form method="POST" action="{{ route('tamu.store') }}" novalidate>
        @csrf
        <div class="bg-white rounded-xl border border-gray-100 divide-y divide-gray-50">

            <div class="p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Identitas Tamu</h2>
                <div class="grid grid-cols-2 gap-4">

                    {{-- Nama Lengkap --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                            class="w-full text-sm border rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-300
                                   {{ $errors->has('nama_lengkap') ? 'border-red-400' : 'border-gray-200' }}">
                        @error('nama_lengkap')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Identitas --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Jenis Identitas <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_identitas" required
                            class="w-full text-sm border rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-300
                                   {{ $errors->has('jenis_identitas') ? 'border-red-400' : 'border-gray-200' }}">
                            <option value="">— Pilih —</option>
                            <option value="ktp"    {{ old('jenis_identitas') === 'ktp'    ? 'selected' : '' }}>KTP</option>
                            <option value="sim"    {{ old('jenis_identitas') === 'sim'    ? 'selected' : '' }}>SIM</option>
                            <option value="paspor" {{ old('jenis_identitas') === 'paspor' ? 'selected' : '' }}>Paspor</option>
                        </select>
                        @error('jenis_identitas')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIK --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            NIK / No. Identitas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="16"
                            placeholder="16 digit NIK"
                            class="w-full text-sm border rounded-lg px-3 py-2.5 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-300
                                   {{ $errors->has('nik') ? 'border-red-400' : 'border-gray-200' }}">
                        @error('nik')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin</label>
                        <select name="jenis_kelamin"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">— Pilih —</option>
                            <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>

                    {{-- No. Telepon --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            No. Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" required
                            class="w-full text-sm border rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-300
                                   {{ $errors->has('no_telepon') ? 'border-red-400' : 'border-gray-200' }}">
                        @error('no_telepon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-300
                                   {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kota Asal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kota Asal</label>
                        <input type="text" name="kota_asal" value="{{ old('kota_asal') }}"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>

                    {{-- Pekerjaan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Pekerjaan</label>
                        <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>

                    {{-- Alamat --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                        <textarea name="alamat" rows="2"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none">{{ old('alamat') }}</textarea>
                    </div>

                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="p-5 flex items-center gap-3">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:ring-offset-2">
                    Simpan Data Tamu
                </button>
                <a href="{{ route('tamu.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">
                    Batal
                </a>
            </div>

        </div>
    </form>
</div>

@endsection