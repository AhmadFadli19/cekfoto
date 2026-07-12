<?php

namespace App\Http\Controllers;

use App\Models\CatinProfile;
use App\Models\GeneticRiskCalculation;
use App\Services\GeneticService;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class GeneticTreeController extends Controller
{
    public function calculate(Request $request, GeneticService $genetic, GeminiService $gemini)
    {
        $validated = $request->validate([
            'rhesus_pria' => 'required|string',
            'rhesus_wanita' => 'required|string',
            'genotipe_pria' => 'nullable|string|in:DD,Dd,dd',
            'genotipe_wanita' => 'nullable|string|in:DD,Dd,dd',
            'carrier_thalasemia_pria' => 'required|boolean',
            'carrier_thalasemia_wanita' => 'required|boolean',
            'catin_profile_id' => 'nullable|exists:catin_profiles,id',
        ]);

        // Determine genotype if not provided
        $genotipePria = $validated['genotipe_pria'] ?? $genetic->estimateGenotype($validated['rhesus_pria']);
        $genotipeWanita = $validated['genotipe_wanita'] ?? $genetic->estimateGenotype($validated['rhesus_wanita']);

        // Calculate Rhesus risk
        $rhesusRisk = $genetic->calculateRhesusRisk($genotipePria, $genotipeWanita);

        // Calculate Thalassemia risk
        $thalRisk = $genetic->calculateThalassemiaRisk(
            $validated['carrier_thalasemia_pria'],
            $validated['carrier_thalasemia_wanita']
        );

        // Get AI explanation
        $aiExplanation = $gemini->explainGeneticRisk([
            'rhesus' => $rhesusRisk,
            'thalasemia' => $thalRisk,
            'genotipe_pria' => $genotipePria,
            'genotipe_wanita' => $genotipeWanita,
        ]);

        // Save to database if profile exists
        if (!empty($validated['catin_profile_id'])) {
            GeneticRiskCalculation::updateOrCreate(
                ['catin_profile_id' => $validated['catin_profile_id'], 'tipe' => 'rhesus'],
                ['probabilitas' => $rhesusRisk['probabilitas'], 'risk_level' => $rhesusRisk['risk_level'], 'penjelasan' => $rhesusRisk['penjelasan'], 'ai_explanation' => $aiExplanation]
            );
            GeneticRiskCalculation::updateOrCreate(
                ['catin_profile_id' => $validated['catin_profile_id'], 'tipe' => 'thalasemia'],
                ['probabilitas' => $thalRisk['probabilitas'], 'risk_level' => $thalRisk['risk_level'], 'penjelasan' => $thalRisk['penjelasan']]
            );
        }

        return response()->json([
            'rhesus' => $rhesusRisk,
            'thalasemia' => $thalRisk,
            'ai_explanation' => $aiExplanation,
            'genotipe_pria' => $genotipePria,
            'genotipe_wanita' => $genotipeWanita,
        ]);
    }
}
