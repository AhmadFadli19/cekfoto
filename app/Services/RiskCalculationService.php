<?php

namespace App\Services;

class RiskCalculationService
{
    /**
     * Calculate regional risk score
     * Formula: Tingkat_Risiko(wilayah) = (w1 × AKI) + (w2 × Defisit_Rh-) + (w3 × (5 - Indeks_Literasi))
     */
    public function calculateRegionalRisk(float $aki, int $defisitRhNegatif, float $indeksLiterasi): float
    {
        $w1 = 0.4; // Weight for AKI (maternal mortality)
        $w2 = 0.35; // Weight for Rh- deficit
        $w3 = 0.25; // Weight for literacy gap

        // Normalize AKI (typically 0-500 per 100k births in Indonesia)
        $normalizedAki = min($aki / 500, 1.0);

        // Normalize deficit (0-100 scale)
        $normalizedDefisit = min($defisitRhNegatif / 100, 1.0);

        // Literacy gap (5 - index, where index is 1-5)
        $literacyGap = (5 - min(max($indeksLiterasi, 1), 5)) / 4;

        $riskScore = ($w1 * $normalizedAki + $w2 * $normalizedDefisit + $w3 * $literacyGap) * 100;

        return round(min(max($riskScore, 0), 100), 1);
    }

    /**
     * Classify risk level from score
     */
    public function classifyRisk(float $score): string
    {
        if ($score >= 70) return 'sangat_tinggi';
        if ($score >= 50) return 'tinggi';
        if ($score >= 30) return 'sedang';
        return 'rendah';
    }

    /**
     * Get risk color for map visualization
     */
    public function getRiskColor(float $score): string
    {
        if ($score >= 70) return '#dc2626'; // red
        if ($score >= 50) return '#f97316'; // orange
        if ($score >= 30) return '#eab308'; // yellow
        return '#22c55e'; // green
    }
}
