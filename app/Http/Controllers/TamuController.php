<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTamuRequest;
use App\Models\Tamu;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    public function index(Request $request)
    {
        $query = Tamu::withCount('reservasi')->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%");
            });
        }

        $tamu = $query->paginate(15)->withQueryString();

        return view('resepsionis.tamu.index', compact('tamu'));
    }

    public function create()
{
    $jenisIdentitas = ['KTP', 'SIM', 'Paspor'];
    return view('resepsionis.tamu.create', compact('jenisIdentitas'));
}
    public function store(StoreTamuRequest $request)
{
    Tamu::create($request->validated());

    // Kalau dari halaman reservasi, redirect kembali ke reservasi
    if (request()->headers->get('referer') && str_contains(request()->headers->get('referer'), 'tamu/create')) {
        return redirect()->route('reservasi.create')
            ->with('success', 'Data tamu berhasil ditambahkan. Silakan pilih tamu.');
    }

    return redirect()->route('tamu.index')
        ->with('success', 'Data tamu berhasil ditambahkan.');
}

    public function show(Tamu $tamu)
    {
        $tamu->load(['reservasi.kamar.tipeKamar', 'reservasi.pembayaran']);
        return view('resepsionis.tamu.show', compact('tamu'));
    }

    public function edit(Tamu $tamu)
{
    $jenisIdentitas = ['KTP', 'SIM', 'Paspor'];
    return view('resepsionis.tamu.edit', compact('tamu', 'jenisIdentitas'));
}

    public function update(StoreTamuRequest $request, Tamu $tamu)
    {
        $tamu->update($request->validated());

        return redirect()->route('tamu.index')
            ->with('success', 'Data tamu berhasil diperbarui.');
    }
}
