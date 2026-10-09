<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $idUser = $request->id_user;

        // =========================
        // RINGKASAN
        // =========================
        $ringkasanQuery = Laporan::query();

        if ($idUser !== null) {
            $ringkasanQuery->where('id_user', $idUser);
        }

        $ringkasan = [
            'total_laporan' => (clone $ringkasanQuery)->count(),
            'total_layak' => (clone $ringkasanQuery)
                ->where('status_kelayakan', 'Layak')
                ->count(),
            'total_tidak_layak' => (clone $ringkasanQuery)
                ->where('status_kelayakan', 'Tidak Layak')
                ->count(),
            'total_menunggu' => (clone $ringkasanQuery)
                ->where('status_verifikasi', 'Menunggu')
                ->count(),
            'total_disetujui' => (clone $ringkasanQuery)
                ->where('status_verifikasi', 'Disetujui')
                ->count(),
            'total_ditolak' => (clone $ringkasanQuery)
                ->where('status_verifikasi', 'Ditolak')
                ->count(),
        ];

        // =========================
        // PER LOKASI
        // =========================
        $perLokasiQuery = Laporan::query()
            ->join('menu', 'laporan.id_menu', '=', 'menu.id_menu')
            ->join(
                'lokasi',
                'menu.id_lokasi',
                '=',
                'lokasi.id_lokasi'
            );

        if ($idUser !== null) {
            $perLokasiQuery->where('laporan.id_user', $idUser);
        }

        $perLokasi = $perLokasiQuery
            ->select(
                'lokasi.nama_lokasi',
                DB::raw(
                    "SUM(CASE WHEN laporan.status_kelayakan = 'Layak' THEN 1 ELSE 0 END) AS layak"
                ),
                DB::raw(
                    "SUM(CASE WHEN laporan.status_kelayakan = 'Tidak Layak' THEN 1 ELSE 0 END) AS tidak_layak"
                )
            )
            ->groupBy(
                'lokasi.id_lokasi',
                'lokasi.nama_lokasi'
            )
            ->orderBy('lokasi.nama_lokasi', 'asc')
            ->get();

        // =========================
        // PER HARI - 7 HARI
        // =========================
        $perHariQuery = Laporan::query()
            ->whereDate(
                'tanggal_lapor',
                '>=',
                now()->subDays(6)->toDateString()
            );

        if ($idUser !== null) {
            $perHariQuery->where('id_user', $idUser);
        }

        $perHari = $perHariQuery
            ->select(
                DB::raw('DATE(tanggal_lapor) AS tanggal'),
                DB::raw(
                    "SUM(CASE WHEN status_kelayakan = 'Layak' THEN 1 ELSE 0 END) AS layak"
                ),
                DB::raw(
                    "SUM(CASE WHEN status_kelayakan = 'Tidak Layak' THEN 1 ELSE 0 END) AS tidak_layak"
                )
            )
            ->groupBy(DB::raw('DATE(tanggal_lapor)'))
            ->orderBy('tanggal', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'ringkasan' => $ringkasan,
                'per_lokasi' => $perLokasi,
                'per_hari' => $perHari,
            ],
        ]);
    }
}