<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Vitamin & Nutrition Visual Scanner — Gemini AI & Deep Learning</title>
<meta name="description" content="Skrining awal defisiensi gizi anak berbasis pengenalan visual foto mata, kulit, dan kuku yang diintegrasikan oleh Gemini AI." />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet" />

<style>
/* ========== GLOBAL VARIABLES & STYLES ========== */
:root {
  --bg-dark: #070913;
  --bg-card: rgba(15, 23, 42, 0.45);
  --bg-card-hover: rgba(30, 41, 59, 0.7);
  --border-color: rgba(255, 255, 255, 0.08);
  --border-glow: rgba(99, 102, 241, 0.25);
  
  --primary: #6366f1;
  --primary-glow: rgba(99, 102, 241, 0.2);
  --success: #10b981;
  --success-glow: rgba(16, 185, 129, 0.2);
  --warning: #f59e0b;
  --warning-glow: rgba(245, 158, 11, 0.2);
  --danger: #ef4444;
  --danger-glow: rgba(239, 68, 68, 0.2);
  --info: #06b6d4;

  --text-main: #f8fafc;
  --text-muted: #94a3b8;
  --text-dark: #475569;

  --radius-lg: 24px;
  --radius-md: 16px;
  --radius-sm: 8px;
  
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Plus Jakarta Sans', sans-serif;
  background-color: var(--bg-dark);
  color: var(--text-main);
  min-height: 100vh;
  overflow-x: hidden;
  position: relative;
  line-height: 1.6;
}

/* Glassmorphism Background Orbs */
.orb-container {
  position: fixed;
  inset: 0;
  pointer-events: none;
  z-index: 0;
  overflow: hidden;
}
.orb {
  position: absolute;
  border-radius: 50%;
  filter: blur(140px);
  opacity: 0.35;
  animation: floatOrb 12s ease-in-out infinite alternate;
}
.orb-1 { width: 500px; height: 500px; background: radial-gradient(circle, #4f46e5, transparent); top: -100px; left: -100px; }
.orb-2 { width: 450px; height: 450px; background: radial-gradient(circle, #06b6d4, transparent); bottom: -50px; right: -50px; animation-delay: -4s; }
.orb-3 { width: 300px; height: 300px; background: radial-gradient(circle, #d946ef, transparent); top: 30%; left: 50%; animation-delay: -8s; }

@keyframes floatOrb {
  0% { transform: translate(0, 0) scale(1); }
  100% { transform: translate(40px, 40px) scale(1.1); }
}

/* ========== LAYOUT ========== */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 40px 24px;
  position: relative;
  z-index: 10;
}

header {
  text-align: center;
  margin-bottom: 50px;
}
header h1 {
  font-family: 'Space Grotesk', sans-serif;
  font-size: 2.8rem;
  font-weight: 700;
  background: linear-gradient(135deg, #f8fafc 30%, #a5b4fc 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 12px;
  letter-spacing: -0.02em;
}
header p {
  color: var(--text-muted);
  font-size: 1.1rem;
  max-width: 600px;
  margin: 0 auto;
}

.main-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 32px;
}
@media (min-width: 992px) {
  .main-grid {
    grid-template-columns: 1.2fr 0.8fr;
  }
}

/* ========== CARDS & GLASSMORPHISM ========== */
.glass-card {
  background: var(--bg-card);
  backdrop-filter: blur(12px);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-lg);
  padding: 32px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
  transition: var(--transition);
  position: relative;
  overflow: hidden;
}
.glass-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; height: 1px;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
}

.glass-card:hover {
  border-color: rgba(99, 102, 241, 0.2);
}

/* ========== CAMERA & PREVIEW AREA ========== */
.scan-area-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}
.area-tabs {
  display: flex;
  gap: 12px;
}
.tab-btn {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid var(--border-color);
  color: var(--text-muted);
  padding: 8px 16px;
  border-radius: 100px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: var(--transition);
}
.tab-btn.active {
  background: var(--primary);
  color: var(--text-main);
  border-color: var(--primary);
  box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
}

/* Camera Wrapper & Overlay */
.camera-wrapper {
  position: relative;
  width: 100%;
  aspect-ratio: 4/3;
  background: #000;
  border-radius: var(--radius-md);
  overflow: hidden;
  border: 2px dashed rgba(255, 255, 255, 0.15);
  display: flex;
  justify-content: center;
  align-items: center;
}
.camera-wrapper.active {
  border-style: solid;
  border-color: var(--primary);
}

video {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transform: scaleX(-1); /* mirror effect */
}
canvas {
  display: none;
}

/* Camera overlays for guidance */
.camera-overlay {
  position: absolute;
  inset: 0;
  pointer-events: none;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 10;
}
.guideline-box {
  border: 2px dashed rgba(255, 255, 255, 0.6);
  box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.65); /* spotlight overlay effect */
  transition: var(--transition);
}
.guideline-box.mata {
  width: 260px;
  height: 160px;
  border-radius: 50%;
}
.guideline-box.kulit {
  width: 220px;
  height: 220px;
  border-radius: var(--radius-md);
}
.guideline-box.kuku {
  width: 240px;
  height: 180px;
  border-radius: 30px;
}
.guideline-text {
  position: absolute;
  bottom: 20px;
  background: rgba(0, 0, 0, 0.85);
  border: 1px solid rgba(255, 255, 255, 0.1);
  padding: 8px 16px;
  border-radius: 100px;
  color: var(--text-main);
  font-size: 0.9rem;
  text-align: center;
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Photo Preview & Drag and Drop */
.preview-container {
  position: absolute;
  inset: 0;
  background: #090e1a;
  display: none;
  justify-content: center;
  align-items: center;
  z-index: 20;
}
.preview-container img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.upload-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
  color: var(--text-muted);
  cursor: pointer;
  padding: 40px;
  text-align: center;
}
.upload-placeholder svg {
  width: 64px;
  height: 64px;
  color: var(--primary);
  margin-bottom: 8px;
  filter: drop-shadow(0 0 10px rgba(99, 102, 241, 0.3));
}
.upload-placeholder button {
  margin-top: 12px;
}

/* Camera Action Buttons */
.camera-actions {
  display: flex;
  justify-content: center;
  gap: 16px;
  margin-top: 24px;
}

/* ========== INPUT FIELDS & FORMS ========== */
.input-group {
  margin-bottom: 20px;
}
.input-label {
  display: block;
  color: var(--text-muted);
  font-weight: 600;
  margin-bottom: 8px;
  font-size: 0.9rem;
}
.input-field {
  width: 100%;
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-sm);
  padding: 12px 16px;
  color: var(--text-main);
  font-family: inherit;
  font-size: 1rem;
  transition: var(--transition);
}
.input-field:focus {
  border-color: var(--primary);
  outline: none;
  background: rgba(255, 255, 255, 0.06);
  box-shadow: 0 0 10px rgba(99, 102, 241, 0.2);
}
.select-field {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 16px center;
  background-size: 16px;
}

