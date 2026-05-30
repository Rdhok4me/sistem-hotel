<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservasiRequest;
use App\Http\Requests\UpdateReservasiRequest;
use App\Models\Kamar;
use App\Models\Reservasi;
use App\Models\Tamu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservasi::with(['tamu', 'kamar.tipeKamar', 'user'])->latest();

        // Filter pencarian
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_reservasi', 'like', "%{$search}%")
                  ->orWhereHas('tamu', fn ($q2) =>
                      $q2->where('nama_lengkap', 'like', "%{$search}%")
                         ->orWhere('nik', 'like', "%{$search}%")
                  );
            });
        }

        // Filter status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Filter tanggal
        if ($tanggal = $request->get('tanggal')) {
            $query->whereDate('tanggal_checkin', $tanggal);
        }

        $reservasi = $query->paginate(15)->withQueryString();

        return view('resepsionis.reservasi.index', compact('reservasi'));
    }

    public function create()
    {
        $kamar = Kamar::with('tipeKamar')
            ->where('status', 'tersedia')
            ->orderBy('lantai')
            ->orderBy('nomor_kamar')
            ->get();

        $tamu = Tamu::orderBy('nama_lengkap')->get();

        return view('resepsionis.reservasi.create', compact('kamar', 'tamu'));
    }

    public function store(StoreReservasiRequest $request)
    {
        DB::transaction(function () use ($request) {
            $kamar = Kamar::findOrFail($request->kamar_id);

            // Validasi kamar masih tersedia
            abort_if($kamar->status !== 'tersedia', 422, 'Kamar tidak tersedia.');

            $checkin      = Carbon::parse($request->tanggal_checkin);
            $checkout     = Carbon::parse($request->tanggal_checkout);
            $jumlahMalam  = $checkin->diffInDays($checkout);

            Reservasi::create([
                'tamu_id'          => $request->tamu_id,
                'kamar_id'         => $request->kamar_id,
                'user_id'          => auth()->id(),
                'tanggal_checkin'  => $request->tanggal_checkin,
                'tanggal_checkout' => $request->tanggal_checkout,
                'jumlah_malam'     => $jumlahMalam,
                'jumlah_tamu'      => $request->jumlah_tamu,
                'harga_per_malam'  => $kamar->tipeKamar->harga_per_malam,
                'total_harga'      => $kamar->tipeKamar->harga_per_malam * $jumlahMalam,
                'status'           => 'konfirmasi',
                'catatan'          => $request->catatan,
            ]);

            $kamar->update(['status' => 'terisi']);
        });

        return redirect()->route('reservasi.index')
            ->with('success', 'Reservasi berhasil dibuat.');
    }

    public function show(Reservasi $reservasi)
    {
        $reservasi->load([
            'tamu', 'kamar.tipeKamar',
            'user', 'pembayaran.user',
            'checkIn.user', 'checkOut.user',
        ]);

        return view('resepsionis.reservasi.show', compact('reservasi'));
    }

    public function edit(Reservasi $reservasi)
    {
        abort_if(
            in_array($reservasi->status, ['checkin', 'checkout', 'batal']),
            403,
            'Reservasi tidak dapat diedit.'
        );

        $kamar = Kamar::with('tipeKamar')
            ->where(fn ($q) => $q->where('status', 'tersedia')
                                  ->orWhere('id', $reservasi->kamar_id))
            ->orderBy('lantai')
            ->orderBy('nomor_kamar')
            ->get();

        $tamu = Tamu::orderBy('nama_lengkap')->get();

        return view('resepsionis.reservasi.edit', compact('reservasi', 'kamar', 'tamu'));
    }

    public function update(UpdateReservasiRequest $request, Reservasi $reservasi)
    {
        abort_if(
            in_array($reservasi->status, ['checkin', 'checkout', 'batal']),
            403,
            'Reservasi tidak dapat diedit.'
        );

        $checkin     = Carbon::parse($request->tanggal_checkin);
        $checkout    = Carbon::parse($request->tanggal_checkout);
        $jumlahMalam = $checkin->diffInDays($checkout);

        $reservasi->update([
            'tanggal_checkin'  => $request->tanggal_checkin,
            'tanggal_checkout' => $request->tanggal_checkout,
            'jumlah_malam'     => $jumlahMalam,
            'jumlah_tamu'      => $request->jumlah_tamu,
            'total_harga'      => $reservasi->harga_per_malam * $jumlahMalam,
            'catatan'          => $request->catatan,
        ]);

        return redirect()->route('reservasi.show', $reservasi)
            ->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function destroy(Reservasi $reservasi)
    {
        abort_if(
            $reservasi->status === 'checkin',
            403,
            'Reservasi yang sedang aktif tidak dapat dibatalkan.'
        );

        DB::transaction(function () use ($reservasi) {
            $reservasi->kamar->update(['status' => 'tersedia']);
            $reservasi->update(['status' => 'batal']);
            $reservasi->delete();
        });

        return redirect()->route('reservasi.index')
            ->with('success', 'Reservasi berhasil dibatalkan.');
    }
}
