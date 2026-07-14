<?php

namespace App\Services;

use App\Models\VitaminDetection;
use App\Services\GeminiService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VitaminScanService
{
    private GeminiService $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    /**
     * Analyze a single photo (real-time, without saving to DB).
     * Returns structured JSON result from Gemini Vision.
     */
    public function analyzeSinglePhoto(UploadedFile $file, string $bagian): array
    {
        $mimeType = $file->getMimeType();
        $base64   = base64_encode(file_get_contents($file->getRealPath()));

        $result = $this->gemini->analyzeImageStructured($base64, $mimeType, $bagian);

        if ($result === null) {
            return [
                'error'   => true,
                'message' => 'AI gagal menganalisis foto. Silakan coba lagi.',
            ];
        }

        return $result;
    }

    /**
     * Full scan: analyze all 3 photos + questionnaire, generate combined report, save to DB.
     */
    public function analyzeFullScan(
        ?UploadedFile $fotoMata,
        ?UploadedFile $fotoKulit,
        ?UploadedFile $fotoKuku,
        array $kuesioner = [],
        array $childData = [],
        ?int $previousSessionId = null
    ): array {
        $storedPaths = [];
        $analyses    = [];
        $confidences = [];
        $highlights  = [];

        // Process each photo through structured AI analysis
        $photos = [
            'mata'  => $fotoMata,
            'kulit' => $fotoKulit,
            'kuku'  => $fotoKuku,
        ];

        foreach ($photos as $bagian => $file) {
            if ($file !== null) {
                // Store the photo
                $path = $file->store("vitamin_scans/{$bagian}", 'public');
                $storedPaths[$bagian] = $path;

                // Analyze with Gemini Vision (structured output)
                $base64   = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();
                $result   = $this->gemini->analyzeImageStructured($base64, $mimeType, $bagian);

                $analyses[$bagian]   = $result;
                $confidences[$bagian] = $result['confidence_score'] ?? null;

                // Collect highlight areas
                if (!empty($result['temuan_visual'])) {
                    foreach ($result['temuan_visual'] as $temuan) {
                        $highlights[] = [
                            'area'      => $bagian,
                            'tanda'     => $temuan['tanda'] ?? '',
                            'highlight' => $temuan['area_highlight'] ?? '',
                        ];
                    }
                }
            }
        }

        // Generate combined cross-correlation report
        $combinedReport = $this->gemini->generateCombinedReport(
            $analyses['mata']  ?? [],
            $analyses['kulit'] ?? [],
            $analyses['kuku']  ?? [],
            $kuesioner
        );

        // Extract risk level
        $levelRisiko = $combinedReport['level_risiko'] ?? 'sedang';

        // Extract deficiencies list
        $defisiensi = [];
        if (!empty($combinedReport['defisiensi_utama'])) {
            foreach ($combinedReport['defisiensi_utama'] as $d) {
                $defisiensi[] = $d['vitamin_mineral'] ?? '';
            }
        }

        // Build text summaries for the legacy text fields
        $analisisTexts = [];
        foreach (['mata', 'kulit', 'kuku'] as $bagian) {
            if (!empty($analyses[$bagian])) {
                $a = $analyses[$bagian];
                $text = "Status: " . ($a['status'] ?? 'unknown');
                if (!empty($a['penjelasan_awam'])) {
                    $text .= "\n" . $a['penjelasan_awam'];
                }
                if (!empty($a['indikasi_defisiensi'])) {
                    $text .= "\nIndikasi: ";
                    $parts = [];
                    foreach ($a['indikasi_defisiensi'] as $id) {
                        $parts[] = ($id['vitamin_mineral'] ?? '') . ' (' . ($id['keyakinan'] ?? '') . ')';
                    }
                    $text .= implode(', ', $parts);
                }
                $analisisTexts[$bagian] = $text;
            }
        }

        // Determine session type
        $sesiTipe = 'awal';
        $statusPerbandingan = null;
        if ($previousSessionId) {
            $sesiTipe = 'checkpoint_h7';
        }

        // Save to database
        $record = VitaminDetection::create([
            'nama_anak'            => $childData['nama_anak'] ?? null,
            'usia_anak'            => $childData['usia_anak'] ?? null,
            'jenis_kelamin'        => $childData['jenis_kelamin'] ?? null,
            'foto_mata'            => $storedPaths['mata']  ?? null,
            'foto_kulit'           => $storedPaths['kulit'] ?? null,
            'foto_kuku'            => $storedPaths['kuku']  ?? null,
            // Legacy text analyses
            'analisis_mata'        => $analisisTexts['mata']  ?? null,
            'analisis_kulit'       => $analisisTexts['kulit'] ?? null,
            'analisis_kuku'        => $analisisTexts['kuku']  ?? null,
            // Structured DL predictions
            'dl_prediksi_mata'     => $analyses['mata']  ?? null,
            'dl_prediksi_kulit'    => $analyses['kulit'] ?? null,
            'dl_prediksi_kuku'     => $analyses['kuku']  ?? null,
            'dl_confidence_mata'   => $confidences['mata']  ?? null,
            'dl_confidence_kulit'  => $confidences['kulit'] ?? null,
            'dl_confidence_kuku'   => $confidences['kuku']  ?? null,
            // Combined report
            'analisis_gabungan'    => $combinedReport['ringkasan_orang_tua'] ?? null,
            'analisis_gabungan_ai' => json_encode($combinedReport, JSON_UNESCAPED_UNICODE),
            'highlight_areas'      => $highlights,
            // Questionnaire
            'jawaban_kuesioner'    => $kuesioner,
            'analisis_gizi'        => $combinedReport['ringkasan_orang_tua'] ?? null,
            // Deficiencies & risk
            'defisiensi_terdeteksi' => $defisiensi,
            'rekomendasi'          => $this->formatRecommendations($combinedReport),
            'level_risiko'         => $levelRisiko,
            // Session tracking
            'sesi_tipe'            => $sesiTipe,
            'sesi_sebelumnya_id'   => $previousSessionId,
            'status_perbandingan'  => $statusPerbandingan,
        ]);

        return [
            'success'          => true,
            'id'               => $record->id,
            'analisis_mata'    => $analyses['mata']  ?? null,
            'analisis_kulit'   => $analyses['kulit'] ?? null,
            'analisis_kuku'    => $analyses['kuku']  ?? null,
            'laporan_gabungan' => $combinedReport,
            'level_risiko'     => $levelRisiko,
            'defisiensi'       => $defisiensi,
            'highlights'       => $highlights,
        ];
    }

    /**
     * Compare follow-up photos with a previous session.
     */
    public function compareWithPrevious(
        int $previousId,
        ?UploadedFile $fotoMata,
        ?UploadedFile $fotoKulit,
        ?UploadedFile $fotoKuku
    ): array {
        $previous = VitaminDetection::findOrFail($previousId);
        $comparisons = [];

        $photos = [
            'mata'  => $fotoMata,
            'kulit' => $fotoKulit,
            'kuku'  => $fotoKuku,
        ];

        foreach ($photos as $bagian => $file) {
            $prevFotoField = "foto_{$bagian}";
            $prevPredField = "dl_prediksi_{$bagian}";

            if ($file !== null && $previous->$prevFotoField) {
                $prevPath = Storage::disk('public')->path($previous->$prevFotoField);
                if (file_exists($prevPath)) {
                    $base64Before = base64_encode(file_get_contents($prevPath));
                    $base64After  = base64_encode(file_get_contents($file->getRealPath()));
                    $mimeType     = $file->getMimeType();

                    $comparisons[$bagian] = $this->gemini->compareBeforeAfter(
                        $base64Before,
                        $base64After,
                        $mimeType,
                        $bagian,
                        $previous->$prevPredField ?? []
                    );
                }
            }
        }

        // Determine overall comparison status
        $statuses = array_map(fn($c) => $c['status_perbandingan'] ?? 'belum_membaik', $comparisons);
        $overallStatus = 'membaik';
        if (in_array('memburuk', $statuses)) {
            $overallStatus = 'memburuk';
        } elseif (in_array('belum_membaik', $statuses)) {
            $overallStatus = 'belum_membaik';
        }

        return [
            'success'              => true,
            'previous_id'          => $previousId,
            'comparisons'          => $comparisons,
            'status_keseluruhan'   => $overallStatus,
        ];
    }

    /**
     * Format recommendations from the combined report into readable text.
     */
    private function formatRecommendations(array $report): string
    {
        $lines = [];

        if (!empty($report['rekomendasi_makanan'])) {
            $lines[] = "📋 REKOMENDASI MAKANAN:";
            foreach ($report['rekomendasi_makanan'] as $r) {
                $prioritas = strtoupper($r['prioritas'] ?? 'sedang');
                $lines[] = "• [{$prioritas}] {$r['makanan']} — {$r['nutrisi_target']} ({$r['frekuensi']})";
            }
        }

        if (!empty($report['rekomendasi_suplemen'])) {
            $lines[] = "\n💊 REKOMENDASI SUPLEMEN:";
            foreach ($report['rekomendasi_suplemen'] as $s) {
                $lines[] = "• {$s['suplemen']} — {$s['alasan']}";
            }
        }

        if (!empty($report['perlu_rujuk_nakes']) && !empty($report['alasan_rujuk'])) {
            $lines[] = "\n⚠️ PERLU RUJUKAN: {$report['alasan_rujuk']}";
        }

        return implode("\n", $lines);
    }
}
