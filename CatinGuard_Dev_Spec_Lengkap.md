# CatinGuard — Dokumen Spesifikasi Pengembangan Lengkap
### Sistem Deteksi Dini & Triase Geospasial Risiko Rhesus & Thalasemia untuk Calon Pengantin Indonesia

> **Status dokumen:** Master spec untuk tim development.
> **Stack target:** Laravel (backend utama/API/Auth/Admin), Golang (service performa tinggi: geospatial engine, tracker real-time, notifikasi), React JS (frontend web + dashboard nakes).
> **Tujuan dokumen:** Menggabungkan (1) spesifikasi fitur awal, (2) hasil riset arsitektur & data (gaya paper akademik), (3) sumber dataset gratis by-provinsi, (4) fitur tambahan yang memperkuat daya saing produk, dan (5) daftar API/layanan gratis untuk setiap kebutuhan teknis — menjadi satu acuan tunggal untuk dikembangkan.

---

## Daftar Isi

1. Latar Belakang & Masalah
2. Cara Mendapatkan Dataset Rh+/Rh- per Provinsi (Sumber Nyata & Cara Aksesnya)
3. Pengetahuan Domain Medis Wajib untuk AI
4. Peran Pengguna (User Roles)
5. Alur Sistem End-to-End
6. Daftar Fitur Lengkap (Fitur Lama + Fitur Baru yang Disarankan)
7. Arsitektur Teknis (Laravel + Golang + React)
8. Semua Layanan/API Gratis yang Dipakai (Pengganti Layanan Berbayar)
9. Skema Database (Ringkas)
10. Roadmap MVP
11. Prinsip Desain & Etika Data

---

## 1. Latar Belakang & Masalah

CatinGuard adalah aplikasi skrining kesehatan pra-nikah (Catin = Calon Pengantin) yang menyasar dua risiko genetik-hematologis besar di Indonesia:

1. **Ketidaksesuaian Rhesus (Rh Incompatibility).** Populasi Rh negatif di Indonesia sangat kecil, diperkirakan hanya sekitar 0,1%–1,2% dari total populasi/pendonor, jauh berbeda dari populasi Eropa/Amerika yang bisa mencapai 15–18%. Akibatnya stok darah Rh- dan RhoGAM langka, sementara pasangan Rh- sering tidak menyadari risikonya terhadap janin (Hemolytic Disease of the Newborn/HDN).
2. **Thalasemia.** Data Kemenkes mencatat lebih dari 11.000 kasus terdaftar secara nasional, dengan konsentrasi tinggi di beberapa provinsi (Jawa Barat menyumbang sekitar 4.255 kasus). Banyak pasangan carrier tidak menyadari status mereka sebelum menikah.

**Masalah inti yang ingin diselesaikan:**
- Rendahnya literasi Catin tentang risiko genetik sebelum menikah.
- Hasil lab darah yang sulit dipahami masyarakat awam.
- Sulitnya menemukan fasilitas kesehatan yang benar-benar memiliki stok penanganan (RhoGAM, darah Rh-, unit transfusi thalasemia).
- Beban nakes/bidan Puskesmas dalam menyusun rencana aksi medis kasus berisiko tinggi.
- Jendela kritis **72 jam emas** pasca-persalinan untuk pemberian RhoGAM pada kasus Rh incompatibility, yang sering terlewat karena tidak ada sistem pelacakan otomatis.

**Filosofi produk:** AI adalah co-pilot, bukan pengganti dokter — mempercepat deteksi, menerjemahkan data medis ke bahasa awam, dan mempercepat rujukan ke faskes yang tepat, dengan human-in-the-loop wajib di setiap keputusan medis.

---

## 2. Cara Mendapatkan Dataset Rh+/Rh- per Provinsi (Sumber Nyata & Cara Aksesnya)

Tidak ada satu dataset nasional tunggal yang langsung memberi "jumlah ibu hamil Rh+/Rh- per provinsi" — data ini harus **disintesis dari beberapa sumber terbuka** yang saling melengkapi. Berikut peta sumber yang sudah diverifikasi tersedia secara terbuka, beserta cara mengaksesnya:

