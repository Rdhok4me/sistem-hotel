<?php
namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $query = Pengeluaran::with('user')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderByDesc('tanggal');

        if ($kategori = $request->get('kategori')) {
            $query->where('kategori', $kategori);
        }

        $pengeluaran = $query->paginate(15)->withQueryString();

        $totalPengeluaran = Pengeluaran::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $perKategori = Pengeluaran::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->selectRaw('kategori, SUM(jumlah) as total')
            ->groupBy('kategori')
            ->get();

        $kategoriList = ['operasional','pemeliharaan','gaji','utilitas','perlengkapan','lainnya'];

        return view('admin.pengeluaran.index', compact(
            'pengeluaran', 'totalPengeluaran', 'perKategori',
            'kategoriList', 'bulan', 'tahun'
        ));
    }

    public function create()
    {
        $kategoriList = ['operasional','pemeliharaan','gaji','utilitas','perlengkapan','lainnya'];
        return view('admin.pengeluaran.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'keterangan' => ['required', 'string', 'max:255'],
            'kategori'   => ['required', 'in:operasional,pemeliharaan,gaji,utilitas,perlengkapan,lainnya'],
            'jumlah'     => ['required', 'numeric', 'min:0'],
            'tanggal'    => ['required', 'date'],
            'catatan'    => ['nullable', 'string', 'max:500'],
        ]);

        $validated['user_id'] = auth()->id();

        Pengeluaran::create($validated);

        return redirect()->route('admin.pengeluaran.index')
            ->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        $kategoriList = ['operasional','pemeliharaan','gaji','utilitas','perlengkapan','lainnya'];
        return view('admin.pengeluaran.edit', compact('pengeluaran', 'kategoriList'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'keterangan' => ['required', 'string', 'max:255'],
            'kategori'   => ['required', 'in:operasional,pemeliharaan,gaji,utilitas,perlengkapan,lainnya'],
            'jumlah'     => ['required', 'numeric', 'min:0'],
            'tanggal'    => ['required', 'date'],
            'catatan'    => ['nullable', 'string', 'max:500'],
        ]);

        $pengeluaran->update($validated);

        return redirect()->route('admin.pengeluaran.index')
            ->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        return redirect()->route('admin.pengeluaran.index')
            ->with('success', 'Data pengeluaran berhasil dihapus.');
    }
}