/* ========== BUTTONS ========== */
.btn {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid var(--border-color);
  color: var(--text-main);
  padding: 12px 24px;
  border-radius: var(--radius-md);
  font-weight: 700;
  cursor: pointer;
  transition: var(--transition);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}
.btn-primary {
  background: var(--primary);
  border-color: var(--primary);
  box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
}
.btn-primary:hover {
  background: #4f46e5;
  box-shadow: 0 4px 20px rgba(99, 102, 241, 0.5);
  transform: translateY(-2px);
}
.btn-success {
  background: var(--success);
  border-color: var(--success);
  box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}
.btn-success:hover {
  background: #059669;
  box-shadow: 0 4px 20px rgba(16, 185, 129, 0.5);
  transform: translateY(-2px);
}
.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none !important;
  box-shadow: none !important;
}

/* ========== STAGE WIZARD FOR QUESTIONNAIRE ========== */
.q-wizard {
  display: flex;
  flex-direction: column;
  gap: 20px;
}
.q-step {
  display: none;
  animation: fadeIn 0.4s ease-in-out;
}
.q-step.active {
  display: block;
}
.q-option {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-md);
  padding: 16px 20px;
  margin-bottom: 12px;
  cursor: pointer;
  transition: var(--transition);
  display: flex;
  align-items: center;
  gap: 16px;
}
.q-option:hover {
  background: rgba(255, 255, 255, 0.06);
  border-color: var(--border-glow);
}
.q-option.selected {
  background: rgba(99, 102, 241, 0.1);
  border-color: var(--primary);
  color: var(--text-main);
}
.q-radio {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  border: 2px solid var(--text-muted);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: var(--transition);
}
.q-option.selected .q-radio {
  border-color: var(--primary);
  background: var(--primary);
}
.q-radio::after {
  content: '';
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--text-main);
  opacity: 0;
  transition: var(--transition);
}
.q-option.selected .q-radio::after {
  opacity: 1;
}

/* Progress bar for Questionnaire */
.progress-container {
  height: 6px;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 100px;
  overflow: hidden;
  margin-bottom: 24px;
}
.progress-bar {
  height: 100%;
  width: 0%;
  background: var(--primary);
  border-radius: 100px;
  transition: width 0.4s ease;
}

/* ========== SCREENING RESULTS AND CONFIDENCE RINGS ========== */
.results-wrapper {
  margin-top: 40px;
}
.results-title {
  font-family: 'Space Grotesk', sans-serif;
  font-size: 1.8rem;
  margin-bottom: 24px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.grid-3 {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 24px;
}

.area-result-card {
  background: rgba(255, 255, 255, 0.02);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-md);
  padding: 24px;
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.area-result-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.area-badge {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 700;
  font-size: 1.1rem;
}
.result-status {
  padding: 4px 10px;
  border-radius: 100px;
  font-size: 0.8rem;
  font-weight: 700;
  text-transform: uppercase;
}
.status-normal { background: var(--success-glow); color: var(--success); }
.status-warn { background: var(--warning-glow); color: var(--warning); }
.status-danger { background: var(--danger-glow); color: var(--danger); }

/* Conf Ring (HydroCare style) */
.ring-container {
  position: relative;
  width: 90px;
  height: 90px;
  margin: 0 auto;
}
svg.ring-svg {
  transform: rotate(-90deg);
  width: 100%;
  height: 100%;
}
circle.ring-bg {
  fill: none;
  stroke: rgba(255, 255, 255, 0.05);
  stroke-width: 8;
}
circle.ring-fill {
  fill: none;
  stroke-width: 8;
  stroke-linecap: round;
  stroke-dasharray: 251.2;
  stroke-dashoffset: 251.2;
  transition: stroke-dashoffset 1s ease-in-out;
}
.ring-value {
  position: absolute;
  inset: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 1.2rem;
  font-weight: 700;
  font-family: 'Space Grotesk', sans-serif;
}

/* ========== DASHBOARD SECTION (FUSION/REPORT LAYER) ========== */
.dashboard-card {
  margin-top: 40px;
  border-color: rgba(99, 102, 241, 0.4);
  box-shadow: 0 0 40px rgba(99, 102, 241, 0.15);
}
.db-header {
  display: flex;
  flex-direction: column;
  gap: 16px;
  border-bottom: 1px solid var(--border-color);
  padding-bottom: 24px;
  margin-bottom: 24px;
}
@media (min-width: 768px) {
  .db-header {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
  }
}

.level-badge {
  padding: 8px 20px;
  border-radius: 100px;
  font-weight: 800;
  font-size: 1rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}
.level-tinggi { background: var(--danger-glow); color: var(--danger); border: 1px solid var(--danger); }
.level-sedang { background: var(--warning-glow); color: var(--warning); border: 1px solid var(--warning); }
.level-rendah { background: var(--success-glow); color: var(--success); border: 1px solid var(--success); }

.report-section-title {
  font-family: 'Space Grotesk', sans-serif;
  font-size: 1.4rem;
  margin-bottom: 16px;
  color: var(--primary);
  display: flex;
  align-items: center;
  gap: 10px;
}

.deficiency-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-bottom: 32px;
}
.deficiency-item {
  background: rgba(255, 255, 255, 0.02);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-md);
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.deficiency-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.deficiency-name {
  font-weight: 700;
  font-size: 1.1rem;
}
.evidence-pill {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid var(--border-color);
  padding: 3px 10px;
  border-radius: 100px;
  font-size: 0.8rem;
  color: var(--text-muted);
}

.correlation-box {
  background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(6, 182, 212, 0.05));
  border: 1px solid rgba(99, 102, 241, 0.2);
  border-radius: var(--radius-md);
  padding: 24px;
  margin-bottom: 32px;
}

.recommendations-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 24px;
}
@media (min-width: 768px) {
  .recommendations-grid {
    grid-template-columns: 1fr 1fr;
  }
}
.rec-column {
  background: rgba(255, 255, 255, 0.015);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-md);
  padding: 24px;
}

/* ========== MONITORING SECTION (BEFORE/AFTER) ========== */
.monitoring-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 24px;
}
.photo-slot {
  background: rgba(0,0,0,0.3);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-md);
  aspect-ratio: 4/3;
  overflow: hidden;
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
}
.photo-slot img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.slot-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  background: rgba(0, 0, 0, 0.7);
  padding: 4px 10px;
  border-radius: 100px;
  font-size: 0.8rem;
  font-weight: 700;
}

