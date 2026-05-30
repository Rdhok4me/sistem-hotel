@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

{{-- Filter periode --}}
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
    <div>
        <p class="text-sm text-gray-400">Ringkasan operasional hotel</p>
    </div>
    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-center gap-2">
        <select name="bulan" onchange="this.form.submit()"
            class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-300">
            @foreach(range(1,12) as $bln)
                <option value="{{ $bln }}" {{ $bulan == $bln ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create(null, $bln)->translatedFormat('F') }}
                </option>
            @endforeach
        </select>
        <select name="tahun" onchange="this.form.submit()"
            class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-300">
            @foreach(range(now()->year, now()->year - 3) as $thn)
                <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
            @endforeach
        </select>
        <a href="{{ route('admin.laporan.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
           class="flex items-center gap-1.5 text-sm bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export Excel
        </a>
    </form>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <p class="text-xs text-gray-500 mb-1">Total Pemasukan</p>
        <p class="text-2xl font-semibold text-gray-900">
            Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
        </p>
        <p class="text-xs text-gray-400 mt-1">bulan {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <p class="text-xs text-gray-500 mb-1">Total Reservasi</p>
        <p class="text-2xl font-semibold text-gray-900">{{ $totalReservasi }}</p>
        <p class="text-xs text-gray-400 mt-1">transaksi bulan ini</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <p class="text-xs text-gray-500 mb-1">Okupansi</p>
        <p class="text-2xl font-semibold text-gray-900">{{ $okupansi }}%</p>
        <p class="text-xs text-gray-400 mt-1">{{ $kamarTerisi }} / {{ $totalKamar }} kamar terisi</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <p class="text-xs text-gray-500 mb-1">Tamu Aktif</p>
        <p class="text-2xl font-semibold text-gray-900">{{ $tamuAktif }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $checkOutHariIni }} checkout hari ini</p>
    </div>
</div>

{{-- Grafik --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-5 mb-5">

    {{-- Grafik pemasukan bulanan --}}
    <div class="lg:col-span-3 bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-semibold text-gray-700">Pemasukan {{ $tahun }}</h2>
            <div class="flex gap-4 text-xs text-gray-400">
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-sm bg-indigo-500 inline-block"></span> Pemasukan
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-4 h-0 border-t-2 border-dashed border-emerald-500 inline-block"></span> Rata-rata
                </span>
            </div>
        </div>
        <div class="relative" style="height:220px">
            <canvas id="chartPemasukan"
                role="img"
                aria-label="Grafik bar pemasukan hotel per bulan tahun {{ $tahun }}">
            </canvas>
        </div>
    </div>

    {{-- Donut tipe kamar --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Reservasi per Tipe</h2>
        <div class="flex flex-col items-center gap-4">
            <div class="relative" style="height:160px;width:160px">
                <canvas id="chartTipe"
                    role="img"
                    aria-label="Donut chart proporsi reservasi per tipe kamar">
                </canvas>
            </div>
            <div class="w-full space-y-2">
                @php $palette = ['#4f46e5','#10b981','#f59e0b','#ef4444','#8b5cf6']; @endphp
                @foreach($tipeKamarStat->sortByDesc('total_reservasi') as $tipe)
                <div class="flex items-center justify-between text-sm">
                    <span class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-sm flex-shrink-0"
                              style="background:{{ $palette[$loop->index % 5] }}"></span>
                        <span class="text-gray-600">{{ $tipe->nama }}</span>
                    </span>
                    <span class="font-semibold text-gray-800">{{ $tipe->total_reservasi }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Grid kamar & reservasi terbaru --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Grid visual kamar --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Status Kamar</h2>
        <div class="grid grid-cols-6 gap-1.5 mb-3" role="list" aria-label="Status semua kamar">
            @foreach($semuaKamar as $k)
            @php
                $cls = match($k->status) {
                    'tersedia'    => 'bg-emerald-100 text-emerald-800',
                    'terisi'      => 'bg-blue-100 text-blue-800',
                    'dibersihkan' => 'bg-amber-100 text-amber-800',
                    default       => 'bg-red-100 text-red-700',
                };
            @endphp
            <div role="listitem"
                 title="{{ $k->nomor_kamar }} — {{ ucfirst($k->status) }}"
                 class="rounded-md py-1.5 text-center text-xs font-medium cursor-default {{ $cls }}">
                {{ $k->nomor_kamar }}
            </div>
            @endforeach
        </div>
        <div class="flex flex-wrap gap-3 text-xs text-gray-500">
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-emerald-100 inline-block"></span> Tersedia</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-blue-100 inline-block"></span> Terisi</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-amber-100 inline-block"></span> Dibersihkan</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-red-100 inline-block"></span> Maintenance</span>
        </div>
    </div>

    {{-- Reservasi terbaru --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-sm font-semibold text-gray-700">Reservasi Terbaru</h2>
            <a href="{{ route('reservasi.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat semua</a>
        </div>
        <div class="space-y-0 divide-y divide-gray-50">
            @foreach($reservasiTerbaru as $r)
            <div class="flex items-center justify-between py-2.5">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $r->tamu->nama_lengkap }}</p>
                    <p class="text-xs text-gray-400">
                        Kamar {{ $r->kamar->nomor_kamar }} ·
                        {{ $r->tanggal_checkin->format('d M') }}
                    </p>
                </div>
                <x-status-badge :status="$r->status" />
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const grafikData  = @json($grafikBulanan);
const tipeData    = @json($tipeKamarStat->values());
const palette     = ['#4f46e5','#10b981','#f59e0b','#ef4444','#8b5cf6'];
const gridColor   = 'rgba(0,0,0,0.05)';
const tickColor   = '#9ca3af';

// Pemasukan bulanan
const nilaiPerBulan = grafikData.map(d => Math.round(d.total / 1000000));
const rataRata      = nilaiPerBulan.filter(v => v > 0);
const avg           = rataRata.length ? Math.round(rataRata.reduce((a,b) => a+b,0) / rataRata.length) : 0;

new Chart(document.getElementById('chartPemasukan'), {
    type: 'bar',
    data: {
        labels: grafikData.map(d => d.bulan),
        datasets: [
            {
                label: 'Pemasukan',
                data: nilaiPerBulan,
                backgroundColor: '#4f46e5',
                borderRadius: 5,
                borderSkipped: false,
                order: 2,
            },
            {
                label: 'Rata-rata',
                data: grafikData.map(() => avg),
                type: 'line',
                borderColor: '#10b981',
                borderWidth: 1.5,
                borderDash: [5, 4],
                pointRadius: 0,
                fill: false,
                tension: 0,
                order: 1,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: c => 'Rp ' + c.parsed.y.toLocaleString('id-ID') + ' jt' } }
        },
        scales: {
            x: { grid: { color: gridColor }, ticks: { color: tickColor, font: { size: 11 } } },
            y: {
                grid: { color: gridColor },
                ticks: { color: tickColor, font: { size: 11 }, callback: v => v + 'jt' },
                beginAtZero: true
            }
        }
    }
});

// Donut tipe kamar
new Chart(document.getElementById('chartTipe'), {
    type: 'doughnut',
    data: {
        labels: tipeData.map(t => t.nama),
        datasets: [{
            data: tipeData.map(t => t.total_reservasi),
            backgroundColor: palette.slice(0, tipeData.length),
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: c => c.label + ': ' + c.parsed + ' reservasi' } }
        }
    }
});
</script>
@endsection
