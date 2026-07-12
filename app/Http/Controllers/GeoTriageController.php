<?php

namespace App\Http\Controllers;

use App\Models\RegionalRiskStat;
use App\Models\Faskes;
use App\Services\RiskCalculationService;
use Illuminate\Http\Request;

class GeoTriageController extends Controller
{
    public function provinces(RiskCalculationService $riskService)
    {
        $stats = RegionalRiskStat::all()->map(function ($stat) use ($riskService) {
            return [
                'province_code' => $stat->province_code,
                'province_name' => $stat->province_name,
                'aki' => $stat->aki,
                'populasi_rh_negatif_persen' => $stat->populasi_rh_negatif_persen,
                'defisit_stok_rh_negatif' => $stat->defisit_stok_rh_negatif,
                'indeks_literasi' => $stat->indeks_literasi,
                'risk_score' => $stat->risk_score,
                'risk_color' => $riskService->getRiskColor($stat->risk_score),
                'risk_level' => $riskService->classifyRisk($stat->risk_score),
                'kasus_thalasemia' => $stat->kasus_thalasemia,
                'jumlah_faskes' => $stat->jumlah_faskes,
                'jumlah_penduduk' => $stat->jumlah_penduduk,
            ];
        });

        return response()->json($stats);
    }

    public function faskes(Request $request)
    {
        $query = Faskes::query();

        if ($request->has('province')) {
            $query->where('province', $request->province);
        }
        if ($request->has('tipe')) {
            $query->where('tipe', $request->tipe);
        }
        if ($request->boolean('has_rhogam')) {
            $query->where('has_rhogam', true);
        }
        if ($request->boolean('has_darah_rh_negatif')) {
            $query->where('has_darah_rh_negatif', true);
        }
        if ($request->boolean('has_transfusi_thalasemia')) {
            $query->where('has_transfusi_thalasemia', true);
        }

        return response()->json($query->get());
    }

    public function updateStock(Request $request)
    {
        $validated = $request->validate([
            'faskes_id' => 'required|exists:faskes,id',
            'rhogam_available' => 'boolean',
            'stok_darah_rh_negatif' => 'integer|min:0',
            'transfusi_thalasemia_available' => 'boolean',
            'catatan' => 'nullable|string',
        ]);

        $faskes = Faskes::findOrFail($validated['faskes_id']);
        $faskes->update([
            'has_rhogam' => $validated['rhogam_available'] ?? $faskes->has_rhogam,
            'has_darah_rh_negatif' => ($validated['stok_darah_rh_negatif'] ?? 0) > 0,
            'last_stock_update' => now(),
        ]);

        $faskes->stockUpdates()->create(array_merge($validated, ['user_id' => 1]));

        return response()->json(['message' => 'Stok berhasil diupdate', 'faskes' => $faskes->fresh()]);
    }
}
