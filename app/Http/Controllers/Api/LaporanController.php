<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Laporan::with([
            'petugas',
            'menu.lokasi',
            'detailMasalah.kategori',
        ]);

        if ($request->has('id_user')) {
            $query->where('id_user', $request->id_user);
        }

        if ($request->has('status_verifikasi')) {
            $query->where(
                'status_verifikasi',
                $request->status_verifikasi
            );
        }

        if ($request->has('id_lokasi')) {
            $query->whereHas('menu', function ($q) use ($request) {
                $q->where('id_lokasi', $request->id_lokasi);
            });
        }

        $laporan = $query
            ->orderBy('tanggal_lapor', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id_laporan' => $item->id_laporan,
                    'id_user' => $item->id_user,

                    'nama_petugas' => $item->petugas
                        ? $item->petugas->nama
                        : null,

                    'id_menu' => $item->id_menu,

                    'nama_menu' => $item->menu
                        ? $item->menu->nama_menu
                        : null,

                    'tanggal_sajian' => $item->menu &&
                        $item->menu->tanggal_sajian
                        ? $item->menu->tanggal_sajian->format('Y-m-d')
                        : null,

                    'id_lokasi' => $item->menu
                        ? $item->menu->id_lokasi
                        : null,

                    'nama_lokasi' => $item->menu &&
                        $item->menu->lokasi
                        ? $item->menu->lokasi->nama_lokasi
                        : null,

                    'tanggal_lapor' => $item->tanggal_lapor
                        ? $item->tanggal_lapor->format('Y-m-d H:i:s')
                        : null,

                    'status_kelayakan' => $item->status_kelayakan,

                    'foto_bukti' => $item->foto_bukti,

                    'catatan' => $item->catatan,

                    'status_verifikasi' => $item->status_verifikasi,

                    // KATEGORI MASALAH
                    'kategori_masalah' => $item->detailMasalah
                        ->map(function ($detail) {
                            return $detail->kategori
                                ? $detail->kategori->nama_kategori
                                : null;
                        })
                        ->filter()
                        ->implode(', '),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $laporan,
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'id_user' => 'required|integer|exists:petugas,id',
        'id_menu' => 'required|integer|exists:menu,id_menu',
        'status_kelayakan' => 'required|in:Layak,Tidak Layak',
        'catatan' => 'nullable|string',
        'foto_bukti' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        'kategori_masalah' => 'nullable|array',
        'kategori_masalah.*' => 'integer|exists:kategori_masalah,id_kategori',
    ]);

    if (
        $request->status_kelayakan === 'Tidak Layak' &&
        !$request->has('kategori_masalah')
    ) {
        return response()->json([
            'success' => false,
            'message' => 'Kategori masalah wajib dipilih jika makanan tidak layak.',
        ], 422);
    }

    $pathFoto = $request->file('foto_bukti')->store(
        'foto_bukti',
        'public'
    );

    $laporan = Laporan::create([
        'id_user' => $request->id_user,
        'id_menu' => $request->id_menu,
        'tanggal_lapor' => now(),
        'status_kelayakan' => $request->status_kelayakan,
        'foto_bukti' => $pathFoto,
        'catatan' => $request->catatan,
        'status_verifikasi' => 'Menunggu',
    ]);

    if ($request->has('kategori_masalah')) {
        foreach ($request->kategori_masalah as $idKategori) {
            $laporan->detailMasalah()->create([
                'id_kategori' => $idKategori,
            ]);
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Laporan berhasil dibuat dan menunggu verifikasi.',
        'data' => [
            'id_laporan' => $laporan->id_laporan,
            'status_verifikasi' => $laporan->status_verifikasi,
        ],
    ], 201);
}

    public function show($id)
    {
        $laporan = Laporan::with([
            'petugas',
            'menu.lokasi',
            'detailMasalah.kategori',
        ])->find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id_laporan' => $laporan->id_laporan,

                'id_user' => $laporan->id_user,

                'nama_petugas' => $laporan->petugas
                    ? $laporan->petugas->nama
                    : null,

                'username' => $laporan->petugas
                    ? $laporan->petugas->username
                    : null,

                'id_menu' => $laporan->id_menu,

                'nama_menu' => $laporan->menu
                    ? $laporan->menu->nama_menu
                    : null,

                'tanggal_sajian' => $laporan->menu &&
                    $laporan->menu->tanggal_sajian
                    ? $laporan->menu->tanggal_sajian->format('Y-m-d')
                    : null,

                'id_lokasi' => $laporan->menu
                    ? $laporan->menu->id_lokasi
                    : null,

                'nama_lokasi' => $laporan->menu &&
                    $laporan->menu->lokasi
                    ? $laporan->menu->lokasi->nama_lokasi
                    : null,

                'jenis_lokasi' => $laporan->menu &&
                    $laporan->menu->lokasi
                    ? $laporan->menu->lokasi->jenis_lokasi
                    : null,

                'alamat' => $laporan->menu &&
                    $laporan->menu->lokasi
                    ? $laporan->menu->lokasi->alamat
                    : null,

                'tanggal_lapor' => $laporan->tanggal_lapor
                    ? $laporan->tanggal_lapor->format('Y-m-d H:i:s')
                    : null,

                'status_kelayakan' => $laporan->status_kelayakan,

                'foto_bukti' => $laporan->foto_bukti,

                'catatan' => $laporan->catatan,

                'status_verifikasi' => $laporan->status_verifikasi,

                // DAFTAR KATEGORI MASALAH
                'daftar_kategori_masalah' => $laporan->detailMasalah
                    ->map(function ($detail) {
                        return [
                            'id_kategori' => $detail->id_kategori,
                            'nama_kategori' => $detail->kategori
                                ? $detail->kategori->nama_kategori
                                : null,
                        ];
                    })
                    ->filter(function ($item) {
                        return $item['nama_kategori'] !== null;
                    })
                    ->values(),
            ],
        ]);
    }

   public function verifikasi(Request $request, $id)
{
    $request->validate([
        'status_verifikasi' => 'required|in:Disetujui,Ditolak',
    ]);

    $laporan = Laporan::with([
        'petugas',
        'menu.lokasi',
    ])->find($id);

    if (!$laporan) {
        return response()->json([
            'success' => false,
            'message' => 'Laporan tidak ditemukan.',
        ], 404);
    }

    $laporan->status_verifikasi = $request->status_verifikasi;
    $laporan->save();

    // Notifikasi untuk Petugas pembuat laporan
    $pesanPetugas = $request->status_verifikasi === 'Disetujui'
        ? 'Laporan Anda untuk ' .
            ($laporan->menu->lokasi->nama_lokasi ?? 'lokasi') .
            ' telah diverifikasi dan disetujui.'
        : 'Laporan Anda untuk ' .
            ($laporan->menu->lokasi->nama_lokasi ?? 'lokasi') .
            ' ditolak/perlu diperiksa kembali.';

    \App\Models\Notifikasi::create([
        'id_laporan' => $laporan->id_laporan,
        'id_user_tujuan' => $laporan->id_user,
        'pesan' => $pesanPetugas,
        'status_baca' => 'Belum Dibaca',
    ]);

    // Jika laporan Tidak Layak dan disetujui,
    // kirim notifikasi ke semua Admin
    if (
        $request->status_verifikasi === 'Disetujui' &&
        $laporan->status_kelayakan === 'Tidak Layak'
    ) {
        $pesanAdmin = 'PERHATIAN: Makanan di ' .
            ($laporan->menu->lokasi->nama_lokasi ?? 'lokasi') .
            ' dikonfirmasi TIDAK LAYAK dikonsumsi.';

        $adminList = \App\Models\Petugas::where('role', 'Admin')->get();

        foreach ($adminList as $admin) {
            \App\Models\Notifikasi::create([
                'id_laporan' => $laporan->id_laporan,
                'id_user_tujuan' => $admin->id,
                'pesan' => $pesanAdmin,
                'status_baca' => 'Belum Dibaca',
            ]);
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Status laporan berhasil diperbarui.',
        'data' => [
            'id_laporan' => $laporan->id_laporan,
            'status_verifikasi' => $laporan->status_verifikasi,
        ],
    ]);
}
}