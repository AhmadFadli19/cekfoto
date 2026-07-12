<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', '');
        $this->model = config('services.gemini.model', 'gemini-2.0-flash');
    }

    /**
     * Send a chat request to Gemini API
     */
    public function chat(array $messages, string $systemPrompt = '', float $temperature = 0.4): ?string
    {
        if (empty($this->apiKey)) {
            return 'Error: GEMINI_API_KEY belum diatur di file .env. Silakan dapatkan API key dari https://aistudio.google.com/';
        }

        $contents = collect($messages)->map(fn($msg) => [
            'role' => $msg['role'],
            'parts' => [['text' => $msg['text']]],
        ])->values()->all();

        $body = ['contents' => $contents];

        if ($systemPrompt) {
            $body['systemInstruction'] = [
                'parts' => [['text' => $systemPrompt]],
            ];
        }

        $body['generationConfig'] = [
            'temperature' => $temperature,
        ];

        try {
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}",
                $body
            );

            if ($response->failed()) {
                Log::error('Gemini API error', ['status' => $response->status(), 'body' => $response->body()]);
                return 'Maaf, terjadi kesalahan saat menghubungi AI. Silakan coba lagi.';
            }

            return $response->json('candidates.0.content.parts.0.text') ?? '';
        } catch (\Exception $e) {
            Log::error('Gemini API exception', ['message' => $e->getMessage()]);
            return 'Maaf, terjadi kesalahan koneksi. Silakan coba lagi.';
        }
    }

    /**
     * Analyze lab results using Gemini
     */
    public function analyzeLabResult(array $labData): ?string
    {
        $prompt = "Kamu adalah asisten medis CatinGuard yang membantu menginterpretasi hasil laboratorium darah untuk pasangan calon pengantin (Catin) di Indonesia. Fokus pada deteksi risiko Rhesus incompatibility dan indikasi Thalasemia trait.\n\nINSTRUKSI:\n- Berikan interpretasi dalam bahasa Indonesia yang mudah dipahami awam\n- Jangan memberikan diagnosis final, hanya interpretasi awal dan edukasi\n- Selalu sertakan disclaimer medis\n- Jika ada indikasi risiko, rekomendasikan pemeriksaan lanjutan\n- Jelaskan setiap nilai lab yang abnormal dengan bahasa sederhana";

        $labText = "Berikut data hasil lab:\n";
        foreach ($labData as $key => $value) {
            if ($value !== null && $value !== '') {
                $labText .= "- {$key}: {$value}\n";
            }
        }

        return $this->chat(
            [['role' => 'user', 'text' => $labText]],
            $prompt,
            0.15
        );
    }

    /**
     * Generate empathetic explanation for genetic risk
     */
    public function explainGeneticRisk(array $riskData): ?string
    {
        $prompt = "Kamu adalah Edu-Bot CatinGuard yang menjelaskan risiko genetik kehamilan dengan empati dan kehangatan. Target audiens: pasangan calon pengantin Indonesia yang mungkin belum familiar dengan istilah medis.\n\nINSTRUKSI:\n- Jelaskan probabilitas dengan bahasa sederhana dan analogi sehari-hari\n- Jangan menakut-nakuti — fokus pada langkah pencegahan yang bisa dilakukan\n- Selalu akhiri dengan pesan harapan dan langkah selanjutnya yang konkret\n- Sertakan disclaimer bahwa ini bukan diagnosis medis final\n- Gunakan bahasa yang suportif dan tidak menghakimi";

        $riskText = json_encode($riskData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return $this->chat(
            [['role' => 'user', 'text' => "Tolong jelaskan hasil analisis risiko genetik berikut kepada pasangan Catin dengan empati:\n" . $riskText]],
            $prompt,
            0.3
        );
    }

    /**
     * Generate action plan for bidan/nakes
     */
    public function generateActionPlan(array $caseData): ?string
    {
        $prompt = "Kamu adalah asisten AI untuk bidan/nakes di Puskesmas Indonesia. Tugasmu membuat DRAF rencana aksi medis berdasarkan kasus berisiko tinggi. PENTING: Ini hanya DRAF yang WAJIB ditinjau dan disetujui oleh tenaga kesehatan sebelum dijalankan. AI tidak pernah membuat keputusan medis otomatis.\n\nFORMAT OUTPUT:\nLangkah 1: [Judul]\n- Detail tindakan\n- Waktu pelaksanaan\n\nLangkah 2: ...\n\nPeringatan: ...\nKontak Darurat: ...\n\nDISCLAIMER: Rencana aksi ini adalah draf yang dihasilkan AI sebagai alat bantu. Keputusan medis final ada di tangan tenaga kesehatan yang menangani kasus.";

        $caseText = json_encode($caseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return $this->chat(
            [['role' => 'user', 'text' => "Buatkan draf rencana aksi untuk kasus berikut:\n" . $caseText]],
            $prompt,
            0.15
        );
    }

    /**
     * Estimate costs for screening procedures
     */
    public function estimateCosts(string $screeningType, string $province): ?string
    {
        $prompt = "Kamu adalah asisten CatinGuard yang membantu calon pengantin memahami estimasi biaya pemeriksaan kesehatan pranikah di Indonesia. Berikan informasi tentang:\n- Kisaran biaya pemeriksaan di fasilitas kesehatan\n- Kemungkinan pemanfaatan BPJS Kesehatan\n- Program pemerintah daerah yang tersedia\n- Tips menghemat biaya pemeriksaan\n\nGunakan bahasa Indonesia yang mudah dipahami. Selalu sampaikan bahwa biaya bisa bervariasi antar daerah dan fasilitas kesehatan.";

        return $this->chat(
            [['role' => 'user', 'text' => "Berapa estimasi biaya untuk {$screeningType} di provinsi {$province}? Apakah bisa ditanggung BPJS?"]],
            $prompt,
            0.3
        );
    }
}
