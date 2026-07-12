<?php

namespace App\Http\Controllers;

use App\Models\PersalinanCase;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TrackerController extends Controller
{
    public function start(Request $request)
    {
        $validated = $request->validate([
            'catin_profile_id' => 'required|exists:catin_profiles,id',
            'bidan_id' => 'nullable|exists:users,id',
            'waktu_persalinan' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $case = PersalinanCase::create(array_merge($validated, [
            'status' => 'menunggu',
            'eskalasi_level' => 'none',
            'eskalasi_log' => [['time' => now()->toIso8601String(), 'event' => 'Timer 72 jam dimulai']],
        ]));

        return response()->json(['case' => $case, 'message' => 'Timer 72 jam RhoGAM dimulai']);
    }

    public function show($id)
    {
        $case = PersalinanCase::with(['catinProfile', 'bidan'])->findOrFail($id);

        $waktuPersalinan = Carbon::parse($case->waktu_persalinan);
        $now = Carbon::now();
        $hoursElapsed = $waktuPersalinan->diffInMinutes($now) / 60;
        $hoursRemaining = max(0, 72 - $hoursElapsed);
        $percentComplete = min(100, ($hoursElapsed / 72) * 100);

        // Determine escalation level
        $eskalasi = 'none';
        if ($hoursElapsed >= 60) $eskalasi = 'jam60';
        elseif ($hoursElapsed >= 48) $eskalasi = 'jam48';
        elseif ($hoursElapsed >= 24) $eskalasi = 'jam24';

        // Auto-update escalation
        if ($case->status === 'menunggu' && $eskalasi !== $case->eskalasi_level) {
            $log = $case->eskalasi_log ?? [];
            $log[] = ['time' => now()->toIso8601String(), 'event' => "Eskalasi ke level {$eskalasi}"];
            $case->update(['eskalasi_level' => $eskalasi, 'eskalasi_log' => $log]);

            if ($hoursElapsed >= 72) {
                $log[] = ['time' => now()->toIso8601String(), 'event' => 'LEWAT BATAS WAKTU 72 JAM'];
                $case->update(['status' => 'lewat_batas', 'eskalasi_log' => $log]);
            }
        }

        return response()->json([
            'case' => $case->fresh(),
            'timer' => [
                'hours_elapsed' => round($hoursElapsed, 1),
                'hours_remaining' => round($hoursRemaining, 1),
                'percent_complete' => round($percentComplete, 1),
                'eskalasi_level' => $eskalasi,
                'is_expired' => $hoursElapsed >= 72,
            ],
        ]);
    }

    public function complete(Request $request, $id)
    {
        $case = PersalinanCase::findOrFail($id);
        $log = $case->eskalasi_log ?? [];
        $log[] = ['time' => now()->toIso8601String(), 'event' => 'RhoGAM diberikan — kasus selesai ditangani'];

        $case->update([
            'status' => 'selesai',
            'rhogam_diberikan_at' => now(),
            'eskalasi_log' => $log,
        ]);

        return response()->json(['case' => $case->fresh(), 'message' => 'Kasus selesai ditangani']);
    }

    public function index()
    {
        $cases = PersalinanCase::with(['catinProfile', 'bidan'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($case) {
                $hoursElapsed = Carbon::parse($case->waktu_persalinan)->diffInMinutes(now()) / 60;
                $case->hours_elapsed = round($hoursElapsed, 1);
                $case->hours_remaining = round(max(0, 72 - $hoursElapsed), 1);
                return $case;
            });

        return response()->json($cases);
    }
}