/* ========== LOADER ANIMATION ========== */
.scanner-overlay {
  position: absolute;
  top: 0; left: 0;
  width: 2px;
  height: 100%;
  background: linear-gradient(to bottom, transparent, var(--primary), transparent);
  box-shadow: 0 0 15px var(--primary);
  animation: scan 2s linear infinite;
  pointer-events: none;
  display: none;
}
@keyframes scan {
  0% { left: 0; }
  50% { left: 100%; }
  100% { left: 0; }
}

.spinner {
  width: 24px;
  height: 24px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: var(--text-main);
  animation: spin 1s ease-in-out infinite;
}
@keyframes spin {
  to { transform: rotate(360deg); }
}

.loading-container {
  display: none;
  flex-direction: column;
  align-items: center;
  gap: 20px;
  padding: 60px 0;
}
.loading-pulse {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: var(--primary-glow);
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
}
.loading-pulse::after {
  content: '';
  position: absolute;
  inset: -10px;
  border-radius: 50%;
  border: 2px solid var(--primary);
  opacity: 0.7;
  animation: pulsePulse 1.5s infinite ease-in-out;
}
@keyframes pulsePulse {
  0% { transform: scale(0.9); opacity: 0.8; }
  100% { transform: scale(1.3); opacity: 0; }
}

