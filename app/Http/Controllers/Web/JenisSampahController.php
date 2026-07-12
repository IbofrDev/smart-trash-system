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
    $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'pengelola';
    return view('admin.jenis-sampah.create', compact('routePrefix'));
}

    public function store(Request $request)
    {
         $validated = $request->validate([
    'nama'           => 'required|string|max:100',
    'deskripsi'      => 'nullable|string',
    'poin_per_kg'    => 'required|integer|min:0',
    'satuan'         => 'required|string|max:10',
    'is_active'      => 'required|boolean',
    'berat_min_gram' => 'required|integer|min:1',
    'berat_max_gram' => 'required|integer|min:1|gte:berat_min_gram',
], [
    'poin_per_kg.min'         => 'Poin per kg tidak boleh negatif.',
    'is_active.boolean'       => 'Status aktif tidak valid.',
    'berat_min_gram.required' => 'Berat minimum wajib diisi.',
    'berat_max_gram.required' => 'Berat maksimum wajib diisi.',
    'berat_max_gram.gte'      => 'Berat maksimum harus lebih besar atau sama dengan berat minimum.',
]);

        $jenisSampah = JenisSampah::create($validated);

        $this->logActivity('Menambahkan jenis sampah baru: ' . $jenisSampah->nama, 'jenis_sampah');

        $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'pengelola';
        return redirect()->route($routePrefix . '.jenis-sampah.index')
            ->with('success', 'Jenis sampah berhasil ditambahkan.');
    }

    public function edit(JenisSampah $jenisSampah)
    {
        return view('admin.jenis-sampah.edit', compact('jenisSampah'));
    }

    public function update(Request $request, JenisSampah $jenisSampah)
    {
                 $validated = $request->validate([
            'nama'           => 'required|string|max:100',
            'deskripsi'      => 'nullable|string',
            'poin_per_kg'    => 'required|integer|min:0',
            'satuan'         => 'required|string|max:10',
            'is_active'      => 'required|boolean',
            'berat_min_gram' => 'required|integer|min:1',
            'berat_max_gram' => 'required|integer|min:1|gte:berat_min_gram',
        ], [
            'poin_per_kg.min'        => 'Poin per kg tidak boleh negatif.',
            'is_active.boolean'      => 'Status aktif tidak valid.',
            'berat_min_gram.required' => 'Berat minimum wajib diisi.',
            'berat_max_gram.required' => 'Berat maksimum wajib diisi.',
            'berat_max_gram.gte'      => 'Berat maksimum harus lebih besar atau sama dengan berat minimum.',
        ]);

        $jenisSampah->update($validated);

        $this->logActivity('Mengupdate jenis sampah: ' . $jenisSampah->nama, 'jenis_sampah');

        $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'pengelola';
        return redirect()->route($routePrefix . '.jenis-sampah.index')
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

        $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'pengelola';
        return redirect()->route($routePrefix . '.jenis-sampah.index')
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