<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vitamin_detections', function (Blueprint $table) {
            // Structured AI/DL predictions per area (JSON)
            $table->json('dl_prediksi_mata')->nullable()->after('analisis_kuku');
            $table->json('dl_prediksi_kulit')->nullable()->after('dl_prediksi_mata');
            $table->json('dl_prediksi_kuku')->nullable()->after('dl_prediksi_kulit');

            // Confidence scores (0.0 - 1.0)
            $table->float('dl_confidence_mata')->nullable()->after('dl_prediksi_kuku');
            $table->float('dl_confidence_kulit')->nullable()->after('dl_confidence_mata');
            $table->float('dl_confidence_kuku')->nullable()->after('dl_confidence_kulit');

            // Combined AI report (Gemini cross-correlation of all areas + questionnaire)
            $table->text('analisis_gabungan_ai')->nullable()->after('analisis_gabungan');

            // Highlighted areas on photos described by AI (JSON)
            $table->json('highlight_areas')->nullable()->after('analisis_gabungan_ai');

            // Session type for follow-up tracking
            $table->string('sesi_tipe')->default('awal')->after('level_risiko');
                // 'awal' | 'checkpoint_h7' | 'ulang_posyandu'

            // Self-referencing FK for before/after comparison
            $table->unsignedBigInteger('sesi_sebelumnya_id')->nullable()->after('sesi_tipe');
            $table->foreign('sesi_sebelumnya_id')
                  ->references('id')
                  ->on('vitamin_detections')
                  ->onDelete('set null');

            // Comparison result when this is a follow-up session
            $table->string('status_perbandingan')->nullable()->after('sesi_sebelumnya_id');
                // 'membaik' | 'belum_membaik' | 'memburuk' | null
        });
    }

    public function down(): void
    {
        Schema::table('vitamin_detections', function (Blueprint $table) {
            $table->dropForeign(['sesi_sebelumnya_id']);
            $table->dropColumn([
                'dl_prediksi_mata',
                'dl_prediksi_kulit',
                'dl_prediksi_kuku',
                'dl_confidence_mata',
                'dl_confidence_kulit',
                'dl_confidence_kuku',
                'analisis_gabungan_ai',
                'highlight_areas',
                'sesi_tipe',
                'sesi_sebelumnya_id',
                'status_perbandingan',
            ]);
        });
    }
};
