@extends('layouts.app')

@section('title', 'Edit Reservasi ' . $reservasi->kode_reservasi)

@section('content')

<div class="max-w-2xl">
    <form method="POST" action="{{ route('reservasi.update', $reservasi) }}" novalidate>
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl border border-gray-100 divide-y divide-gray-50">

            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-gray-700">Edit Reservasi</h2>
                    <span class="font-mono text-xs text-gray-400">{{ $reservasi->kode_reservasi }}</span>
                </div>

                {{-- Info kamar & tamu (read-only) --}}
                <div class="grid grid-cols-2 gap-4 mb-5 p-4 bg-gray-50 rounded-lg text-sm">
                    <div>
                        <p class="text-gray-400 text-xs mb-0.5">Tamu</p>
                        <p class="font-medium text-gray-800">{{ $reservasi->tamu->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-0.5">Kamar</p>
                        <p class="font-medium text-gray-800">
                            {{ $reservasi->kamar->nomor_kamar }} ({{ $reservasi->kamar->tipeKamar->nama }})
                        </p>
                    </div>
                </div>

                {{-- Tanggal --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="tanggal_checkin" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Check-in <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal_checkin" name="tanggal_checkin"
                            value="{{ old('tanggal_checkin', $reservasi->tanggal_checkin->format('Y-m-d')) }}"
                            required
                            class="w-full text-sm border rounded-lg px-3 py-2.5
                                   focus:outline-none focus:ring-2 focus:ring-indigo-300
                                   {{ $errors->has('tanggal_checkin') ? 'border-red-400' : 'border-gray-200' }}">
                        @error('tanggal_checkin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="tanggal_checkout" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Check-out <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal_checkout" name="tanggal_checkout"
                            value="{{ old('tanggal_checkout', $reservasi->tanggal_checkout->format('Y-m-d')) }}"
                            required
                            class="w-full text-sm border rounded-lg px-3 py-2.5
                                   focus:outline-none focus:ring-2 focus:ring-indigo-300
                                   {{ $errors->has('tanggal_checkout') ? 'border-red-400' : 'border-gray-200' }}">
                        @error('tanggal_checkout')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Kalkulasi --}}
                <div id="kalkulasi" class="bg-indigo-50 border border-indigo-100 rounded-lg px-4 py-3 mb-4 text-sm text-indigo-800">
                    <span id="info-malam"></span> malam ×
                    Rp <span id="info-harga">{{ number_format($reservasi->harga_per_malam, 0, ',', '.') }}</span>/malam =
                    <strong>Rp <span id="info-total"></span></strong>
                </div>

                {{-- Jumlah tamu --}}
                <div class="w-32 mb-4">
                    <label for="jumlah_tamu" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Jumlah Tamu
                    </label>
                    <input type="number" id="jumlah_tamu" name="jumlah_tamu"
                        value="{{ old('jumlah_tamu', $reservasi->jumlah_tamu) }}"
                        min="1" max="10" required
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5
                               focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>

                {{-- Catatan --}}
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Catatan <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea id="catatan" name="catatan" rows="3"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5
                               focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none">{{ old('catatan', $reservasi->catatan) }}</textarea>
                </div>
            </div>

            <div class="p-5 flex items-center gap-3">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium
                           px-5 py-2.5 rounded-lg transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('reservasi.show', $reservasi) }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>

<script>
const hargaPerMalam = {{ $reservasi->harga_per_malam }};

function hitungTotal() {
    const ci  = document.getElementById('tanggal_checkin').value;
    const co  = document.getElementById('tanggal_checkout').value;
    if (!ci || !co) return;

    const malam = Math.round((new Date(co) - new Date(ci)) / 86400000);
    if (malam <= 0) return;

    const fmt = n => n.toLocaleString('id-ID');
    document.getElementById('info-malam').textContent = malam;
    document.getElementById('info-total').textContent = fmt(malam * hargaPerMalam);
}

hitungTotal();
['tanggal_checkin','tanggal_checkout'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', hitungTotal);
});
</script>
@endsection
