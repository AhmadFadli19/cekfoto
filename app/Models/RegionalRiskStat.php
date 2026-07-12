<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RegionalRiskStat extends Model
{
    protected $table = 'regional_risk_stats';
    protected $fillable = [
        'province_code', 'province_name', 'aki', 'populasi_rh_negatif_persen',
        'defisit_stok_rh_negatif', 'indeks_literasi', 'risk_score',
        'kasus_thalasemia', 'jumlah_faskes', 'jumlah_penduduk',
    ];
}
