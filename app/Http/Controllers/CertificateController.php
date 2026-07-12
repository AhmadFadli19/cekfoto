<?php

namespace App\Http\Controllers;

use App\Models\CatinProfile;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function show($catinId)
    {
        $profile = CatinProfile::with(['labResults', 'geneticRisks', 'referralCards', 'readinessScore'])->findOrFail($catinId);

        $certificate = [
            'nomor_sertifikat' => 'CG-CERT-' . str_pad($profile->id, 6, '0', STR_PAD_LEFT),
            'tanggal_terbit' => now()->format('d F Y'),
            'nama_pria' => $profile->nama_pria,
            'nama_wanita' => $profile->nama_wanita,
            'status_skrining' => $profile->labResults->count() > 0 ? 'Selesai' : 'Belum Lengkap',
            'golongan_darah_pria' => $profile->golongan_darah_pria . ($profile->rhesus_pria ? ' (' . $profile->rhesus_pria . ')' : ''),
            'golongan_darah_wanita' => $profile->golongan_darah_wanita . ($profile->rhesus_wanita ? ' (' . $profile->rhesus_wanita . ')' : ''),
            'risk_summary' => $profile->geneticRisks->pluck('risk_level', 'tipe')->toArray(),
            'readiness_score' => $profile->readinessScore?->total_score ?? 0,
            'is_complete' => $profile->labResults->count() > 0 && $profile->geneticRisks->count() > 0,
        ];

        return response()->json($certificate);
    }
}
