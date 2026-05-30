<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Reservasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function store(Request $request, Reservasi $reservasi)
    {
        abort_if(
            in_array($reservasi->status, ['batal', 'checkout']),
            422,
            'Reservasi tidak dapat diproses pembayarannya.'
        );

        $request->validate([
            'metode_bayar' => ['required', 'in:tunai,transfer_bank,kartu_kredit,kartu_debit,qris'],
            'jumlah_bayar' => ['required', 'numeric', 'min:1'],
            'keterangan'   => ['nullable', 'string', 'max:255'],
        ]);

        $totalTagihan  = (float) $reservasi->total_harga
            + (float) ($reservasi->checkOut?->biaya_tambahan ?? 0);
        $jumlahBayar   = (float) $request->jumlah_bayar;
        $sisaBayar     = max(0, $totalTagihan - $jumlahBayar);
        $statusBayar   = $sisaBayar <= 0 ? 'lunas' : ($jumlahBayar > 0 ? 'dp' : 'belum_bayar');

        Pembayaran::updateOrCreate(
            ['reservasi_id' => $reservasi->id],
            [
                'user_id'         => auth()->id(),
                'jumlah_bayar'    => $jumlahBayar,
                'jumlah_dp'       => $statusBayar === 'dp' ? $jumlahBayar : 0,
                'sisa_pembayaran' => $sisaBayar,
                'metode_bayar'    => $request->metode_bayar,
                'status_bayar'    => $statusBayar,
                'waktu_bayar'     => now(),
                'keterangan'      => $request->keterangan,
            ]
        );

        return redirect()->route('reservasi.show', $reservasi)
            ->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function invoice(Reservasi $reservasi)
    {
        $reservasi->load([
            'tamu',
            'kamar.tipeKamar',
            'user',
            'pembayaran',
            'checkIn',
            'checkOut',
        ]);

        $pdf = Pdf::loadView('invoice.template', compact('reservasi'))
            ->setPaper('a4', 'portrait');

        $namaFile = 'invoice-' . $reservasi->kode_reservasi . '.pdf';

        return $pdf->stream($namaFile);
    }
}
