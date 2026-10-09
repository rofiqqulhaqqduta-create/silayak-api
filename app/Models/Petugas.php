<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';

    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'role',
        'id_lokasi',
        'status',
    ];

    protected $hidden = [
        'password',
    ];
}