<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersalinanCase extends Model
{
    protected $fillable = ['catin_profile_id', 'bidan_id', 'waktu_persalinan', 'status', 'eskalasi_level', 'rhogam_diberikan_at', 'catatan', 'eskalasi_log'];
    protected function casts(): array { return ['waktu_persalinan' => 'datetime', 'rhogam_diberikan_at' => 'datetime', 'eskalasi_log' => 'array']; }
    public function catinProfile(): BelongsTo { return $this->belongsTo(CatinProfile::class); }
    public function bidan(): BelongsTo { return $this->belongsTo(User::class, 'bidan_id'); }
}
