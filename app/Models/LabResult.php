<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabResult extends Model
{
    protected $fillable = [
        'catin_profile_id', 'jenis', 'golongan_darah', 'rhesus',
        'hemoglobin', 'mcv', 'mch', 'mchc', 'hba2', 'hbf',
        'risk_level', 'ai_interpretation', 'recommendations',
        'file_path', 'milik',
    ];

    public function catinProfile(): BelongsTo { return $this->belongsTo(CatinProfile::class); }
}