### 2.1 Data Golongan Darah & Rhesus (level provinsi/kabupaten/kecamatan)

| Sumber | Cakupan | Format | Cara Akses |
|---|---|---|---|
| **Satu Data Indonesia (SDI) — data.go.id / katalog.data.go.id** | Berbagai dataset "Jumlah Penduduk Menurut Golongan Darah" per provinsi/kabupaten/kecamatan/kelurahan, diterbitkan oleh Dinas Dukcapil masing-masing daerah (contoh: Jawa Barat 2013–2023, Kalimantan Barat 2025, DIY per-kapanewon/kalurahan) | CSV, JSON, GeoJSON | Cari dengan tag `Golongan Darah` di `https://data.go.id/dataset?tags=Golongan+Darah`, unduh langsung atau pakai endpoint CKAN API-nya (`/api/3/action/package_search?tags=Golongan+Darah`) — **gratis, tanpa API key** |
| **Portal Data Terbuka Provinsi (mis. opendata.jabarprov.go.id, dinas dukcapil daerah lain)** | Data golongan darah termasuk breakdown Rh (A-, B-, AB-, O-) per wilayah administratif | CSV/JSON | Setiap provinsi biasanya punya portal open data sendiri (`opendata.<nama-provinsi>prov.go.id`) — cari dataset "Golongan Darah" di masing-masing |
| **Ditjen Dukcapil Kemendagri (siap diminta via rilis pers/API SIAK)** | Data agregat nasional jumlah penduduk per golongan darah termasuk pemisahan Rh+/Rh- (A, A-, B, B-, AB, AB-, O, O-) dari database kependudukan (SIAK) | Rilis berkala/permintaan resmi | Untuk akses granular, ajukan permohonan data statistik agregat (bukan data pribadi) ke Dukcapil — gratis untuk keperluan riset/aplikasi kesehatan publik; sebagai baseline sementara pakai angka rilis pers nasional yang sudah dipublikasikan |
| **API Statistik Golongan Darah — Satu Data Bencana Indonesia (data.bnpb.go.id)** | API yang menyediakan data statistik golongan darah untuk keperluan kebencanaan (bisa dipakai sebagai referensi silang) | API/JSON | Gratis, cek dokumentasi dataset di `data.bnpb.go.id` |
| **PMI / UDD (Unit Donor Darah) tingkat kota** | Data stok & permintaan darah per golongan/Rh (contoh: UDD PMI Kota Tangerang, Kota Yogyakarta, Kota Tasikmalaya — banyak dipublikasikan di data.go.id/opendata daerah) | CSV | Cari `"stok darah"` atau `"golongan darah PMI"` di data.go.id atau portal opendata kota bersangkutan |

### 2.2 Data Kesehatan Ibu & Kematian Maternal (untuk layer risiko wilayah)

| Sumber | Kegunaan | Format | Akses |
|---|---|---|---|
| **data.go.id — "Angka Kematian Ibu (AKI)"** | AKI per 100.000 kelahiran hidup per provinsi | CSV | Gratis, tanpa key |
| **BPS (Badan Pusat Statistik) — sirusa.web.bps.go.id & bps.go.id** | Indikator kesehatan ibu, IPM, indikator demografis per provinsi | CSV/XLSX/API BPS Web API | BPS punya **Web API resmi** (`webapi.bps.go.id`) yang butuh registrasi akun gratis untuk mendapatkan API key |
| **SATUSEHAT / Kemenkes (registrasifasyankes.kemkes.go.id)** | Titik koordinat & status registrasi seluruh fasyankes (RS, Puskesmas, klinik, UTD) se-Indonesia | API/CSV | Gratis untuk fasyankes/vendor RME terdaftar — lihat Bagian 8.2 untuk cara daftar |
| **Kaggle "Maternal Mortality Dataset" & "Maternal Health Dataset"** | Data komorbiditas klinis untuk melatih model prediksi risiko tinggi kehamilan (referensi internasional, dipakai sebagai fitur tambahan model ML) | CSV/XLSX | Gratis, download via Kaggle API (butuh akun Kaggle gratis + token) |