/* ========== MISC UTILITIES ========== */
.hidden {
  display: none !important;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>

<div class="orb-container">
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>
</div>

<div class="container">
  <header>
    <h1>Nutrition Visual Scanner</h1>
    <p>Skrining AI defisiensi vitamin dan zat gizi anak melalui analisis terstruktur mata, kulit, dan kuku.</p>
  </header>

  <div class="main-grid">
    <!-- LEFT COLUMN: SCAN & INPUT AREA -->
    <div class="q-wizard" id="setupFormSection">
      <!-- STEP 1: ANAK PROFILE -->
      <div class="glass-card" id="profileStep">
        <h2 class="report-section-title" style="margin-bottom: 24px;">Data Anak</h2>
        <div class="input-group">
          <label class="input-label">Nama Lengkap Anak</label>
          <input type="text" id="namaAnak" class="input-field" placeholder="Masukkan nama anak" />
        </div>
        <div class="input-group">
          <label class="input-label">Usia Anak (Bulan)</label>
          <input type="number" id="usiaAnak" class="input-field" placeholder="Contoh: 24" min="0" max="216" />
        </div>
        <div class="input-group">
          <label class="input-label">Jenis Kelamin</label>
          <select id="jenisKelamin" class="input-field select-field">
            <option value="" disabled selected>Pilih jenis kelamin</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
          </select>
        </div>
        <div style="text-align: right; margin-top: 32px;">
          <button class="btn btn-primary" onclick="goToStep('questionnaireStep')">Lanjut ke Kuesioner →</button>
        </div>
      </div>

      <!-- STEP 2: NUTRITION QUESTIONNAIRE -->
      <div class="glass-card hidden" id="questionnaireStep">
        <div class="progress-container">
          <div class="progress-bar" id="qProgressBar"></div>
        </div>
        
        <h2 class="report-section-title" id="questionTitle" style="margin-bottom: 24px;">Pertanyaan 1</h2>
        <div id="optionsContainer">
          <!-- Dynamically populated -->
        </div>
        
        <div style="display: flex; justify-content: space-between; margin-top: 32px;">
          <button class="btn" onclick="prevQuestion()">Sebelumnya</button>
          <button class="btn btn-primary" id="nextQBtn" onclick="nextQuestion()">Berikutnya →</button>
        </div>
      </div>

      <!-- STEP 3: SCANNING AREA -->
      <div class="glass-card hidden" id="scanStep">
        <div class="scan-area-header">
          <h2 class="report-section-title" style="margin-bottom: 0;">Ambil Foto Area</h2>
          <div class="area-tabs">
            <button class="tab-btn active" data-area="mata" onclick="switchScanArea('mata')">👁️ Mata</button>
            <button class="tab-btn" data-area="kulit" onclick="switchScanArea('kulit')">🤲 Kulit</button>
            <button class="tab-btn" data-area="kuku" onclick="switchScanArea('kuku')">💅 Kuku</button>
          </div>
        </div>

        <div class="camera-wrapper" id="cameraWrapper">
          <div class="scanner-overlay" id="scannerOverlay"></div>
          
          <!-- Quality warning toast -->
          <div id="qualityToast" class="hidden" style="position: absolute; top: 16px; z-index: 35; background: rgba(239, 68, 68, 0.9); padding: 8px 16px; border-radius: 100px; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 8px; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(8px); box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
            <span id="qualityToastText">Foto Terlalu Gelap!</span>
          </div>

          <video id="videoFeed" autoplay playsinline muted></video>
          <canvas id="snapshotCanvas"></canvas>
          
          <div class="camera-overlay">
            <div class="guideline-box mata" id="guidelineBox"></div>
            <div class="guideline-text" id="guidelineText">
              <span>Pastikan area mata pas di dalam lingkaran</span>
            </div>
          </div>

          <!-- Preview capture overlay -->
          <div class="preview-container" id="previewContainer">
            <img id="capturedImagePreview" src="" alt="Capture Preview" />
          </div>

          <!-- Drag and Drop/Upload Backup Placeholder -->
          <div class="upload-placeholder hidden" id="uploadPlaceholder">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            <p>Kamera tidak terdeteksi atau tidak diizinkan.</p>
            <p style="font-size: 0.85rem;">Klik di bawah untuk mengupload foto dari galeri/penyimpanan.</p>
            <button class="btn btn-primary btn-sm" onclick="triggerFileInput()">Pilih Foto</button>
          </div>
        </div>

        <input type="file" id="fileFallbackInput" accept="image/*" class="hidden" onchange="handleFileInput(event)" />

        <div class="camera-actions">
          <button class="btn" id="cameraToggleBtn" onclick="toggleCameraInput()">Gunakan File Upload</button>
          <button class="btn btn-primary" id="captureBtn" onclick="captureSnapshot()">Ambil Foto</button>
          <button class="btn hidden" id="retakeBtn" onclick="retakePhoto()">Foto Ulang</button>
          <button class="btn btn-success hidden" id="analyzeBtn" onclick="analyzeCurrentArea()">Analisis AI Area Ini</button>
        </div>
      </div>
    </div>

    <!-- RIGHT COLUMN: PROGRESS SIDEBAR & QUICK INSTRUCTIONS -->
    <div>
      <!-- SCAN PROGRESS STATUS CARD -->
      <div class="glass-card" style="margin-bottom: 24px;">
        <h3 class="report-section-title">Status Skrining</h3>
        <div style="display: flex; flex-direction: column; gap: 16px; margin: 20px 0;">
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <span>Data Profil</span>
            <span id="badgeProfile" class="result-status status-danger">Belum</span>
          </div>
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <span>Kuesioner</span>
            <span id="badgeQ" class="result-status status-danger">Belum</span>
          </div>
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <span>Foto Mata</span>
            <span id="badgeMata" class="result-status status-danger">Belum</span>
          </div>
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <span>Foto Kulit</span>
            <span id="badgeKulit" class="result-status status-danger">Belum</span>
          </div>
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <span>Foto Kuku</span>
            <span id="badgeKuku" class="result-status status-danger">Belum</span>
          </div>
        </div>

        <button class="btn btn-primary" id="finalSubmitBtn" style="width: 100%;" disabled onclick="submitAllForFinalReport()">
          Dapatkan Laporan Gabungan AI
        </button>
      </div>

      <!-- MEDICAL DISCLAIMER & INFOPANEL -->
      <div class="glass-card" style="background: rgba(239, 68, 68, 0.05); border-color: rgba(239, 68, 68, 0.15);">
        <h3 class="report-section-title" style="color: var(--danger);">⚠️ Disclaimer Medis</h3>
        <p style="font-size: 0.88rem; color: var(--text-muted);">
          Aplikasi skrining ini menggunakan kecerdasan buatan (Gemini AI & deep learning) untuk menduga potensi defisiensi gizi berdasarkan indikator visual eksternal. 
          Hasil analisis adalah indikasi awal/dugaan sementara dan <strong>bukan diagnosis medis final</strong>. 
          Konsultasikan dengan dokter spesialis anak atau kunjungi Posyandu terdekat untuk verifikasi klinis langsung sebelum mengambil keputusan medis atau pemberian suplemen dosis tinggi.
        </p>
      </div>
    </div>
  </div>

  <!-- LOADER SPINNER / INTERMEDIATE SCREEN -->
  <div class="glass-card loading-container" id="loaderContainer">
    <div class="loading-pulse">
      <div class="spinner" style="width: 40px; height: 40px;"></div>
    </div>
    <h3 id="loaderTitle">Sedang Menganalisis...</h3>
    <p id="loaderSubtitle" style="color: var(--text-muted); text-align: center; max-width: 400px;">
      Gemini AI sedang menginterpretasi citra visual & korelasi nutrisi. Harap tunggu beberapa detik.
    </p>
  </div>

  <!-- AREA ANALYSIS FEEDBACK SECTION (POPULATED AFTER INDIVIDUAL ANALYSIS) -->
  <div class="results-wrapper hidden" id="areaResultsSection">
    <h2 class="results-title">
      <svg style="width: 24px; height: 24px; color: var(--primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
      </svg>
      Hasil Analisis Visual Area
    </h2>
    <div class="grid-3">
      <!-- Eye card -->
      <div class="area-result-card" id="cardResultMata">
        <div class="area-result-header">
          <span class="area-badge">👁️ Mata</span>
          <span class="result-status" id="badgeResultMata">-</span>
        </div>
        <div class="ring-container">
          <svg class="ring-svg">
            <circle class="ring-bg" cx="45" cy="45" r="40"></circle>
            <circle class="ring-fill" cx="45" cy="45" r="40" id="ringResultMata" stroke="var(--primary)"></circle>
          </svg>
          <div class="ring-value" id="valResultMata">0%</div>
        </div>
        <p style="font-size: 0.9rem; text-align: center;" id="descResultMata">Belum dianalisis</p>
      </div>

      <!-- Skin card -->
      <div class="area-result-card" id="cardResultKulit">
        <div class="area-result-header">
          <span class="area-badge">🤲 Kulit</span>
          <span class="result-status" id="badgeResultKulit">-</span>
        </div>
        <div class="ring-container">
          <svg class="ring-svg">
            <circle class="ring-bg" cx="45" cy="45" r="40"></circle>
            <circle class="ring-fill" cx="45" cy="45" r="40" id="ringResultKulit" stroke="var(--primary)"></circle>
          </svg>
          <div class="ring-value" id="valResultKulit">0%</div>
        </div>
        <p style="font-size: 0.9rem; text-align: center;" id="descResultKulit">Belum dianalisis</p>
      </div>

      <!-- Nail card -->
      <div class="area-result-card" id="cardResultKuku">
        <div class="area-result-header">
          <span class="area-badge">💅 Kuku</span>
          <span class="result-status" id="badgeResultKuku">-</span>
        </div>
        <div class="ring-container">
          <svg class="ring-svg">
            <circle class="ring-bg" cx="45" cy="45" r="40"></circle>
            <circle class="ring-fill" cx="45" cy="45" r="40" id="ringResultKuku" stroke="var(--primary)"></circle>
          </svg>
          <div class="ring-value" id="valResultKuku">0%</div>
        </div>
        <p style="font-size: 0.9rem; text-align: center;" id="descResultKuku">Belum dianalisis</p>
      </div>
    </div>
  </div>

  <!-- FINAL COMBINED REPORT (SHOWN AFTER DEDUCTIVE AI ORCHESTRATION) -->
  <div class="glass-card dashboard-card hidden" id="finalReportSection">
    <div class="db-header">
      <div>
        <h2 style="font-family: 'Space Grotesk', sans-serif; font-size: 2rem;">Laporan Defisiensi Gizi Gabungan</h2>
        <p style="color: var(--text-muted); margin-top: 4px;" id="reportChildMeta">Anak: John Doe (36 Bulan) | L</p>
      </div>
      <div>
        <span class="level-badge" id="reportRiskBadge">Level Risiko: Tinggi</span>
      </div>
    </div>

    <!-- Parental Summary -->
    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px; margin-bottom: 32px;">
      <h3 style="font-size: 1.1rem; margin-bottom: 8px;">Ringkasan Kondisi</h3>
      <p style="color: var(--text-muted); font-size: 1rem; line-height: 1.7;" id="reportSummaryText">
        -
      </p>
    </div>

    <!-- Core Deficiencies Detected -->
    <h3 class="report-section-title">
      <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
      </svg>
      Defisiensi Nutrisi Terindikasi Utama
    </h3>
    <div class="deficiency-list" id="reportDeficiencyList">
      <!-- Populated dynamically -->
    </div>

    <!-- Cross Correlation Insights -->
    <div class="correlation-box">
      <h3 style="font-family: 'Space Grotesk', sans-serif; font-size: 1.2rem; color: var(--text-main); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
        🔍 AI Cross-Correlation Insight
      </h3>
      <p id="reportCorrelationText" style="color: var(--text-main); font-size: 0.95rem; line-height: 1.7;">
        -
      </p>
    </div>

    <!-- Recommendations Grid -->
    <h3 class="report-section-title">
      <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
      </svg>
      Rencana Tindak Lanjut & Pemenuhan Gizi
    </h3>
    <div class="recommendations-grid">
      <!-- Foods column -->
      <div class="rec-column">
        <h4 style="font-family: 'Space Grotesk', sans-serif; font-size: 1.1rem; color: var(--success); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
          🥦 Rekomendasi Makanan Harian
        </h4>
        <ul style="list-style: none; display: flex; flex-direction: column; gap: 12px;" id="reportFoodsList">
          <!-- Populated dynamically -->
        </ul>
      </div>

      <!-- Supplements & Medical advice column -->
      <div class="rec-column">
        <h4 style="font-family: 'Space Grotesk', sans-serif; font-size: 1.1rem; color: var(--warning); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
          💊 Suplemen & Rujukan Klinis
        </h4>
        <ul style="list-style: none; display: flex; flex-direction: column; gap: 12px;" id="reportSupplementsList">
          <!-- Populated dynamically -->
        </ul>
      </div>
    </div>

    <!-- BEFORE / AFTER CHECKPOINT MONITORING -->
    <div style="margin-top: 40px; border-top: 1px solid var(--border-color); padding-top: 40px;" id="monitoringSection" class="hidden">
      <h3 class="report-section-title">
        📈 Monitoring Pemulihan Gizi (Sebelum vs Sesudah)
      </h3>
      <div class="monitoring-grid">
        <div class="photo-slot">
          <span class="slot-badge">Sebelum (Awal)</span>
          <img id="monitoringBeforeImg" src="" alt="Awal" />
        </div>
        <div class="photo-slot">
          <span class="slot-badge">Sesudah (Hari ke-7)</span>
          <img id="monitoringAfterImg" src="" alt="Sekarang" />
        </div>
      </div>
      <div class="correlation-box" style="background: rgba(16, 185, 129, 0.05); border-color: rgba(16, 185, 129, 0.2);">
        <h4 style="font-family: 'Space Grotesk', sans-serif; font-size: 1.1rem; margin-bottom: 8px;" id="comparisonStatusTitle">
          Status Perkembangan: Membaik
        </h4>
        <p id="comparisonSummaryText" style="color: var(--text-muted); font-size: 0.95rem;">
          -
        </p>
      </div>
    </div>

    <!-- ACTIONS -->
    <div style="display: flex; gap: 16px; justify-content: flex-end; margin-top: 40px;">
      <button class="btn" onclick="window.print()">Cetak Laporan PDF</button>
      <button class="btn btn-primary" onclick="restartScreening()">Mulai Skrining Baru</button>
    </div>
  </div>
</div>

<!-- ==================== WEB APP JAVASCRIPT ==================== -->
<script>
// ---------- APP STATE ----------
const state = {
  namaAnak: '',
  usiaAnak: null,
  jenisKelamin: '',
  jawabanKuesioner: {},
  currentScanArea: 'mata', // 'mata' | 'kulit' | 'kuku'
  photos: {
    mata: null, // base64 / file
    kulit: null,
    kuku: null
  },
  analyses: {
    mata: null,
    kulit: null,
    kuku: null
  },
  useCamera: true,
  stream: null
};

// Questionnaire Questions config
const questions = [
  {
    id: 'makan_hewani',
    pertanyaan: 'Seberapa sering anak mengonsumsi protein hewani (daging, ayam, ikan, telur) dalam seminggu?',
    pilihan: [
      { teks: 'Setiap hari (sangat baik)', nilai: 'setiap_hari' },
      { teks: '3-4 kali seminggu (cukup)', nilai: 'jarang_cukup' },
      { teks: '1-2 kali seminggu atau kurang (kurang protein)', nilai: 'kurang' }
    ]
  },
  {
    id: 'sayur_hijau',
    pertanyaan: 'Apakah anak rutin mengonsumsi sayuran hijau (bayam, brokoli, kangkung)?',
    pilihan: [
      { teks: 'Ya, hampir setiap makan', nilai: 'sering' },
      { teks: 'Kadang-kadang (2-3 kali seminggu)', nilai: 'kadang' },
      { teks: 'Jarang sekali atau menolak sayur', nilai: 'jarang' }
    ]
  },
  {
    id: 'buah_vitamin',
    pertanyaan: 'Bagaimana konsumsi buah-buahan segar (jeruk, pepaya, mangga, alpukat)?',
    pilihan: [
      { teks: 'Setiap hari', nilai: 'setiap_hari' },
      { teks: 'Beberapa kali seminggu', nilai: 'beberapa_kali' },
      { teks: 'Jarang sekali', nilai: 'jarang' }
    ]
  },
  {
    id: 'gejala_lesu',
    pertanyaan: 'Apakah anak sering terlihat lesu, lemas, atau tidak bersemangat beraktivitas?',
    pilihan: [
      { teks: 'Tidak pernah, selalu aktif ceria', nilai: 'tidak' },
      { teks: 'Kadang-kadang terlihat cepat lelah', nilai: 'kadang' },
      { teks: 'Ya, sering terlihat lesu dan mengantuk', nilai: 'ya' }
    ]
  }
];

let currentQIdx = 0;

// ---------- EVENT INITIALIZATION ----------
document.addEventListener('DOMContentLoaded', () => {
  initCamera();
});

// ---------- NAVIGATION & STEPS ----------
function goToStep(stepId) {
  // Validate Profile Step
  if (stepId === 'questionnaireStep') {
    state.namaAnak = document.getElementById('namaAnak').value.trim();
    state.usiaAnak = document.getElementById('usiaAnak').value;
    state.jenisKelamin = document.getElementById('jenisKelamin').value;

    if (!state.namaAnak || !state.usiaAnak || !state.jenisKelamin) {
      alert('Mohon lengkapi data profil anak terlebih dahulu.');
      return;
    }
    
    // Update profil badge
    const badge = document.getElementById('badgeProfile');
    badge.textContent = 'Lengkap';
    badge.className = 'result-status status-normal';

    // Build question
    currentQIdx = 0;
    renderQuestion();
  }

  // Toggle step sections
  document.getElementById('profileStep').classList.add('hidden');
  document.getElementById('questionnaireStep').classList.add('hidden');
  document.getElementById('scanStep').classList.add('hidden');

  document.getElementById(stepId).classList.remove('hidden');
}

// ---------- QUESTIONNAIRE LOGIC ----------
function renderQuestion() {
  const q = questions[currentQIdx];
  document.getElementById('questionTitle').textContent = `Pertanyaan ${currentQIdx + 1} dari ${questions.length}`;
  
  const container = document.getElementById('optionsContainer');
  container.innerHTML = `
    <p style="margin-bottom: 20px; font-weight: 600; font-size: 1.1rem;">${q.pertanyaan}</p>
  `;

  q.pilihan.forEach(opt => {
    const isSelected = state.jawabanKuesioner[q.id] === opt.nilai;
    const optDiv = document.createElement('div');
    optDiv.className = `q-option ${isSelected ? 'selected' : ''}`;
    optDiv.onclick = () => selectOption(q.id, opt.nilai);
    optDiv.innerHTML = `
      <div class="q-radio"></div>
      <span>${opt.teks}</span>
    `;
    container.appendChild(optDiv);
  });

  // Update progress bar
  const pct = ((currentQIdx + 1) / questions.length) * 100;
  document.getElementById('qProgressBar').style.width = `${pct}%`;

  // Update button text on last question
  const nextBtn = document.getElementById('nextQBtn');
  if (currentQIdx === questions.length - 1) {
    nextBtn.textContent = 'Selesai & Ke Kamera →';
  } else {
    nextBtn.textContent = 'Berikutnya →';
  }
}

function selectOption(qId, val) {
  state.jawabanKuesioner[qId] = val;
  renderQuestion();
}

function nextQuestion() {
  const q = questions[currentQIdx];
  if (!state.jawabanKuesioner[q.id]) {
    alert('Mohon pilih salah satu jawaban.');
    return;
  }

  if (currentQIdx < questions.length - 1) {
    currentQIdx++;
    renderQuestion();
  } else {
    // Save questionnaire status
    const badge = document.getElementById('badgeQ');
    badge.textContent = 'Selesai';
    badge.className = 'result-status status-normal';

    goToStep('scanStep');
    startCamera();
  }
}

function prevQuestion() {
  if (currentQIdx > 0) {
    currentQIdx--;
    renderQuestion();
  } else {
    goToStep('profileStep');
  }
}

// ---------- CAMERA SYSTEM ----------
async function initCamera() {
  // Start requesting permission / scanning devices
  try {
    const devices = await navigator.mediaDevices.enumerateDevices();
    const hasVideo = devices.some(device => device.kind === 'videoinput');
    if (!hasVideo) {
      toggleCameraInput(false); // Fallback to file input
    }
  } catch (err) {
    toggleCameraInput(false);
  }
}

async function startCamera() {
  if (!state.useCamera) return;

  const video = document.getElementById('videoFeed');
  
  if (state.stream) {
    state.stream.getTracks().forEach(track => track.stop());
  }

  try {
    const constraints = {
      video: {
        facingMode: 'user', // front camera for ease of self/child positioning
        width: { ideal: 640 },
        height: { ideal: 480 }
      },
      audio: false
    };

    state.stream = await navigator.mediaDevices.getUserMedia(constraints);
    video.srcObject = state.stream;
    document.getElementById('cameraWrapper').classList.add('active');
  } catch (err) {
    console.error('Camera access failed:', err);
    toggleCameraInput(false);
  }
}

function stopCamera() {
  if (state.stream) {
    state.stream.getTracks().forEach(track => track.stop());
    state.stream = null;
  }
}

function toggleCameraInput(forceCamera = null) {
  state.useCamera = forceCamera !== null ? forceCamera : !state.useCamera;
  
  const video = document.getElementById('videoFeed');
  const uploadPlaceholder = document.getElementById('uploadPlaceholder');
  const captureBtn = document.getElementById('captureBtn');
  const toggleBtn = document.getElementById('cameraToggleBtn');

  if (state.useCamera) {
    video.classList.remove('hidden');
    uploadPlaceholder.classList.add('hidden');
    captureBtn.classList.remove('hidden');
    toggleBtn.textContent = 'Gunakan File Upload';
    startCamera();
  } else {
    stopCamera();
    video.classList.add('hidden');
    uploadPlaceholder.classList.remove('hidden');
    captureBtn.classList.add('hidden');
    toggleBtn.textContent = 'Gunakan Kamera';
    retakePhoto();
  }
}

function switchScanArea(area) {
  // Save current active tab
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.classList.remove('active');
    if (btn.getAttribute('data-area') === area) {
      btn.classList.add('active');
    }
  });

  state.currentScanArea = area;

  // Update guideline visual look & instruction text
  const box = document.getElementById('guidelineBox');
  const text = document.getElementById('guidelineText');
  
  box.className = `guideline-box ${area}`;
  
  if (area === 'mata') {
    text.innerHTML = '<span>Pastikan area mata pas di dalam lingkaran</span>';
  } else if (area === 'kulit') {
    text.innerHTML = '<span>Fokuskan pada area kulit wajah/lengan</span>';
  } else if (area === 'kuku') {
    text.innerHTML = '<span>Posisikan jari tangan sejajar di dalam bingkai</span>';
  }

  // Handle previewing already captured photo for this area
  if (state.photos[area]) {
    showPreview(state.photos[area]);
  } else {
    retakePhoto();
  }
}

