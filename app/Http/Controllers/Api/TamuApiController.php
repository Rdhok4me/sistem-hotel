<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;

class TamuApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Tamu::latest();

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%");
            });
        }

        $tamu = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'data'      => $tamu->items(),
            'total'     => $tamu->total(),
            'per_page'  => $tamu->perPage(),
            'page'      => $tamu->currentPage(),
            'last_page' => $tamu->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap'    => ['required', 'string', 'max:100'],
            'jenis_identitas' => ['required', 'in:ktp,sim,paspor'],
            'nik'             => ['required', 'string', 'unique:tamu'],
            'no_telepon'      => ['required', 'string', 'max:20'],
            'jenis_kelamin'   => ['nullable', 'in:L,P'],
            'email'           => ['nullable', 'email'],
            'alamat'          => ['nullable', 'string'],
            'kota_asal'       => ['nullable', 'string'],
        ]);

        $tamu = Tamu::create($validated);

        return response()->json([
            'message' => 'Data tamu berhasil ditambahkan.',
            'data'    => $tamu,
        ], 201);
    }

    public function show($id)
    {
        $tamu = Tamu::withCount('reservasi')->findOrFail($id);

        return response()->json([
            'id'              => $tamu->id,
            'nama_lengkap'    => $tamu->nama_lengkap,
            'jenis_identitas' => $tamu->jenis_identitas,
            'nik'             => $tamu->nik,
            'jenis_kelamin'   => $tamu->jenis_kelamin,
            'no_telepon'      => $tamu->no_telepon,
            'email'           => $tamu->email,
            'alamat'          => $tamu->alamat,
            'kota_asal'       => $tamu->kota_asal,
            'total_reservasi' => $tamu->reservasi_count,
        ]);
    }
}