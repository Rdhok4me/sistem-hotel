<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Pembayaran;
use App\Models\Reservasi;
use App\Models\TipeKamar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // ── Dashboard Admin ───────────────────────────────────────

    public function admin(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        // Statistik ringkasan
        $totalKamar     = Kamar::count();
        $kamarTerisi    = Kamar::where('status', 'terisi')->count();
        $okupansi       = $totalKamar > 0 ? round($kamarTerisi / $totalKamar * 100) : 0;

        $totalPemasukan = Pembayaran::where('status_bayar', 'lunas')
            ->whereMonth('waktu_bayar', $bulan)
            ->whereYear('waktu_bayar', $tahun)
            ->sum('jumlah_bayar');

        $totalReservasi = Reservasi::whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->whereNotIn('status', ['batal'])
            ->count();

        $tamuAktif = Reservasi::where('status', 'checkin')->count();

        $checkOutHariIni = Reservasi::where('status', 'checkout')
            ->whereDate('updated_at', today())
            ->count();

        // Grafik pemasukan 12 bulan
        $grafikBulanan = collect(range(1, 12))->map(function ($bln) use ($tahun) {
            return [
                'bulan' => Carbon::create($tahun, $bln)->translatedFormat('M'),
                'total' => (float) Pembayaran::where('status_bayar', 'lunas')
                    ->whereMonth('waktu_bayar', $bln)
                    ->whereYear('waktu_bayar', $tahun)
                    ->sum('jumlah_bayar'),
            ];
        });

        // Grafik 7 hari terakhir
        $grafikMingguan = collect(range(6, 0))->map(function ($hari) {
            $tgl = now()->subDays($hari);
            return [
                'hari'  => $tgl->translatedFormat('D'),
                'total' => (float) Pembayaran::where('status_bayar', 'lunas')
                    ->whereDate('waktu_bayar', $tgl)
                    ->sum('jumlah_bayar'),
            ];
        });

        // Tipe kamar terlaris
        $tipeKamarStat = TipeKamar::all()->map(function ($tipe) use ($bulan, $tahun) {
            $tipe->total_reservasi = Reservasi::whereHas('kamar', function ($q) use ($tipe) {
                $q->where('tipe_kamar_id', $tipe->id);
            })
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->whereNotIn('status', ['batal'])
            ->count();
            return $tipe;
        });

        // Status semua kamar untuk grid visual
        $semuaKamar = Kamar::with('tipeKamar')
            ->orderBy('lantai')
            ->orderBy('nomor_kamar')
            ->get();

        // 10 reservasi terbaru
        $reservasiTerbaru = Reservasi::with(['tamu', 'kamar.tipeKamar'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalKamar', 'kamarTerisi', 'okupansi',
            'totalPemasukan', 'totalReservasi', 'tamuAktif', 'checkOutHariIni',
            'grafikBulanan', 'grafikMingguan', 'tipeKamarStat',
            'semuaKamar', 'reservasiTerbaru', 'bulan', 'tahun'
        ));
    }

    // ── Dashboard Resepsionis ─────────────────────────────────

    public function resepsionis()
    {
        $checkInHariIni = Reservasi::checkinHariIni()->count();

        $checkOutHariIni = Reservasi::where('status', 'checkout')
            ->whereDate('updated_at', today())
            ->count();

        $pendingPembayaran = Reservasi::where('status', '!=', 'batal')
            ->where(function ($q) {
                $q->whereDoesntHave('pembayaran')
                  ->orWhereHas('pembayaran', fn ($q2) =>
                      $q2->where('status_bayar', '!=', 'lunas')
                  );
            })->count();

        // Aktivitas hari ini (check-in + checkout)
       $aktivitasHariIni = Reservasi::with(['tamu', 'kamar'])
    ->where(function ($q) {
        $q->where(fn ($q2) => $q2->where('status', 'konfirmasi')
                                  ->whereDate('tanggal_checkin', today()))
          ->orWhere(fn ($q3) => $q3->where('status', 'checkin')
                                   ->whereDate('tanggal_checkout', today()))
          ->orWhere(fn ($q4) => $q4->where('status', 'checkout')
                                   ->whereDate('updated_at', today()));
    })
    ->orderBy('tanggal_checkin')
    ->get();

        // Kamar yang tersedia beserta harga
        $kamarTersedia = Kamar::with('tipeKamar')
            ->where('status', 'tersedia')
            ->orderBy('lantai')
            ->orderBy('nomor_kamar')
            ->get();

        return view('resepsionis.dashboard', compact(
            'checkInHariIni', 'checkOutHariIni', 'pendingPembayaran',
            'aktivitasHariIni', 'kamarTersedia'
        ));
    }
}