### 2.3 Strategi Praktis untuk Tim Dev

Karena tidak ada dataset siap pakai "ibu hamil Rh- per provinsi", lakukan **estimasi statistik** dengan rumus:

```
Estimasi_IbuHamil_Rh-(wilayah) = Jumlah_Penduduk_Perempuan_UsiaSubur(wilayah)
                                   × Rasio_Rh-_Nasional_atau_Lokal
                                   × Angka_Fertilitas_Total(wilayah)
```

- `Jumlah_Penduduk_Perempuan_UsiaSubur` → BPS (Sensus/Susenas per provinsi).
- `Rasio_Rh-` → ambil dari data Dukcapil/dataset golongan darah daerah bila tersedia; kalau tidak ada data lokal, gunakan rasio nasional (~0,1–1,2%) sebagai fallback, dan **tandai jelas di UI bahwa itu angka estimasi**, bukan data riil per individu.
- `Angka_Fertilitas_Total` → BPS.

Simpan seluruh sumber ini sebagai **data pipeline terjadwal** (cron job harian/mingguan) yang menarik update dari CKAN API data.go.id + BPS Web API, lalu diproses dengan Pandas/GeoPandas (Python) atau native Golang (pakai `encoding/csv` + `paulmach/orb` untuk GeoJSON) menjadi tabel `regional_risk_stats` di database.

> ⚠️ **Catatan etika data:** karena ini data kesehatan/genetik sensitif, gunakan hanya **data agregat wilayah** (tidak pernah data individu tanpa consent eksplisit) untuk layer peta publik. Data individu Catin hanya boleh diakses oleh yang bersangkutan dan nakes yang menangani kasusnya.

---

## 3. Pengetahuan Domain Medis Wajib untuk AI

### 3.1 Genetika Rhesus (Rh)
- Ditentukan gen **RHD**: Rh+ (antigen D ada) dominan atas Rh- (antigen D tidak ada).
- Pola pewarisan:
  - Rh- × Rh- → 100% anak Rh-.
  - Rh+ homozigot (DD) × Rh- → 100% anak Rh+ (karier d).
  - Rh+ heterozigot (Dd) × Rh- → 50% Rh+, 50% Rh-.
  - Rh+ (Dd) × Rh+ (Dd) → 25% Rh-, 75% Rh+.
- **Risiko utama:** ibu Rh- mengandung janin Rh+ → sensitisasi antibodi ibu → risiko HDN pada kehamilan berikutnya.
- **Pencegahan standar:** injeksi **Anti-D immunoglobulin (RhoGAM)** dalam **72 jam** pasca-persalinan/keguguran/trauma kehamilan.

### 3.2 Genetika Thalasemia
- Kelainan darah keturunan autosomal resesif (gangguan produksi hemoglobin rantai alfa/beta).
- Jika kedua Catin carrier: 25% anak sehat, 50% carrier, 25% Thalasemia Mayor (butuh transfusi seumur hidup).
- Skrining: **CBC (Complete Blood Count)** + **elektroforesis hemoglobin** (relatif mahal, belum merata).

### 3.3 Batasan Wajib untuk AI
- Tidak boleh memberi diagnosis final — hanya interpretasi awal & edukasi.
- Wajib disclaimer medis di setiap output.
- Temperature rendah (±0.1–0.2) untuk fitur medis agar konsisten & minim halusinasi.
- Fitur rencana aksi medis wajib pakai **RAG** berbasis dokumen SOP resmi Kemenkes, bukan pengetahuan umum model.

---

## 4. Peran Pengguna (User Roles)

| Role | Deskripsi | Akses Utama |
|---|---|---|
| **Catin (Calon Pengantin)** | Pengguna umum | Input data lab, hasil visual, edukasi, cari faskes |
| **Bidan / Nakes Puskesmas** | Tenaga kesehatan primer | Dashboard kasus, AI Action-Plan, update stok faskes, 72-Hour Tracker |
| **Admin Faskes / RS / PMI** | Petugas rumah sakit/PMI | Update ketersediaan stok RhoGAM/darah Rh- |
| **Admin Sistem** | Pengelola aplikasi | Manajemen data, monitoring akurasi AI, audit log |
| **(Baru) Relawan/Komunitas (mis. mitra RNI)** | Komunitas donor darah langka | Lihat permintaan darurat terverifikasi, konfirmasi ketersediaan diri sebagai donor |

