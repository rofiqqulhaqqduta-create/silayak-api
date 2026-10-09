<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'id_user_tujuan' => 'required|integer|exists:petugas,id',
        ]);

        $notifikasi = Notifikasi::where(
            'id_user_tujuan',
            $request->id_user_tujuan
        )
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $notifikasi,
        ]);
    }

    public function countBelumDibaca(Request $request)
    {
        $request->validate([
            'id_user_tujuan' => 'required|integer|exists:petugas,id',
        ]);

        $jumlah = Notifikasi::where(
            'id_user_tujuan',
            $request->id_user_tujuan
        )
        ->where('status_baca', 'Belum Dibaca')
        ->count();

        return response()->json([
            'success' => true,
            'jumlah' => $jumlah,
        ]);
    }

    public function tandaiDibaca($id)
    {
        $notifikasi = Notifikasi::find($id);

        if (!$notifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan.',
            ], 404);
        }

        $notifikasi->status_baca = 'Sudah Dibaca';
        $notifikasi->save();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca.',
        ]);
    }
}