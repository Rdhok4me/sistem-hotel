@extends('layouts.app')

@section('title', 'Detail Tamu')

@section('content')

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('tamu.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <h1 class="text-xl font-semibold text-gray-800">Detail Tamu</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">

    {{-- Profil tamu --}}
    <div class="card p-5">
        {{-- Avatar inisial --}}
        <div class="flex items-center gap-4 mb-5 pb-5 border-b border-gray-100">
            <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center
                        text-lg font-semibold text-indigo-700 flex-shrink-0">
                {{ $tamu->inisial }}
            </div>
            <div>
                <p class="font-semibold text-gray-800 text-base">{{ $tamu->nama_lengkap }}</p>
                <p class="text-xs text-gray-400">
                    {{ $tamu->reservasi_count ?? $tamu->reservasi->count() }} kali menginap
                </p>
            </div>
        </div>

        {{-- Data identitas --}}
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-gray-400">NIK</dt>
                <dd class="font-mono text-gray-700 text-xs">{{ $tamu->nik }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">Jenis identitas</dt>
                <dd class="text-gray-700">{{ strtoupper($tamu->jenis_identitas) }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">Jenis kelamin</dt>
                <dd class="text-gray-700">
                    {{ $tamu->jenis_kelamin ? $tamu->jenis_kelamin_lengkap : '-' }}
                </dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">Tanggal lahir</dt>
                <dd class="text-gray-700">
                    {{ $tamu->tanggal_lahir ? $tamu->tanggal_lahir->format('d M Y') : '-' }}
                </dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">No. telepon</dt>
                <dd class="text-gray-700">{{ $tamu->no_telepon }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">Email</dt>
                <dd class="text-gray-700 text-xs">{{ $tamu->email ?? '-' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">Kota asal</dt>
                <dd class="text-gray-700">{{ $tamu->kota_asal ?? '-' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">Pekerjaan</dt>
                <dd class="text-gray-700">{{ $tamu->pekerjaan ?? '-' }}</dd>
            </div>
            @if($tamu->alamat)
            <div>
                <dt class="text-gray-400 mb-1">Alamat</dt>
                <dd class="text-gray-700 text-xs leading-relaxed">{{ $tamu->alamat }}</dd>
            </div>
            @endif
        </dl>

        <div class="mt-5 pt-5 border-t border-gray-100">
            <a href="{{ route('tamu.edit', $tamu) }}" class="btn-secondary w-full text-center block">
                Edit Data Tamu
            </a>
        </div>
    </div>

    {{-- Riwayat reservasi --}}
    <div class="md:col-span-2 card overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Riwayat Reservasi</h2>
        </div>

        @forelse($tamu->reservasi as $r)
        <div class="px-5 py-4 border-b border-gray-50 hover:bg-gray-50 transition-colors">
            <div class="flex justify-between items-start gap-3">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-mono text-xs text-gray-400">{{ $r->kode_reservasi }}</span>
                        @php $badge = $r->status_badge; @endphp
                        <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                    </div>
                    <p class="text-sm font-medium text-gray-800">
                        Kamar {{ $r->kamar->nomor_kamar }}
                        <span class="text-gray-400 font-normal">({{ $r->kamar->tipeKamar->nama }})</span>
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $r->tanggal_checkin->format('d M Y') }}
                        →
                        {{ $r->tanggal_checkout->format('d M Y') }}
                        <span class="text-gray-300 mx-1">·</span>
                        {{ $r->jumlah_malam }} malam
                    </p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-semibold text-gray-800">
                        Rp {{ number_format($r->total_harga, 0, ',', '.') }}
                    </p>
                    @if($r->pembayaran)
                        @php $pb = $r->pembayaran->status_badge; @endphp
                        <span class="badge {{ $pb['class'] }} mt-1">{{ $pb['label'] }}</span>
                    @endif
                    <div class="mt-2">
                        <a href="{{ route('reservasi.show', $r) }}"
                           class="text-xs text-indigo-600 hover:underline">Detail</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="px-5 py-12 text-center">
            <p class="text-gray-400 text-sm">Tamu ini belum memiliki riwayat reservasi.</p>
            <a href="{{ route('reservasi.create') }}?tamu={{ $tamu->id }}"
               class="btn-primary inline-block mt-3">
                Buat Reservasi
            </a>
        </div>
        @endforelse
    </div>
</div>

@endsection