---

## 5. Alur Sistem End-to-End

```
[Catin daftar & isi data]
        ↓
[Edukasi awal: Fitur 1 - Literasi]
        ↓
[Upload hasil lab: Fitur 2 - AI Lab Scanner]
        ↓
[Hasil divisualisasikan: Fitur 3 - Genetic Tree]
        ↓
  Jika berisiko tinggi →
        ↓
[Referral Card dibuat: Fitur 7] → [Geo-Triage cari faskes: Fitur 4 - Peta Beranda]
        ↓
[Bidan terima kasus di dashboard] → [AI Action-Plan: Fitur 6]
        ↓
[Jika kasus Rh- pasca-persalinan] → [72-Hour Golden Tracker: Fitur 5]
        ↓
[Eskalasi otomatis bila lewat batas waktu] → [Fitur 9 - Jaringan Donor Darurat]
```

---

## 6. Daftar Fitur Lengkap

### FITUR 1 — Literasi Kesehatan Kehamilan & Kesesuaian Rh
Modul edukasi interaktif (artikel, infografis, kuis), Chatbot edukasi (LLM lokal) untuk pertanyaan awam ("Apa itu Rh negatif?"), bahasa disederhanakan.

### FITUR 2 — AI Lab Scanner
OCR hasil tes darah (golongan darah, Rh, indikasi thalasemia trait dari CBC/MCV/MCH) → estimasi risiko awal → rekomendasi pemeriksaan lanjutan bila indikasi → Referral Card otomatis bila berisiko tinggi.

### FITUR 3 — Visual Predictive Genetic Tree
Input status Rh & carrier thalasemia kedua Catin → model probabilitas (rule-based genetics Mendel) → infografis visual (persen sehat/carrier/berisiko) → wajib disandingkan **Edu-Bot** empatik agar tidak memicu kecemasan berlebihan.

**Tabel Matriks Probabilitas (acuan logika sistem):**

| Status Ibu | Status Ayah | Probabilitas Keturunan | Level Risiko |
|---|---|---|---|
| Rh- (dd) | Rh- (dd) | 100% Rh- | Rendah |
| Rh- (dd) | Rh+ heterozigot (Dd) | 50% Rh+, 50% Rh- | Tinggi — pantau kehamilan, siapkan RhoGAM |
| Rh- (dd) | Rh+ homozigot (DD) | 100% Rh+ (Dd) | Sangat tinggi — RhoGAM wajib |
| Carrier Thalasemia | Sehat (bukan carrier) | 50% carrier, 50% sehat | Rendah |
| Carrier Thalasemia | Carrier Thalasemia | 25% mayor, 50% carrier, 25% sehat | Sangat tinggi — konseling genetik pranikah wajib |

### FITUR 4 — AI Geo-Triage & Pemetaan Faskes (Beranda — *Killer Feature*)
Bukan sekadar RS terdekat, tapi RS/PMI yang **benar-benar punya stok** RhoGAM/darah Rh-/layanan transfusi thalasemia. Peta interaktif multilayer di beranda:

- **Layer 1 — Densitas Kematian Ibu:** heat-map hijau→merah dari data AKI per wilayah.
- **Layer 2 — Konflik Genetik & Kelangkaan Darah:** animasi *pulsing alert* di titik koordinat wilayah yang punya populasi Rh- tapi stok darah Rh-/RhoGAM nol.
- **Layer 3 — Indeks Literasi:** tekstur hatching pada wilayah dengan skor literasi digital rendah (data Kemenkominfo/Komdigi), menandai wilayah rawan akibat kurangnya literasi.

**Rumus pembobotan risiko wilayah:**

```
Tingkat_Risiko(wilayah) = (w1 × AKI) + (w2 × Defisit_Rh-) + (w3 × (5 - Indeks_Literasi))
```

