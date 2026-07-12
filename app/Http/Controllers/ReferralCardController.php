<?php

namespace App\Http\Controllers;

use App\Models\ReferralCard;
use App\Models\CatinProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferralCardController extends Controller
{
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'catin_profile_id' => 'required|exists:catin_profiles,id',
            'risk_level' => 'required|string',
            'ringkasan_risiko' => 'required|array',
            'faskes_tujuan_id' => 'nullable|exists:faskes,id',
        ]);

        $card = ReferralCard::create([
            'catin_profile_id' => $validated['catin_profile_id'],
            'kode_referral' => 'CG-' . strtoupper(Str::random(8)),
            'status' => 'aktif',
            'ringkasan_risiko' => $validated['ringkasan_risiko'],
            'risk_level' => $validated['risk_level'],
            'faskes_tujuan_id' => $validated['faskes_tujuan_id'] ?? null,
        ]);

        return response()->json($card);
    }

    public function show($id)
    {
        return response()->json(
            ReferralCard::with(['catinProfile', 'faskesTujuan'])->findOrFail($id)
        );
    }

    public function verify($kode)
    {
        $card = ReferralCard::where('kode_referral', $kode)->with(['catinProfile', 'faskesTujuan'])->firstOrFail();
        return response()->json($card);
    }
}
