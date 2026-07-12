<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadinessScore extends Model
{
    protected $fillable = ['catin_profile_id', 'skor_skrining', 'skor_kepatuhan', 'skor_edukasi', 'total_score', 'detail'];
    protected function casts(): array { return ['detail' => 'array']; }
    public function catinProfile(): BelongsTo { return $this->belongsTo(CatinProfile::class); }
}
