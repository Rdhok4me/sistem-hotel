<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Pembayaran;
use App\Models\Reservasi;

class DashboardApiController extends Controller
{
    public function index()
    {
        $totalKamar       = Kamar::count();
        $kamarTersedia    = Kamar::where('status', 'tersedia')->count();
        $kamarTerisi      = Kamar::where('status', 'terisi')->count();
        $kamarDibersihkan = Kamar::where('status', 'dibersihkan')->count();
        $kamarMaintenance = Kamar::where('status', 'maintenance')->count();
        $okupansi         = $totalKamar > 0 ? round($kamarTerisi / $totalKamar * 100) : 0;

        $totalPemasukan = Pembayaran::where('status_bayar', 'lunas')
            ->whereMonth('waktu_bayar', now()->month)
            ->whereYear('waktu_bayar', now()->year)
            ->sum('jumlah_bayar');

        $checkinHariIni  = Reservasi::checkinHariIni()->count();
        $checkoutHariIni = Reservasi::where('status', 'checkout')
            ->whereDate('updated_at', today())->count();

        return response()->json([
            'kamar' => [
                'total'       => $totalKamar,
                'tersedia'    => $kamarTersedia,
                'terisi'      => $kamarTerisi,
                'dibersihkan' => $kamarDibersihkan,
                'maintenance' => $kamarMaintenance,
                'okupansi'    => $okupansi . '%',
            ],
            'keuangan' => [
                'pemasukan_bulan_ini' => $totalPemasukan,
            ],
            'aktivitas_hari_ini' => [
                'checkin'  => $checkinHariIni,
                'checkout' => $checkoutHariIni,
            ],
        ]);
    }
}
