<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Lokasi::withCount('bakSampahs');

        if ($request->filled('search')) {
            $query->where('nama_lokasi', 'like', "%{$request->search}%");
        }

        $lokasis = $query->orderBy('nama_lokasi')->paginate(15);

        return view('admin.lokasi.index', compact('lokasis'));
    }

    public function create()
    {
        return view('admin.lokasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'koordinat' => 'nullable|string|max:50',
        ]);

        $lokasi = Lokasi::create($validated);

        $this->logActivity('Menambahkan lokasi baru: ' . $lokasi->nama_lokasi, 'lokasi');

        return redirect()->route($routePrefix . '.lokasi.index')
            ->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function show(Lokasi $lokasi)
    {
        $lokasi->load('bakSampahs');
        return view('admin.lokasi.show', compact('lokasi'));
    }

    public function edit(Lokasi $lokasi)
    {
        return view('admin.lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'koordinat' => 'nullable|string|max:50',
        ]);

        $lokasi->update($validated);

        $this->logActivity('Mengupdate lokasi: ' . $lokasi->nama_lokasi, 'lokasi');

        return redirect()->route($routePrefix . '.lokasi.index')
            ->with('success', 'Lokasi berhasil diupdate.');
    }

    public function destroy(Lokasi $lokasi)
    {
        if ($lokasi->bakSampahs()->count() > 0) {
            return back()->with('error', 'Lokasi tidak bisa dihapus karena masih memiliki bak sampah terdaftar.');
        }

        $name = $lokasi->nama_lokasi;
        $lokasi->delete();

        $this->logActivity('Menghapus lokasi: ' . $name, 'lokasi');

        return redirect()->route($routePrefix . '.lokasi.index')
            ->with('success', 'Lokasi berhasil dihapus.');
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