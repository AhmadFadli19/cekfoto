<?php

namespace App\Http\Controllers;

use App\Models\ActionPlan;
use App\Models\CatinProfile;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class ActionPlanController extends Controller
{
    public function generate(Request $request, GeminiService $gemini)
    {
        $validated = $request->validate([
            'catin_profile_id' => 'required|exists:catin_profiles,id',
            'case_summary' => 'required|string',
        ]);

        $profile = CatinProfile::with(['labResults', 'geneticRisks'])->findOrFail($validated['catin_profile_id']);

        $caseData = [
            'nama_pria' => $profile->nama_pria,
            'nama_wanita' => $profile->nama_wanita,
            'rhesus_pria' => $profile->rhesus_pria,
            'rhesus_wanita' => $profile->rhesus_wanita,
            'carrier_thal_pria' => $profile->carrier_thalasemia_pria,
            'carrier_thal_wanita' => $profile->carrier_thalasemia_wanita,
            'ringkasan_kasus' => $validated['case_summary'],
            'hasil_lab' => $profile->labResults->toArray(),
            'risiko_genetik' => $profile->geneticRisks->toArray(),
        ];

        $aiDraft = $gemini->generateActionPlan($caseData);

        $plan = ActionPlan::create([
            'catin_profile_id' => $validated['catin_profile_id'],
            'created_by' => 1,
            'ai_draft' => $aiDraft,
            'status' => 'draft',
        ]);

        return response()->json(['plan' => $plan, 'ai_draft' => $aiDraft]);
    }

    public function approve(Request $request, $id)
    {
        $plan = ActionPlan::findOrFail($id);
        $plan->update([
            'status' => 'approved',
            'final_plan' => $request->input('final_plan', $plan->ai_draft),
            'approved_by' => 1,
            'approved_at' => now(),
        ]);

        return response()->json(['plan' => $plan->fresh(), 'message' => 'Rencana aksi disetujui']);
    }
}