Routing berbasis inventaris riil (bukan sekadar jarak), dengan mekanisme crowdsourcing: bidan/RS mitra menekan tombol "Stok Tersedia" untuk update manual mingguan, sebagai pengganti sementara integrasi inventory API penuh.

### FITUR 5 — 72-Hour Golden Tracker
Timer 72 jam otomatis begitu persalinan/keguguran ibu Rh- tercatat. Eskalasi bertingkat:
- Jam ke-24 → notifikasi ke bidan pendamping.
- Jam ke-48 → prioritas tinggi ke Kepala Puskesmas/Admin RS + Geo-Triage otomatis cek stok RhoGAM terdekat.
- Jam ke-60 → SMS/WhatsApp Gateway ke Dinas Kesehatan + status "Code Red" pada Referral Card.

Status: *Menunggu Tindakan* → *Selesai Ditangani* → *Lewat Batas Waktu (Eskalasi)*.

### FITUR 6 — AI Action-Plan Generator untuk Bidan/Nakes
LLM lokal (RAG berbasis SOP Kemenkes) menghasilkan draf rencana aksi ("Langkah 1: ... Langkah 2: ..."). Bidan meninjau & menyetujui — human-in-the-loop wajib, AI tidak pernah otomatis dieksekusi.

### FITUR 7 — Referral Card & Sistem Alert
Kartu digital QR berisi ringkasan hasil skrining, dipindai faskes tujuan agar rujukan cepat tanpa Catin mengulang riwayat medis. Terintegrasi dengan Fitur 4 (alert otomatis ke faskes bersstok relevan).

---

### 🆕 FITUR TAMBAHAN YANG DISARANKAN (untuk daya tarik & diferensiasi lebih kuat)

**FITUR 8 — Jaringan Donor Darurat Terverifikasi (Emergency Rare-Blood Network)**
Terinspirasi dari komunitas Rhesus Negatif Indonesia (RNI) yang selama ini berfungsi sebagai "bank darah bayangan". Sistem menyediakan direktori donor Rh- **opt-in & terverifikasi** (nama tersamar, hanya golongan darah + kota + status ketersediaan) yang bisa di-broadcast otomatis ke area tertentu saat ada kasus darurat (misal permintaan RS gagal terpenuhi >2 jam). Donor menerima notifikasi push/WhatsApp, bisa konfirmasi kesediaan 1-klik. **Nilai unik:** menjembatani sistem formal (RS/PMI) dengan jejaring akar rumput yang sudah terbukti menyelamatkan nyawa, tapi dengan verifikasi & privasi yang lebih baik daripada grup WhatsApp/Facebook manual.

**FITUR 9 — Skor Kesiapsiagaan Kehamilan (Pregnancy Readiness Score)**
Gamifikasi ringan: skor 0–100 berdasarkan kelengkapan skrining, kepatuhan jadwal kontrol, dan pemahaman edukasi (dari kuis Fitur 1). Mendorong engagement Catin agar tidak berhenti di tengah jalan setelah skrining awal.

**FITUR 10 — Mode Konsultasi Rujuk-Cepat via Video/Chat dengan Bidan (Telemedicine Ringan)**
Bukan telemedicine umum, tapi khusus untuk membahas hasil skrining Rh/thalasemia dengan bidan/nakes terdaftar sebelum memutuskan rujuk ke spesialis — mengisi celah antara "tahu risiko" dan "bingung harus ngapain".

**FITUR 11 — Simulasi Biaya & Estimasi BPJS/Jalur Pembiayaan**
Banyak Catin ragu memeriksakan diri karena takut biaya elektroforesis Hb mahal. Fitur ini menampilkan estimasi biaya pemeriksaan lanjutan + info skema BPJS Kesehatan/Program Pemerintah (skrining thalasemia pranikah kini masuk beberapa program daerah) agar keputusan tidak terhambat masalah biaya.

**FITUR 12 — Sertifikat Skrining Pranikah Digital (terintegrasi syarat KUA/Capil)**
Beberapa daerah di Indonesia sudah mensyaratkan bukti skrining kesehatan pranikah untuk pengurusan dokumen nikah. Referral Card/hasil CatinGuard bisa diekspor jadi PDF/QR resmi yang diterima KUA — memberi insentif konkret bagi Catin untuk benar-benar menyelesaikan skrining, bukan sekadar fitur "nice to have".

