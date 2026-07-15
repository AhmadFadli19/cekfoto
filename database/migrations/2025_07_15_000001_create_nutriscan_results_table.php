<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the nutriscan_results table for GiziKu AI Nutrition Screening.
     */
    public function up(): void
    {
        Schema::create('nutriscan_results', function (Blueprint $table) {
            $table->id();

            // Child identification
            $table->string('nama_anak', 100)->nullable()->comment('Nama anak (opsional)');
            $table->unsignedInteger('usia_bulan')->comment('Usia anak dalam bulan');
            $table->char('jenis_kelamin', 1)->comment('L = Laki-laki, P = Perempuan');

            // Anthropometry measurements
            $table->decimal('berat_badan', 5, 2)->nullable()->comment('Berat badan dalam kg');
            $table->decimal('tinggi_badan', 5, 2)->nullable()->comment('Tinggi/panjang badan dalam cm');

            // WHO Z-scores
            $table->decimal('z_score_bbu', 5, 2)->nullable()->comment('Z-skor Berat Badan / Umur');
            $table->decimal('z_score_tbu', 5, 2)->nullable()->comment('Z-skor Tinggi Badan / Umur');
            $table->decimal('z_score_bbtb', 5, 2)->nullable()->comment('Z-skor Berat Badan / Tinggi Badan');

            // Status classification
            $table->string('status_antropometri')->nullable()
                ->comment('Status: normal, gizi_kurang, gizi_buruk, pendek, stunting, wasting, gemuk, obesitas');

            // Photo paths (stored in public disk under giziku_scans/)
            $table->string('foto_wajah_path')->nullable();
            $table->string('foto_tangan_path')->nullable();

            // Questionnaire data (JSON)
            $table->json('kuesioner_data')->nullable()->comment('Jawaban kuesioner pola makan anak');

            // Screening score & risk level
            $table->unsignedSmallInteger('skor_gizi')->nullable()->comment('Skor gizi 0–100');
            $table->string('level_risiko')->nullable()->comment('rendah | sedang | tinggi');

            // AI report output
            $table->json('laporan_ai')->nullable()->comment('Laporan lengkap dari Gemini AI');
            $table->json('defisiensi_terdeteksi')->nullable()->comment('Daftar defisiensi yang terdeteksi AI');

            $table->timestamps();

            // Indexes for common queries
            $table->index('level_risiko');
            $table->index('jenis_kelamin');
            $table->index('usia_bulan');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutriscan_results');
    }
};