// Capture photo from video stream
function captureSnapshot() {
  const video = document.getElementById('videoFeed');
  const canvas = document.getElementById('snapshotCanvas');
  const context = canvas.getContext('2d');
  
  if (!state.stream) return;

  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;
  
  // Mirror logic correction for the snapshot
  context.translate(canvas.width, 0);
  context.scale(-1, 1);
  context.drawImage(video, 0, 0, canvas.width, canvas.height);
  context.setTransform(1, 0, 0, 1, 0, 0); // reset scale

  // Assess photo quality & apply auto white-balance color calibration
  const quality = assessQualityAndCalibrate(canvas);
  
  const toast = document.getElementById('qualityToast');
  const toastText = document.getElementById('qualityToastText');

  if (quality.status === 'fail') {
    toastText.textContent = quality.message;
    toast.classList.remove('hidden');
    
    // Auto-hide warning after 4.5 seconds
    setTimeout(() => {
      toast.classList.add('hidden');
    }, 4500);

    // If extremely poor quality, alert user and do not accept
    alert(quality.message + "\n\nFoto dibatalkan. Silakan ambil ulang dengan pencahayaan dan fokus yang lebih baik.");
    retakePhoto();
    return;
  } else {
    toast.classList.add('hidden');
  }

  const base64Data = canvas.toDataURL('image/jpeg');
  state.photos[state.currentScanArea] = base64Data;

  showPreview(base64Data);
}