**FITUR 13 — Mode Offline-First untuk Daerah 3T**
Karena target termasuk Puskesmas di Sulawesi/Indonesia Timur dengan konektivitas terbatas, aplikasi bidan/nakes (React Native/PWA) menyimpan data lokal (IndexedDB/SQLite) dan sinkronisasi otomatis saat sinyal tersedia — konsisten dengan filosofi "LLM lokal tanpa ketergantungan internet stabil".

**FITUR 14 — Dashboard Epidemiologi untuk Dinas Kesehatan/Kemenkes**
Agregasi anonim tren risiko per wilayah (bukan data individu) sebagai *policy tool* — memperkuat nilai jual ke pemerintah/juri hackathon karena bukan cuma B2C tapi juga B2G (business-to-government).

---

## 7. Arsitektur Teknis (Laravel + Golang + React)

### 7.1 Pembagian Tanggung Jawab

```
┌─────────────────────┐      ┌──────────────────────────┐      ┌─────────────────────────┐
│   REACT JS (SPA)    │◄────►│   LARAVEL (Core API)      │◄────►│  GOLANG (Service Cepat) │
│  - Web Catin        │ REST │  - Auth (Sanctum/Passport)│ gRPC │  - Geo-Triage Engine     │
│  - Dashboard Nakes   │ JSON │  - CRUD data pasien/lab   │ /    │  - 72-Hour Tracker (WS)  │
│  - Peta interaktif   │      │  - Modul RAG orchestrator │ REST │  - Notification worker   │
│    (Leaflet/Mapbox)  │      │  - Referral Card & QR     │      │  - OCR job queue runner  │
│  - PWA offline-ready │      │  - Admin panel (Filament) │      │  - Realtime stok faskes  │
└─────────────────────┘      └──────────────────────────┘      └─────────────────────────┘
                                        │                                   │
                                        ▼                                   ▼
                              ┌────────────────────┐            ┌────────────────────────┐
                              │ MySQL/PostgreSQL +  │            │  Redis (queue, pub/sub, │
                              │ PostGIS (data geo)  │            │  cache stok real-time)  │
                              └────────────────────┘            └────────────────────────┘
                                        │
                                        ▼
                              ┌────────────────────────────┐
                              │ LLM Lokal via LM Studio     │
                              │ http://localhost:1234/v1/   │
                              │ chat/completions             │
                              │ + Vector DB (RAG SOP Kemenkes)│
                              └────────────────────────────┘
```

**Kenapa dibagi begini:**
- **Laravel** unggul untuk business logic, auth, admin panel (pakai Filament, gratis & cepat dibuat), ORM/migration rapi, dan orkestrasi pemanggilan LLM lokal (karena banyak library RAG/PHP HTTP client sudah matang).
- **Golang** dipakai khusus untuk beban yang butuh **concurrency & latensi rendah**: engine geospasial (hitung ulang skor risiko ribuan polygon), WebSocket untuk 72-Hour Tracker real-time (countdown live di dashboard bidan), dan notification worker (fan-out ke banyak faskes sekaligus). Goroutine jauh lebih ringan daripada PHP worker untuk kasus ini.
- **React JS** untuk SPA yang responsif, plus PWA agar dashboard bidan bisa dipakai semi-offline di daerah 3T.

### 7.2 Komunikasi Antar Layanan
- Laravel ↔ Golang: **REST internal** (paling sederhana untuk MVP) atau gRPC (untuk versi lanjutan, expose lewat `.proto` khusus geo-triage & tracker).
- Auth lintas layanan: JWT yang diterbitkan Laravel, divalidasi Golang service via shared secret/JWKS.
- Event-driven: Redis Pub/Sub atau Laravel Queue + Golang consumer untuk event seperti `persalinan.dicatat`, `stok.diupdate`, `rujukan.dibuat`.

### 7.3 Contoh Endpoint Kunci

