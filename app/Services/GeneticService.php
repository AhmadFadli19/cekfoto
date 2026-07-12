<?php

namespace App\Services;

class GeneticService
{
    /**
     * Calculate Rhesus inheritance probability
     * Based on Mendelian genetics (RHD gene)
     */
    public function calculateRhesusRisk(string $genotipePria, string $genotipeWanita): array
    {
        // dd x dd = 100% Rh-
        if ($genotipePria === 'dd' && $genotipeWanita === 'dd') {
            return [
                'probabilitas' => ['rh_positif' => 0, 'rh_negatif' => 100],
                'risk_level' => 'rendah',
                'penjelasan' => 'Kedua pasangan Rh negatif. Semua anak akan Rh negatif. Tidak ada risiko ketidakcocokan Rhesus pada kehamilan.',
            ];
        }

        // DD x dd = 100% Rh+ (Dd carrier)
        if ($genotipePria === 'DD' && $genotipeWanita === 'dd') {
            return [
                'probabilitas' => ['rh_positif' => 100, 'rh_negatif' => 0],
                'risk_level' => 'sangat_tinggi',
                'penjelasan' => 'Ibu Rh negatif, ayah Rh positif homozigot. Semua anak akan Rh positif. Risiko HDN (Hemolytic Disease of Newborn) SANGAT TINGGI. RhoGAM WAJIB disiapkan untuk setiap kehamilan.',
            ];
        }
        if ($genotipePria === 'dd' && $genotipeWanita === 'DD') {
            return [
                'probabilitas' => ['rh_positif' => 100, 'rh_negatif' => 0],
                'risk_level' => 'rendah',
                'penjelasan' => 'Ayah Rh negatif, ibu Rh positif homozigot. Tidak ada risiko ketidakcocokan Rhesus karena ibu Rh positif.',
            ];
        }

        // Dd x dd = 50% Rh+, 50% Rh-
        if ($genotipePria === 'Dd' && $genotipeWanita === 'dd') {
            return [
                'probabilitas' => ['rh_positif' => 50, 'rh_negatif' => 50],
                'risk_level' => 'tinggi',
                'penjelasan' => 'Ibu Rh negatif, ayah Rh positif heterozigot. 50% kemungkinan anak Rh positif. Risiko HDN TINGGI — pantau kehamilan dan siapkan RhoGAM.',
            ];
        }
        if ($genotipePria === 'dd' && $genotipeWanita === 'Dd') {
            return [
                'probabilitas' => ['rh_positif' => 50, 'rh_negatif' => 50],
                'risk_level' => 'rendah',
                'penjelasan' => 'Ayah Rh negatif, ibu Rh positif heterozigot. Tidak ada risiko ketidakcocokan Rhesus karena ibu Rh positif.',
            ];
        }

        // DD x DD = 100% Rh+
        if ($genotipePria === 'DD' && $genotipeWanita === 'DD') {
            return [
                'probabilitas' => ['rh_positif' => 100, 'rh_negatif' => 0],
                'risk_level' => 'rendah',
                'penjelasan' => 'Kedua pasangan Rh positif homozigot. Semua anak akan Rh positif. Tidak ada risiko.',
            ];
        }

        // Dd x DD or DD x Dd = 100% Rh+ (mix of DD and Dd)
        if (($genotipePria === 'Dd' && $genotipeWanita === 'DD') || ($genotipePria === 'DD' && $genotipeWanita === 'Dd')) {
            return [
                'probabilitas' => ['rh_positif' => 100, 'rh_negatif' => 0],
                'risk_level' => 'rendah',
                'penjelasan' => 'Kedua pasangan Rh positif. Semua anak akan Rh positif. Tidak ada risiko.',
            ];
        }

        // Dd x Dd = 75% Rh+, 25% Rh-
        if ($genotipePria === 'Dd' && $genotipeWanita === 'Dd') {
            return [
                'probabilitas' => ['rh_positif' => 75, 'rh_negatif' => 25],
                'risk_level' => 'rendah',
                'penjelasan' => 'Kedua pasangan Rh positif heterozigot. 25% kemungkinan anak Rh negatif, tapi karena ibu Rh positif, tidak ada risiko HDN.',
            ];
        }

        // Default fallback
        return [
            'probabilitas' => ['rh_positif' => 50, 'rh_negatif' => 50],
            'risk_level' => 'sedang',
            'penjelasan' => 'Data genotipe tidak lengkap. Konsultasikan dengan dokter untuk analisis lebih lanjut.',
        ];
    }

    /**
     * Calculate Thalassemia carrier risk
     * Autosomal recessive inheritance
     */
    public function calculateThalassemiaRisk(bool $carrierPria, bool $carrierWanita): array
    {
        if (!$carrierPria && !$carrierWanita) {
            return [
                'probabilitas' => ['sehat' => 100, 'carrier' => 0, 'mayor' => 0],
                'risk_level' => 'rendah',
                'penjelasan' => 'Tidak ada yang carrier Thalasemia. Risiko sangat rendah.',
            ];
        }

        if ($carrierPria && !$carrierWanita) {
            return [
                'probabilitas' => ['sehat' => 50, 'carrier' => 50, 'mayor' => 0],
                'risk_level' => 'rendah',
                'penjelasan' => 'Hanya calon suami yang carrier. 50% anak bisa carrier tetapi tidak akan menderita Thalasemia Mayor.',
            ];
        }

        if (!$carrierPria && $carrierWanita) {
            return [
                'probabilitas' => ['sehat' => 50, 'carrier' => 50, 'mayor' => 0],
                'risk_level' => 'rendah',
                'penjelasan' => 'Hanya calon istri yang carrier. 50% anak bisa carrier tetapi tidak akan menderita Thalasemia Mayor.',
            ];
        }

        // Both carriers
        return [
            'probabilitas' => ['sehat' => 25, 'carrier' => 50, 'mayor' => 25],
            'risk_level' => 'sangat_tinggi',
            'penjelasan' => 'KEDUA pasangan adalah carrier Thalasemia. 25% kemungkinan anak menderita Thalasemia Mayor (membutuhkan transfusi darah seumur hidup). KONSELING GENETIK PRANIKAH SANGAT DIANJURKAN.',
        ];
    }

    /**
     * Determine Rhesus genotype from phenotype
     * If exact genotype unknown, estimate based on population data
     */
    public function estimateGenotype(string $rhesus): string
    {
        if (strtolower($rhesus) === 'negatif' || $rhesus === '-') {
            return 'dd';
        }
        // For Rh+, without family testing, assume heterozygous (Dd) as more conservative estimate
        // In Indonesia, with very low Rh- prevalence, most Rh+ are DD
        return 'Dd'; // Conservative assumption
    }
}
