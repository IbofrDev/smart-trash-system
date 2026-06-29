<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::withCount('mahasiswas')->orderBy('nama')->get();
        return view('admin.achievement.index', compact('achievements'));
    }

    public function create()
    {
        return view('admin.achievement.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'syarat_type' => 'required|in:total_kg,streak,transaksi_count,first_time',
            'syarat_value' => 'required|integer|min:0',
            'poin_bonus' => 'required|integer|min:0',
        ]);

        $achievement = Achievement::create($validated);

        $this->logActivity('Menambahkan achievement baru: ' . $achievement->nama, 'achievement');

        return redirect()->route($routePrefix . '.achievement.index')
            ->with('success', 'Achievement berhasil ditambahkan.');
    }

    public function edit(Achievement $achievement)
    {
        return view('admin.achievement.edit', compact('achievement'));
    }

    public function update(Request $request, Achievement $achievement)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'syarat_type' => 'required|in:total_kg,streak,transaksi_count,first_time',
            'syarat_value' => 'required|integer|min:0',
            'poin_bonus' => 'required|integer|min:0',
        ]);

        $achievement->update($validated);

        $this->logActivity('Mengupdate achievement: ' . $achievement->nama, 'achievement');

        return redirect()->route($routePrefix . '.achievement.index')
            ->with('success', 'Achievement berhasil diupdate.');
    }

    public function destroy(Achievement $achievement)
    {
        if ($achievement->mahasiswas()->count() > 0) {
            return back()->with('error', 'Achievement tidak bisa dihapus karena sudah diraih oleh mahasiswa.');
        }

        $name = $achievement->nama;
        $achievement->delete();

        $this->logActivity('Menghapus achievement: ' . $name, 'achievement');

        return redirect()->route($routePrefix . '.achievement.index')
            ->with('success', 'Achievement berhasil dihapus.');
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