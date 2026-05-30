@extends('layouts.app')

@section('title', 'Daftar Reservasi')

@section('content')

{{-- Header --}}
<div class="flex justify-between items-center mb-5">
    <p class="text-sm text-gray-400">{{ $reservasi->total() }} reservasi ditemukan</p>
    <a href="{{ route('reservasi.create') }}"
       class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Reservasi Baru
    </a>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-100 p-4 mb-4">
    <form method="GET" action="{{ route('reservasi.index') }}" class="flex flex-wrap gap-3">
        <input
            type="text" name="search"
            value="{{ request('search') }}"
            placeholder="Cari kode, nama tamu, NIK..."
            class="flex-1 min-w-48 text-sm border border-gray-200 rounded-lg px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-indigo-300"
        >
        <select name="status"
            class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">Semua status</option>
            @foreach(['pending','konfirmasi','checkin','checkout','batal'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                </option>
            @endforeach
        </select>
        <input type="date" name="tanggal" value="{{ request('tanggal') }}"
            class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
        <button type="submit"
            class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition">
            Cari
        </button>
        @if(request()->hasAny(['search','status','tanggal']))
        <a href="{{ route('reservasi.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2">Reset</a>
        @endif
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs text-gray-500 font-medium px-4 py-3">Kode</th>
                    <th class="text-left text-xs text-gray-500 font-medium px-4 py-3">Tamu</th>
                    <th class="text-left text-xs text-gray-500 font-medium px-4 py-3">Kamar</th>
                    <th class="text-left text-xs text-gray-500 font-medium px-4 py-3">Check-in</th>
                    <th class="text-left text-xs text-gray-500 font-medium px-4 py-3">Check-out</th>
                    <th class="text-left text-xs text-gray-500 font-medium px-4 py-3">Total</th>
                    <th class="text-left text-xs text-gray-500 font-medium px-4 py-3">Status</th>
                    <th class="text-left text-xs text-gray-500 font-medium px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($reservasi as $r)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $r->kode_reservasi }}</td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $r->tamu->nama_lengkap }}</p>
                        <p class="text-xs text-gray-400">{{ $r->tamu->nik }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $r->kamar->nomor_kamar }}</p>
                        <p class="text-xs text-gray-400">{{ $r->kamar->tipeKamar->nama }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $r->tanggal_checkin->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $r->tanggal_checkout->format('d M Y') }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">
                        Rp {{ number_format($r->total_harga, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3">
                        <x-status-badge :status="$r->status" />
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('reservasi.show', $r) }}"
                           class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Tidak ada data reservasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($reservasi->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $reservasi->links() }}
    </div>
    @endif
</div>
@endsection
