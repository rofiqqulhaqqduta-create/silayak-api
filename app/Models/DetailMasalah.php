<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailMasalah extends Model
{
    use HasFactory;

    protected $table = 'detail_masalah';

    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_laporan',
        'id_kategori',
    ];

    public function laporan()
    {
        return $this->belongsTo(
            Laporan::class,
            'id_laporan',
            'id_laporan'
        );
    }

    public function kategori()
    {
        return $this->belongsTo(
            KategoriMasalah::class,
            'id_kategori',
            'id_kategori'
        );
    }
}