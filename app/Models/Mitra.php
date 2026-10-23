<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    protected $fillable = [
        'nama_perusahaan',
        'bidang_usaha',
        'alamat',
        'kota',
        'telepon',
        'email',
        'kontak_person',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}