<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonorDirectory extends Model
{
    protected $table = 'donor_directory';
    protected $fillable = ['user_id', 'golongan_darah', 'rhesus', 'province', 'city', 'is_available', 'is_verified', 'last_donor_date'];
    protected function casts(): array { return ['is_available' => 'boolean', 'is_verified' => 'boolean', 'last_donor_date' => 'datetime']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
