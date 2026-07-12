<?php

namespace App\Http\Controllers;

use App\Models\LabResult;
use App\Models\CatinProfile;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class LabScanController extends Controller
{
    public function analyze(Request $request, GeminiService $gemini)
    {
        $validated = $request->validate([
            'catin_profile_id' => 'nullable|exists:catin_profiles,id',
            'golongan_darah' => 'nullable|string',
            'rhesus' => 'nullable|string',
            'hemoglobin' => 'nullable|numeric',
            'mcv' => 'nullable|numeric',
            'mch' => 'nullable|numeric',
            'mchc' => 'nullable|numeric',
            'hba2' => 'nullable|numeric',
            'hbf' => 'nullable|numeric',
            'milik' => 'required|in:pria,wanita',
        ]);

        // Determine risk level based on lab values
        $riskLevel = $this->assessRisk($validated);

        // Get AI interpretation from Gemini
        $aiInterpretation = $gemini->analyzeLabResult($validated);

        // Build recommendations
        $recommendations = $this->buildRecommendations($validated, $riskLevel);

        // Save to database if profile exists
        $labResult = null;
        if (!empty($validated['catin_profile_id'])) {
            $labResult = LabResult::create(array_merge($validated, [
                'jenis' => 'manual',
                'risk_level' => $riskLevel,
                'ai_interpretation' => $aiInterpretation,
                'recommendations' => $recommendations,
            ]));
        }

        return response()->json([
            'risk_level' => $riskLevel,
            'ai_interpretation' => $aiInterpretation,
            'recommendations' => $recommendations,
            'lab_result_id' => $labResult?->id,
            'values' => $validated,
        ]);
    }

    private function assessRisk(array $data): string
    {
        $risks = [];

        // Check Rh negative
        if (isset($data['rhesus']) && in_array(strtolower($data['rhesus']), ['negatif', '-', 'negative'])) {
            $risks[] = 'tinggi';
        }

        // Check thalassemia indicators from CBC
        if (isset($data['mcv']) && $data['mcv'] < 80) {
            $risks[] = 'sedang'; // Microcytic - possible thalassemia trait
        }
        if (isset($data['mch']) && $data['mch'] < 27) {
            $risks[] = 'sedang';
        }
        if (isset($data['hemoglobin']) && $data['hemoglobin'] < 11) {
            $risks[] = 'sedang';
        }
        if (isset($data['hba2']) && $data['hba2'] > 3.5) {
            $risks[] = 'tinggi'; // Beta-thalassemia trait indicator
        }

        if (in_array('tinggi', $risks)) return 'tinggi';
        if (in_array('sedang', $risks)) return 'sedang';
        return 'rendah';
    }

    private function buildRecommendations(array $data, string $riskLevel): string
    {
        $recs = [];

        if ($riskLevel === 'tinggi' || $riskLevel === 'sangat_tinggi') {
            $recs[] = 'Segera konsultasikan hasil ini ke dokter spesialis hematologi atau dokter kandungan.';
        }

        if (isset($data['rhesus']) && in_array(strtolower($data['rhesus']), ['negatif', '-'])) {
            $recs[] = 'Rhesus negatif terdeteksi. Pastikan pasangan Anda juga melakukan tes golongan darah dan Rhesus.';
            $recs[] = 'Jika pasangan Rh positif, konsultasikan tentang RhoGAM (Anti-D immunoglobulin) untuk kehamilan nanti.';
        }

        if (isset($data['mcv']) && $data['mcv'] < 80) {
            $recs[] = 'Nilai MCV rendah bisa mengindikasikan Thalasemia trait. Disarankan pemeriksaan elektroforesis hemoglobin.';
        }

        if (empty($recs)) {
            $recs[] = 'Hasil lab dalam batas normal. Tetap lakukan pemeriksaan rutin sebelum menikah.';
        }

        return implode("\n", $recs);
    }
}
