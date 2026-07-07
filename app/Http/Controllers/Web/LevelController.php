<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        $levels = Level::withCount('mahasiswas')->orderBy('urutan')->get();
        return view('admin.level.index', compact('levels'));
    }

    public function create()
    {
        $maxUrutan = Level::max('urutan') ?? 0;
        return view('admin.level.create', compact('maxUrutan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_level' => 'required|string|max:50',
            'min_poin' => 'required|integer|min:0',
            'max_poin' => 'required|integer|min:0|gt:min_poin',
            'urutan' => 'required|integer|min:1|unique:level,urutan',
        ], [
            'max_poin.gt' => 'Max poin harus lebih besar dari min poin.',
            'urutan.unique' => 'Urutan level sudah digunakan.',
        ]);

        $level = Level::create($validated);

        $this->logActivity('Menambahkan level baru: ' . $level->nama_level, 'level');

        $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'pengelola';
        return redirect()->route($routePrefix . '.level.index')
            ->with('success', 'Level berhasil ditambahkan.');
    }

    public function edit(Level $level)
    {
        return view('admin.level.edit', compact('level'));
    }

    public function update(Request $request, Level $level)
    {
        $validated = $request->validate([
            'nama_level' => 'required|string|max:50',
            'min_poin' => 'required|integer|min:0',
            'max_poin' => 'required|integer|min:0|gt:min_poin',
            'urutan' => 'required|integer|min:1|unique:level,urutan,' . $level->id,
        ]);

        $level->update($validated);

        $this->logActivity('Mengupdate level: ' . $level->nama_level, 'level');

        $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'pengelola';
        return redirect()->route($routePrefix . '.level.index')
            ->with('success', 'Level berhasil diupdate.');
    }

    public function destroy(Level $level)
    {
        if ($level->mahasiswas()->count() > 0) {
            return back()->with('error', 'Level tidak bisa dihapus karena masih ada mahasiswa yang menggunakan level ini.');
        }

        $name = $level->nama_level;
        $level->delete();

        $this->logActivity('Menghapus level: ' . $name, 'level');

        $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'pengelola';
        return redirect()->route($routePrefix . '.level.index')
            ->with('success', 'Level berhasil dihapus.');
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