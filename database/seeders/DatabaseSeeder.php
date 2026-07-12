<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\RegionalRiskStat;
use App\Models\Faskes;
use App\Models\EducationContent;
use App\Models\CatinProfile;
use App\Models\DonorDirectory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== USERS ====================
        User::create(['name' => 'Admin Sistem', 'email' => 'admin@catinguard.id', 'password' => Hash::make('password'), 'role' => 'admin_sistem']);
        User::create(['name' => 'Bidan Sari', 'email' => 'bidan@catinguard.id', 'password' => Hash::make('password'), 'role' => 'bidan', 'province' => 'Jawa Barat', 'city' => 'Bandung']);
        User::create(['name' => 'Ahmad & Siti', 'email' => 'catin@catinguard.id', 'password' => Hash::make('password'), 'role' => 'catin', 'province' => 'Jawa Barat', 'city' => 'Bandung']);
        User::create(['name' => 'Admin RS Hasan Sadikin', 'email' => 'faskes@catinguard.id', 'password' => Hash::make('password'), 'role' => 'admin_faskes', 'province' => 'Jawa Barat']);
        User::create(['name' => 'Donor Andi', 'email' => 'donor@catinguard.id', 'password' => Hash::make('password'), 'role' => 'relawan', 'province' => 'DKI Jakarta']);

        // ==================== DEMO CATIN PROFILE ====================
        CatinProfile::create([
            'user_id' => 3, 'nama_pria' => 'Ahmad Fauzi', 'nama_wanita' => 'Siti Nurhaliza',
            'golongan_darah_pria' => 'A', 'golongan_darah_wanita' => 'O',
            'rhesus_pria' => 'positif', 'rhesus_wanita' => 'negatif',
            'rhesus_genotipe_pria' => 'Dd', 'rhesus_genotipe_wanita' => 'dd',
            'carrier_thalasemia_pria' => true, 'carrier_thalasemia_wanita' => true,
            'tanggal_rencana_nikah' => now()->addMonths(3),
            'province' => 'Jawa Barat', 'city' => 'Bandung',
        ]);

        // ==================== DONOR ====================
        DonorDirectory::create([
            'user_id' => 5, 'golongan_darah' => 'O', 'rhesus' => 'negatif',
            'province' => 'DKI Jakarta', 'city' => 'Jakarta Selatan',
            'is_available' => true, 'is_verified' => true,
        ]);

        // ==================== 34 PROVINCES DATA ====================
        $provinces = [
            ['code' => 'AC', 'name' => 'Aceh', 'aki' => 170, 'rh_neg' => 0.3, 'defisit' => 25, 'literasi' => 2.8, 'thal' => 120, 'faskes' => 45, 'penduduk' => 5370000],
            ['code' => 'SU', 'name' => 'Sumatera Utara', 'aki' => 140, 'rh_neg' => 0.5, 'defisit' => 30, 'literasi' => 3.2, 'thal' => 350, 'faskes' => 78, 'penduduk' => 14800000],
            ['code' => 'SB', 'name' => 'Sumatera Barat', 'aki' => 110, 'rh_neg' => 0.4, 'defisit' => 15, 'literasi' => 3.5, 'thal' => 180, 'faskes' => 42, 'penduduk' => 5530000],
            ['code' => 'RI', 'name' => 'Riau', 'aki' => 120, 'rh_neg' => 0.3, 'defisit' => 20, 'literasi' => 3.0, 'thal' => 95, 'faskes' => 38, 'penduduk' => 6900000],
            ['code' => 'JA', 'name' => 'Jambi', 'aki' => 130, 'rh_neg' => 0.2, 'defisit' => 18, 'literasi' => 3.0, 'thal' => 75, 'faskes' => 28, 'penduduk' => 3630000],
            ['code' => 'SS', 'name' => 'Sumatera Selatan', 'aki' => 150, 'rh_neg' => 0.4, 'defisit' => 22, 'literasi' => 2.9, 'thal' => 210, 'faskes' => 48, 'penduduk' => 8470000],
            ['code' => 'BE', 'name' => 'Bengkulu', 'aki' => 160, 'rh_neg' => 0.2, 'defisit' => 28, 'literasi' => 2.7, 'thal' => 45, 'faskes' => 18, 'penduduk' => 2010000],
            ['code' => 'LA', 'name' => 'Lampung', 'aki' => 145, 'rh_neg' => 0.3, 'defisit' => 20, 'literasi' => 3.0, 'thal' => 180, 'faskes' => 42, 'penduduk' => 9010000],
            ['code' => 'BB', 'name' => 'Kep. Bangka Belitung', 'aki' => 100, 'rh_neg' => 0.3, 'defisit' => 12, 'literasi' => 3.3, 'thal' => 35, 'faskes' => 15, 'penduduk' => 1520000],
            ['code' => 'KR', 'name' => 'Kep. Riau', 'aki' => 90, 'rh_neg' => 0.4, 'defisit' => 10, 'literasi' => 3.6, 'thal' => 40, 'faskes' => 22, 'penduduk' => 2180000],
            ['code' => 'JK', 'name' => 'DKI Jakarta', 'aki' => 60, 'rh_neg' => 0.8, 'defisit' => 5, 'literasi' => 4.5, 'thal' => 520, 'faskes' => 185, 'penduduk' => 10560000],
            ['code' => 'JB', 'name' => 'Jawa Barat', 'aki' => 95, 'rh_neg' => 0.6, 'defisit' => 35, 'literasi' => 3.5, 'thal' => 4255, 'faskes' => 210, 'penduduk' => 49940000],
            ['code' => 'JT', 'name' => 'Jawa Tengah', 'aki' => 85, 'rh_neg' => 0.5, 'defisit' => 28, 'literasi' => 3.4, 'thal' => 1850, 'faskes' => 195, 'penduduk' => 37030000],
            ['code' => 'JI', 'name' => 'Jawa Timur', 'aki' => 80, 'rh_neg' => 0.5, 'defisit' => 25, 'literasi' => 3.6, 'thal' => 2100, 'faskes' => 205, 'penduduk' => 40670000],
            ['code' => 'YO', 'name' => 'DI Yogyakarta', 'aki' => 55, 'rh_neg' => 0.6, 'defisit' => 5, 'literasi' => 4.3, 'thal' => 180, 'faskes' => 65, 'penduduk' => 3900000],
            ['code' => 'BT', 'name' => 'Banten', 'aki' => 100, 'rh_neg' => 0.5, 'defisit' => 20, 'literasi' => 3.4, 'thal' => 650, 'faskes' => 85, 'penduduk' => 12690000],
            ['code' => 'BA', 'name' => 'Bali', 'aki' => 50, 'rh_neg' => 0.4, 'defisit' => 5, 'literasi' => 4.2, 'thal' => 90, 'faskes' => 55, 'penduduk' => 4320000],
            ['code' => 'NB', 'name' => 'Nusa Tenggara Barat', 'aki' => 180, 'rh_neg' => 0.3, 'defisit' => 35, 'literasi' => 2.5, 'thal' => 250, 'faskes' => 35, 'penduduk' => 5320000],
            ['code' => 'NT', 'name' => 'Nusa Tenggara Timur', 'aki' => 220, 'rh_neg' => 0.2, 'defisit' => 45, 'literasi' => 2.0, 'thal' => 180, 'faskes' => 28, 'penduduk' => 5640000],
            ['code' => 'KB', 'name' => 'Kalimantan Barat', 'aki' => 160, 'rh_neg' => 0.3, 'defisit' => 30, 'literasi' => 2.8, 'thal' => 120, 'faskes' => 32, 'penduduk' => 5410000],
            ['code' => 'KT', 'name' => 'Kalimantan Tengah', 'aki' => 150, 'rh_neg' => 0.2, 'defisit' => 25, 'literasi' => 2.9, 'thal' => 65, 'faskes' => 22, 'penduduk' => 2770000],
            ['code' => 'KS', 'name' => 'Kalimantan Selatan', 'aki' => 130, 'rh_neg' => 0.3, 'defisit' => 20, 'literasi' => 3.1, 'thal' => 110, 'faskes' => 35, 'penduduk' => 4180000],
            ['code' => 'KI', 'name' => 'Kalimantan Timur', 'aki' => 100, 'rh_neg' => 0.4, 'defisit' => 15, 'literasi' => 3.5, 'thal' => 85, 'faskes' => 42, 'penduduk' => 3770000],
            ['code' => 'KU', 'name' => 'Kalimantan Utara', 'aki' => 170, 'rh_neg' => 0.2, 'defisit' => 32, 'literasi' => 2.5, 'thal' => 25, 'faskes' => 12, 'penduduk' => 710000],
            ['code' => 'SA', 'name' => 'Sulawesi Utara', 'aki' => 110, 'rh_neg' => 0.3, 'defisit' => 18, 'literasi' => 3.3, 'thal' => 70, 'faskes' => 32, 'penduduk' => 2620000],
            ['code' => 'ST', 'name' => 'Sulawesi Tengah', 'aki' => 180, 'rh_neg' => 0.2, 'defisit' => 30, 'literasi' => 2.6, 'thal' => 55, 'faskes' => 22, 'penduduk' => 3050000],
            ['code' => 'SN', 'name' => 'Sulawesi Selatan', 'aki' => 120, 'rh_neg' => 0.4, 'defisit' => 22, 'literasi' => 3.2, 'thal' => 320, 'faskes' => 65, 'penduduk' => 9070000],
            ['code' => 'SG', 'name' => 'Sulawesi Tenggara', 'aki' => 160, 'rh_neg' => 0.2, 'defisit' => 28, 'literasi' => 2.7, 'thal' => 45, 'faskes' => 18, 'penduduk' => 2750000],
            ['code' => 'GO', 'name' => 'Gorontalo', 'aki' => 190, 'rh_neg' => 0.2, 'defisit' => 35, 'literasi' => 2.5, 'thal' => 30, 'faskes' => 12, 'penduduk' => 1200000],
            ['code' => 'SR', 'name' => 'Sulawesi Barat', 'aki' => 200, 'rh_neg' => 0.1, 'defisit' => 38, 'literasi' => 2.3, 'thal' => 20, 'faskes' => 10, 'penduduk' => 1420000],
            ['code' => 'MA', 'name' => 'Maluku', 'aki' => 210, 'rh_neg' => 0.2, 'defisit' => 40, 'literasi' => 2.2, 'thal' => 35, 'faskes' => 15, 'penduduk' => 1870000],
            ['code' => 'MU', 'name' => 'Maluku Utara', 'aki' => 200, 'rh_neg' => 0.2, 'defisit' => 38, 'literasi' => 2.3, 'thal' => 25, 'faskes' => 12, 'penduduk' => 1280000],
            ['code' => 'PB', 'name' => 'Papua Barat', 'aki' => 250, 'rh_neg' => 0.3, 'defisit' => 48, 'literasi' => 1.8, 'thal' => 15, 'faskes' => 10, 'penduduk' => 1130000],
            ['code' => 'PA', 'name' => 'Papua', 'aki' => 280, 'rh_neg' => 0.4, 'defisit' => 55, 'literasi' => 1.5, 'thal' => 25, 'faskes' => 18, 'penduduk' => 4300000],
        ];

        $riskService = new \App\Services\RiskCalculationService();

        foreach ($provinces as $p) {
            $riskScore = $riskService->calculateRegionalRisk($p['aki'], $p['defisit'], $p['literasi']);
            RegionalRiskStat::create([
                'province_code' => $p['code'],
                'province_name' => $p['name'],
                'aki' => $p['aki'],
                'populasi_rh_negatif_persen' => $p['rh_neg'],
                'defisit_stok_rh_negatif' => $p['defisit'],
                'indeks_literasi' => $p['literasi'],
                'risk_score' => $riskScore,
                'kasus_thalasemia' => $p['thal'],
                'jumlah_faskes' => $p['faskes'],
                'jumlah_penduduk' => $p['penduduk'],
            ]);
        }

        // ==================== SAMPLE FASKES ====================
        $faskesData = [
            ['nama' => 'RSUP Dr. Hasan Sadikin', 'tipe' => 'rs', 'province' => 'Jawa Barat', 'city' => 'Bandung', 'lat' => -6.8884, 'lng' => 107.6084, 'rhogam' => true, 'rh_neg' => true, 'thal' => true, 'elektro' => true],
            ['nama' => 'RS Cipto Mangunkusumo', 'tipe' => 'rs', 'province' => 'DKI Jakarta', 'city' => 'Jakarta Pusat', 'lat' => -6.1959, 'lng' => 106.8459, 'rhogam' => true, 'rh_neg' => true, 'thal' => true, 'elektro' => true],
            ['nama' => 'RSUD Dr. Soetomo', 'tipe' => 'rs', 'province' => 'Jawa Timur', 'city' => 'Surabaya', 'lat' => -7.2709, 'lng' => 112.7575, 'rhogam' => true, 'rh_neg' => true, 'thal' => true, 'elektro' => true],
            ['nama' => 'RSUP Dr. Sardjito', 'tipe' => 'rs', 'province' => 'DI Yogyakarta', 'city' => 'Sleman', 'lat' => -7.7679, 'lng' => 110.3710, 'rhogam' => true, 'rh_neg' => true, 'thal' => true, 'elektro' => true],
            ['nama' => 'RSUP Dr. Kariadi', 'tipe' => 'rs', 'province' => 'Jawa Tengah', 'city' => 'Semarang', 'lat' => -6.9942, 'lng' => 110.4111, 'rhogam' => true, 'rh_neg' => true, 'thal' => true, 'elektro' => true],
            ['nama' => 'PMI Kota Bandung', 'tipe' => 'pmi_utd', 'province' => 'Jawa Barat', 'city' => 'Bandung', 'lat' => -6.9147, 'lng' => 107.6098, 'rhogam' => false, 'rh_neg' => true, 'thal' => false, 'elektro' => false],
            ['nama' => 'PMI DKI Jakarta', 'tipe' => 'pmi_utd', 'province' => 'DKI Jakarta', 'city' => 'Jakarta Pusat', 'lat' => -6.1866, 'lng' => 106.8307, 'rhogam' => false, 'rh_neg' => true, 'thal' => false, 'elektro' => false],
            ['nama' => 'Puskesmas Garuda', 'tipe' => 'puskesmas', 'province' => 'Jawa Barat', 'city' => 'Bandung', 'lat' => -6.9105, 'lng' => 107.6006, 'rhogam' => false, 'rh_neg' => false, 'thal' => false, 'elektro' => false],
            ['nama' => 'Puskesmas Kecamatan Menteng', 'tipe' => 'puskesmas', 'province' => 'DKI Jakarta', 'city' => 'Jakarta Pusat', 'lat' => -6.1960, 'lng' => 106.8374, 'rhogam' => false, 'rh_neg' => false, 'thal' => false, 'elektro' => false],
            ['nama' => 'RS Fatmawati', 'tipe' => 'rs', 'province' => 'DKI Jakarta', 'city' => 'Jakarta Selatan', 'lat' => -6.2946, 'lng' => 106.7930, 'rhogam' => true, 'rh_neg' => true, 'thal' => true, 'elektro' => true],
            ['nama' => 'RSUP H. Adam Malik', 'tipe' => 'rs', 'province' => 'Sumatera Utara', 'city' => 'Medan', 'lat' => 3.5773, 'lng' => 98.6532, 'rhogam' => true, 'rh_neg' => true, 'thal' => true, 'elektro' => true],
            ['nama' => 'RSUP Dr. M. Djamil', 'tipe' => 'rs', 'province' => 'Sumatera Barat', 'city' => 'Padang', 'lat' => -0.9471, 'lng' => 100.3539, 'rhogam' => true, 'rh_neg' => false, 'thal' => true, 'elektro' => true],
            ['nama' => 'RSUP Sanglah', 'tipe' => 'rs', 'province' => 'Bali', 'city' => 'Denpasar', 'lat' => -8.6732, 'lng' => 115.2126, 'rhogam' => true, 'rh_neg' => true, 'thal' => true, 'elektro' => true],
            ['nama' => 'RSUD Prof. Dr. W.Z. Johannes', 'tipe' => 'rs', 'province' => 'Nusa Tenggara Timur', 'city' => 'Kupang', 'lat' => -10.1603, 'lng' => 123.5876, 'rhogam' => false, 'rh_neg' => false, 'thal' => false, 'elektro' => true],
            ['nama' => 'RS Wahidin Sudirohusodo', 'tipe' => 'rs', 'province' => 'Sulawesi Selatan', 'city' => 'Makassar', 'lat' => -5.1353, 'lng' => 119.4877, 'rhogam' => true, 'rh_neg' => true, 'thal' => true, 'elektro' => true],
        ];

        foreach ($faskesData as $f) {
            Faskes::create([
                'nama' => $f['nama'], 'tipe' => $f['tipe'], 'province' => $f['province'],
                'city' => $f['city'], 'latitude' => $f['lat'], 'longitude' => $f['lng'],
                'has_rhogam' => $f['rhogam'], 'has_darah_rh_negatif' => $f['rh_neg'],
                'has_transfusi_thalasemia' => $f['thal'], 'has_elektroforesis' => $f['elektro'],
                'last_stock_update' => now(),
            ]);
        }

        // ==================== EDUCATION CONTENT ====================
        $articles = [
            ['judul' => 'Apa Itu Rhesus dan Mengapa Penting untuk Calon Pengantin?', 'kategori' => 'rhesus', 'tipe' => 'artikel', 'urutan' => 1,
             'konten' => 'Rhesus (Rh) adalah salah satu sistem penggolongan darah selain ABO. Setiap orang memiliki Rh positif (+) atau Rh negatif (-). Di Indonesia, sekitar 99% penduduk memiliki Rh positif, sehingga Rh negatif tergolong sangat langka. Mengapa ini penting? Jika seorang wanita Rh negatif menikah dengan pria Rh positif, ada risiko ketidakcocokan Rhesus pada kehamilan yang disebut Hemolytic Disease of the Newborn (HDN). Kondisi ini terjadi ketika sistem imun ibu membentuk antibodi yang menyerang sel darah merah janin. Kabar baiknya, risiko ini bisa dicegah dengan injeksi RhoGAM (Anti-D immunoglobulin) yang diberikan dalam waktu 72 jam setelah persalinan.',
             'ringkasan' => 'Pelajari tentang golongan darah Rhesus dan dampaknya bagi kehamilan Anda.'],
            ['judul' => 'Thalasemia: Apa yang Harus Diketahui Calon Pengantin', 'kategori' => 'thalasemia', 'tipe' => 'artikel', 'urutan' => 2,
             'konten' => 'Thalasemia adalah kelainan darah keturunan yang menyebabkan tubuh membuat hemoglobin lebih sedikit dari normal. Ada dua tipe utama: alfa-thalasemia dan beta-thalasemia. Yang perlu Anda ketahui: seseorang bisa menjadi "carrier" (pembawa sifat) thalasemia tanpa menunjukkan gejala. Jika kedua calon pengantin adalah carrier, ada kemungkinan 25% anak mereka menderita Thalasemia Mayor yang membutuhkan transfusi darah rutin seumur hidup. Di Indonesia, tercatat lebih dari 11.000 kasus thalasemia, dengan Jawa Barat sebagai provinsi dengan kasus terbanyak. Skrining pranikah melalui pemeriksaan CBC (Complete Blood Count) dan elektroforesis hemoglobin dapat mendeteksi status carrier sebelum menikah.',
             'ringkasan' => 'Kenali thalasemia dan pentingnya skrining pranikah untuk mencegah risiko.'],
            ['judul' => 'Memahami Hasil Lab Darah Anda', 'kategori' => 'umum', 'tipe' => 'artikel', 'urutan' => 3,
             'konten' => 'Hasil lab darah sering terasa membingungkan dengan banyak singkatan dan angka. Berikut penjelasan sederhana: Hemoglobin (Hb) mengukur kadar protein pembawa oksigen di darah — normal untuk wanita 12-16 g/dL, pria 14-18 g/dL. MCV (Mean Corpuscular Volume) mengukur ukuran sel darah merah — jika di bawah 80 fL, bisa mengindikasikan anemia defisiensi besi atau thalasemia trait. MCH (Mean Corpuscular Hemoglobin) mengukur jumlah hemoglobin per sel darah merah — normal 27-31 pg. HbA2 di atas 3.5% bisa mengindikasikan beta-thalasemia trait. Golongan darah ABO (A, B, AB, O) dan Rhesus (+/-) adalah identitas darah Anda yang tidak berubah seumur hidup.',
             'ringkasan' => 'Panduan sederhana memahami hasil pemeriksaan lab darah.'],
            ['judul' => 'Kuis: Seberapa Paham Anda tentang Risiko Rhesus?', 'kategori' => 'rhesus', 'tipe' => 'kuis', 'urutan' => 4,
             'konten' => 'Uji pemahaman Anda tentang risiko ketidakcocokan Rhesus pada kehamilan.',
             'ringkasan' => 'Kuis interaktif tentang Rhesus',
             'quiz_data' => [
                 ['question' => 'Apa yang dimaksud dengan Rh negatif?', 'options' => ['Darah yang tidak memiliki antigen D', 'Darah yang berbahaya', 'Darah yang langka di Eropa', 'Darah yang tidak bisa ditransfusikan'], 'correct' => 0],
                 ['question' => 'Kapan RhoGAM harus diberikan?', 'options' => ['Sebelum menikah', 'Dalam 72 jam setelah persalinan', 'Saat hamil trimester pertama', 'Satu tahun setelah menikah'], 'correct' => 1],
                 ['question' => 'Jika ibu Rh- dan ayah Rh+ (Dd), berapa persen kemungkinan anak Rh+?', 'options' => ['100%', '75%', '50%', '25%'], 'correct' => 2],
                 ['question' => 'Apa risiko utama ketidakcocokan Rhesus pada kehamilan?', 'options' => ['Diabetes gestasional', 'Hemolytic Disease of the Newborn (HDN)', 'Preeklampsia', 'Kelahiran prematur'], 'correct' => 1],
             ]],
        ];

        foreach ($articles as $article) {
            EducationContent::create($article);
        }
    }
}
