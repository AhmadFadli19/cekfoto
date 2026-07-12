<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CatinProfile extends Model
{
    protected $fillable = [
        'user_id', 'nama_pria', 'nama_wanita',
        'golongan_darah_pria', 'golongan_darah_wanita',
        'rhesus_pria', 'rhesus_wanita',
        'rhesus_genotipe_pria', 'rhesus_genotipe_wanita',
        'carrier_thalasemia_pria', 'carrier_thalasemia_wanita',
        'tanggal_rencana_nikah', 'province', 'city', 'readiness_score',
    ];

    protected function casts(): array
    {
        return [
            'carrier_thalasemia_pria' => 'boolean',
            'carrier_thalasemia_wanita' => 'boolean',
            'tanggal_rencana_nikah' => 'date',
        ];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function labResults(): HasMany { return $this->hasMany(LabResult::class); }
    public function geneticRisks(): HasMany { return $this->hasMany(GeneticRiskCalculation::class); }
    public function referralCards(): HasMany { return $this->hasMany(ReferralCard::class); }
    public function persalinanCases(): HasMany { return $this->hasMany(PersalinanCase::class); }
    public function actionPlans(): HasMany { return $this->hasMany(ActionPlan::class); }
    public function readinessScore(): HasOne { return $this->hasOne(ReadinessScore::class); }
}
