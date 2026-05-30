@extends('layouts.app')
@section('title', 'Data Pengeluaran')
@section('content')

<div class="flex justify-between items-center mb-5">
    <div>
        <h1 class="text-xl font-semibold text-gray-800">Data Pengeluaran</h1>
        <p class="text-sm text-gray-400 mt-0.5">Kelola pengeluaran operasional hotel</p>
    </div>
    <a href="{{ route('admin.pengeluaran.create') }}" class="btn-primary">+ Tambah Pengeluaran</a>
</div>

@include('components.alert')

{{-- Filter --}}
<form method="GET" action="{{ route('admin.pengeluaran.index') }}" class="flex gap-2 mb-5 flex-wrap">
    <select name="bulan" onchange="this.form.submit()" class="form-input w-auto">
        @foreach(range(1,12) as $bln)
            <option value="{{ $bln }}" {{ $bulan == $bln ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create(null, $bln)->translatedFormat('F') }}
            </option>
        @endforeach
    </select>
    <select name="tahun" onchange="this.form.submit()" class="form-input w-auto">
        @foreach(range(now()->year, now()->year - 3) as $thn)
            <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
        @endforeach
    </select>
    <select name="kategori" onchange="this.form.submit()" class="form-input w-auto">
        <option value="">Semua kategori</option>
        @foreach($kategoriList as $kat)
            <option value="{{ $kat }}" {{ request('kategori') === $kat ? 'selected' : '' }}>
                {{ ucfirst($kat) }}
            </option>
        @endforeach
    </select>
</form>

{{-- Statistik --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
    <div class="card p-4 col-span-2">
        <p class="text-xs text-gray-500 mb-1">Total Pengeluaran Bulan Ini</p>
        <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
    </div>
    @foreach($perKategori as $kat)
    <div class="card p-4">
        <p class="text-xs text-gray-500 mb-1">{{ ucfirst($kat->kategori) }}</p>
        <p class="text-lg font-semibold text-gray-800">Rp {{ number_format($kat->total, 0, ',', '.') }}</p>
    </div>
    @endforeach
</div>

{{-- Tabel --}}
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50">
                <th class="table-header">Tanggal</th>
                <th class="table-header">Keterangan</th>
                <th class="table-header">Kategori</th>
                <th class="table-header">Jumlah</th>
                <th class="table-header">Dicatat Oleh</th>
                <th class="table-header text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($pengeluaran as $p)
            <tr class="hover:bg-gray-50">
                <td class="table-cell">{{ $p->tanggal->translatedFormat('d M Y') }}</td>
                <td class="table-cell">
                    <p class="font-medium text-gray-800">{{ $p->keterangan }}</p>
                    @if($p->catatan)
                        <p class="text-xs text-gray-400">{{ $p->catatan }}</p>
                    @endif
                </td>
                <td class="table-cell">
                    <span class="badge bg-indigo-50 text-indigo-700">{{ ucfirst($p->kategori) }}</span>
                </td>
                <td class="table-cell font-semibold text-red-600">
                    Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                </td>
                <td class="table-cell text-gray-500">{{ $p->user->name }}</td>
                <td class="table-cell text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.pengeluaran.edit', $p) }}"
                           class="text-xs text-indigo-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.pengeluaran.destroy', $p) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit"
                                data-confirm="Hapus pengeluaran ini?"
                                class="text-xs text-red-600 hover:underline">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-10 text-center text-gray-400">
                    Tidak ada data pengeluaran bulan ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($pengeluaran->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $pengeluaran->links() }}
    </div>
    @endif
</div>
@endsection