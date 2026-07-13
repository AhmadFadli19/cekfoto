<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VitaminDetection extends Model
{
    protected $fillable = [
        'nama_anak',
        'usia_anak',
        'jenis_kelamin',
        'foto_mata',
        'foto_kulit',
        'foto_kuku',
        'analisis_mata',
        'analisis_kulit',
        'analisis_kuku',
        'analisis_gabungan',
        'jawaban_kuesioner',
        'analisis_gizi',
        'defisiensi_terdeteksi',
        'rekomendasi',
        'level_risiko',
    ];

    protected $casts = [
        'jawaban_kuesioner'   => 'array',
        'defisiensi_terdeteksi' => 'array',
    ];
}
