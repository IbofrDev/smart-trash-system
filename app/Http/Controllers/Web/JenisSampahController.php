<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class JenisSampahController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisSampah::withCount('transaksiSampah');

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', "%{$request->search}%");
        }

        $jenisSampahs = $query->orderBy('nama')->paginate(15);

        return view('admin.jenis-sampah.index', compact('jenisSampahs'));
    }

    public function create()
    {
        return view('admin.jenis-sampah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'poin_per_kg' => 'required|integer|min:0',
            'satuan' => 'required|string|max:10',
            'is_active' => 'required|boolean',
        ]);

        $jenisSampah = JenisSampah::create($validated);

        $this->logActivity('Menambahkan jenis sampah baru: ' . $jenisSampah->nama, 'jenis_sampah');

        return redirect()->route('admin.jenis-sampah.index')
            ->with('success', 'Jenis sampah berhasil ditambahkan.');
    }

    public function edit(JenisSampah $jenisSampah)
    {
        return view('admin.jenis-sampah.edit', compact('jenisSampah'));
    }

    public function update(Request $request, JenisSampah $jenisSampah)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'poin_per_kg' => 'required|integer|min:0',
            'satuan' => 'required|string|max:10',
            'is_active' => 'required|boolean',
        ]);

        $jenisSampah->update($validated);

        $this->logActivity('Mengupdate jenis sampah: ' . $jenisSampah->nama, 'jenis_sampah');

        return redirect()->route('admin.jenis-sampah.index')
            ->with('success', 'Jenis sampah berhasil diupdate.');
    }

    public function destroy(JenisSampah $jenisSampah)
    {
        if ($jenisSampah->transaksiSampah()->count() > 0) {
            return back()->with('error', 'Jenis sampah tidak bisa dihapus karena sudah digunakan dalam transaksi.');
        }

        $name = $jenisSampah->nama;
        $jenisSampah->delete();

        $this->logActivity('Menghapus jenis sampah: ' . $name, 'jenis_sampah');

        return redirect()->route('admin.jenis-sampah.index')
            ->with('success', 'Jenis sampah berhasil dihapus.');
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