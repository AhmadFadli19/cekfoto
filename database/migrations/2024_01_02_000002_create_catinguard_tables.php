<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catin_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_pria');
            $table->string('nama_wanita');
            $table->string('golongan_darah_pria')->nullable(); // A, B, AB, O
            $table->string('golongan_darah_wanita')->nullable();
            $table->string('rhesus_pria')->nullable(); // positif, negatif
            $table->string('rhesus_wanita')->nullable();
            $table->string('rhesus_genotipe_pria')->nullable(); // DD, Dd, dd
            $table->string('rhesus_genotipe_wanita')->nullable();
            $table->boolean('carrier_thalasemia_pria')->default(false);
            $table->boolean('carrier_thalasemia_wanita')->default(false);
            $table->date('tanggal_rencana_nikah')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->integer('readiness_score')->default(0);
            $table->timestamps();
        });

        Schema::create('lab_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catin_profile_id')->constrained()->onDelete('cascade');
            $table->string('jenis')->default('manual'); // manual, ocr
            $table->string('golongan_darah')->nullable();
            $table->string('rhesus')->nullable();
            $table->float('hemoglobin')->nullable();
            $table->float('mcv')->nullable();
            $table->float('mch')->nullable();
            $table->float('mchc')->nullable();
            $table->float('hba2')->nullable();
            $table->float('hbf')->nullable();
            $table->string('risk_level')->nullable(); // rendah, sedang, tinggi, sangat_tinggi
            $table->text('ai_interpretation')->nullable();
            $table->text('recommendations')->nullable();
            $table->string('file_path')->nullable();
            $table->string('milik')->default('pria'); // pria, wanita
            $table->timestamps();
        });

        Schema::create('genetic_risk_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catin_profile_id')->constrained()->onDelete('cascade');
            $table->string('tipe'); // rhesus, thalasemia
            $table->json('probabilitas'); // {"sehat": 50, "carrier": 25, "mayor": 25}
            $table->string('risk_level'); // rendah, sedang, tinggi, sangat_tinggi
            $table->text('penjelasan')->nullable();
            $table->text('ai_explanation')->nullable();
            $table->timestamps();
        });

        Schema::create('referral_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catin_profile_id')->constrained()->onDelete('cascade');
            $table->string('kode_referral')->unique();
            $table->string('status')->default('aktif'); // aktif, digunakan, expired
            $table->json('ringkasan_risiko');
            $table->string('risk_level');
            $table->text('catatan_nakes')->nullable();
            $table->foreignId('faskes_tujuan_id')->nullable();
            $table->timestamps();
        });

        Schema::create('faskes', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tipe'); // rs, puskesmas, klinik, pmi_utd
            $table->string('alamat')->nullable();
            $table->string('province');
            $table->string('city');
            $table->double('latitude');
            $table->double('longitude');
            $table->string('telepon')->nullable();
            $table->boolean('has_rhogam')->default(false);
            $table->boolean('has_darah_rh_negatif')->default(false);
            $table->boolean('has_transfusi_thalasemia')->default(false);
            $table->boolean('has_elektroforesis')->default(false);
            $table->timestamp('last_stock_update')->nullable();
            $table->timestamps();
        });

        Schema::create('faskes_stock_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faskes_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('rhogam_available')->default(false);
            $table->integer('stok_darah_rh_negatif')->default(0);
            $table->boolean('transfusi_thalasemia_available')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('persalinan_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catin_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('bidan_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('waktu_persalinan');
            $table->string('status')->default('menunggu'); // menunggu, selesai, lewat_batas
            $table->string('eskalasi_level')->default('none'); // none, jam24, jam48, jam60
            $table->timestamp('rhogam_diberikan_at')->nullable();
            $table->text('catatan')->nullable();
            $table->json('eskalasi_log')->nullable();
            $table->timestamps();
        });

        Schema::create('action_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catin_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('ai_draft');
            $table->text('final_plan')->nullable();
            $table->string('status')->default('draft'); // draft, reviewed, approved, rejected
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('regional_risk_stats', function (Blueprint $table) {
            $table->id();
            $table->string('province_code')->unique();
            $table->string('province_name');
            $table->float('aki')->default(0); // Angka Kematian Ibu per 100k
            $table->float('populasi_rh_negatif_persen')->default(0);
            $table->integer('defisit_stok_rh_negatif')->default(0);
            $table->float('indeks_literasi')->default(3); // 1-5 scale
            $table->float('risk_score')->default(0); // computed
            $table->integer('kasus_thalasemia')->default(0);
            $table->integer('jumlah_faskes')->default(0);
            $table->integer('jumlah_penduduk')->default(0);
            $table->timestamps();
        });

        Schema::create('donor_directory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('golongan_darah');
            $table->string('rhesus');
            $table->string('province');
            $table->string('city');
            $table->boolean('is_available')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('last_donor_date')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('changes')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        Schema::create('education_contents', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori'); // rhesus, thalasemia, kehamilan, umum
            $table->text('konten');
            $table->text('ringkasan')->nullable();
            $table->string('tipe')->default('artikel'); // artikel, infografis, kuis
            $table->json('quiz_data')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('readiness_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catin_profile_id')->constrained()->onDelete('cascade');
            $table->integer('skor_skrining')->default(0); // 0-30
            $table->integer('skor_kepatuhan')->default(0); // 0-30
            $table->integer('skor_edukasi')->default(0); // 0-40
            $table->integer('total_score')->default(0); // 0-100
            $table->json('detail')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('readiness_scores');
        Schema::dropIfExists('education_contents');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('donor_directory');
        Schema::dropIfExists('regional_risk_stats');
        Schema::dropIfExists('action_plans');
        Schema::dropIfExists('persalinan_cases');
        Schema::dropIfExists('faskes_stock_updates');
        Schema::dropIfExists('faskes');
        Schema::dropIfExists('referral_cards');
        Schema::dropIfExists('genetic_risk_calculations');
        Schema::dropIfExists('lab_results');
        Schema::dropIfExists('catin_profiles');
    }
};
