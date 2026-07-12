<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaskesStockUpdate extends Model
{
    protected $fillable = ['faskes_id', 'user_id', 'rhogam_available', 'stok_darah_rh_negatif', 'transfusi_thalasemia_available', 'catatan'];
    protected function casts(): array { return ['rhogam_available' => 'boolean', 'transfusi_thalasemia_available' => 'boolean']; }
    public function faskes(): BelongsTo { return $this->belongsTo(Faskes::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
