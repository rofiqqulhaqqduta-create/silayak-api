<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('lokasi');

        if ($request->has('id_lokasi')) {
            $query->where('id_lokasi', $request->id_lokasi);
        }

        $menu = $query
            ->orderBy('tanggal_sajian', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id_menu' => $item->id_menu,
                    'nama_menu' => $item->nama_menu,
                    'tanggal_sajian' => $item->tanggal_sajian->format('Y-m-d'),
                    'id_lokasi' => $item->id_lokasi,
                    'nama_lokasi' => $item->lokasi
                        ? $item->lokasi->nama_lokasi
                        : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $menu,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'tanggal_sajian' => 'required|date',
            'id_lokasi' => 'required|integer|exists:lokasi,id_lokasi',
        ]);

        $menu = Menu::create([
            'nama_menu' => $request->nama_menu,
            'tanggal_sajian' => $request->tanggal_sajian,
            'id_lokasi' => $request->id_lokasi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan.',
            'data' => $menu,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'tanggal_sajian' => 'required|date',
            'id_lokasi' => 'required|integer|exists:lokasi,id_lokasi',
        ]);

        $menu->update([
            'nama_menu' => $request->nama_menu,
            'tanggal_sajian' => $request->tanggal_sajian,
            'id_lokasi' => $request->id_lokasi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui.',
            'data' => $menu,
        ]);
    }

    public function destroy($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu tidak ditemukan.',
            ], 404);
        }

        try {
            $menu->delete();

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Menu tidak bisa dihapus karena masih dipakai pada laporan.',
            ], 409);
        }
    }
}