// Client-side image quality scanner & Gray-world white balance calibrator
function assessQualityAndCalibrate(canvas) {
  const context = canvas.getContext('2d');
  const width = canvas.width;
  const height = canvas.height;
  
  // Downsample/sample data for performance
  const imgData = context.getImageData(0, 0, width, height);
  const data = imgData.data;

  let totalLuminance = 0;
  let rSum = 0, gSum = 0, bSum = 0;
  const totalPixels = data.length / 4;

  // 1. Calculate Average Luminance (brightness) and RGB sums
  for (let i = 0; i < data.length; i += 4) {
    const r = data[i];
    const g = data[i+1];
    const b = data[i+2];

    rSum += r;
    gSum += g;
    bSum += b;

    // Luminance ITU-R BT.601 formula
    totalLuminance += (0.299 * r) + (0.587 * g) + (0.114 * b);
  }

  const avgLuminance = totalLuminance / totalPixels;
  const avgR = rSum / totalPixels;
  const avgG = gSum / totalPixels;
  const avgB = bSum / totalPixels;

  // 2. High-frequency Edge/Detail detection for Blur Check (simulated Laplacian)
  let edgeSum = 0;
  const step = 8; // Sample every 8th pixel
  let sampleCount = 0;

  for (let y = step; y < height - step; y += step) {
    for (let x = step; x < width - step; x += step) {
      const idx = (y * width + x) * 4;
      const val = (0.299 * data[idx]) + (0.587 * data[idx+1]) + (0.114 * data[idx+2]);

      // Right pixel neighbor
      const idxR = (y * width + (x + 1)) * 4;
      const valR = (0.299 * data[idxR]) + (0.587 * data[idxR+1]) + (0.114 * data[idxR+2]);

      // Bottom pixel neighbor
      const idxB = ((y + 1) * width + x) * 4;
      const valB = (0.299 * data[idxB]) + (0.587 * data[idxB+1]) + (0.114 * data[idxB+2]);

      const dx = valR - val;
      const dy = valB - val;
      edgeSum += Math.sqrt(dx * dx + dy * dy);
      sampleCount++;
    }
  }

  const avgEdge = edgeSum / sampleCount;

  let status = 'good';
  let message = '';

  // Quality Validation Checks
  if (avgLuminance < 60) {
    status = 'fail';
    message = '⚠️ Kualitas Rendah: Foto Terlalu Gelap!';
  } else if (avgLuminance > 240) {
    status = 'fail';
    message = '⚠️ Kualitas Rendah: Foto Terlalu Silau!';
  } else if (avgEdge < 5.2) {
    status = 'fail';
    message = '⚠️ Kualitas Rendah: Foto Terlalu Blur / Tidak Fokus!';
  }

  // 3. Gray World Color Calibration (Normalize white balance color cast)
  if (status === 'good') {
    const targetGray = (avgR + avgG + avgB) / 3;
    const scaleR = targetGray / (avgR || 1);
    const scaleG = targetGray / (avgG || 1);
    const scaleB = targetGray / (avgB || 1);

    // Recalibrate color channels
    for (let i = 0; i < data.length; i += 4) {
      data[i]     = Math.min(255, Math.max(0, data[i] * scaleR));
      data[i+1]   = Math.min(255, Math.max(0, data[i+1] * scaleG));
      data[i+2]   = Math.min(255, Math.max(0, data[i+2] * scaleB));
    }
    context.putImageData(imgData, 0, 0);
  }

  return { status, message };
}


