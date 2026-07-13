<?php

namespace App\Http\Controllers;

use App\Models\VitaminDetection;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VitaminDetectionController extends Controller
{
    private GeminiService $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    /**
     * Analyze a single photo (mata / kulit / kuku)
     * POST /api/vitamin-detection/analyze-photo
     */
    public function analyzePhoto(Request $request)
    {
        $request->validate([
            'foto'   => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'bagian' => 'required|in:mata,kulit,kuku',
        ]);

        $file     = $request->file('foto');
        $mimeType = $file->getMimeType();
        $base64   = base64_encode(file_get_contents($file->getRealPath()));

        $analisis = $this->gemini->analyzeImageForVitamin($base64, $mimeType, $request->bagian);

        return response()->json([
            'success'  => true,
            'bagian'   => $request->bagian,
            'analisis' => $analisis,
        ]);
    }

    /**
     * Submit all 3 photos + questionnaire, save to DB
     * POST /api/vitamin-detection/submit
     */
    public function submit(Request $request)
    {
        if (is_string($request->input('jawaban_kuesioner'))) {
            $request->merge([
                'jawaban_kuesioner' => json_decode($request->input('jawaban_kuesioner'), true)
            ]);
        }

        $request->validate([
            'nama_anak'        => 'nullable|string|max:100',
            'usia_anak'        => 'nullable|integer|min:0|max:216', // max 18 tahun
            'jenis_kelamin'    => 'nullable|in:L,P',
            'foto_mata'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'foto_kulit'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'foto_kuku'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'jawaban_kuesioner' => 'required|array',
        ]);

        $storedPaths = [];
        $analyses    = [];

        // Process each photo
        foreach (['mata', 'kulit', 'kuku'] as $bagian) {
            if ($request->hasFile("foto_{$bagian}")) {
                $file   = $request->file("foto_{$bagian}");
                $path   = $file->store("vitamin_detections/{$bagian}", 'public');
                $storedPaths[$bagian] = $path;

                $base64   = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();
                $analyses[$bagian] = $this->gemini->analyzeImageForVitamin($base64, $mimeType, $bagian);
            }
        }

        // Analyze questionnaire + photos combined
        $kuesioner      = $request->input('jawaban_kuesioner', []);
        $analisisGizi   = $this->gemini->analyzeNutritionQuestionnaire($kuesioner, $analyses);

        // Extract risk level from analysis text
        $levelRisiko = 'sedang';
        if ($analisisGizi) {
            $lower = strtolower($analisisGizi);
            if (str_contains($lower, 'level risiko: tinggi') || str_contains($lower, 'risiko tinggi')) {
                $levelRisiko = 'tinggi';
            } elseif (str_contains($lower, 'level risiko: rendah') || str_contains($lower, 'risiko rendah')) {
                $levelRisiko = 'rendah';
            }
        }

        // Save to database
        $record = VitaminDetection::create([
            'nama_anak'            => $request->input('nama_anak'),
            'usia_anak'            => $request->input('usia_anak'),
            'jenis_kelamin'        => $request->input('jenis_kelamin'),
            'foto_mata'            => $storedPaths['mata']  ?? null,
            'foto_kulit'           => $storedPaths['kulit'] ?? null,
            'foto_kuku'            => $storedPaths['kuku']  ?? null,
            'analisis_mata'        => $analyses['mata']  ?? null,
            'analisis_kulit'       => $analyses['kulit'] ?? null,
            'analisis_kuku'        => $analyses['kuku']  ?? null,
            'jawaban_kuesioner'    => $kuesioner,
            'analisis_gizi'        => $analisisGizi,
            'level_risiko'         => $levelRisiko,
        ]);

        return response()->json([
            'success'         => true,
            'id'              => $record->id,
            'analisis_mata'   => $analyses['mata']  ?? null,
            'analisis_kulit'  => $analyses['kulit'] ?? null,
            'analisis_kuku'   => $analyses['kuku']  ?? null,
            'analisis_gizi'   => $analisisGizi,
            'level_risiko'    => $levelRisiko,
        ]);
    }

    /**
     * Get a detection record
     * GET /api/vitamin-detection/{id}
     */
    public function show(int $id)
    {
        $record = VitaminDetection::findOrFail($id);
        return response()->json($record);
    }

    /**
     * List all detections (paginated)
     * GET /api/vitamin-detection
     */
    public function index()
    {
        $records = VitaminDetection::orderByDesc('created_at')->paginate(20);
        return response()->json($records);
    }
}
