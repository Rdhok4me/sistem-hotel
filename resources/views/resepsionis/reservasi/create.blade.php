@extends('layouts.app')

@section('title', 'Buat Reservasi Baru')

@section('content')

<div class="max-w-2xl">

    <form method="POST" action="{{ route('reservasi.store') }}" novalidate>
        @csrf

        <div class="bg-white rounded-xl border border-gray-100 divide-y divide-gray-50">

            {{-- Bagian 1: Pilih Tamu --}}
            <div class="p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Data Tamu</h2>
                <div class="space-y-4">
                    <div>
                        <label for="tamu_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Tamu <span class="text-red-500">*</span>
                        </label>
                        <select id="tamu_id" name="tamu_id" required
                            class="w-full text-sm border rounded-lg px-3 py-2.5
                                   focus:outline-none focus:ring-2 focus:ring-indigo-300
                                   {{ $errors->has('tamu_id') ? 'border-red-400' : 'border-gray-200' }}">
                            <option value="">— Pilih Tamu —</option>
                            @foreach($tamu as $t)
                            <option value="{{ $t->id }}" {{ old('tamu_id') == $t->id ? 'selected' : '' }}>
                                {{ $t->nama_lengkap }} ({{ strtoupper($t->jenis_identitas) }}: {{ $t->nik }})
                            @endforeach
                        </select>
                        @error('tamu_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400 mt-1">
                            Tamu baru?
                            <a href="{{ route('tamu.create') }}" class="text-indigo-600 hover:underline">Daftarkan tamu terlebih dahulu</a>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Bagian 2: Pilih Kamar --}}
            <div class="p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Kamar</h2>
                <div>
                    <label for="kamar_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Pilih Kamar <span class="text-red-500">*</span>
                    </label>
                    <select id="kamar_id" name="kamar_id" required
                        class="w-full text-sm border rounded-lg px-3 py-2.5
                               focus:outline-none focus:ring-2 focus:ring-indigo-300
                               {{ $errors->has('kamar_id') ? 'border-red-400' : 'border-gray-200' }}">
                        <option value="">— Pilih Kamar Tersedia —</option>
                        @php $tipeSebelumnya = null; @endphp
                        @foreach($kamar as $k)
                            @if($tipeSebelumnya !== $k->tipeKamar->nama)
                                @if($tipeSebelumnya !== null) </optgroup> @endif
                                <optgroup label="{{ $k->tipeKamar->nama }} — Rp {{ number_format($k->tipeKamar->harga_per_malam, 0, ',', '.') }}/malam">
                                @php $tipeSebelumnya = $k->tipeKamar->nama; @endphp
                            @endif
                            <option value="{{ $k->id }}"
                                data-harga="{{ $k->tipeKamar->harga_per_malam }}"
                                {{ old('kamar_id') == $k->id ? 'selected' : '' }}>
                                Kamar {{ $k->nomor_kamar }} (Lantai {{ $k->lantai }})
                            </option>
                        @endforeach
                        @if($tipeSebelumnya !== null) </optgroup> @endif
                    </select>
                    @error('kamar_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Bagian 3: Tanggal & Tamu --}}
            <div class="p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Detail Menginap</h2>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="tanggal_checkin" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Tanggal Check-in <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal_checkin" name="tanggal_checkin"
                            value="{{ old('tanggal_checkin') }}"
                            min="{{ date('Y-m-d') }}" required
                            class="w-full text-sm border rounded-lg px-3 py-2.5
                                   focus:outline-none focus:ring-2 focus:ring-indigo-300
                                   {{ $errors->has('tanggal_checkin') ? 'border-red-400' : 'border-gray-200' }}">
                        @error('tanggal_checkin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="tanggal_checkout" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Tanggal Check-out <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal_checkout" name="tanggal_checkout"
                            value="{{ old('tanggal_checkout') }}" required
                            class="w-full text-sm border rounded-lg px-3 py-2.5
                                   focus:outline-none focus:ring-2 focus:ring-indigo-300
                                   {{ $errors->has('tanggal_checkout') ? 'border-red-400' : 'border-gray-200' }}">
                        @error('tanggal_checkout')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Kalkulasi otomatis --}}
                <div id="kalkulasi" class="hidden bg-indigo-50 border border-indigo-100 rounded-lg px-4 py-3 mb-4 text-sm text-indigo-800">
                    <span id="info-malam" class="font-semibold"></span> malam ×
                    Rp <span id="info-harga"></span>/malam =
                    <strong>Rp <span id="info-total"></span></strong>
                </div>

                <div class="w-32">
                    <label for="jumlah_tamu" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Jumlah Tamu <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="jumlah_tamu" name="jumlah_tamu"
                        value="{{ old('jumlah_tamu', 1) }}" min="1" max="10" required
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5
                               focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
            </div>

            {{-- Bagian 4: Catatan --}}
            <div class="p-5">
                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Catatan <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea id="catatan" name="catatan" rows="3"
                    placeholder="Permintaan khusus, kebutuhan tambahan, dll..."
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5
                           focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none">{{ old('catatan') }}</textarea>
            </div>

            {{-- Tombol aksi --}}
            <div class="p-5 flex items-center gap-3">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium
                           px-5 py-2.5 rounded-lg transition focus:outline-none focus:ring-2
                           focus:ring-indigo-300 focus:ring-offset-2">
                    Simpan Reservasi
                </button>
                <a href="{{ route('reservasi.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>

<script>
function hitungTotal() {
    const checkin  = document.getElementById('tanggal_checkin').value;
    const checkout = document.getElementById('tanggal_checkout').value;
    const opt      = document.getElementById('kamar_id').selectedOptions[0];
    const harga    = parseFloat(opt?.dataset.harga ?? 0);

    if (!checkin || !checkout || !harga) {
        document.getElementById('kalkulasi').classList.add('hidden');
        return;
    }

    const malam = Math.round((new Date(checkout) - new Date(checkin)) / 86400000);
    if (malam <= 0) {
        document.getElementById('kalkulasi').classList.add('hidden');
        return;
    }

    const fmt = n => n.toLocaleString('id-ID');
    document.getElementById('info-malam').textContent = malam;
    document.getElementById('info-harga').textContent = fmt(harga);
    document.getElementById('info-total').textContent = fmt(malam * harga);
    document.getElementById('kalkulasi').classList.remove('hidden');
}

['tanggal_checkin', 'tanggal_checkout', 'kamar_id'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', hitungTotal);
});
</script>
{{-- Modal Tambah Tamu --}}
<div id="modal-tamu" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-base font-semibold text-gray-800">Daftarkan Tamu Baru</h3>
            <button type="button" onclick="document.getElementById('modal-tamu').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
        </div>
        <form method="POST" action="{{ route('tamu.store') }}" id="form-tamu-modal">
            @csrf
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" required class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="nik" required class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon <span class="text-red-500">*</span></label>
                        <input type="text" name="no_telepon" required class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('modal-tamu').classList.add('hidden')"
                    class="px-4 py-2 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Simpan Tamu</button>
            </div>
        </form>
    </div>
</div>

@endsection
