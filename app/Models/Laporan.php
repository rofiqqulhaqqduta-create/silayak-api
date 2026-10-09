<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';

    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_user',
        'id_menu',
        'tanggal_lapor',
        'status_kelayakan',
        'foto_bukti',
        'catatan',
        'status_verifikasi',
    ];

    protected $casts = [
        'tanggal_lapor' => 'datetime',
    ];

    public function petugas()
    {
        return $this->belongsTo(
            Petugas::class,
            'id_user',
            'id'
        );
    }

    public function menu()
    {
        return $this->belongsTo(
            Menu::class,
            'id_menu',
            'id_menu'
        );
    }

    public function detailMasalah()
    {
        return $this->hasMany(
            DetailMasalah::class,
            'id_laporan',
            'id_laporan'
        );
    }
}