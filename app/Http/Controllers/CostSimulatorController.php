<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\Request;

class CostSimulatorController extends Controller
{
    public function simulate(Request $request, GeminiService $gemini)
    {
        $validated = $request->validate([
            'jenis_pemeriksaan' => 'required|string',
            'province' => 'nullable|string',
        ]);

        $costs = $this->getEstimatedCosts($validated['jenis_pemeriksaan']);
        $aiExplanation = $gemini->estimateCosts($validated['jenis_pemeriksaan'], $validated['province'] ?? 'Indonesia');

        return response()->json([
            'estimasi_biaya' => $costs,
            'ai_explanation' => $aiExplanation,
            'bpjs_coverage' => $this->getBpjsInfo($validated['jenis_pemeriksaan']),
        ]);
    }

    private function getEstimatedCosts(string $jenis): array
    {
        $costs = [
            'tes_golongan_darah' => ['min' => 50000, 'max' => 150000, 'nama' => 'Tes Golongan Darah & Rhesus'],
            'cbc' => ['min' => 100000, 'max' => 300000, 'nama' => 'Complete Blood Count (CBC)'],
            'elektroforesis_hb' => ['min' => 250000, 'max' => 500000, 'nama' => 'Elektroforesis Hemoglobin'],
            'rhogam' => ['min' => 1500000, 'max' => 3000000, 'nama' => 'Injeksi RhoGAM (Anti-D)'],
            'konseling_genetik' => ['min' => 200000, 'max' => 500000, 'nama' => 'Konseling Genetik'],
            'paket_pranikah' => ['min' => 500000, 'max' => 1500000, 'nama' => 'Paket Pemeriksaan Pranikah Lengkap'],
        ];

        return $costs[$jenis] ?? $costs['paket_pranikah'];
    }

    private function getBpjsInfo(string $jenis): array
    {
        return [
            'covered' => in_array($jenis, ['tes_golongan_darah', 'cbc']),
            'partial' => in_array($jenis, ['elektroforesis_hb', 'rhogam']),
            'info' => 'Pemeriksaan dasar umumnya ditanggung BPJS Kesehatan melalui rujukan dari Puskesmas/FKTP. Untuk pemeriksaan lanjutan seperti elektroforesis Hb, diperlukan rujukan dari dokter spesialis.',
        ];
    }
}