function showPreview(src) {
  const preview = document.getElementById('previewContainer');
  const img = document.getElementById('capturedImagePreview');
  img.src = src;
  preview.style.display = 'flex';

  document.getElementById('captureBtn').classList.add('hidden');
  document.getElementById('retakeBtn').classList.remove('hidden');
  document.getElementById('analyzeBtn').classList.remove('hidden');
}

function retakePhoto() {
  const preview = document.getElementById('previewContainer');
  preview.style.display = 'none';
  
  state.photos[state.currentScanArea] = null;

  if (state.useCamera) {
    document.getElementById('captureBtn').classList.remove('hidden');
  }
  document.getElementById('retakeBtn').classList.add('hidden');
  document.getElementById('analyzeBtn').classList.add('hidden');
}

// File input handlers
function triggerFileInput() {
  document.getElementById('fileFallbackInput').click();
}

function handleFileInput(e) {
  const file = e.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = function(event) {
    const base64Data = event.target.result;
    state.photos[state.currentScanArea] = base64Data;
    showPreview(base64Data);
  };
  reader.readAsDataURL(file);
}

// ---------- REAL-TIME ANALYSIS PER AREA ----------
async function analyzeCurrentArea() {
  const area = state.currentScanArea;
  const base64Data = state.photos[area];

  if (!base64Data) return;

  // Show scanning visual loader
  const scannerOverlay = document.getElementById('scannerOverlay');
  scannerOverlay.style.display = 'block';
  document.getElementById('analyzeBtn').disabled = true;
  document.getElementById('retakeBtn').disabled = true;

  try {
    // Base64 to blob conversion for standard form upload
    const blob = await fetch(base64Data).then(r => r.blob());
    const formData = new FormData();
    formData.append('foto', blob, `${area}.jpg`);
    formData.append('bagian', area);

    const response = await fetch('/api/vitamin-scan/analyze-single', {
      method: 'POST',
      body: formData
    });

    const data = await response.json();
    
    if (data.success && data.result) {
      state.analyses[area] = data.result;
      renderAreaResult(area, data.result);
      
      // Update badge status
      const badge = document.getElementById(`badge${area.charAt(0).toUpperCase() + area.slice(1)}`);
      badge.textContent = 'Analisis Selesai';
      badge.className = 'result-status status-normal';

      // Check if all steps completed to enable final submit
      checkCompletion();
    } else {
      alert(data.message || 'Gagal menganalisis area.');
    }
  } catch (err) {
    console.error('Single photo scan error:', err);
    alert('Terjadi kesalahan saat menghubungi server AI.');
  } finally {
    scannerOverlay.style.display = 'none';
    document.getElementById('analyzeBtn').disabled = false;
    document.getElementById('retakeBtn').disabled = false;
  }
}

function renderAreaResult(area, result) {
  // Show results area if hidden
  document.getElementById('areaResultsSection').classList.remove('hidden');

  // Badge status
  const badge = document.getElementById(`badgeResult${area.charAt(0).toUpperCase() + area.slice(1)}`);
  badge.textContent = result.status === 'normal' ? 'Normal' : 'Terindikasi';
  badge.className = `result-status ${result.status === 'normal' ? 'status-normal' : 'status-danger'}`;

  // Confidence ring calculations (circumference = 2 * PI * r = 251.2 for r=40)
  const confidence = Math.round((result.confidence_score || 0.8) * 100);
  const ring = document.getElementById(`ringResult${area.charAt(0).toUpperCase() + area.slice(1)}`);
  const valText = document.getElementById(`valResult${area.charAt(0).toUpperCase() + area.slice(1)}`);
  
  valText.textContent = `${confidence}%`;
  
  // Color the ring based on level
  let strokeColor = 'var(--success)';
  if (result.status === 'terindikasi') {
    strokeColor = confidence > 70 ? 'var(--danger)' : 'var(--warning)';
  }
  ring.style.stroke = strokeColor;
  
  const offset = 251.2 - (251.2 * confidence) / 100;
  ring.style.strokeDashoffset = offset;

  // Text description
  const desc = document.getElementById(`descResult${area.charAt(0).toUpperCase() + area.slice(1)}`);
  desc.textContent = result.penjelasan_awam || 'Kondisi area tampak normal.';
}

