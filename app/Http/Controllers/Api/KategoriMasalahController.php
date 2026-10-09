<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriMasalah;
use Illuminate\Http\Request;

class KategoriMasalahController extends Controller
{
    public function index()
    {
        $kategori = KategoriMasalah::orderBy('nama_kategori', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $kategori,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = KategoriMasalah::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori masalah berhasil ditambahkan.',
            'data' => $kategori,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriMasalah::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori masalah tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori masalah berhasil diperbarui.',
            'data' => $kategori,
        ]);
    }

    public function destroy($id)
    {
        $kategori = KategoriMasalah::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori masalah tidak ditemukan.',
            ], 404);
        }

        try {
            $kategori->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori masalah berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori masalah tidak bisa dihapus karena masih digunakan.',
            ], 409);
        }
    }
}