```
POST   /api/catin/register
POST   /api/lab-scan/upload            (Laravel → forward file ke OCR worker Golang)
GET    /api/genetic-tree/{catinId}
POST   /api/referral-card/generate
GET    /api/geo-triage/map             (proxy ke Golang geo-service, response GeoJSON)
POST   /api/persalinan/catat           (memicu 72h tracker di Golang service)
GET    /ws/tracker/{caseId}            (WebSocket Golang, live countdown)
POST   /api/action-plan/generate       (Laravel → panggil LLM lokal + RAG)
POST   /api/faskes/update-stok
```

### 7.4 AI Lokal via LM Studio (tidak berubah dari spesifikasi awal)

```
LLM_BASE_URL=http://localhost:1234/v1
LLM_API_KEY=lm-studio
LLM_MODEL_NAME=<nama-model-yang-di-load-di-LM-Studio>
```

Body contoh:
```json
{
  "model": "<nama-model>",
  "messages": [
    { "role": "system", "content": "<konteks domain Bagian 3 + SOP Kemenkes via RAG>" },
    { "role": "user", "content": "<pertanyaan / data pasien>" }
  ],
  "temperature": 0.15
}
```
- Temperature 0.1–0.2 untuk semua fitur interpretasi medis/action-plan.
- Temperature 0.4–0.6 khusus chatbot edukasi ringan (Fitur 1) agar terasa natural.

---

## 8. Semua Layanan/API Gratis yang Dipakai (Pengganti Layanan Berbayar)

| Kebutuhan | Pilihan Gratis | Catatan |
|---|---|---|
| **LLM/Generative AI** | LM Studio (lokal, model open-weight seperti Llama/Qwen/Mistral) | Tanpa biaya per-token, jalan di server puskesmas/on-premise, cocok untuk data sensitif |
| **OCR hasil lab** | Tesseract OCR (open-source) atau model vision lokal ringan yang di-load lewat LM Studio | Tesseract gratis, cukup akurat untuk teks cetak/hasil print lab |
| **Peta interaktif** | Leaflet.js + tile OpenStreetMap (gratis, tanpa API key untuk penggunaan wajar) | Alternatif Mapbox GL JS punya tier gratis terbatas (perlu API key, ada kuota bulanan) |
| **Geocoding (alamat → koordinat)** | Nominatim (OpenStreetMap, gratis dengan rate limit) | Untuk volume tinggi, self-host Nominatim di server sendiri |
| **Data golongan darah/kesehatan** | data.go.id (CKAN API, gratis tanpa key), opendata provinsi, BPS Web API (gratis, perlu registrasi akun untuk API key) | Lihat Bagian 2 |
| **Data faskes** | registrasifasyankes.kemkes.go.id / SATUSEHAT Platform | Gratis untuk fasyankes/vendor RME terdaftar — daftar akun di `satusehat.kemkes.go.id/platform`, lengkapi data sistem RME di DFO/REGFASYANKES, dapat Organization ID/Client ID/Client Secret dalam 1–3 hari kerja. Untuk tahap development, gunakan **Sandbox API** (langsung tersedia setelah daftar akun, tanpa perlu verifikasi institusi) |
| **Notifikasi push** | Firebase Cloud Messaging (gratis, kuota besar) | Untuk notifikasi 72-Hour Tracker & alert stok |
| **WhatsApp Gateway** | Baileys (library open-source Node.js, pakai WhatsApp Web API tanpa biaya) atau tier gratis Fonnte/Wablas (terbatas) | Untuk skala hackathon/MVP, Baileys paling hemat biaya; untuk produksi resmi pertimbangkan WhatsApp Business API (berbayar) |
| **SMS Gateway** | Tier gratis/trial provider lokal, atau fallback ke WhatsApp/push saja untuk MVP | SMS umumnya tidak sepenuhnya gratis — bisa dijadikan fitur opsional tahap lanjut |
| **Vector DB untuk RAG** | Chroma / Qdrant (open-source, self-host gratis) atau pgvector (extension PostgreSQL, gratis) | pgvector paling praktis karena bisa satu database dengan data utama Laravel |
| **Hosting/PaaS gratis untuk demo** | Railway/Render free tier, atau VPS kampus/mitra untuk demo hackathon | Untuk produksi real, tetap perlu server berbayar, tapi untuk MVP/demo cukup tier gratis |
| **Admin panel Laravel** | Filament (open-source, gratis) | Mempercepat pembuatan dashboard admin sistem tanpa build UI dari nol |
| **Autentikasi** | Laravel Sanctum (built-in, gratis) | Cukup untuk SPA + mobile token auth |
| **Model ML ringan (prediksi risiko tambahan)** | scikit-learn (Python) untuk prototyping rule-based genetics, atau native Go untuk versi produksi | Perhitungan Mendel sebenarnya rule-based sederhana, tidak wajib ML kompleks |

