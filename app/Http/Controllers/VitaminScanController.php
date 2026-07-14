<?php

namespace App\Http\Controllers;

use App\Models\VitaminDetection;
use App\Services\VitaminScanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VitaminScanController extends Controller
{
    private VitaminScanService $scanService;

    public function __construct(VitaminScanService $scanService)
    {
        $this->scanService = $scanService;
    }

    /**
     * Analyze a single photo (real-time prediction)
     * POST /api/vitamin-scan/analyze-single
     */
    public function analyzeSingle(Request $request)
    {
        $request->validate([
            'foto'   => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'bagian' => 'required|in:mata,kulit,kuku',
        ]);

        try {
            $result = $this->scanService->analyzeSinglePhoto(
                $request->file('foto'),
                $request->bagian
            );

            return response()->json([
                'success' => true,
                'result'  => $result
            ]);
        } catch (\Exception $e) {
            Log::error('VitaminScanController single analysis error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menganalisis foto.'
            ], 500);
        }
    }

    /**
     * Submit all photos + questionnaire for a full scan
     * POST /api/vitamin-scan/analyze-full
     */
    public function analyzeFull(Request $request)
    {
        if (is_string($request->input('jawaban_kuesioner'))) {
            $request->merge([
                'jawaban_kuesioner' => json_decode($request->input('jawaban_kuesioner'), true)
            ]);
        }

        $request->validate([
            'nama_anak'          => 'required|string|max:100',
            'usia_anak'          => 'required|integer|min:0|max:216', // in months, max 18 years
            'jenis_kelamin'      => 'required|in:L,P',
            'foto_mata'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'foto_kulit'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'foto_kuku'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'jawaban_kuesioner'  => 'required|array',
            'previous_session_id'=> 'nullable|exists:vitamin_detections,id',
        ]);

        try {
            $result = $this->scanService->analyzeFullScan(
                $request->file('foto_mata'),
                $request->file('foto_kulit'),
                $request->file('foto_kuku'),
                $request->input('jawaban_kuesioner'),
                [
                    'nama_anak'     => $request->nama_anak,
                    'usia_anak'     => $request->usia_anak,
                    'jenis_kelamin' => $request->jenis_kelamin,
                ],
                $request->previous_session_id
            );

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('VitaminScanController full analysis error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses analisis lengkap gizi anak.'
            ], 500);
        }
    }

    /**
     * Get details of a scan session
     * GET /api/vitamin-scan/{id}
     */
    public function show(int $id)
    {
        $record = VitaminDetection::with('previousSession')->findOrFail($id);
        
        // Decode JSON representation for detailed frontend output
        if ($record->analisis_gabungan_ai) {
            $record->laporan_gabungan_decoded = json_decode($record->analisis_gabungan_ai, true);
        }

        return response()->json($record);
    }

    /**
     * Compare follow-up photos for H+7 tracking
     * POST /api/vitamin-scan/{id}/compare
     */
    public function compare(Request $request, int $id)
    {
        $request->validate([
            'foto_mata'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'foto_kulit' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'foto_kuku'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        try {
            $comparison = $this->scanService->compareWithPrevious(
                $id,
                $request->file('foto_mata'),
                $request->file('foto_kulit'),
                $request->file('foto_kuku')
            );

            // Update original record status
            $record = VitaminDetection::findOrFail($id);
            $record->update([
                'status_perbandingan' => $comparison['status_keseluruhan'],
                'sesi_tipe'           => 'checkpoint_h7',
            ]);

            return response()->json($comparison);
        } catch (\Exception $e) {
            Log::error('VitaminScanController compare error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membandingkan foto monitoring.'
            ], 500);
        }
    }

    /**
     * Get timeline history of scans for a child
     * GET /api/vitamin-scan/history/{childName}
     */
    public function history(string $childName)
    {
        $records = VitaminDetection::where('nama_anak', 'like', "%{$childName}%")
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'records' => $records
        ]);
    }
}
