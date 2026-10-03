<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TracerResponse extends Model
{
    use HasFactory;

    protected $table = 'tracer_responses';

    protected $fillable = [
        'alumni_id',
        'tahun_tracer',
        'status_utama',
        'nama_perusahaan',
        'jabatan',
        'kisaran_gaji',
        'linear_dengan_jurusan',
        'nama_kampus',
        'program_studi',
        'nama_usaha',
        'bidang_usaha',
        'omset_bulanan',
        'saran_sekolah',
    ];

    protected $casts = [
        'linear_dengan_jurusan' => 'boolean',
        'tahun_tracer' => 'integer',
    ];

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class);
    }
}