function checkCompletion() {
  const profileOk = state.namaAnak && state.usiaAnak && state.jenisKelamin;
  const qOk = Object.keys(state.jawabanKuesioner).length >= questions.length;
  const scansOk = state.analyses.mata || state.analyses.kulit || state.analyses.kuku;

  const btn = document.getElementById('finalSubmitBtn');
  if (profileOk && qOk && scansOk) {
    btn.disabled = false;
  } else {
    btn.disabled = true;
  }
}

// ---------- FINAL SUBMISSION & CROSS-CORRELATION DEDUCTION ----------
async function submitAllForFinalReport() {
  // Hide scan area and questionnaire, show loader
  document.getElementById('setupFormSection').classList.add('hidden');
  document.getElementById('areaResultsSection').classList.add('hidden');
  document.getElementById('finalReportSection').classList.add('hidden');
  
  const loader = document.getElementById('loaderContainer');
  loader.style.display = 'flex';

  stopCamera();

  const formData = new FormData();
  formData.append('nama_anak', state.namaAnak);
  formData.append('usia_anak', state.usiaAnak);
  formData.append('jenis_kelamin', state.jenisKelamin);
  formData.append('jawaban_kuesioner', JSON.stringify(state.jawabanKuesioner));

  // Convert snapshots to Blobs
  const appendPhotoIfPresent = async (area, name) => {
    if (state.photos[area]) {
      const blob = await fetch(state.photos[area]).then(r => r.blob());
      formData.append(name, blob, `${area}.jpg`);
    }
  };

  await appendPhotoIfPresent('mata', 'foto_mata');
  await appendPhotoIfPresent('kulit', 'foto_kulit');
  await appendPhotoIfPresent('kuku', 'foto_kuku');

  try {
    const response = await fetch('/api/vitamin-scan/analyze-full', {
      method: 'POST',
      body: formData
    });

    const data = await response.json();
    loader.style.display = 'none';

    if (data.id && data.laporan_gabungan) {
      renderFinalReport(data);
    } else {
      alert('Gagal menghasilkan laporan AI. Silakan coba kembali.');
      document.getElementById('setupFormSection').classList.remove('hidden');
    }
  } catch (err) {
    console.error('Final analysis report submission error:', err);
    loader.style.display = 'none';
    alert('Koneksi terputus saat merangkum data.');
    document.getElementById('setupFormSection').classList.remove('hidden');
  }
}

function renderFinalReport(data) {
  const report = data.laporan_gabungan;
  
  const section = document.getElementById('finalReportSection');
  section.classList.remove('hidden');

  // Child Metadata
  document.getElementById('reportChildMeta').textContent = 
    `Anak: ${state.namaAnak} (${state.usiaAnak} Bulan) | ${state.jenisKelamin === 'L' ? 'Laki-laki' : 'Perempuan'}`;

  // Risk Badge
  const badge = document.getElementById('reportRiskBadge');
  badge.textContent = `Level Risiko: ${report.level_risiko || 'sedang'}`;
  badge.className = `level-badge level-${report.level_risiko || 'sedang'}`;

  // Ringkasan Orang Tua
  document.getElementById('reportSummaryText').textContent = report.ringkasan_orang_tua || '-';

  // Deficiencies
  const list = document.getElementById('reportDeficiencyList');
  list.innerHTML = '';
  
  if (report.defisiensi_utama && report.defisiensi_utama.length > 0) {
    report.defisiensi_utama.forEach(d => {
      const item = document.createElement('div');
      item.className = 'deficiency-item';
      
      const evidence = d.sumber_bukti ? d.sumber_bukti.map(s => `<span class="evidence-pill">${s}</span>`).join(' ') : '';
      
      item.innerHTML = `
        <div class="deficiency-meta">
          <span class="deficiency-name">${d.vitamin_mineral}</span>
          <span class="result-status status-danger" style="font-size: 0.75rem;">Keyakinan: ${d.keyakinan}</span>
        </div>
        <p style="font-size: 0.88rem; color: var(--text-muted); margin: 6px 0;">${d.penjelasan}</p>
        <div style="display: flex; gap: 8px; margin-top: 8px; align-items: center;">
          <span style="font-size: 0.78rem; color: var(--text-dark);">Bukti Visual:</span>
          ${evidence}
        </div>
      `;
      list.appendChild(item);
    });
  } else {
    list.innerHTML = '<p style="color: var(--text-muted);">Tidak terdeteksi defisiensi nutrisi utama yang signifikan.</p>';
  }

  // Cross Correlation
  const corrText = document.getElementById('reportCorrelationText');
  if (report.korelasi_antar_area && report.korelasi_antar_area.length > 0) {
    const listHtml = report.korelasi_antar_area.map(c => 
      `• <strong>${c.temuan}</strong> (${c.area_terlibat.join(' & ')}) → ${c.kesimpulan}`
    ).join('<br/><br/>');
    corrText.innerHTML = listHtml;
  } else {
    corrText.textContent = 'Tidak ada korelasi defisiensi silang antartubuh yang mencurigakan.';
  }

  // Foods
  const foodsList = document.getElementById('reportFoodsList');
  foodsList.innerHTML = '';
  if (report.rekomendasi_makanan && report.rekomendasi_makanan.length > 0) {
    report.rekomendasi_makanan.forEach(f => {
      const li = document.createElement('li');
      li.innerHTML = `
        <div style="display: flex; justify-content: space-between;">
          <strong>${f.makanan}</strong>
          <span class="evidence-pill" style="color: var(--success); border-color: rgba(16,185,129,0.2);">${f.prioritas}</span>
        </div>
        <p style="font-size: 0.82rem; color: var(--text-muted);">Membantu mencukupi: ${f.nutrisi_target} (${f.frekuensi})</p>
      `;
      foodsList.appendChild(li);
    });
  } else {
    foodsList.innerHTML = '<li>Rekomendasi makanan bernutrisi umum.</li>';
  }

  // Supplements
  const suppsList = document.getElementById('reportSupplementsList');
  suppsList.innerHTML = '';
  if (report.rekomendasi_suplemen && report.rekomendasi_suplemen.length > 0) {
    report.rekomendasi_suplemen.forEach(s => {
      const li = document.createElement('li');
      li.innerHTML = `
        <strong>${s.suplemen}</strong>
        <p style="font-size: 0.82rem; color: var(--text-muted);">${s.alasan}</p>
      `;
      suppsList.appendChild(li);
    });
  }
  
  if (report.perlu_rujuk_nakes) {
    const li = document.createElement('li');
    li.style.marginTop = '16px';
    li.innerHTML = `
      <div class="result-status status-danger" style="display: inline-block; margin-bottom: 8px;">Perlu Rujukan Klinis</div>
      <p style="font-size: 0.88rem; color: var(--text-main); font-weight: 500;">
        ${report.alasan_rujuk}
      </p>
    `;
    suppsList.appendChild(li);
  }

  section.scrollIntoView({ behavior: 'smooth' });
}

function restartScreening() {
  location.reload();
}
</script>
</body>
</html>
