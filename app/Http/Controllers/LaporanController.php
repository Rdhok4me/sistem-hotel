<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKeuanganExport;
use App\Models\Kamar;
use App\Models\Pembayaran;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan  = (int) $request->get('bulan', now()->month);
        $tahun  = (int) $request->get('tahun', now()->year);
        $periode = $request->get('periode', 'bulanan');

        // Query dasar pembayaran lunas
        $query = Pembayaran::with([
            'reservasi.tamu',
            'reservasi.kamar.tipeKamar',
            'reservasi.checkOut',
            'user',
        ])->where('status_bayar', 'lunas');

        $query = match ($periode) {
            'harian'   => $query->whereDate('waktu_bayar', today()),
            'mingguan' => $query->whereBetween('waktu_bayar', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]),
            default    => $query->whereMonth('waktu_bayar', $bulan)
                                ->whereYear('waktu_bayar', $tahun),
        };

        $pembayaran     = $query->orderBy('waktu_bayar', 'desc')->get();
        $totalPemasukan = $pembayaran->sum('jumlah_bayar');

        // Ringkasan per tipe kamar
        $perTipe = $pembayaran->groupBy(
            fn ($p) => $p->reservasi->kamar->tipeKamar->nama
        )->map(fn ($group) => [
            'jumlah'  => $group->count(),
            'total'   => $group->sum('jumlah_bayar'),
        ]);

        // Grafik harian dalam bulan (untuk chart)
        $grafikHarian = Pembayaran::selectRaw(
            'DATE(waktu_bayar) as tanggal, SUM(jumlah_bayar) as total'
        )
            ->where('status_bayar', 'lunas')
            ->whereMonth('waktu_bayar', $bulan)
            ->whereYear('waktu_bayar', $tahun)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Statistik tambahan
        $totalReservasi  = Reservasi::whereNotIn('status', ['batal'])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->count();

        $totalKamar      = Kamar::count();
        $rerataHarian    = $grafikHarian->avg('total') ?? 0;

        return view('admin.laporan.index', compact(
            'pembayaran', 'totalPemasukan', 'perTipe',
            'grafikHarian', 'totalReservasi', 'totalKamar',
            'rerataHarian', 'periode', 'bulan', 'tahun'
        ));
    }

    public function export(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $namaBulan = Carbon::create($tahun, $bulan)->translatedFormat('F-Y');
        $namaFile  = 'laporan-keuangan-' . $namaBulan . '.xlsx';

        return Excel::download(
            new LaporanKeuanganExport($bulan, $tahun),
            $namaFile
        );
    }
}
