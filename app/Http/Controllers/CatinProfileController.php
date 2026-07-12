<?php

namespace App\Http\Controllers;

use App\Models\CatinProfile;
use Illuminate\Http\Request;

class CatinProfileController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pria' => 'required|string',
            'nama_wanita' => 'required|string',
            'golongan_darah_pria' => 'nullable|string',
            'golongan_darah_wanita' => 'nullable|string',
            'rhesus_pria' => 'nullable|string',
            'rhesus_wanita' => 'nullable|string',
            'carrier_thalasemia_pria' => 'boolean',
            'carrier_thalasemia_wanita' => 'boolean',
            'tanggal_rencana_nikah' => 'nullable|date',
            'province' => 'nullable|string',
            'city' => 'nullable|string',
        ]);

        $profile = CatinProfile::create(array_merge($validated, ['user_id' => 1]));

        return response()->json($profile);
    }

    public function show($id)
    {
        return response()->json(
            CatinProfile::with(['labResults', 'geneticRisks', 'referralCards', 'readinessScore'])->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $profile = CatinProfile::findOrFail($id);
        $profile->update($request->all());
        return response()->json($profile->fresh());
    }
}
