<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Level;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::with('level');

        // Filter by level
        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('rfid_uid', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $mahasiswas = $query->paginate(15);
        $levels = Level::orderBy('urutan')->get();

        return view('admin.mahasiswa.index', compact('mahasiswas', 'levels'));
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load([
            'level',
            'achievements',
            'leaderboard',
            'transaksiSampah' => function ($q) {
                $q->with(['jenisSampah', 'bakSampah'])
                  ->orderBy('tanggal_transaksi', 'desc')
                  ->limit(20);
            },
        ]);

        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $levels = Level::orderBy('urutan')->get();
        return view('admin.mahasiswa.edit', compact('mahasiswa', 'levels'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'nim' => 'nullable|string|max:20',
            'prodi' => 'nullable|string|max:30',
            'rfid_uid' => ['nullable', 'string', 'max:20', Rule::unique('mahasiswa')->ignore($mahasiswa->id)],
            'total_poin' => 'required|integer|min:0',
            'level_id' => 'required|exists:level,id',
        ]);

             $mahasiswa->update($validated);

        $this->logActivity('Mengupdate data mahasiswa: ' . $mahasiswa->name, 'mahasiswa');

        $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'pengelola';

        return redirect()->route($routePrefix . '.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diupdate.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $name = $mahasiswa->name;

        $mahasiswa->transaksiSession()->delete();
        $mahasiswa->vouchers()->delete();

        $mahasiswa->delete();

        $this->logActivity('Menghapus mahasiswa: ' . $name, 'mahasiswa');

        $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'pengelola';

        return redirect()->route($routePrefix . '.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil dihapus.');
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