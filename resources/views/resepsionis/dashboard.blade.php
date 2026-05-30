@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Sambutan --}}
<div class="flex justify-between items-start mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-900">Selamat datang, {{ auth()->user()->name }}</h2>
        <p class="text-sm text-gray-400 mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <a href="{{ route('reservasi.create') }}"
       class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Reservasi Baru
    </a>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
        <p class="text-xs text-blue-500 font-medium mb-1">Check-in Hari Ini</p>
        <p class="text-3xl font-bold text-blue-700">{{ $checkInHariIni }}</p>
        <p class="text-xs text-blue-400 mt-1">tamu dijadwalkan masuk</p>
    </div>
    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
        <p class="text-xs text-amber-500 font-medium mb-1">Check-out Hari Ini</p>
        <p class="text-3xl font-bold text-amber-700">{{ $checkOutHariIni }}</p>
        <p class="text-xs text-amber-400 mt-1">tamu dijadwalkan keluar</p>
    </div>
    <div class="bg-red-50 border border-red-100 rounded-xl p-4">
        <p class="text-xs text-red-500 font-medium mb-1">Tagihan Tertunda</p>
        <p class="text-3xl font-bold text-red-700">{{ $pendingPembayaran }}</p>
        <p class="text-xs text-red-400 mt-1">reservasi belum lunas</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Aktivitas hari ini --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Aktivitas Hari Ini</h2>

        @forelse($aktivitasHariIni as $r)
        <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-indigo-50 flex items-center justify-center flex-shrink-0">
                    <span class="text-indigo-600 font-semibold text-xs">
                        {{ strtoupper(substr($r->tamu->nama_lengkap, 0, 2)) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $r->tamu->nama_lengkap }}</p>
                    <p class="text-xs text-gray-400">
                        Kamar {{ $r->kamar->nomor_kamar }}
                        @if($r->status === 'konfirmasi' && $r->tanggal_checkin->isToday())
                            · Check-in hari ini
                        @elseif($r->status === 'checkin' && $r->tanggal_checkout->isToday())
                            · Check-out hari ini
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <x-status-badge :status="$r->status" />
                <a href="{{ route('reservasi.show', $r) }}"
                   class="text-xs text-indigo-600 hover:underline">Detail</a>
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-gray-400">
            <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm">Tidak ada aktivitas hari ini</p>
        </div>
        @endforelse
    </div>

    {{-- Kamar tersedia --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-semibold text-gray-700">Kamar Tersedia</h2>
            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                {{ $kamarTersedia->count() }} kamar
            </span>
        </div>

        @forelse($kamarTersedia->take(10) as $k)
        <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
            <div class="flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
                <div>
                    <span class="text-sm font-medium text-gray-800">Kamar {{ $k->nomor_kamar }}</span>
                    <span class="text-xs text-gray-400 ml-1">({{ $k->tipeKamar->nama }})</span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm font-medium text-gray-700">
                    Rp {{ number_format($k->tipeKamar->harga_per_malam, 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-400">per malam</p>
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-gray-400">
            <p class="text-sm">Semua kamar sedang terisi</p>
        </div>
        @endforelse

        @if($kamarTersedia->count() > 10)
        <div class="mt-3 text-center">
            <a href="{{ route('kamar.index', ['status' => 'tersedia']) }}"
               class="text-xs text-indigo-600 hover:underline">
                Lihat {{ $kamarTersedia->count() - 10 }} kamar lainnya
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
