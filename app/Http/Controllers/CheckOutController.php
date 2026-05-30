<?php
namespace App\Http\Controllers;
use App\Models\CheckOut;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CheckOutController extends Controller
{
    public function store(Request $request, Reservasi $reservasi)
    {
        abort_if(
            $reservasi->status !== 'checkin',
            422,
            'Status reservasi tidak valid untuk proses check-out.'
        );

        // Tambahkan di sini
        if ($reservasi->checkOut()->exists()) {
            return back()->with('error', 'Reservasi ini sudah melakukan check-out.');
        }

        $request->validate([
            'biaya_tambahan'    => ['nullable', 'numeric', 'min:0'],
            'keterangan_biaya'  => ['nullable', 'string', 'max:255'],
            'catatan'           => ['nullable', 'string', 'max:255'],
        ]);
        $biayaTambahan = (float) $request->biaya_tambahan;
        $totalAkhir    = (float) $reservasi->total_harga + $biayaTambahan;
        DB::transaction(function () use ($request, $reservasi, $biayaTambahan, $totalAkhir) {
            CheckOut::create([
                'reservasi_id'     => $reservasi->id,
                'user_id'          => auth()->id(),
                'waktu_checkout'   => now(),
                'biaya_tambahan'   => $biayaTambahan,
                'keterangan_biaya' => $request->keterangan_biaya,
                'total_akhir'      => $totalAkhir,
                'catatan'          => $request->catatan,
            ]);
            $reservasi->update(['status' => 'checkout']);
            $reservasi->kamar->update(['status' => 'dibersihkan']);
        });
        return redirect()->route('reservasi.show', $reservasi)
            ->with('success', 'Check-out berhasil. Terima kasih telah menginap!');
    }
}