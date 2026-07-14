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
        'analisis_gabungan_ai',
        'jawaban_kuesioner',
        'analisis_gizi',
        'defisiensi_terdeteksi',
        'rekomendasi',
        'level_risiko',
        // DL / Gemini structured predictions
        'dl_prediksi_mata',
        'dl_prediksi_kulit',
        'dl_prediksi_kuku',
        'dl_confidence_mata',
        'dl_confidence_kulit',
        'dl_confidence_kuku',
        'highlight_areas',
        // Session tracking
        'sesi_tipe',
        'sesi_sebelumnya_id',
        'status_perbandingan',
    ];

    protected $casts = [
        'jawaban_kuesioner'     => 'array',
        'defisiensi_terdeteksi' => 'array',
        'dl_prediksi_mata'      => 'array',
        'dl_prediksi_kulit'     => 'array',
        'dl_prediksi_kuku'      => 'array',
        'highlight_areas'       => 'array',
    ];

    /**
     * The previous screening session (for before/after comparison)
     */
    public function previousSession()
    {
        return $this->belongsTo(VitaminDetection::class, 'sesi_sebelumnya_id');
    }

    /**
     * Follow-up screening sessions referencing this one
     */
    public function followUpSessions()
    {
        return $this->hasMany(VitaminDetection::class, 'sesi_sebelumnya_id');
    }

    /**
     * Scope: filter by session type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('sesi_tipe', $type);
    }

    /**
     * Get overall confidence as average of non-null area confidences
     */
    public function getOverallConfidenceAttribute(): ?float
    {
        $scores = array_filter([
            $this->dl_confidence_mata,
            $this->dl_confidence_kulit,
            $this->dl_confidence_kuku,
        ], fn($v) => $v !== null);

        return count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : null;
    }
}
