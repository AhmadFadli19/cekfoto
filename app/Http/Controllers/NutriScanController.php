<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use App\Services\AnthropometryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NutriScanController extends Controller
{
    public function __construct(
        private GeminiService $gemini,
        private AnthropometryService $anthro
    ) {}

    /**
     * Main analyze endpoint
     * POST /api/nutriscan/analyze
     */
    public function analyze(Request $request)
    {
        // Validate input
        $request->validate([
            'nama_anak'    => 'nullable|string|max:100',
            'usia_bulan'   => 'required|integer|min:0|max:216',
            'jenis_kelamin'=> 'required|in:L,P',
            'berat_badan'  => 'nullable|numeric|min:1|max:100',
            'tinggi_badan' => 'nullable|numeric|min:30|max:200',
            'foto_wajah'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'foto_tangan'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'kuesioner'    => 'required|string', // JSON string
        ]);

        $kuesioner = json_decode($request->input('kuesioner'), true) ?? [];

        // Process photos
        $fotoAnalyses = [];
        $storedPaths = [];

        foreach (['foto_wajah', 'foto_tangan'] as $fotoKey) {
            if ($request->hasFile($fotoKey)) {
                $file = $request->file($fotoKey);
                $bagian = ($fotoKey === 'foto_wajah') ? 'wajah' : 'tangan';

                // Store temporarily
                $path = $file->store("giziku_scans/{$bagian}", 'public');
                $storedPaths[$bagian] = $path;

                // Analyze with Gemini Vision
                $base64 = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();

                $analisis = $this->gemini->analyzeChildPhoto($base64, $mimeType, $bagian);
                $fotoAnalyses[$bagian] = $analisis;
            }
        }

        // Calculate anthropometry Z-scores
        $antropometri = null;
        $usiaBulan = (int) $request->input('usia_bulan');
        $jenisKelamin = $request->input('jenis_kelamin');
        $beratBadan = $request->input('berat_badan') ? (float) $request->input('berat_badan') : null;
        $tinggiBadan = $request->input('tinggi_badan') ? (float) $request->input('tinggi_badan') : null;

        if ($beratBadan && $tinggiBadan) {
            $antropometri = $this->anthro->calculate($usiaBulan, $jenisKelamin, $beratBadan, $tinggiBadan);
        }

        // Generate comprehensive nutrition report
        $report = $this->gemini->generateChildNutritionReport(
            fotoAnalyses: $fotoAnalyses,
            kuesioner: $kuesioner,
            childData: [
                'nama_anak'     => $request->input('nama_anak', 'Anak'),
                'usia_bulan'    => $usiaBulan,
                'jenis_kelamin' => $jenisKelamin,
                'berat_badan'   => $beratBadan,
                'tinggi_badan'  => $tinggiBadan,
                'antropometri'  => $antropometri,
            ]
        );

        // Calculate nutrition score
        $skorGizi = $this->calculateNutritionScore($report, $antropometri, $kuesioner);
        $levelRisiko = $this->determineLevelRisiko($skorGizi, $report);

        return response()->json([
            'success'       => true,
            'nama_anak'     => $request->input('nama_anak', 'Anak'),
            'usia_bulan'    => $usiaBulan,
            'skor_gizi'     => $skorGizi,
            'level_risiko'  => $levelRisiko,
            'antropometri'  => $antropometri,
            'foto_analisis' => $fotoAnalyses,
            'laporan'       => $report,
            'foto_paths'    => $storedPaths,
        ]);
    }

    private function calculateNutritionScore(?array $report, ?array $antropometri, array $kuesioner): int
    {
        $score = 70; // base score

        // Deduct for anthropometry issues
        if ($antropometri) {
            if ($antropometri['status_tbu'] === 'stunting') $score -= 25;
            elseif ($antropometri['status_tbu'] === 'pendek') $score -= 15;
            if ($antropometri['status_bbu'] === 'gizi_buruk') $score -= 20;
            elseif ($antropometri['status_bbu'] === 'gizi_kurang') $score -= 10;
            if ($antropometri['status_bbtb'] === 'wasting') $score -= 15;
        }

        // Adjust for questionnaire
        $frekuensiMakan = $kuesioner['frekuensi_makan'] ?? '';
        if (str_contains($frekuensiMakan, '< 2')) $score -= 10;
        elseif (str_contains($frekuensiMakan, '4-5') || str_contains($frekuensiMakan, '> 5')) $score += 5;

        $proteinHewani = $kuesioner['protein_hewani'] ?? '';
        if (str_contains($proteinHewani, 'Hampir tidak')) $score -= 10;
        elseif (str_contains($proteinHewani, 'Setiap hari')) $score += 5;

        $nafsuMakan = $kuesioner['nafsu_makan'] ?? '';
        if (str_contains($nafsuMakan, 'Sangat kurang')) $score -= 10;
        elseif (str_contains($nafsuMakan, 'Sangat baik')) $score += 5;

        // Deduct from report findings
        if ($report && !empty($report['defisiensi_terdeteksi'])) {
            $jumlahDefisiensi = count($report['defisiensi_terdeteksi']);
            $score -= min($jumlahDefisiensi * 5, 20);
        }

        return max(0, min(100, $score));
    }

    private function determineLevelRisiko(int $score, ?array $report): string
    {
        if ($score < 40) return 'tinggi';
        if ($score < 65) return 'sedang';
        return 'rendah';
    }
}
