<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BakSampah;
use App\Models\Lokasi;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BakSampahController extends Controller
{
    public function index(Request $request)
    {
        $query = BakSampah::with('lokasi');

        // Filter by lokasi
        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('nama', 'like', "%{$request->search}%");
        }

        $bakSampahs = $query->orderBy('nama')->paginate(15);
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();

        return view('admin.bak-sampah.index', compact('bakSampahs', 'lokasis'));
    }

    public function create()
    {
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();
        return view('admin.bak-sampah.create', compact('lokasis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'lokasi_id' => 'required|exists:lokasi,id',
            'status' => 'required|in:aktif,nonaktif,maintenance',
            'kapasitas_max' => 'nullable|numeric|min:0',
        ]);

        // Generate API key unik
        $validated['api_key'] = Str::random(64);

        $bakSampah = BakSampah::create($validated);

        $this->logActivity('Menambahkan bak sampah baru: ' . $bakSampah->nama, 'bak_sampah');

        return redirect()->route('admin.bak-sampah.index')
            ->with('success', 'Bak sampah berhasil ditambahkan.');
    }

    public function show(BakSampah $bakSampah)
    {
        $bakSampah->load(['lokasi', 'transaksiSampah' => function ($q) {
            $q->with(['mahasiswa', 'jenisSampah'])
              ->orderBy('tanggal_transaksi', 'desc')
              ->limit(20);
        }]);

        // Statistik
        $stats = [
            'total_transaksi' => $bakSampah->transaksiSampah()->count(),
            'total_berat' => $bakSampah->transaksiSampah()->sum('berat'),
            'total_poin' => $bakSampah->transaksiSampah()->sum('poin_didapat'),
        ];

        return view('admin.bak-sampah.show', compact('bakSampah', 'stats'));
    }

    public function edit(BakSampah $bakSampah)
    {
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();
        return view('admin.bak-sampah.edit', compact('bakSampah', 'lokasis'));
    }

    public function update(Request $request, BakSampah $bakSampah)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'lokasi_id' => 'required|exists:lokasi,id',
            'status' => 'required|in:aktif,nonaktif,maintenance',
            'kapasitas_max' => 'nullable|numeric|min:0',
        ]);

        $bakSampah->update($validated);

        $this->logActivity('Mengupdate bak sampah: ' . $bakSampah->nama, 'bak_sampah');

        return redirect()->route('admin.bak-sampah.index')
            ->with('success', 'Bak sampah berhasil diupdate.');
    }

    /**
     * Regenerate API Key
     */
    public function regenerateApiKey(BakSampah $bakSampah)
    {
        $bakSampah->update(['api_key' => Str::random(64)]);

        $this->logActivity('Regenerate API key bak sampah: ' . $bakSampah->nama, 'bak_sampah');

        return back()->with('success', 'API Key berhasil di-regenerate.');
    }

    public function destroy(BakSampah $bakSampah)
    {
        if ($bakSampah->transaksiSampah()->count() > 0) {
            return back()->with('error', 'Bak sampah tidak bisa dihapus karena sudah memiliki transaksi.');
        }

        $name = $bakSampah->nama;
        $bakSampah->delete();

        $this->logActivity('Menghapus bak sampah: ' . $name, 'bak_sampah');

        return redirect()->route('admin.bak-sampah.index')
            ->with('success', 'Bak sampah berhasil dihapus.');
    }

    private function logActivity(string $aktivitas, string $tabel = null): void
    {
        LogAktivitas::create([
            'user_type' => auth()->user()->role,
            'user_id' => auth()->id(),
            'aktivitas' => $aktivitas,
            'tabel_target' => $tabel,
            'ip_address' => request()->ip(),
        ]);
    }
}