<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function store(Request $request, Reservasi $reservasi)
    {
        abort_if(
            $reservasi->status !== 'konfirmasi',
            422,
            'Status reservasi tidak valid untuk proses check-in.'
        );

        if ($reservasi->checkIn()->exists()) {
            return back()->with('error', 'Reservasi ini sudah melakukan check-in.');
        }

        $request->validate([
            'jumlah_tamu_aktual' => ['required', 'integer', 'min:1'],
            'catatan'            => ['nullable', 'string', 'max:255'],
        ]);

        CheckIn::create([
            'reservasi_id'       => $reservasi->id,
            'user_id'            => auth()->id(),
            'waktu_checkin'      => now(),
            'jumlah_tamu_aktual' => $request->jumlah_tamu_aktual,
            'catatan'            => $request->catatan,
        ]);

        $reservasi->update(['status' => 'checkin']);

        return redirect()->route('reservasi.show', $reservasi)
            ->with('success', 'Check-in berhasil dicatat. Selamat datang!');
    }
}