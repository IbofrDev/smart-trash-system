<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\NotifikasiUser;
use Illuminate\Http\Request;

class NotifikasiUserController extends Controller
{
    public function index()
    {
        $notifikasis = NotifikasiUser::where('user_id', auth()->id())
            ->with('bakSampah')
            ->orderByDesc('created_at')
            ->paginate(20);

        // Mark all as read saat buka halaman
        NotifikasiUser::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.notifikasi-user.index', compact('notifikasis'));
    }

    public function markRead($id)
    {
        NotifikasiUser::where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['is_read' => true]);

        return back();
    }

    public function markAllRead()
    {
        NotifikasiUser::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}