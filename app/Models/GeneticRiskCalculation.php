<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneticRiskCalculation extends Model
{
    protected $fillable = ['catin_profile_id', 'tipe', 'probabilitas', 'risk_level', 'penjelasan', 'ai_explanation'];
    protected function casts(): array { return ['probabilitas' => 'array']; }
    public function catinProfile(): BelongsTo { return $this->belongsTo(CatinProfile::class); }
}
