<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    // LOGIN PETUGAS / ADMIN
public function login(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $petugas = Petugas::where('username', $request->username)->first();

    if (!$petugas) {
        return response()->json([
            'success' => false,
            'message' => 'Username atau password salah.',
        ], 401);
    }

    if (!Hash::check($request->password, $petugas->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Username atau password salah.',
        ], 401);
    }

    if ($petugas->status === 'Menunggu') {
        return response()->json([
            'success' => false,
            'message' => 'Akun Anda masih menunggu verifikasi Admin/Koordinator SPPG.',
        ], 403);
    }

    if ($petugas->status === 'Ditolak') {
        return response()->json([
            'success' => false,
            'message' => 'Akun Anda tidak disetujui. Hubungi Admin/Koordinator SPPG.',
        ], 403);
    }

    return response()->json([
        'success' => true,
        'message' => 'Login berhasil.',
        'data' => $this->formatPetugas($petugas),
    ], 200);
}
    // REGISTRASI PETUGAS
    public function register(Request $request)
    {
        $request->validate([
            'namaLengkap' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:petugas,username',
            'password' => 'required|min:6',
            'idLokasi' => 'nullable|integer',
        ]);

        $petugas = Petugas::create([
            'nama' => $request->namaLengkap,
            'username' => $request->username,
            'email' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'Petugas',
            'id_lokasi' => $request->idLokasi,
            'status' => 'Menunggu',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil, menunggu persetujuan admin.',
            'data' => $this->formatPetugas($petugas),
        ], 201);
    }

    // DAFTAR PETUGAS
    public function index()
    {
        $petugas = Petugas::orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return $this->formatPetugas($item);
            });

        return response()->json([
            'success' => true,
            'data' => $petugas,
        ]);
    }

    // APPROVE PETUGAS
    public function approve($id)
    {
        $petugas = Petugas::find($id);

        if (!$petugas) {
            return response()->json([
                'success' => false,
                'message' => 'Petugas tidak ditemukan.'
            ], 404);
        }

        $petugas->status = 'Aktif';
        $petugas->save();

        return response()->json([
            'success' => true,
            'message' => 'Petugas berhasil disetujui.',
            'data' => $this->formatPetugas($petugas),
        ]);
    }

    // REJECT PETUGAS
    public function reject($id)
    {
        $petugas = Petugas::find($id);

        if (!$petugas) {
            return response()->json([
                'success' => false,
                'message' => 'Petugas tidak ditemukan.'
            ], 404);
        }

        $petugas->status = 'Ditolak';
        $petugas->save();

        return response()->json([
            'success' => true,
            'message' => 'Petugas berhasil ditolak.',
            'data' => $this->formatPetugas($petugas),
        ]);
    }

    // FORMAT DATA AGAR SESUAI DENGAN UserModel FLUTTER
    private function formatPetugas($petugas)
    {
        return [
            'id_user' => $petugas->id,
            'nama_lengkap' => $petugas->nama,
            'username' => $petugas->username,
            'role' => $petugas->role,
            'id_lokasi' => $petugas->id_lokasi,
            'nama_lokasi' => null,
            'status_akun' => $petugas->status,
        ];
    }
}