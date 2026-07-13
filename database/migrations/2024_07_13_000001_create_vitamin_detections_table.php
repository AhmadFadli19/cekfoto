<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vitamin_detections', function (Blueprint $table) {
            $table->id();
            $table->string('nama_anak')->nullable();
            $table->integer('usia_anak')->nullable(); // in months
            $table->string('jenis_kelamin')->nullable(); // L/P

            // Photo paths
            $table->string('foto_mata')->nullable();
            $table->string('foto_kulit')->nullable();
            $table->string('foto_kuku')->nullable();

            // AI photo analysis results
            $table->text('analisis_mata')->nullable();
            $table->text('analisis_kulit')->nullable();
            $table->text('analisis_kuku')->nullable();
            $table->text('analisis_gabungan')->nullable();

            // Nutrition questionnaire answers (JSON)
            $table->json('jawaban_kuesioner')->nullable();

            // AI nutrition questionnaire analysis
            $table->text('analisis_gizi')->nullable();

            // Detected deficiencies (JSON array)
            $table->json('defisiensi_terdeteksi')->nullable();

            // Final recommendation
            $table->text('rekomendasi')->nullable();

            // Risk level: rendah, sedang, tinggi
            $table->string('level_risiko')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vitamin_detections');
    }
};
