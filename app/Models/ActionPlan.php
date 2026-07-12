<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionPlan extends Model
{
    protected $fillable = ['catin_profile_id', 'created_by', 'ai_draft', 'final_plan', 'status', 'approved_by', 'approved_at'];
    protected function casts(): array { return ['approved_at' => 'datetime']; }
    public function catinProfile(): BelongsTo { return $this->belongsTo(CatinProfile::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
}
