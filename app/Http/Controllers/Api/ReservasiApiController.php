<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservasiApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservasi::with(['tamu', 'kamar.tipeKamar'])->latest();

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $reservasi = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'data'      => collect($reservasi->items())->map(fn($r) => [
                'id'               => $r->id,
                'kode_reservasi'   => $r->kode_reservasi,
                'tamu'             => $r->tamu->nama_lengkap,
                'kamar'            => $r->kamar->nomor_kamar,
                'tanggal_checkin'  => $r->tanggal_checkin,
                'tanggal_checkout' => $r->tanggal_checkout,
                'jumlah_malam'     => $r->jumlah_malam,
                'total_harga'      => $r->total_harga,
                'status'           => $r->status,
            ]),
            'total'     => $reservasi->total(),
            'per_page'  => $reservasi->perPage(),
            'page'      => $reservasi->currentPage(),
            'last_page' => $reservasi->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tamu_id'          => ['required', 'exists:tamu,id'],
            'kamar_id'         => ['required', 'exists:kamar,id'],
            'tanggal_checkin'  => ['required', 'date', 'after_or_equal:today'],
            'tanggal_checkout' => ['required', 'date', 'after:tanggal_checkin'],
            'jumlah_tamu'      => ['required', 'integer', 'min:1'],
            'catatan'          => ['nullable', 'string'],
        ]);

        $kamar = Kamar::with('tipeKamar')->findOrFail($validated['kamar_id']);

        if ($kamar->status !== 'tersedia') {
            return response()->json(['message' => 'Kamar tidak tersedia.'], 422);
        }

        $checkin  = Carbon::parse($validated['tanggal_checkin']);
        $checkout = Carbon::parse($validated['tanggal_checkout']);
        $malam    = $checkin->diffInDays($checkout);

        $reservasi = Reservasi::create([
            'tamu_id'          => $validated['tamu_id'],
            'kamar_id'         => $validated['kamar_id'],
            'user_id'          => $request->user()->id,
            'tanggal_checkin'  => $validated['tanggal_checkin'],
            'tanggal_checkout' => $validated['tanggal_checkout'],
            'jumlah_malam'     => $malam,
            'jumlah_tamu'      => $validated['jumlah_tamu'],
            'harga_per_malam'  => $kamar->tipeKamar->harga_per_malam,
            'total_harga'      => $malam * $kamar->tipeKamar->harga_per_malam,
            'status'           => 'konfirmasi',
            'catatan'          => $validated['catatan'] ?? null,
        ]);

        return response()->json([
            'message'        => 'Reservasi berhasil dibuat.',
            'kode_reservasi' => $reservasi->kode_reservasi,
            'total_harga'    => $reservasi->total_harga,
            'status'         => $reservasi->status,
        ], 201);
    }

    public function show($id)
    {
        $reservasi = Reservasi::with([
            'tamu', 'kamar.tipeKamar', 'pembayaran', 'checkIn', 'checkOut'
        ])->findOrFail($id);

        return response()->json([
            'id'               => $reservasi->id,
            'kode_reservasi'   => $reservasi->kode_reservasi,
            'tamu'             => [
                'nama' => $reservasi->tamu->nama_lengkap,
                'nik'  => $reservasi->tamu->nik,
                'telp' => $reservasi->tamu->no_telepon,
            ],
            'kamar'            => [
                'nomor' => $reservasi->kamar->nomor_kamar,
                'tipe'  => $reservasi->kamar->tipeKamar->nama,
                'harga' => $reservasi->harga_per_malam,
            ],
            'tanggal_checkin'  => $reservasi->tanggal_checkin,
            'tanggal_checkout' => $reservasi->tanggal_checkout,
            'jumlah_malam'     => $reservasi->jumlah_malam,
            'total_harga'      => $reservasi->total_harga,
            'status'           => $reservasi->status,
            'pembayaran'       => $reservasi->pembayaran ? [
                'status' => $reservasi->pembayaran->status_bayar,
                'jumlah' => $reservasi->pembayaran->jumlah_bayar,
            ] : null,
        ]);
    }
}