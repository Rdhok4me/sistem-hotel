<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\TipeKamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index(Request $request)
    {
        $query = Kamar::with('tipeKamar')->orderBy('lantai')->orderBy('nomor_kamar');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($tipe = $request->get('tipe')) {
            $query->where('tipe_kamar_id', $tipe);
        }

        $kamar     = $query->paginate(20)->withQueryString();
        $tipeKamar = TipeKamar::all();

        // Statistik status
        $stats = [
            'tersedia'    => Kamar::where('status', 'tersedia')->count(),
            'terisi'      => Kamar::where('status', 'terisi')->count(),
            'dibersihkan' => Kamar::where('status', 'dibersihkan')->count(),
            'maintenance' => Kamar::where('status', 'maintenance')->count(),
        ];

        return view('resepsionis.kamar.index', compact('kamar', 'tipeKamar', 'stats'));
    }

    public function create()
    {
        $tipeKamar = TipeKamar::all();
        return view('resepsionis.kamar.create', compact('tipeKamar'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe_kamar_id' => ['required', 'exists:tipe_kamar,id'],
            'nomor_kamar'   => ['required', 'string', 'max:10', 'unique:kamar'],
            'lantai'        => ['required', 'integer', 'min:1'],
            'keterangan'    => ['nullable', 'string', 'max:255'],
        ]);

        Kamar::create($validated);

        return redirect()->route('kamar.index')
            ->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Kamar $kamar)
    {
        $tipeKamar = TipeKamar::all();
        return view('resepsionis.kamar.edit', compact('kamar', 'tipeKamar'));
    }

    public function update(Request $request, Kamar $kamar)
    {
        $validated = $request->validate([
            'tipe_kamar_id' => ['required', 'exists:tipe_kamar,id'],
            'lantai'        => ['required', 'integer', 'min:1'],
            'status'        => ['required', 'in:tersedia,terisi,dibersihkan,maintenance'],
            'keterangan'    => ['nullable', 'string', 'max:255'],
        ]);

        $kamar->update($validated);

        return redirect()->route('kamar.index')
            ->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy(Kamar $kamar)
    {
        if ($kamar->reservasi()->whereIn('status', ['konfirmasi', 'checkin'])->exists()) {
            return back()->with('error', 'Kamar tidak dapat dihapus karena masih ada reservasi aktif.');
        }

        $kamar->delete();

        return redirect()->route('kamar.index')
            ->with('success', 'Kamar berhasil dihapus.');
    }

    // Update status kamar (AJAX-friendly)
    public function updateStatus(Request $request, Kamar $kamar)
    {
        $request->validate(['status' => 'required|in:tersedia,terisi,dibersihkan,maintenance']);
        $kamar->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status kamar berhasil diubah.');
    }
}
