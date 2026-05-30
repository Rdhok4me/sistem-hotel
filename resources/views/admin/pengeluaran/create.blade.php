@extends('layouts.app')
@section('title', 'Tambah Pengeluaran')
@section('content')

<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.pengeluaran.store') }}">
        @csrf
        <div class="card divide-y divide-gray-50">
            <div class="p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Detail Pengeluaran</h2>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Keterangan <span class="text-red-500">*</span></label>
                        <input type="text" name="keterangan" value="{{ old('keterangan') }}" required
                            class="form-input" placeholder="Contoh: Bayar listrik bulan Mei">
                        @error('keterangan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori" required class="form-input">
                                <option value="">-- Pilih --</option>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori') === $kat ? 'selected' : '' }}>
                                        {{ ucfirst($kat) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', today()->format('Y-m-d')) }}"
                                required class="form-input">
                            @error('tanggal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Jumlah (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah" value="{{ old('jumlah') }}" required min="0"
                            class="form-input" placeholder="Contoh: 500000">
                        @error('jumlah')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Catatan <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <textarea name="catatan" rows="3" class="form-input resize-none"
                            placeholder="Keterangan tambahan...">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="p-5 flex gap-3">
                <button type="submit" class="btn-primary">Simpan Pengeluaran</button>
                <a href="{{ route('admin.pengeluaran.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection