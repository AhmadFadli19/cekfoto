<?php

namespace App\Services;

/**
 * AnthropometryService
 *
 * Calculates WHO Child Growth Standards Z-scores for:
 *  - BB/U  (Weight-for-Age)
 *  - TB/U  (Length/Height-for-Age)
 *  - BB/TB (Weight-for-Length/Height)
 *
 * Reference: WHO Child Growth Standards (2006)
 * Simplified median & SD values for 0–60 months at 6-month intervals
 * with linear interpolation for intermediate ages.
 */
class AnthropometryService
{
    // -------------------------------------------------------------------------
    // WHO Reference Tables (median & SD) — simplified for 0–60 months
    // Keys: usia_bulan (0, 6, 12, 18, 24, 30, 36, 42, 48, 54, 60)
    // -------------------------------------------------------------------------

    /**
     * Weight-for-Age (BB/U) reference — Boys (L)
     * [median_kg, SD_kg]
     */
    private array $bbULaki = [
         0 => [3.35, 0.43],
         6 => [7.93, 0.84],
        12 => [9.65, 0.99],
        18 => [10.94, 1.10],
        24 => [12.19, 1.23],
        30 => [13.34, 1.33],
        36 => [14.44, 1.44],
        42 => [15.46, 1.53],
        48 => [16.41, 1.62],
        54 => [17.34, 1.72],
        60 => [18.26, 1.82],
    ];

    /**
     * Weight-for-Age (BB/U) reference — Girls (P)
     */
    private array $bbUPerempuan = [
         0 => [3.23, 0.41],
         6 => [7.30, 0.80],
        12 => [8.95, 0.93],
        18 => [10.23, 1.04],
        24 => [11.48, 1.17],
        30 => [12.63, 1.28],
        36 => [13.73, 1.37],
        42 => [14.74, 1.46],
        48 => [15.71, 1.55],
        54 => [16.68, 1.65],
        60 => [17.69, 1.76],
    ];

    /**
     * Length/Height-for-Age (TB/U) reference — Boys (L)
     * [median_cm, SD_cm]
     */
    private array $tbULaki = [
         0 => [49.9, 1.89],
         6 => [67.6, 2.17],
        12 => [75.7, 2.40],
        18 => [82.3, 2.57],
        24 => [87.8, 2.76],
        30 => [92.7, 2.94],
        36 => [96.1, 3.12],
        42 => [99.9, 3.30],
        48 => [103.3, 3.47],
        54 => [106.4, 3.62],
        60 => [110.0, 3.78],
    ];

    /**
     * Length/Height-for-Age (TB/U) reference — Girls (P)
     */
    private array $tbUPerempuan = [
         0 => [49.1, 1.86],
         6 => [65.7, 2.13],
        12 => [74.0, 2.37],
        18 => [80.7, 2.55],
        24 => [86.4, 2.76],
        30 => [91.1, 2.94],
        36 => [95.1, 3.10],
        42 => [98.7, 3.27],
        48 => [101.8, 3.43],
        54 => [105.1, 3.58],
        60 => [108.4, 3.73],
    ];

    /**
     * Weight-for-Length (BB/TB) reference — Boys (L)
     * Indexed by height (cm) at 6-unit intervals from 45–110 cm
     * [median_kg, SD_kg]
     */
    private array $bbTbLaki = [
         45 => [2.44, 0.35],
         50 => [3.31, 0.41],
         55 => [4.34, 0.50],
         60 => [5.63, 0.60],
         65 => [6.97, 0.71],
         70 => [8.22, 0.80],
         75 => [9.31, 0.88],
         80 => [10.32, 0.96],
         85 => [11.31, 1.05],
         90 => [12.27, 1.14],
         95 => [13.24, 1.25],
        100 => [14.25, 1.38],
        105 => [15.34, 1.54],
        110 => [16.55, 1.73],
    ];

