<?php

namespace App\Http\Controllers;

use App\Models\CatinProfile;
use App\Models\ReadinessScore;
use Illuminate\Http\Request;

class ReadinessScoreController extends Controller
{
    public function show($catinId)
    {
        $profile = CatinProfile::with(['labResults', 'geneticRisks', 'referralCards', 'readinessScore'])->findOrFail($catinId);

        // Calculate score components
        $skorSkrining = 0;
        if ($profile->golongan_darah_pria && $profile->golongan_darah_wanita) $skorSkrining += 10;
        if ($profile->rhesus_pria && $profile->rhesus_wanita) $skorSkrining += 10;
        if ($profile->labResults->count() > 0) $skorSkrining += 10;

        $skorKepatuhan = 0;
        if ($profile->geneticRisks->count() > 0) $skorKepatuhan += 15;
        if ($profile->referralCards->count() > 0) $skorKepatuhan += 15;

        $skorEdukasi = min(40, $profile->readiness_score ?? 0);

        $total = $skorSkrining + $skorKepatuhan + $skorEdukasi;

        $score = ReadinessScore::updateOrCreate(
            ['catin_profile_id' => $catinId],
            [
                'skor_skrining' => $skorSkrining,
                'skor_kepatuhan' => $skorKepatuhan,
                'skor_edukasi' => $skorEdukasi,
                'total_score' => $total,
                'detail' => [
                    'golongan_darah' => (bool)($profile->golongan_darah_pria && $profile->golongan_darah_wanita),
                    'rhesus' => (bool)($profile->rhesus_pria && $profile->rhesus_wanita),
                    'lab_results' => $profile->labResults->count() > 0,
                    'genetic_analysis' => $profile->geneticRisks->count() > 0,
                    'referral' => $profile->referralCards->count() > 0,
                ],
            ]
        );

        return response()->json($score);
    }
}
