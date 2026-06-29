<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SettingPoin;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class SettingPoinController extends Controller
{
    public function index()
    {
        $settings = SettingPoin::orderBy('nama_setting')->get();
        return view('admin.setting-poin.index', compact('settings'));
    }

    public function edit(SettingPoin $settingPoin)
    {
        return view('admin.setting-poin.edit', compact('settingPoin'));
    }

    public function update(Request $request, SettingPoin $settingPoin)
    {
        $validated = $request->validate([
            'value' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $oldValue = $settingPoin->value;
        $settingPoin->update($validated);

        $this->logActivity(
            "Mengubah setting '{$settingPoin->nama_setting}' dari {$oldValue} ke {$validated['value']}",
            'setting_poin'
        );

        return redirect()->route($routePrefix . '.setting-poin.index')
            ->with('success', 'Setting poin berhasil diupdate.');
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