    /**
     * Weight-for-Length (BB/TB) reference — Girls (P)
     */
    private array $bbTbPerempuan = [
         45 => [2.37, 0.34],
         50 => [3.20, 0.40],
         55 => [4.19, 0.49],
         60 => [5.45, 0.58],
         65 => [6.77, 0.69],
         70 => [8.00, 0.78],
         75 => [9.07, 0.86],
         80 => [10.06, 0.93],
         85 => [11.04, 1.02],
         90 => [11.99, 1.10],
         95 => [12.99, 1.20],
        100 => [14.04, 1.31],
        105 => [15.18, 1.46],
        110 => [16.44, 1.63],
    ];

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Calculate all anthropometry Z-scores and return status interpretations.
     *
     * @param  int    $usiaBulan    Age in months (0–216)
     * @param  string $jenisKelamin 'L' (laki-laki) or 'P' (perempuan)
     * @param  float  $beratBadan   Weight in kg
     * @param  float  $tinggiBadan  Length/Height in cm
     * @return array{
     *   z_bbu: float, z_tbu: float, z_bbtb: float,
     *   status_bbu: string, status_tbu: string, status_bbtb: string,
     *   interpretasi: string
     * }
     */
    public function calculate(int $usiaBulan, string $jenisKelamin, float $beratBadan, float $tinggiBadan): array
    {
        $isLaki = ($jenisKelamin === 'L');

        // Clamp age to 0–60 months for reference tables
        $ageClamped = min($usiaBulan, 60);

        // --- BB/U Z-score ---
        [$medianBbu, $sdBbu] = $this->interpolateByAge(
            $ageClamped,
            $isLaki ? $this->bbULaki : $this->bbUPerempuan
        );
        $zBbu = $this->calcZ($beratBadan, $medianBbu, $sdBbu);

        // --- TB/U Z-score ---
        [$medianTbu, $sdTbu] = $this->interpolateByAge(
            $ageClamped,
            $isLaki ? $this->tbULaki : $this->tbUPerempuan
        );
        $zTbu = $this->calcZ($tinggiBadan, $medianTbu, $sdTbu);

        // --- BB/TB Z-score ---
        [$medianBbtb, $sdBbtb] = $this->interpolateByHeight(
            $tinggiBadan,
            $isLaki ? $this->bbTbLaki : $this->bbTbPerempuan
        );
        $zBbtb = $this->calcZ($beratBadan, $medianBbtb, $sdBbtb);

        // --- Status classification ---
        $statusBbu  = $this->classifyBbu($zBbu);
        $statusTbu  = $this->classifyTbu($zTbu);
        $statusBbtb = $this->classifyBbtb($zBbtb);

        // --- Interpretasi gabungan ---
        $interpretasi = $this->buildInterpretasi($statusBbu, $statusTbu, $statusBbtb, $zBbu, $zTbu, $zBbtb);

        return [
            'z_bbu'        => round($zBbu, 2),
            'z_tbu'        => round($zTbu, 2),
            'z_bbtb'       => round($zBbtb, 2),
            'status_bbu'   => $statusBbu,
            'status_tbu'   => $statusTbu,
            'status_bbtb'  => $statusBbtb,
            'median_bb'    => round($medianBbu, 2),
            'median_tb'    => round($medianTbu, 2),
            'interpretasi' => $interpretasi,
        ];
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function calcZ(float $measured, float $median, float $sd): float
    {
        if ($sd <= 0) return 0.0;
        return ($measured - $median) / $sd;
    }

    /**
     * Interpolate median and SD by age (months) from a sparse table.
     * Table keys must be sorted ascending (0, 6, 12, …).
     */
    private function interpolateByAge(int $ageMonths, array $table): array
    {
        $keys = array_keys($table);
        sort($keys);

        // Exact match
        if (isset($table[$ageMonths])) {
            return $table[$ageMonths];
        }

        // Below minimum
        if ($ageMonths <= $keys[0]) {
            return $table[$keys[0]];
        }

        // Above maximum
        if ($ageMonths >= $keys[count($keys) - 1]) {
            return $table[$keys[count($keys) - 1]];
        }

        // Find surrounding keys and linear interpolate
        $lower = $keys[0];
        $upper = $keys[count($keys) - 1];

        foreach ($keys as $i => $k) {
            if ($k <= $ageMonths) $lower = $k;
            if ($k >= $ageMonths) { $upper = $k; break; }
        }

        if ($lower === $upper) {
            return $table[$lower];
        }

        $fraction = ($ageMonths - $lower) / ($upper - $lower);
        $medianL  = $table[$lower][0];
        $sdL      = $table[$lower][1];
        $medianU  = $table[$upper][0];
        $sdU      = $table[$upper][1];

        return [
            $medianL + $fraction * ($medianU - $medianL),
            $sdL     + $fraction * ($sdU     - $sdL),
        ];
    }

    /**
     * Interpolate median and SD by height (cm) from a sparse BB/TB table.
     */
    private function interpolateByHeight(float $height, array $table): array
    {
        $keys = array_keys($table);
        sort($keys);

        if ($height <= $keys[0])                   return $table[$keys[0]];
        if ($height >= $keys[count($keys) - 1])    return $table[$keys[count($keys) - 1]];

        $lower = $keys[0];
        $upper = $keys[count($keys) - 1];

        foreach ($keys as $k) {
            if ($k <= $height) $lower = $k;
            if ($k >= $height) { $upper = $k; break; }
        }

        if ($lower === $upper) return $table[$lower];

        $fraction = ($height - $lower) / ($upper - $lower);
        return [
            $table[$lower][0] + $fraction * ($table[$upper][0] - $table[$lower][0]),
            $table[$lower][1] + $fraction * ($table[$upper][1] - $table[$lower][1]),
        ];
    }

    /**
     * Classify BB/U (Weight-for-Age) status.
     * <-3 SD : gizi_buruk
     * -3 to -2: gizi_kurang
     * -2 to +2: normal
     * >+2    : lebih / overweight
     */
    private function classifyBbu(float $z): string
    {
        if ($z < -3.0) return 'gizi_buruk';
        if ($z < -2.0) return 'gizi_kurang';
        if ($z > 2.0)  return 'lebih';
        return 'normal';
    }

    /**
     * Classify TB/U (Length/Height-for-Age) status.
     * <-3 SD : stunting
     * -3 to -2: pendek
     * -2 to +2: normal
     * >+2    : tinggi
     */
    private function classifyTbu(float $z): string
    {
        if ($z < -3.0) return 'stunting';
        if ($z < -2.0) return 'pendek';
        if ($z > 2.0)  return 'tinggi';
        return 'normal';
    }

    /**
     * Classify BB/TB (Weight-for-Length/Height) status.
     * <-3 SD : wasting (gizi buruk akut)
     * -3 to -2: kurus
     * -2 to +2: normal
     * +2 to +3: gemuk
     * >+3    : obesitas
     */
    private function classifyBbtb(float $z): string
    {
        if ($z < -3.0) return 'wasting';
        if ($z < -2.0) return 'kurus';
        if ($z > 3.0)  return 'obesitas';
        if ($z > 2.0)  return 'gemuk';
        return 'normal';
    }

    /**
     * Build a human-readable Indonesian interpretation string.
     */
    private function buildInterpretasi(
        string $statusBbu,
        string $statusTbu,
        string $statusBbtb,
        float  $zBbu,
        float  $zTbu,
        float  $zBbtb
    ): string {
        $parts = [];

        // BB/U
        $parts[] = match ($statusBbu) {
            'gizi_buruk'  => "Berat badan sangat kurang (Z-skor BB/U: {$zBbu}). Anak berisiko gizi buruk dan perlu penanganan segera.",
            'gizi_kurang' => "Berat badan kurang (Z-skor BB/U: {$zBbu}). Perhatikan asupan kalori dan protein anak.",
            'lebih'       => "Berat badan berlebih (Z-skor BB/U: {$zBbu}). Perhatikan pola makan untuk menghindari obesitas.",
            default       => "Berat badan normal (Z-skor BB/U: {$zBbu}).",
        };

        // TB/U
        $parts[] = match ($statusTbu) {
            'stunting' => "Tinggi badan sangat pendek/stunting (Z-skor TB/U: {$zTbu}). Ini menandakan kekurangan gizi kronis jangka panjang.",
            'pendek'   => "Tinggi badan pendek (Z-skor TB/U: {$zTbu}). Risiko stunting — perhatikan asupan protein, zink, dan vitamin D.",
            'tinggi'   => "Tinggi badan di atas rata-rata (Z-skor TB/U: {$zTbu}). Pertumbuhan tinggi badan baik.",
            default    => "Tinggi badan normal (Z-skor TB/U: {$zTbu}).",
        };

        // BB/TB
        $parts[] = match ($statusBbtb) {
            'wasting'  => "Sangat kurus/wasting (Z-skor BB/TB: {$zBbtb}). Kondisi gizi buruk akut — segera rujuk ke fasilitas kesehatan.",
            'kurus'    => "Badan kurus (Z-skor BB/TB: {$zBbtb}). Perlu peningkatan asupan energi dan protein.",
            'gemuk'    => "Badan gemuk (Z-skor BB/TB: {$zBbtb}). Perhatikan kualitas makanan dan aktivitas fisik.",
            'obesitas' => "Obesitas (Z-skor BB/TB: {$zBbtb}). Konsultasikan dengan dokter mengenai manajemen berat badan.",
            default    => "Proporsi berat-tinggi badan normal (Z-skor BB/TB: {$zBbtb}).",
        };

        return implode(' ', $parts);
    }
}
