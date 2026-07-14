<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $model;
    private string $groqKey;
    private string $groqModel;

    public function __construct()
    {
        $this->apiKey    = config('services.gemini.key', '');
        $this->model     = config('services.gemini.model', 'gemini-3.5-flash');
        $this->groqKey   = config('services.groq.key', '');
        $this->groqModel = config('services.groq.model', 'llama-3.3-70b-versatile');
    }

    /**
     * Get ordered list of models to try (primary + fallbacks)
     */
    private function getModelChain(): array
    {
        $primary = $this->model;
        $all = ['gemini-3.5-flash', 'gemini-2.0-flash', 'gemini-2.0-flash-lite', 'gemini-flash-latest', 'gemini-flash-lite-latest', 'gemini-pro-latest'];

        // Put primary first, then others
        $chain = [$primary];
        foreach ($all as $m) {
            if ($m !== $primary) {
                $chain[] = $m;
            }
        }
        return $chain;
    }

    /**
     * Call Gemini API with model fallback + retry on 503
     */
    private function callWithFallback(array $body, int $timeoutSec = 90): ?\Illuminate\Http\Client\Response
    {
        $models = $this->getModelChain();

        foreach ($models as $modelName) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$this->apiKey}";

            // Try this model up to 2 times (for 503)
            for ($attempt = 1; $attempt <= 2; $attempt++) {
                Log::info("Gemini: trying {$modelName} (attempt {$attempt})");

                $response = Http::timeout($timeoutSec)->post($url, $body);

                if ($response->successful()) {
                    Log::info("Gemini: success with {$modelName}");
                    return $response;
                }

                $status = $response->status();

                // 503 overload → wait and retry same model once
                if ($status === 503 && $attempt === 1) {
                    Log::warning("Gemini {$modelName} 503 overload — retrying in 8s");
                    sleep(8);
                    continue;
                }

                // Any other failure (or 503 on 2nd attempt, or 429, or 404), go to next model
                Log::warning("Gemini {$modelName} failed with status {$status} — trying next model");
                break;
            }
        }

        // All models failed — return last response
        return $response ?? null;
    }

    /**
     * Call Groq API for text completions
     */
    private function callGroq(array $groqMessages, float $temperature = 0.4): ?string
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->groqKey}",
                    'Content-Type'  => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model'       => $this->groqModel,
                    'messages'    => $groqMessages,
                    'temperature' => $temperature,
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content');
            }

            Log::error('Groq API error', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('Groq API exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Send a chat request to Groq (primary) or Gemini API (fallback)
     */
    public function chat(array $messages, string $systemPrompt = '', float $temperature = 0.4): ?string
    {
        // 1. Try Groq if key is available
        if (!empty($this->groqKey)) {
            $groqMessages = [];
            if ($systemPrompt) {
                $groqMessages[] = ['role' => 'system', 'content' => $systemPrompt];
            }
            foreach ($messages as $msg) {
                $role = ($msg['role'] === 'model') ? 'assistant' : $msg['role'];
                $text = $msg['text'] ?? $msg['content'] ?? '';
                $groqMessages[] = ['role' => $role, 'content' => $text];
            }

            $groqOutput = $this->callGroq($groqMessages, $temperature);
            if ($groqOutput !== null) {
                Log::info("Using Groq API response");
                return $groqOutput;
            }
            Log::warning('Groq failed, falling back to Gemini.');
        }

        // 2. Fallback to Gemini if Groq fails or is not set
        if (empty($this->apiKey)) {
            return 'Error: API key belum diatur di file .env. Silakan tambahkan API key Groq atau Gemini.';
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
            $response = $this->callWithFallback($body, 60);

            if ($response === null || $response->failed()) {
                $status = $response ? $response->status() : 500;
                Log::error('Gemini API error', ['status' => $status, 'body' => $response ? $response->body() : 'No response']);
                if ($status === 429) {
                    return 'AI sedang sibuk (quota habis sementara). Tunggu 1 menit lalu coba lagi.';
                }
                if ($status === 503) {
                    return 'Server AI sedang overload. Tunggu beberapa detik lalu coba lagi.';
                }
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

    /**
     * Analyze a photo (base64) for vitamin/nutrient deficiency signs
     * $bagian: 'mata' | 'kulit' | 'kuku'
     */
    public function analyzeImageForVitamin(string $base64Image, string $mimeType, string $bagian): ?string
    {
        if (empty($this->apiKey)) {
            return 'Error: GEMINI_API_KEY belum diatur.';
        }

        $bagianPrompts = [
            'mata'  => "Kamu adalah dokter spesialis gizi anak. Analisis foto MATA anak ini untuk mendeteksi tanda-tanda kekurangan vitamin/nutrisi. Perhatikan: warna konjungtiva (pucat = anemia/Fe/B12), kekeringan/Bitot's spot (Vit A), kemerahan/peradangan, perdarahan subkonjungtiva.",
            'kulit' => "Kamu adalah dokter spesialis gizi anak. Analisis foto KULIT anak ini untuk mendeteksi tanda-tanda kekurangan vitamin/nutrisi. Perhatikan: pucat (anemia/Fe), kekeringan/bersisik (Vit A/E), peradangan/dermatitis (Vit B2/B3/Zn), hiperpigmentasi, petekie/perdarahan (Vit C/K).",
            'kuku'  => "Kamu adalah dokter spesialis gizi anak. Analisis foto KUKU anak ini untuk mendeteksi tanda-tanda kekurangan vitamin/nutrisi. Perhatikan: pucat/putih (anemia/Fe/Protein), kuku sendok/koilonikia (Fe), garis Beau (Zn/Protein), rapuh/pecah-pecah (Biotin/Fe/Ca), tanda Muehrcke (Protein).",
        ];

        $systemPrompt = ($bagianPrompts[$bagian] ?? "Analisis foto ini untuk tanda kekurangan gizi.")
            . "\n\nFORMAT JAWABAN (singkat, jelas, tidak bertele-tele):\n"
            . "**Temuan Visual:** [deskripsi singkat apa yang terlihat]\n"
            . "**Indikasi Defisiensi:** [daftar vitamin/mineral yang mungkin kurang, beserta alasannya singkat]\n"
            . "**Tingkat Keyakinan:** Rendah / Sedang / Tinggi\n"
            . "**Catatan:** [peringatan atau hal penting]\n\n"
            . "PENTING: Ini hanya skrining awal berbasis foto, BUKAN diagnosis medis. Selalu rekomendasikan pemeriksaan dokter.";

        $body = [
            'contents' => [[
                'role'  => 'user',
                'parts' => [
                    ['text' => "Tolong analisis foto {$bagian} anak ini:"],
                    [
                        'inlineData' => [
                            'mimeType' => $mimeType,
                            'data'     => $base64Image,
                        ],
                    ],
                ],
            ]],
            'systemInstruction' => [
                'parts' => [['text' => $systemPrompt]],
            ],
            'generationConfig' => ['temperature' => 0.1],
        ];

        try {
            $response = $this->callWithFallback($body, 90);

            if ($response === null || $response->failed()) {
                $status = $response ? $response->status() : 500;
                Log::error('Gemini Vision error', ['status' => $status, 'body' => $response ? $response->body() : 'No response']);
                if ($status === 429) {
                    return '**Error 429:** AI sedang sibuk. Tunggu 1–2 menit lalu klik tombol Analisis AI lagi.';
                }
                if ($status === 503) {
                    return '**Error 503:** Server AI overload. Tunggu beberapa detik lalu coba lagi.';
                }
                return 'Gagal menganalisis foto. Pastikan format gambar valid (JPG/PNG) dan coba lagi.';
            }

            return $response->json('candidates.0.content.parts.0.text') ?? 'Tidak ada hasil analisis.';
        } catch (\Exception $e) {
            Log::error('Gemini Vision exception', ['message' => $e->getMessage()]);
            return 'Terjadi kesalahan saat menganalisis foto. Silakan coba lagi.';
        }
    }

    /**
     * Analyze nutrition questionnaire answers for a child
     */
    public function analyzeNutritionQuestionnaire(array $answers, array $photoAnalyses): ?string
    {
        $systemPrompt = "Kamu adalah ahli gizi anak Indonesia. Berdasarkan jawaban kuesioner pola makan dan aktivitas anak, identifikasi potensi defisiensi nutrisi.\n\n"
            . "FORMAT JAWABAN (singkat, terstruktur):\n"
            . "**Pola Makan:** [ringkasan singkat pola makan anak]\n"
            . "**Defisiensi Potensial dari Kuesioner:**\n"
            . "- [Vitamin/Mineral]: [alasan singkat berdasarkan jawaban]\n"
            . "**Korelasi dengan Foto:** [apakah temuan foto mendukung defisiensi yang sama]\n"
            . "**Kesimpulan Defisiensi Utama:** [daftar 1-3 defisiensi paling mungkin]\n"
            . "**Rekomendasi Makanan:** [3-5 makanan spesifik yang harus ditambah]\n"
            . "**Level Risiko:** Rendah / Sedang / Tinggi\n"
            . "**Perlu Rujukan Dokter:** Ya / Tidak — [alasan]\n\n"
            . "PENTING: Singkat dan jelas. Gunakan bahasa awam. Ini bukan diagnosis medis.";

        $kuesionerText = "JAWABAN KUESIONER GIZI ANAK:\n";
        foreach ($answers as $pertanyaan => $jawaban) {
            $kuesionerText .= "- {$pertanyaan}: {$jawaban}\n";
        }

        $fotoText = "\nHASIL ANALISIS FOTO:\n";
        foreach ($photoAnalyses as $bagian => $analisis) {
            if (!empty($analisis)) {
                $fotoText .= "Foto {$bagian}: {$analisis}\n\n";
            }
        }

        return $this->chat(
            [['role' => 'user', 'text' => $kuesionerText . $fotoText . "\nMohon analisis kondisi gizi anak ini secara komprehensif."]],
            $systemPrompt,
            0.15
        );
    }

    // =========================================================================
    //  VITAMIN SCAN — STRUCTURED AI ANALYSIS (DEEP LEARNING STYLE)
    // =========================================================================

    /**
     * Analyze a photo with Gemini Vision and return STRUCTURED JSON result.
     * This is the "deep learning" layer — using Gemini's vision model as
     * the classification engine (analogous to HydroCare's ResNet-50).
     *
     * @param  string $base64Image  Base64-encoded image data
     * @param  string $mimeType     Image MIME type (image/jpeg, image/png, etc.)
     * @param  string $bagian       'mata' | 'kulit' | 'kuku'
     * @return array|null           Structured prediction result
     */
    public function analyzeImageStructured(string $base64Image, string $mimeType, string $bagian): ?array
    {
        if (empty($this->apiKey)) {
            return ['error' => 'GEMINI_API_KEY belum diatur.'];
        }

        // Load knowledge base for this area
        $signs = config("vitamin_deficiency_signs.{$bagian}", []);
        $tandaRef = '';
        if (!empty($signs['tanda_visual'])) {
            foreach ($signs['tanda_visual'] as $tv) {
                $indikasiStr = implode(', ', $tv['indikasi']);
                $tandaRef .= "- {$tv['tanda']}: {$tv['deskripsi']} → Indikasi: {$indikasiStr}\n";
            }
        }

        $bagianLabel = $signs['label'] ?? ucfirst($bagian);

        $systemPrompt = <<<PROMPT
Kamu adalah sistem AI computer vision medis (deep learning classifier) yang menganalisis foto {$bagianLabel} anak untuk mendeteksi tanda-tanda kekurangan vitamin dan nutrisi.

REFERENSI TANDA VISUAL YANG HARUS DICEK:
{$tandaRef}

INSTRUKSI PENTING:
1. Analisis foto dengan teliti, identifikasi SEMUA tanda visual yang terlihat.
2. Untuk setiap tanda, tentukan indikasi defisiensi dan tingkat keyakinan.
3. Jika ada tanda yang mengarah ke kondisi serius (kuning, sangat cekung, koilonikia berat), tandai perlu_rujuk = true.
4. Ini adalah SKRINING AWAL, bukan diagnosis. Gunakan kata "terindikasi", "kemungkinan", BUKAN "didiagnosis".

WAJIB JAWAB DALAM FORMAT JSON BERIKUT (tanpa markdown code block, langsung JSON):
{
  "area": "{$bagian}",
  "status": "normal" | "terindikasi",
  "temuan_visual": [
    {
      "tanda": "nama tanda yang terdeteksi",
      "deskripsi": "apa yang terlihat pada foto",
      "area_highlight": "deskripsi lokasi pada foto (misal: kelopak mata bawah)"
    }
  ],
  "indikasi_defisiensi": [
    {
      "vitamin_mineral": "nama vitamin/mineral",
      "alasan": "alasan singkat berdasarkan tanda visual",
      "keyakinan": "rendah" | "sedang" | "tinggi"
    }
  ],
  "confidence_score": 0.0-1.0,
  "perlu_rujuk": false,
  "alasan_rujuk": "alasan jika perlu_rujuk true, kosong jika false",
  "penjelasan_awam": "penjelasan singkat 1-2 kalimat untuk orang tua dalam bahasa sederhana"
}

Jika foto normal / tidak ada tanda kekurangan, kembalikan status "normal" dengan temuan_visual dan indikasi_defisiensi kosong, confidence_score tinggi.
PROMPT;

        $body = [
            'contents' => [[
                'role'  => 'user',
                'parts' => [
                    ['text' => "Analisis foto {$bagianLabel} anak ini untuk deteksi kekurangan vitamin/nutrisi:"],
                    [
                        'inlineData' => [
                            'mimeType' => $mimeType,
                            'data'     => $base64Image,
                        ],
                    ],
                ],
            ]],
            'systemInstruction' => [
                'parts' => [['text' => $systemPrompt]],
            ],
            'generationConfig' => [
                'temperature'    => 0.05,
                'responseMimeType' => 'application/json',
            ],
        ];

        try {
            $response = $this->callWithFallback($body, 120);

            if ($response === null || $response->failed()) {
                $status = $response ? $response->status() : 500;
                Log::error('Gemini Structured Vision error', [
                    'bagian' => $bagian,
                    'status' => $status,
                    'body'   => $response ? $response->body() : 'No response',
                ]);
                return [
                    'error'  => true,
                    'status' => $status,
                    'message' => $status === 429
                        ? 'AI sedang sibuk. Tunggu 1-2 menit lalu coba lagi.'
                        : 'Gagal menganalisis foto. Silakan coba lagi.',
                ];
            }

            $rawText = $response->json('candidates.0.content.parts.0.text') ?? '';
            // Clean any markdown code fences if present
            $rawText = preg_replace('/^```(?:json)?\s*/i', '', trim($rawText));
            $rawText = preg_replace('/\s*```$/', '', $rawText);

            $parsed = json_decode($rawText, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Gemini structured output not valid JSON', [
                    'bagian'  => $bagian,
                    'raw'     => substr($rawText, 0, 500),
                ]);
                // Return a fallback structured result
                return [
                    'area'                 => $bagian,
                    'status'               => 'terindikasi',
                    'temuan_visual'        => [],
                    'indikasi_defisiensi'  => [],
                    'confidence_score'     => 0.5,
                    'perlu_rujuk'          => false,
                    'alasan_rujuk'         => '',
                    'penjelasan_awam'      => $rawText,
                    'raw_response'         => true,
                ];
            }

            return $parsed;
        } catch (\Exception $e) {
            Log::error('Gemini Structured Vision exception', ['message' => $e->getMessage()]);
            return [
                'error'   => true,
                'message' => 'Terjadi kesalahan saat menganalisis foto.',
            ];
        }
    }

    /**
     * Compare before/after photos using Gemini Vision.
     *
     * @param  string $base64Before  Base64 of the initial photo
     * @param  string $base64After   Base64 of the follow-up photo
     * @param  string $mimeType      Image MIME type
     * @param  string $bagian        'mata' | 'kulit' | 'kuku'
     * @param  array  $previousResult  Previous structured analysis result
     * @return array|null
     */
    public function compareBeforeAfter(
        string $base64Before,
        string $base64After,
        string $mimeType,
        string $bagian,
        array  $previousResult = []
    ): ?array {
        if (empty($this->apiKey)) {
            return ['error' => 'GEMINI_API_KEY belum diatur.'];
        }

        $prevSummary = json_encode($previousResult, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $systemPrompt = <<<PROMPT
Kamu adalah sistem AI medis yang membandingkan 2 foto {$bagian} anak: SEBELUM (foto awal saat skrining) dan SESUDAH (foto follow-up setelah pemberian bantuan gizi).

HASIL ANALISIS AWAL:
{$prevSummary}

TUGAS:
Bandingkan kedua foto dan tentukan apakah kondisi MEMBAIK, BELUM_MEMBAIK, atau MEMBURUK.

WAJIB JAWAB DALAM FORMAT JSON (tanpa markdown code block):
{
  "status_perbandingan": "membaik" | "belum_membaik" | "memburuk",
  "perubahan_visual": [
    {
      "aspek": "aspek yang berubah (misal: warna konjungtiva)",
      "sebelum": "kondisi di foto sebelum",
      "sesudah": "kondisi di foto sesudah",
      "arah": "membaik" | "tetap" | "memburuk"
    }
  ],
  "confidence_score": 0.0-1.0,
  "kesimpulan": "penjelasan singkat untuk orang tua",
  "rekomendasi_lanjutan": "saran tindak lanjut"
}
PROMPT;

        $body = [
            'contents' => [[
                'role'  => 'user',
                'parts' => [
                    ['text' => "Foto SEBELUM ({$bagian}):"],
                    ['inlineData' => ['mimeType' => $mimeType, 'data' => $base64Before]],
                    ['text' => "Foto SESUDAH ({$bagian}):"],
                    ['inlineData' => ['mimeType' => $mimeType, 'data' => $base64After]],
                    ['text' => 'Bandingkan kedua foto ini dan tentukan perkembangan kondisi anak.'],
                ],
            ]],
            'systemInstruction' => [
                'parts' => [['text' => $systemPrompt]],
            ],
            'generationConfig' => [
                'temperature'      => 0.05,
                'responseMimeType' => 'application/json',
            ],
        ];

        try {
            $response = $this->callWithFallback($body, 120);

            if ($response === null || $response->failed()) {
                return ['error' => true, 'message' => 'Gagal membandingkan foto.'];
            }

            $rawText = $response->json('candidates.0.content.parts.0.text') ?? '';
            $rawText = preg_replace('/^```(?:json)?\s*/i', '', trim($rawText));
            $rawText = preg_replace('/\s*```$/', '', $rawText);

            $parsed = json_decode($rawText, true);
            return json_last_error() === JSON_ERROR_NONE ? $parsed : [
                'status_perbandingan' => 'belum_membaik',
                'kesimpulan'          => $rawText,
                'raw_response'        => true,
            ];
        } catch (\Exception $e) {
            Log::error('Gemini Compare exception', ['message' => $e->getMessage()]);
            return ['error' => true, 'message' => 'Terjadi kesalahan saat membandingkan foto.'];
        }
    }

    /**
     * Generate a combined/cross-correlated report from all area analyses.
     * This is the "Gemini fusion" layer that makes the system smarter than
     * analyzing each area independently.
     *
     * @param  array $hasilMata   Structured result from eye analysis
     * @param  array $hasilKulit  Structured result from skin analysis
     * @param  array $hasilKuku   Structured result from nail analysis
     * @param  array $kuesioner   Questionnaire answers (optional)
     * @return array|null
     */
    public function generateCombinedReport(
        array $hasilMata  = [],
        array $hasilKulit = [],
        array $hasilKuku  = [],
        array $kuesioner  = []
    ): ?array {
        $dataGabungan = json_encode([
            'analisis_mata'  => $hasilMata,
            'analisis_kulit' => $hasilKulit,
            'analisis_kuku'  => $hasilKuku,
            'kuesioner'      => $kuesioner,
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        // Load food recommendations
        $rekomendasiRef = config('vitamin_deficiency_signs.rekomendasi_makanan', []);
        $rekomendasiText = '';
        foreach ($rekomendasiRef as $nutrisi => $info) {
            $makanan = implode(', ', $info['makanan']);
            $rekomendasiText .= "- {$nutrisi}: {$makanan} | Suplemen: {$info['suplemen']}\n";
        }

        $systemPrompt = <<<PROMPT
Kamu adalah ahli gizi dan dokter anak AI yang menganalisis hasil skrining gabungan dari 3 area tubuh (mata, kulit, kuku) plus kuesioner gizi anak.

DATA ANALISIS PER AREA:
{$dataGabungan}

REFERENSI REKOMENDASI MAKANAN:
{$rekomendasiText}

TUGAS UTAMA:
1. CROSS-CORRELATE: Temukan defisiensi yang muncul di LEBIH DARI SATU area (ini meningkatkan keyakinan).
   Contoh: kuku pucat + konjungtiva pucat = indikasi anemia/Fe KUAT.
2. GABUNGKAN dengan data kuesioner untuk validasi tambahan.
3. Berikan rekomendasi makanan SPESIFIK dari referensi di atas.
4. Tentukan level risiko keseluruhan.

WAJIB JAWAB DALAM FORMAT JSON (tanpa markdown code block):
{
  "status_keseluruhan": "normal" | "terindikasi_ringan" | "terindikasi_sedang" | "terindikasi_berat",
  "level_risiko": "rendah" | "sedang" | "tinggi",
  "defisiensi_utama": [
    {
      "vitamin_mineral": "nama",
      "keyakinan": "rendah" | "sedang" | "tinggi",
      "sumber_bukti": ["mata", "kulit", "kuku", "kuesioner"],
      "penjelasan": "kenapa disimpulkan ini"
    }
  ],
  "korelasi_antar_area": [
    {
      "temuan": "deskripsi korelasi",
      "area_terlibat": ["mata", "kuku"],
      "kesimpulan": "apa artinya"
    }
  ],
  "rekomendasi_makanan": [
    {
      "makanan": "nama makanan",
      "nutrisi_target": "vitamin/mineral yang dipenuhi",
      "frekuensi": "berapa kali sehari/minggu",
      "prioritas": "tinggi" | "sedang" | "rendah"
    }
  ],
  "rekomendasi_suplemen": [
    {
      "suplemen": "nama",
      "alasan": "kenapa diperlukan"
    }
  ],
  "perlu_rujuk_nakes": true | false,
  "alasan_rujuk": "penjelasan jika perlu rujuk",
  "ringkasan_orang_tua": "2-3 kalimat penjelasan sederhana untuk orang tua, tanpa istilah medis berat",
  "disclaimer": "Ini adalah skrining awal berbasis AI, bukan diagnosis medis. Selalu konsultasikan dengan dokter atau tenaga kesehatan."
}
PROMPT;

        try {
            $body = [
                'contents' => [[
                    'role'  => 'user',
                    'parts' => [['text' => 'Analisis gabungan hasil skrining gizi anak ini dan berikan laporan komprehensif.']],
                ]],
                'systemInstruction' => [
                    'parts' => [['text' => $systemPrompt]],
                ],
                'generationConfig' => [
                    'temperature'      => 0.1,
                    'responseMimeType' => 'application/json',
                ],
            ];

            $response = $this->callWithFallback($body, 120);

            if ($response === null || $response->failed()) {
                return ['error' => true, 'message' => 'Gagal menghasilkan laporan gabungan.'];
            }

            $rawText = $response->json('candidates.0.content.parts.0.text') ?? '';
            $rawText = preg_replace('/^```(?:json)?\s*/i', '', trim($rawText));
            $rawText = preg_replace('/\s*```$/', '', $rawText);

            $parsed = json_decode($rawText, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Combined report JSON parse failed', ['raw' => substr($rawText, 0, 500)]);
                return [
                    'status_keseluruhan'  => 'terindikasi_sedang',
                    'level_risiko'        => 'sedang',
                    'ringkasan_orang_tua' => $rawText,
                    'raw_response'        => true,
                ];
            }

            return $parsed;
        } catch (\Exception $e) {
            Log::error('Combined report exception', ['message' => $e->getMessage()]);
            return ['error' => true, 'message' => 'Terjadi kesalahan saat menghasilkan laporan.'];
        }
    }
}

