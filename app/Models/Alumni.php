<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable = [
        'nisn',
        'nama',
        'tanggal_lahir',
        'jurusan',
        'tahun_lulus',
        'no_hp',
        'email',
        'alamat',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahun_lulus' => 'integer',
    ];

    public function tracerResponses(): HasMany
    {
        return $this->hasMany(TracerResponse::class);
    }
}
