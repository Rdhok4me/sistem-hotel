<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarApiController extends Controller
{
    public function index(Request $request)
    {
        $kamar = Kamar::with('tipeKamar')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('lantai')
            ->orderBy('nomor_kamar')
            ->get()
            ->map(fn($k) => [
                'id'          => $k->id,
                'nomor_kamar' => $k->nomor_kamar,
                'lantai'      => $k->lantai,
                'status'      => $k->status,
                'tipe'        => $k->tipeKamar->nama,
                'harga_malam' => $k->tipeKamar->harga_per_malam,
                'keterangan'  => $k->keterangan,
            ]);

        return response()->json([
            'total' => $kamar->count(),
            'data'  => $kamar,
        ]);
    }

    public function tersedia()
    {
        $kamar = Kamar::with('tipeKamar')
            ->where('status', 'tersedia')
            ->orderBy('lantai')
            ->orderBy('nomor_kamar')
            ->get()
            ->map(fn($k) => [
                'id'          => $k->id,
                'nomor_kamar' => $k->nomor_kamar,
                'lantai'      => $k->lantai,
                'tipe'        => $k->tipeKamar->nama,
                'harga_malam' => $k->tipeKamar->harga_per_malam,
            ]);

        return response()->json([
            'total' => $kamar->count(),
            'data'  => $kamar,
        ]);
    }

    public function show($id)
    {
        $kamar = Kamar::with('tipeKamar')->findOrFail($id);

        return response()->json([
            'id'          => $kamar->id,
            'nomor_kamar' => $kamar->nomor_kamar,
            'lantai'      => $kamar->lantai,
            'status'      => $kamar->status,
            'tipe'        => $kamar->tipeKamar->nama,
            'harga_malam' => $kamar->tipeKamar->harga_per_malam,
            'keterangan'  => $kamar->keterangan,
        ]);
    }
}