---

## 9. Skema Database (Ringkas)

Tabel inti yang perlu ada sejak awal (nama tabel contoh, sesuaikan konvensi Laravel):

- `users` (role: catin, bidan, admin_faskes, admin_sistem, relawan)
- `catin_profiles` (data pasangan, status Rh, status carrier thalasemia)
- `lab_results` (hasil OCR: golongan darah, MCV, MCH, dsb + file asli)
- `genetic_risk_calculations` (hasil probabilitas Fitur 3)
- `referral_cards` (QR, status, ringkasan risiko)
- `faskes` (koordinat, jenis, kontak, dari SATUSEHAT)
- `faskes_stock_updates` (crowdsourcing stok RhoGAM/darah Rh-, timestamp, siapa yang update)
- `persalinan_cases` (untuk 72-Hour Tracker: waktu mulai, status, eskalasi log)
- `action_plans` (draf AI, status review, siapa yang approve)
- `regional_risk_stats` (agregat wilayah: AKI, defisit Rh-, indeks literasi — hasil pipeline Bagian 2.3)
- `donor_directory` (Fitur 8, opt-in, data tersamar)
- `audit_logs` (wajib untuk data kesehatan sensitif)

---

## 10. Roadmap MVP

| Tahap | Fitur | Alasan Prioritas |
|---|---|---|
| MVP 1 | Fitur 1 (Literasi) + Fitur 2 (AI Lab Scanner) | Fondasi input data & edukasi dasar |
| MVP 2 | Fitur 3 (Genetic Tree) + Fitur 7 (Referral Card) | Nilai jual visual utama |
| MVP 3 | Fitur 4 (Geo-Triage/Peta Beranda) | Killer feature untuk demo, tapi butuh data faskes |
| MVP 4 | Fitur 6 (Action-Plan Generator) | Sisi dashboard nakes |
| MVP 5 | Fitur 5 (72-Hour Golden Tracker) | Paling unik, butuh integrasi data persalinan matang |
| MVP 6 (opsional, penguat) | Fitur 8 (Jaringan Donor), Fitur 12 (Sertifikat Digital) | Diferensiasi tambahan untuk juri/pengguna nyata |

---

## 11. Prinsip Desain & Etika Data

- Bahasa awam terlebih dahulu, istilah medis selalu disertai penjelasan sederhana.
- Human-in-the-loop wajib di semua keputusan medis — AI hanya membuat draf/saran.
- Data pribadi & hasil lab terenkripsi, akses dibatasi sesuai role.
- Disclaimer medis wajib tampil di setiap output AI yang berkaitan dengan interpretasi hasil lab/rencana tindakan.
- UI terasa suportif dan tidak menghakimi (topik pranikah & kesehatan reproduksi bersifat sensitif).
- Data geospasial publik di beranda **hanya agregat wilayah**, tidak pernah menampilkan data individu.

---

*Dokumen ini menggabungkan spesifikasi produk awal (`CatinGuard_Spesifikasi_Aplikasi.md`) dan laporan riset arsitektur (`Sistem_Peta_Risiko_Kehamilan_Rhesus_Unik.pdf`), ditambah hasil riset sumber data terbuka per Juli 2026 dan penyesuaian arsitektur untuk stack Laravel + Golang + React JS. Gunakan sebagai acuan tunggal (single source of truth) untuk tim development.*
