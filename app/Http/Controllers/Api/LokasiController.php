<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    // MENAMPILKAN SEMUA LOKASI
    public function index()
    {
        $lokasi = Lokasi::orderBy('nama_lokasi', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $lokasi,
        ]);
    }

    // MENAMBAH LOKASI
    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'jenis_lokasi' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:255',
        ]);

        $lokasi = Lokasi::create([
            'nama_lokasi' => $request->nama_lokasi,
            'jenis_lokasi' => $request->jenis_lokasi,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil ditambahkan.',
            'data' => $lokasi,
        ], 201);
    }

    // MENGUBAH LOKASI
    public function update(Request $request, $id)
    {
        $lokasi = Lokasi::find($id);

        if (!$lokasi) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'jenis_lokasi' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:255',
        ]);

        $lokasi->update([
            'nama_lokasi' => $request->nama_lokasi,
            'jenis_lokasi' => $request->jenis_lokasi,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil diperbarui.',
            'data' => $lokasi,
        ]);
    }

    // MENGHAPUS LOKASI
    public function destroy($id)
    {
        $lokasi = Lokasi::find($id);

        if (!$lokasi) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan.',
            ], 404);
        }

        try {
            $lokasi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak bisa dihapus karena masih digunakan data lain.',
            ], 409);
        }
    }
}