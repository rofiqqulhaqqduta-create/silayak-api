<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $primaryKey = 'id_notifikasi';

    protected $fillable = [
        'id_laporan',
        'id_user_tujuan',
        'pesan',
        'status_baca',
    ];

    public function laporan()
    {
        return $this->belongsTo(
            Laporan::class,
            'id_laporan',
            'id_laporan'
        );
    }

    public function userTujuan()
    {
        return $this->belongsTo(
            Petugas::class,
            'id_user_tujuan',
            'id'
        );
    }
}