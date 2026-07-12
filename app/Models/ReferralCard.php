<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralCard extends Model
{
    protected $fillable = ['catin_profile_id', 'kode_referral', 'status', 'ringkasan_risiko', 'risk_level', 'catatan_nakes', 'faskes_tujuan_id'];
    protected function casts(): array { return ['ringkasan_risiko' => 'array']; }
    public function catinProfile(): BelongsTo { return $this->belongsTo(CatinProfile::class); }
    public function faskesTujuan(): BelongsTo { return $this->belongsTo(Faskes::class, 'faskes_tujuan_id'); }
}
