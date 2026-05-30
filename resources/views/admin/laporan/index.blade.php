@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')

{{-- Header --}}
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
    <h1 class="text-xl font-semibold text-gray-800">Laporan Keuangan</h1>

    <form method="GET" action="{{ route('admin.laporan') }}" class="flex flex-wrap items-center gap-2">
        <select name="periode" onchange="this.form.submit()"
            class="form-input w-auto">
            <option value="harian"   {{ $periode === 'harian'   ? 'selected' : '' }}>Harian</option>
            <option value="mingguan" {{ $periode === 'mingguan' ? 'selected' : '' }}>Mingguan</option>
            <option value="bulanan"  {{ $periode === 'bulanan'  ? 'selected' : '' }}>Bulanan</option>
        </select>

        @if($periode === 'bulanan')
        <select name="bulan" onchange="this.form.submit()" class="form-input w-auto">
            @foreach(range(1,12) as $bln)
                <option value="{{ $bln }}" {{ $bulan == $bln ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create(null, $bln)->translatedFormat('F') }}
                </option>
            @endforeach
        </select>
        <select name="tahun" onchange="this.form.submit()" class="form-input w-auto">
            @foreach(range(now()->year, now()->year - 4) as $thn)
                <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
            @endforeach
        </select>
        @endif

        <a href="{{ route('admin.laporan.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
           class="btn-primary flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export Excel
        </a>
    </form>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-indigo-50 rounded-xl p-4">
        <p class="text-xs text-indigo-500 mb-1">Total pemasukan</p>
        <p class="text-2xl font-semibold text-indigo-700">
            Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
        </p>
        <p class="text-xs text-indigo-400 mt-1">{{ ucfirst($periode) }}</p>
    </div>
    <div class="bg-gray-50 rounded-xl p-4">
        <p class="text-xs text-gray-500 mb-1">Jumlah transaksi</p>
        <p class="text-2xl font-semibold text-gray-700">{{ $pembayaran->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">pembayaran lunas</p>
    </div>
    <div class="bg-emerald-50 rounded-xl p-4">
        <p class="text-xs text-emerald-500 mb-1">Rata-rata / hari</p>
        <p class="text-2xl font-semibold text-emerald-700">
            Rp {{ number_format($rerataHarian, 0, ',', '.') }}
        </p>
        <p class="text-xs text-emerald-400 mt-1">dalam periode ini</p>
    </div>
    <div class="bg-amber-50 rounded-xl p-4">
        <p class="text-xs text-amber-500 mb-1">Total reservasi</p>
        <p class="text-2xl font-semibold text-amber-700">{{ $totalReservasi }}</p>
        <p class="text-xs text-amber-400 mt-1">tidak termasuk batal</p>
    </div>
</div>

{{-- Grafik + ringkasan per tipe --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

    {{-- Grafik harian --}}
    <div class="md:col-span-2 card p-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Grafik pemasukan harian</h2>
        <div style="height: 200px; position: relative;">
            <canvas id="chartHarian" role="img"
                aria-label="Grafik pemasukan harian {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}">
            </canvas>
        </div>
    </div>

    {{-- Ringkasan per tipe kamar --}}
    <div class="card p-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Per tipe kamar</h2>
        <div class="space-y-3">
            @foreach($perTipe as $nama => $data)
            <div>
                <div class="flex justify-between text-xs text-gray-600 mb-1">
                    <span class="font-medium">{{ $nama }}</span>
                    <span>{{ $data['jumlah'] }} transaksi</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    @php
                        $pct = $totalPemasukan > 0
                            ? round($data['total'] / $totalPemasukan * 100)
                            : 0;
                    @endphp
                    <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-0.5">
                    Rp {{ number_format($data['total'], 0, ',', '.') }}
                    <span class="text-gray-300 mx-1">·</span>
                    {{ $pct }}%
                </p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Tabel transaksi --}}
<div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-700">Rincian transaksi</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="table-header">Kode</th>
                    <th class="table-header">Tamu</th>
                    <th class="table-header">Kamar</th>
                    <th class="table-header">Metode</th>
                    <th class="table-header">Waktu</th>
                    <th class="table-header text-right">Jumlah</th>
                    <th class="table-header">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pembayaran as $p)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="table-cell font-mono text-xs text-gray-400">
                        {{ $p->kode_pembayaran }}
                    </td>
                    <td class="table-cell font-medium text-gray-800">
                        {{ $p->reservasi->tamu->nama_lengkap }}
                    </td>
                    <td class="table-cell">
                        {{ $p->reservasi->kamar->nomor_kamar }}
                        <span class="text-gray-400 text-xs">
                            ({{ $p->reservasi->kamar->tipeKamar->nama }})
                        </span>
                    </td>
                    <td class="table-cell">{{ $p->metode_bayar_label }}</td>
                    <td class="table-cell text-gray-500">
                        {{ $p->waktu_bayar?->format('d M Y H:i') }}
                    </td>
                    <td class="table-cell text-right font-semibold text-gray-800">
                        Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}
                    </td>
                    <td class="table-cell">
                        @php $badge = $p->status_badge; @endphp
                        <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-gray-400 text-sm">
                        Tidak ada transaksi pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($pembayaran->count() > 0)
            <tfoot>
                <tr class="bg-indigo-50 border-t-2 border-indigo-200">
                    <td colspan="5" class="px-4 py-3 text-sm font-semibold text-indigo-800">
                        Total Pemasukan
                    </td>
                    <td class="px-4 py-3 text-right font-bold text-indigo-800">
                        Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const grafikHarian = @json($grafikHarian);
const isDark = matchMedia('(prefers-color-scheme: dark)').matches;
const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';

new Chart(document.getElementById('chartHarian'), {
    type: 'bar',
    data: {
        labels: grafikHarian.map(d => d.tanggal),
        datasets: [{
            label: 'Pemasukan',
            data: grafikHarian.map(d => Math.round(d.total / 1000)),
            backgroundColor: '#4f46e5',
            borderRadius: 4,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: c => 'Rp ' + (c.parsed.y * 1000).toLocaleString('id-ID')
                }
            }
        },
        scales: {
            x: { grid: { color: gridColor }, ticks: { color: '#9ca3af', font: { size: 10 }, maxRotation: 45 } },
            y: { grid: { color: gridColor }, ticks: { color: '#9ca3af', font: { size: 10 }, callback: v => v + 'rb' }, beginAtZero: true }
        }
    }
});
</script>

@endsection
