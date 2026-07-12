<?php

namespace App\Http\Controllers;

use App\Models\RegionalRiskStat;
use App\Models\CatinProfile;
use App\Models\PersalinanCase;
use App\Models\Faskes;

class EpidemiologiController extends Controller
{
    public function stats()
    {
        $provinces = RegionalRiskStat::orderBy('risk_score', 'desc')->get();

        $summary = [
            'total_provinces' => $provinces->count(),
            'high_risk_provinces' => $provinces->where('risk_score', '>=', 50)->count(),
            'avg_aki' => round($provinces->avg('aki'), 1),
            'total_thalasemia' => $provinces->sum('kasus_thalasemia'),
            'total_faskes' => $provinces->sum('jumlah_faskes'),
            'total_penduduk' => $provinces->sum('jumlah_penduduk'),
            'avg_risk_score' => round($provinces->avg('risk_score'), 1),
        ];

        return response()->json([
            'summary' => $summary,
            'provinces' => $provinces,
        ]);
    }

    public function dashboard()
    {
        return response()->json([
            'total_catin' => CatinProfile::count(),
            'total_cases' => PersalinanCase::count(),
            'active_cases' => PersalinanCase::where('status', 'menunggu')->count(),
            'resolved_cases' => PersalinanCase::where('status', 'selesai')->count(),
            'total_faskes' => Faskes::count(),
            'faskes_with_rhogam' => Faskes::where('has_rhogam', true)->count(),
        ]);
    }
}
