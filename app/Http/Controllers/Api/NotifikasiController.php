<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    /**
     * Get List Notifikasi (paginated)
     * GET /api/notifikasi
     */
    public function index(Request $request)
    {
        $mahasiswa = $request->user();

        $notifikasi = Notifikasi::where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->through(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'pesan' => $item->pesan,
                    'tipe' => $item->tipe,
                    'is_read' => (bool) $item->is_read,
                    'created_at' => $item->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ], 200);
    }

    /**
     * Mark as Read
     * PUT /api/notifikasi/{id}/read
     */
    public function markAsRead(Request $request, $id)
    {
        $mahasiswa = $request->user();

        $notifikasi = Notifikasi::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$notifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        $notifikasi->update(['is_read' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca'
        ], 200);
    }

    /**
     * Mark All as Read
     * PUT /api/notifikasi/read-all
     */
    public function markAllAsRead(Request $request)
    {
        $mahasiswa = $request->user();

        Notifikasi::where('mahasiswa_id', $mahasiswa->id)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sudah dibaca'
        ], 200);
    }

    /**
     * Get Unread Count
     * GET /api/notifikasi/unread-count
     */
    public function unreadCount(Request $request)
    {
        $mahasiswa = $request->user();

        $count = Notifikasi::where('mahasiswa_id', $mahasiswa->id)
            ->where('is_read', 0)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $count
            ]
        ], 200);
    }
}