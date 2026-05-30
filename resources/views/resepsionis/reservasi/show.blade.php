@extends('layouts.app')

@section('title', 'Detail Reservasi ' . $reservasi->kode_reservasi)

@section('content')

<div class="flex justify-between items-start mb-5">
    <div class="flex items-center gap-2">
        <a href="{{ route('reservasi.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">Reservasi</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-600 font-mono">{{ $reservasi->kode_reservasi }}</span>
    </div>
    <div class="flex items-center gap-2">
        @if(in_array($reservasi->status, ['pending', 'konfirmasi']))
        <a href="{{ route('reservasi.edit', $reservasi) }}"
           class="text-sm border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 text-gray-600">
            Edit
        </a>
        @endif
        @can('invoice.print')
        @if($reservasi->pembayaran)
        <a href="{{ route('pembayaran.invoice', $reservasi) }}" target="_blank"
           class="flex items-center gap-1.5 text-sm border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 text-gray-600">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak Invoice
        </a>
        @endif
        @endcan
        @if(!in_array($reservasi->status, ['checkout', 'batal']))
        <form method="POST" action="{{ route('reservasi.destroy', $reservasi) }}"
              onsubmit="return confirm('Batalkan reservasi ini?')">
            @csrf @method('DELETE')
            <button type="submit"
                class="text-sm text-red-600 border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-50">
                Batalkan
            </button>
        </form>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Kolom kiri: info utama --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Info reservasi --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-sm font-semibold text-gray-700">Informasi Reservasi</h2>
                <x-status-badge :status="$reservasi->status" />
            </div>
            <div class="grid grid-cols-2 gap-x-8 gap-y-3 text-sm">
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Kode Reservasi</p>
                    <p class="font-mono font-medium text-gray-800">{{ $reservasi->kode_reservasi }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Diinput oleh</p>
                    <p class="font-medium text-gray-800">{{ $reservasi->user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Check-in</p>
                    <p class="font-medium text-gray-800">{{ $reservasi->tanggal_checkin->translatedFormat('l, d F Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Check-out</p>
                    <p class="font-medium text-gray-800">{{ $reservasi->tanggal_checkout->translatedFormat('l, d F Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Durasi</p>
                    <p class="font-medium text-gray-800">{{ $reservasi->jumlah_malam }} malam</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Jumlah Tamu</p>
                    <p class="font-medium text-gray-800">{{ $reservasi->jumlah_tamu }} orang</p>
                </div>
                @if($reservasi->catatan)
                <div class="col-span-2">
                    <p class="text-gray-400 text-xs mb-0.5">Catatan</p>
                    <p class="text-gray-700">{{ $reservasi->catatan }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Info tamu --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-semibold text-gray-700">Data Tamu</h2>
                <a href="{{ route('tamu.show', $reservasi->tamu) }}"
                   class="text-xs text-indigo-600 hover:underline">Lihat profil</a>
            </div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-indigo-700 font-semibold text-sm">{{ $reservasi->tamu->inisial }}</span>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $reservasi->tamu->nama_lengkap }}</p>
                    <p class="text-xs text-gray-400">{{ $reservasi->tamu->jenis_kelamin_lengkap }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">NIK</p>
                    <p class="font-mono text-gray-700">{{ $reservasi->tamu->nik }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Telepon</p>
                    <p class="text-gray-700">{{ $reservasi->tamu->no_telepon }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-400 text-xs">Alamat</p>
                    <p class="text-gray-700">{{ $reservasi->tamu->alamat ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Riwayat aktivitas --}}
        @if($reservasi->checkIn || $reservasi->checkOut)
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Riwayat Aktivitas</h2>
            <div class="space-y-3">
                @if($reservasi->checkIn)
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Check-in dilakukan</p>
                        <p class="text-xs text-gray-400">
                            {{ $reservasi->checkIn->waktu_checkin->translatedFormat('d F Y, H:i') }}
                            · oleh {{ $reservasi->checkIn->user->name }}
                        </p>
                        @if($reservasi->checkIn->catatan)
                        <p class="text-xs text-gray-500 mt-0.5">{{ $reservasi->checkIn->catatan }}</p>
                        @endif
                    </div>
                </div>
                @endif
                @if($reservasi->checkOut)
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Check-out dilakukan</p>
                        <p class="text-xs text-gray-400">
                            {{ $reservasi->checkOut->waktu_checkout->translatedFormat('d F Y, H:i') }}
                            · oleh {{ $reservasi->checkOut->user->name }}
                        </p>
                        @if($reservasi->checkOut->biaya_tambahan > 0)
                        <p class="text-xs text-gray-500 mt-0.5">
                            Biaya tambahan: Rp {{ number_format($reservasi->checkOut->biaya_tambahan, 0, ',', '.') }}
                            @if($reservasi->checkOut->keterangan_biaya)
                                ({{ $reservasi->checkOut->keterangan_biaya }})
                            @endif
                        </p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Kolom kanan: kamar, pembayaran, aksi --}}
    <div class="space-y-4">

        {{-- Info kamar --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Kamar</h2>
            <div class="text-center mb-3">
                <p class="text-3xl font-bold text-indigo-600">{{ $reservasi->kamar->nomor_kamar }}</p>
                <p class="text-sm text-gray-500">{{ $reservasi->kamar->tipeKamar->nama }}</p>
                <p class="text-xs text-gray-400">Lantai {{ $reservasi->kamar->lantai }}</p>
            </div>
            <div class="border-t border-gray-50 pt-3 space-y-1.5 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Harga/malam</span>
                    <span class="font-medium">Rp {{ number_format($reservasi->harga_per_malam, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ $reservasi->jumlah_malam }} malam</span>
                    <span class="font-medium">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</span>
                </div>
                @if($reservasi->checkOut?->biaya_tambahan > 0)
                <div class="flex justify-between">
                    <span class="text-gray-500">Biaya tambahan</span>
                    <span class="font-medium">Rp {{ number_format($reservasi->checkOut->biaya_tambahan, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between border-t border-gray-100 pt-1.5 font-semibold">
                    <span>Total</span>
                    <span class="text-indigo-600">
                        Rp {{ number_format($reservasi->total_akhir, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Pembayaran --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Pembayaran</h2>
            @if($reservasi->pembayaran)
                <div class="space-y-2 text-sm mb-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <x-status-badge :status="$reservasi->pembayaran->status_bayar" type="pembayaran" />
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Metode</span>
                        <span class="font-medium">{{ $reservasi->pembayaran->metode_bayar_label }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Dibayar</span>
                        <span class="font-medium">Rp {{ number_format($reservasi->pembayaran->jumlah_bayar, 0, ',', '.') }}</span>
                    </div>
                    @if($reservasi->pembayaran->sisa_pembayaran > 0)
                    <div class="flex justify-between text-red-600">
                        <span>Sisa</span>
                        <span class="font-semibold">Rp {{ number_format($reservasi->pembayaran->sisa_pembayaran, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            @else
                <p class="text-sm text-gray-400 mb-3">Belum ada pembayaran</p>
            @endif

            @can('pembayaran.create')
            @if(!in_array($reservasi->status, ['batal', 'checkout']) && ($reservasi->pembayaran?->status_bayar !== 'lunas'))
            <form method="POST" action="{{ route('pembayaran.store', $reservasi) }}" class="space-y-3">
                @csrf
                <select name="metode_bayar" required
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">Pilih metode</option>
                    <option value="tunai">Tunai</option>
                    <option value="transfer_bank">Transfer Bank</option>
                    <option value="kartu_kredit">Kartu Kredit</option>
                    <option value="kartu_debit">Kartu Debit</option>
                    <option value="qris">QRIS</option>
                </select>
                <input type="number" name="jumlah_bayar"
                    placeholder="Jumlah bayar (Rp)"
                    min="1"
                    value="{{ $reservasi->total_akhir }}"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-2 rounded-lg transition">
                    Catat Pembayaran
                </button>
            </form>
            @endif
            @endcan
        </div>

        {{-- Aksi Check-in / Check-out --}}
        @if($reservasi->status === 'konfirmasi')
        @can('checkin.create')
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Proses Check-in</h2>
            <form method="POST" action="{{ route('checkin.store', $reservasi) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="text-xs text-gray-500 mb-1 block">Jumlah tamu aktual</label>
                    <input type="number" name="jumlah_tamu_aktual"
                        value="{{ $reservasi->jumlah_tamu }}" min="1"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <input type="text" name="catatan" placeholder="Catatan (opsional)"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 rounded-lg transition">
                    Proses Check-in
                </button>
            </form>
        </div>
        @endcan
        @endif

        @if($reservasi->status === 'checkin')
        @can('checkout.create')
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Proses Check-out</h2>
            <form method="POST" action="{{ route('checkout.store', $reservasi) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="text-xs text-gray-500 mb-1 block">Biaya tambahan (Rp)</label>
                    <input type="number" name="biaya_tambahan" value="0" min="0"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <input type="text" name="keterangan_biaya" placeholder="Keterangan biaya (misal: minibar)"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <input type="text" name="catatan" placeholder="Catatan (opsional)"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <button type="submit"
                    class="w-full bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium py-2 rounded-lg transition"
                    onclick="return confirm('Proses check-out sekarang?')">
                    Proses Check-out
                </button>
            </form>
        </div>
        @endcan
        @endif

    </div>
</div>
@endsection
