<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Deteksi Kekurangan Vitamin & Gizi Anak — AI Powered</title>
<meta name="description" content="Deteksi kekurangan vitamin anak melalui foto mata, kulit, dan kuku menggunakan AI Gemini. Dilengkapi kuesioner gizi interaktif." />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet" />

<style>
/* ========== RESET & BASE ========== */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
  font-family: 'Plus Jakarta Sans', sans-serif;
  background: #0A0F1E;
  color: #E2E8F0;
  min-height: 100vh;
  overflow-x: hidden;
  -webkit-font-smoothing: antialiased;
}

/* ========== CSS VARIABLES ========== */
:root {
  --bg-deep: #0A0F1E;
  --bg-card: rgba(255,255,255,0.04);
  --bg-card-hover: rgba(255,255,255,0.07);
  --border: rgba(255,255,255,0.08);
  --border-glow: rgba(99,179,237,0.35);
  --text-primary: #F0F4FF;
  --text-secondary: #94A3B8;
  --text-muted: #64748B;

  --green: #10B981;
  --green-glow: rgba(16,185,129,0.25);
  --blue: #63B3ED;
  --blue-glow: rgba(99,179,237,0.2);
  --violet: #A78BFA;
  --violet-glow: rgba(167,139,250,0.2);
  --amber: #F59E0B;
  --amber-glow: rgba(245,158,11,0.2);
  --red: #F87171;
  --red-glow: rgba(248,113,113,0.2);

  --radius-sm: 12px;
  --radius-md: 18px;
  --radius-lg: 24px;
  --radius-xl: 32px;

  --shadow: 0 25px 50px -12px rgba(0,0,0,0.6);
  --shadow-glow-green: 0 0 40px rgba(16,185,129,0.15);
  --shadow-glow-blue: 0 0 40px rgba(99,179,237,0.15);
}

/* ========== BACKGROUND EFFECTS ========== */
.bg-orbs {
  position: fixed; inset: 0; pointer-events: none; z-index: 0; overflow: hidden;
}
.orb {
  position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.35;
  animation: floatOrb 8s ease-in-out infinite;
}
.orb-1 { width: 600px; height: 600px; background: radial-gradient(circle, #1a4a6e, transparent); top: -200px; left: -100px; animation-delay: 0s; }
.orb-2 { width: 500px; height: 500px; background: radial-gradient(circle, #1e3a5f, transparent); top: 30%; right: -150px; animation-delay: 3s; }
.orb-3 { width: 400px; height: 400px; background: radial-gradient(circle, #2d1b69, transparent); bottom: -100px; left: 30%; animation-delay: 6s; }
@keyframes floatOrb {
  0%, 100% { transform: translateY(0) scale(1); }
  50% { transform: translateY(-30px) scale(1.05); }
}

/* ========== LAYOUT ========== */
.container {
  position: relative; z-index: 1;
  max-width: 900px; margin: 0 auto; padding: 0 20px 80px;
}

/* ========== HEADER ========== */
.header {
  text-align: center;
  padding: 60px 0 50px;
}
.header-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(99,179,237,0.1); border: 1px solid rgba(99,179,237,0.3);
  border-radius: 100px; padding: 6px 16px; margin-bottom: 24px;
  font-size: 0.78rem; font-weight: 600; color: var(--blue);
  letter-spacing: 0.08em; text-transform: uppercase;
}
.header-badge .pulse {
  width: 7px; height: 7px; background: var(--green); border-radius: 50%;
  animation: pulse 2s ease-in-out infinite;
  box-shadow: 0 0 0 0 var(--green-glow);
}
@keyframes pulse {
  0% { box-shadow: 0 0 0 0 rgba(16,185,129,0.5); }
  70% { box-shadow: 0 0 0 8px rgba(16,185,129,0); }
  100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); }
}
.header h1 {
  font-size: clamp(2rem, 5vw, 3.2rem);
  font-weight: 800; line-height: 1.1;
  color: var(--text-primary);
  margin-bottom: 16px;
}
.header h1 .gradient-text {
  background: linear-gradient(135deg, #63B3ED 0%, #A78BFA 50%, #10B981 100%);
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  background-clip: text;
}
.header p {
  font-size: 1.05rem; color: var(--text-secondary);
  max-width: 560px; margin: 0 auto; line-height: 1.7;
}

/* ========== STEP INDICATOR ========== */
.steps-nav {
  display: flex; align-items: center; justify-content: center;
  gap: 0; margin-bottom: 48px;
}
.step-item {
  display: flex; flex-direction: column; align-items: center; gap: 8px;
  position: relative;
}
.step-item:not(:last-child)::after {
  content: '';
  position: absolute; top: 20px; left: calc(100% + 1px);
  width: 60px; height: 2px;
  background: var(--border);
  z-index: 0;
}
.step-item.done:not(:last-child)::after { background: var(--green); }
.step-circle {
  width: 40px; height: 40px; border-radius: 50%;
  border: 2px solid var(--border);
  background: var(--bg-card);
  display: flex; align-items: center; justify-content: center;
  font-size: 0.85rem; font-weight: 700; color: var(--text-muted);
  transition: all 0.4s ease; position: relative; z-index: 1;
}
.step-item.active .step-circle {
  border-color: var(--blue); color: var(--blue);
  background: rgba(99,179,237,0.1);
  box-shadow: 0 0 20px rgba(99,179,237,0.3);
}
.step-item.done .step-circle {
  border-color: var(--green); background: rgba(16,185,129,0.15); color: var(--green);
}
.step-label {
  font-size: 0.72rem; font-weight: 600; color: var(--text-muted);
  text-align: center; white-space: nowrap; text-transform: uppercase; letter-spacing: 0.05em;
}
.step-item.active .step-label { color: var(--blue); }
.step-item.done .step-label { color: var(--green); }
.steps-gap { width: 60px; height: 2px; background: var(--border); align-self: center; margin-bottom: 28px; }

/* ========== SECTION ========== */
.section {
  display: none;
  animation: fadeSlideIn 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}
.section.active { display: block; }
@keyframes fadeSlideIn {
  from { opacity: 0; transform: translateY(24px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ========== CARD ========== */
.card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 32px;
  backdrop-filter: blur(20px);
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}
.card:hover { border-color: var(--border-glow); }
.card-header {
  display: flex; align-items: center; gap: 14px; margin-bottom: 28px;
}
.card-icon {
  width: 48px; height: 48px; border-radius: var(--radius-sm);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.5rem; flex-shrink: 0;
}
.card-icon.green { background: var(--green-glow); }
.card-icon.blue { background: var(--blue-glow); }
.card-icon.violet { background: var(--violet-glow); }
.card-icon.amber { background: var(--amber-glow); }
.card-title { font-size: 1.2rem; font-weight: 700; color: var(--text-primary); }
.card-subtitle { font-size: 0.85rem; color: var(--text-secondary); margin-top: 2px; }

/* ========== FORM ========== */
.form-row {
  display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 24px;
}
.form-group { display: flex; flex-direction: column; gap: 8px; }
.form-group.full { grid-column: 1 / -1; }
.form-group label {
  font-size: 0.82rem; font-weight: 600;
  color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;
}
.form-control {
  background: rgba(255,255,255,0.05);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  color: var(--text-primary);
  padding: 12px 16px; font-size: 0.95rem; font-family: inherit;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
  width: 100%;
}
.form-control:focus {
  outline: none; border-color: var(--blue);
  box-shadow: 0 0 0 3px rgba(99,179,237,0.15);
}
.form-control option { background: #1a2035; }

/* ========== PHOTO UPLOAD AREA ========== */
.photo-grid {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;
  margin-bottom: 28px;
}
.photo-upload-card {
  position: relative; border-radius: var(--radius-md);
  border: 2px dashed var(--border);
  background: rgba(255,255,255,0.02);
  overflow: hidden; cursor: pointer;
  transition: all 0.3s ease; aspect-ratio: 1;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  gap: 10px;
}
.photo-upload-card:hover, .photo-upload-card.drag-over {
  border-color: var(--blue); background: rgba(99,179,237,0.05);
  transform: translateY(-3px);
  box-shadow: var(--shadow-glow-blue);
}
.photo-upload-card.uploaded {
  border-style: solid; border-color: var(--green);
  box-shadow: var(--shadow-glow-green);
}
.photo-upload-card.analyzing { border-color: var(--violet); }
.photo-upload-card .upload-icon {
  font-size: 2.5rem; opacity: 0.6; transition: transform 0.3s ease;
}
.photo-upload-card:hover .upload-icon { transform: scale(1.15); opacity: 1; }
.photo-upload-card .upload-label {
  font-size: 0.9rem; font-weight: 600; color: var(--text-secondary);
  text-align: center; line-height: 1.4;
}
.photo-upload-card .upload-hint {
  font-size: 0.75rem; color: var(--text-muted); text-align: center;
}
.photo-upload-card input[type="file"] {
  position: absolute; inset: 0; opacity: 0; cursor: pointer; z-index: 2;
}
.photo-preview {
  position: absolute; inset: 0; z-index: 1;
}
.photo-preview img {
  width: 100%; height: 100%; object-fit: cover;
}
.photo-preview-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 50%);
  display: flex; align-items: flex-end; padding: 10px;
}
.photo-status-badge {
  display: inline-flex; align-items: center; gap: 5px;
  border-radius: 100px; padding: 4px 10px; font-size: 0.72rem; font-weight: 700;
}
.photo-status-badge.ok { background: rgba(16,185,129,0.85); color: #fff; }
.photo-status-badge.analyzing { background: rgba(167,139,250,0.85); color: #fff; }
.photo-part-label {
  position: absolute; top: 10px; left: 10px; z-index: 3;
  background: rgba(0,0,0,0.6); backdrop-filter: blur(8px);
  border-radius: 8px; padding: 5px 10px;
  font-size: 0.75rem; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 5px;
}

/* ========== ANALYZE BUTTON IN PHOTO CARD ========== */
.analyze-btn-small {
  position: absolute; bottom: 10px; right: 10px; z-index: 4;
  background: var(--blue); color: #fff;
  border: none; border-radius: 8px; padding: 6px 12px;
  font-size: 0.75rem; font-weight: 700; cursor: pointer;
  transition: all 0.2s ease; display: none;
}
.photo-upload-card.uploaded .analyze-btn-small { display: block; }
.analyze-btn-small:hover { background: #4299e1; transform: scale(1.05); }
.analyze-btn-small:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

/* ========== ANALYSIS RESULT CARD ========== */
.analysis-result {
  margin-top: 16px;
  background: rgba(99,179,237,0.06);
  border: 1px solid rgba(99,179,237,0.2);
  border-radius: var(--radius-md);
  padding: 20px 24px;
  display: none;
}
.analysis-result.show { display: block; animation: fadeSlideIn 0.4s ease forwards; }
.analysis-result-header {
  display: flex; align-items: center; gap: 10px; margin-bottom: 14px;
}
.analysis-result-title {
  font-size: 0.85rem; font-weight: 700; color: var(--blue); text-transform: uppercase; letter-spacing: 0.05em;
}
.analysis-text {
  font-size: 0.9rem; line-height: 1.8; color: var(--text-secondary);
  white-space: pre-wrap;
}
.analysis-text strong, .analysis-text b { color: var(--text-primary); font-weight: 700; }

/* ========== QUESTIONNAIRE ========== */
.q-group { margin-bottom: 32px; }
.q-group-title {
  font-size: 0.8rem; font-weight: 700; color: var(--violet);
  text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 16px;
  display: flex; align-items: center; gap: 8px;
}
.q-group-title::after {
  content: ''; flex: 1; height: 1px; background: var(--border);
}
.q-item {
  background: rgba(255,255,255,0.03);
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  padding: 18px 20px;
  margin-bottom: 12px;
  transition: border-color 0.2s ease;
}
.q-item:hover { border-color: var(--violet-glow); }
.q-text {
  font-size: 0.92rem; font-weight: 500; color: var(--text-primary);
  margin-bottom: 14px; line-height: 1.5;
}
.q-options {
  display: flex; gap: 10px; flex-wrap: wrap;
}
.q-opt {
  position: relative; cursor: pointer;
}
.q-opt input { position: absolute; opacity: 0; width: 0; height: 0; }
.q-opt-label {
  display: inline-block; padding: 8px 18px;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--border);
  border-radius: 100px; font-size: 0.85rem; font-weight: 500;
  color: var(--text-secondary); cursor: pointer;
  transition: all 0.2s ease;
}
.q-opt input:checked + .q-opt-label {
  background: rgba(167,139,250,0.15);
  border-color: var(--violet); color: var(--violet);
  font-weight: 700;
}
.q-opt-label:hover { border-color: var(--violet); color: var(--text-primary); }
.q-select {
  background: rgba(255,255,255,0.05);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  color: var(--text-primary); padding: 10px 14px;
  font-size: 0.9rem; font-family: inherit; width: 100%; max-width: 280px;
  transition: border-color 0.2s ease;
}
.q-select:focus { outline: none; border-color: var(--violet); }
.q-select option { background: #1a2035; }

/* ========== BUTTONS ========== */
.btn-row {
  display: flex; gap: 14px; justify-content: flex-end; margin-top: 32px;
}
.btn {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 14px 28px; border-radius: 100px;
  font-weight: 700; font-size: 0.95rem; font-family: inherit;
  border: none; cursor: pointer;
  transition: all 0.25s ease;
}
.btn-primary {
  background: linear-gradient(135deg, #3b82f6, #6366f1);
  color: #fff; box-shadow: 0 8px 24px rgba(99,102,241,0.4);
}
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(99,102,241,0.5); }
.btn-primary:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
.btn-green {
  background: linear-gradient(135deg, #059669, #10B981);
  color: #fff; box-shadow: 0 8px 24px rgba(16,185,129,0.4);
}
.btn-green:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(16,185,129,0.5); }
.btn-green:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
.btn-ghost {
  background: rgba(255,255,255,0.06); color: var(--text-secondary);
  border: 1px solid var(--border);
}
.btn-ghost:hover { background: rgba(255,255,255,0.1); color: var(--text-primary); }

/* ========== SPINNER ========== */
.spinner {
  width: 18px; height: 18px;
  border: 2px solid rgba(255,255,255,0.3);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ========== LOADING OVERLAY ========== */
.loading-overlay {
  display: none; position: fixed; inset: 0; z-index: 999;
  background: rgba(10,15,30,0.85); backdrop-filter: blur(12px);
  flex-direction: column; align-items: center; justify-content: center; gap: 20px;
  text-align: center;
}
.loading-overlay.show { display: flex; }
.loading-ring {
  width: 64px; height: 64px;
  border: 3px solid rgba(99,179,237,0.2);
  border-top-color: var(--blue);
  border-radius: 50%;
  animation: spin 1s linear infinite;
}
.loading-text { font-size: 1.1rem; font-weight: 600; color: var(--text-primary); }
.loading-sub { font-size: 0.85rem; color: var(--text-secondary); max-width: 320px; line-height: 1.6; }

/* ========== RESULT SECTION ========== */
.result-hero {
  text-align: center; padding: 40px 0 32px; margin-bottom: 32px;
}
.risk-badge {
  display: inline-flex; align-items: center; gap: 10px;
  border-radius: var(--radius-xl); padding: 14px 28px;
  font-size: 1.1rem; font-weight: 800; margin-bottom: 24px;
  border: 2px solid;
}
.risk-badge.rendah { background: var(--green-glow); border-color: var(--green); color: var(--green); }
.risk-badge.sedang { background: var(--amber-glow); border-color: var(--amber); color: var(--amber); }
.risk-badge.tinggi { background: var(--red-glow); border-color: var(--red); color: var(--red); }
.result-grid {
  display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px;
}
.result-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  padding: 24px;
  transition: all 0.3s ease;
}
.result-card:hover { border-color: var(--border-glow); }
.result-card.full { grid-column: 1 / -1; }
.result-card-title {
  font-size: 0.8rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.08em; margin-bottom: 14px;
  display: flex; align-items: center; gap: 8px;
}
.result-card-title.mata-color { color: #60A5FA; }
.result-card-title.kulit-color { color: #F9A8D4; }
.result-card-title.kuku-color { color: #86EFAC; }
.result-card-title.gizi-color { color: var(--violet); }
.result-text {
  font-size: 0.875rem; line-height: 1.85; color: var(--text-secondary);
  white-space: pre-wrap;
}
.deficiency-tags {
  display: flex; flex-wrap: wrap; gap: 8px; margin-top: 16px;
}
.tag {
  padding: 6px 14px; border-radius: 100px; font-size: 0.8rem; font-weight: 700;
}
.tag-vitamin-a { background: rgba(251,191,36,0.15); color: #FCD34D; border: 1px solid rgba(251,191,36,0.3); }
.tag-vitamin-b { background: rgba(167,139,250,0.15); color: #C4B5FD; border: 1px solid rgba(167,139,250,0.3); }
.tag-vitamin-c { background: rgba(52,211,153,0.15); color: #6EE7B7; border: 1px solid rgba(52,211,153,0.3); }
.tag-vitamin-d { background: rgba(251,146,60,0.15); color: #FCA570; border: 1px solid rgba(251,146,60,0.3); }
.tag-fe { background: rgba(248,113,113,0.15); color: #FCA5A5; border: 1px solid rgba(248,113,113,0.3); }
.tag-zn { background: rgba(96,165,250,0.15); color: #93C5FD; border: 1px solid rgba(96,165,250,0.3); }
.tag-protein { background: rgba(217,119,6,0.15); color: #FCD34D; border: 1px solid rgba(217,119,6,0.3); }
.new-detection-btn {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(255,255,255,0.06); border: 1px solid var(--border);
  border-radius: 100px; padding: 12px 24px;
  color: var(--text-secondary); font-weight: 600; font-size: 0.9rem;
  cursor: pointer; transition: all 0.2s ease; font-family: inherit;
  margin-top: 20px;
}
.new-detection-btn:hover { background: rgba(255,255,255,0.1); color: var(--text-primary); }

/* ========== DISCLAIMER ========== */
.disclaimer {
  background: rgba(245,158,11,0.07); border: 1px solid rgba(245,158,11,0.2);
  border-radius: var(--radius-md); padding: 16px 20px;
  display: flex; align-items: flex-start; gap: 12px;
  margin-top: 24px;
}
.disclaimer-icon { font-size: 1.3rem; flex-shrink: 0; margin-top: 1px; }
.disclaimer-text { font-size: 0.82rem; color: var(--amber); line-height: 1.6; }
.disclaimer-text strong { color: #FCD34D; }

/* ========== PROGRESS BAR ========== */
.progress-bar {
  width: 100%; height: 4px;
  background: var(--border); border-radius: 100px;
  margin-bottom: 40px; overflow: hidden;
}
.progress-fill {
  height: 100%; border-radius: 100px;
  background: linear-gradient(90deg, #3b82f6, #8b5cf6, #10b981);
  transition: width 0.5s cubic-bezier(0.22, 1, 0.36, 1);
}

/* ========== SAVED RECORD CHIP ========== */
.saved-chip {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.3);
  border-radius: 100px; padding: 6px 14px; font-size: 0.8rem;
  font-weight: 600; color: var(--green); margin-top: 12px;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 640px) {
  .photo-grid { grid-template-columns: 1fr; }
  .result-grid { grid-template-columns: 1fr; }
  .form-row { grid-template-columns: 1fr; }
  .steps-nav { gap: 0; }
  .step-item:not(:last-child)::after { width: 30px; }
  .q-options { flex-direction: column; }
  .btn-row { flex-direction: column; }
  .btn { justify-content: center; }
}
</style>
</head>
<body>

<div class="bg-orbs">
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
  <div class="loading-ring"></div>
  <div class="loading-text" id="loadingText">Menganalisis foto…</div>
  <div class="loading-sub" id="loadingSub">AI Gemini sedang membaca tanda-tanda fisik pada foto. Mohon tunggu sebentar.</div>
</div>

<div class="container">
  <!-- Header -->
  <div class="header">
    <div class="header-badge">
      <span class="pulse"></span>
      AI Gemini Vision
    </div>
    <h1>Deteksi <span class="gradient-text">Kekurangan Vitamin</span><br>& Gizi Anak</h1>
    <p>Upload foto mata, kulit, dan kuku anak. AI kami akan mendeteksi tanda kekurangan nutrisi secara visual, dilengkapi kuesioner pola makan.</p>
  </div>

  <!-- Progress -->
  <div class="progress-bar"><div class="progress-fill" id="progressFill" style="width: 25%"></div></div>

  <!-- Step Nav -->
  <div class="steps-nav" style="gap: 40px; margin-bottom: 48px;">
    <div class="step-item active" id="step-nav-1">
      <div class="step-circle">1</div>
      <div class="step-label">Data Anak</div>
    </div>
    <div class="step-item" id="step-nav-2">
      <div class="step-circle">2</div>
      <div class="step-label">Foto Fisik</div>
    </div>
    <div class="step-item" id="step-nav-3">
      <div class="step-circle">3</div>
      <div class="step-label">Kuesioner</div>
    </div>
    <div class="step-item" id="step-nav-4">
      <div class="step-circle">✓</div>
      <div class="step-label">Hasil</div>
    </div>
  </div>

  <!-- ======================== STEP 1: Data Anak ======================== -->
  <div class="section active" id="section-1">
    <div class="card">
      <div class="card-header">
        <div class="card-icon green">👶</div>
        <div>
          <div class="card-title">Data Identitas Anak</div>
          <div class="card-subtitle">Informasi dasar untuk menyimpan hasil pemeriksaan</div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group full">
          <label for="namaAnak">Nama Anak</label>
          <input type="text" id="namaAnak" class="form-control" placeholder="Contoh: Budi Santoso (opsional)" />
        </div>
        <div class="form-group">
          <label for="usiaAnak">Usia (bulan)</label>
          <input type="number" id="usiaAnak" class="form-control" placeholder="Contoh: 36" min="0" max="216" />
        </div>
        <div class="form-group">
          <label for="jenisKelamin">Jenis Kelamin</label>
          <select id="jenisKelamin" class="form-control">
            <option value="">-- Pilih --</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
          </select>
        </div>
      </div>
      <div class="disclaimer">
        <div class="disclaimer-icon">🔒</div>
        <div class="disclaimer-text">
          <strong>Privasi Anda Terlindungi.</strong> Data dan foto yang diupload hanya digunakan untuk analisis AI dan tidak dibagikan ke pihak ketiga. Nama anak bersifat opsional.
        </div>
      </div>
      <div class="btn-row">
        <button class="btn btn-primary" onclick="goStep(2)">
          Lanjut ke Foto Fisik
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </button>
      </div>
    </div>
  </div>

  <!-- ======================== STEP 2: Foto Fisik ======================== -->
  <div class="section" id="section-2">
    <div class="card">
      <div class="card-header">
        <div class="card-icon blue">📷</div>
        <div>
          <div class="card-title">Upload Foto Fisik</div>
          <div class="card-subtitle">Upload foto mata, kulit, dan kuku — AI akan menganalisis satu per satu</div>
        </div>
      </div>

      <div class="photo-grid">
        <!-- Mata -->
        <div class="photo-upload-card" id="card-mata" ondragover="handleDragOver(event,'mata')" ondrop="handleDrop(event,'mata')" ondragleave="handleDragLeave('mata')">
          <div class="photo-part-label">👁️ Mata</div>
          <input type="file" id="foto-mata" accept="image/*" onchange="handlePhotoChange('mata',this)" />
          <div class="photo-preview" id="preview-mata" style="display:none">
            <img id="preview-img-mata" src="" alt="Mata" />
            <div class="photo-preview-overlay">
              <span class="photo-status-badge ok" id="badge-mata">✓ Terupload</span>
            </div>
          </div>
          <div id="placeholder-mata">
            <div class="upload-icon">👁️</div>
            <div class="upload-label">Foto Mata</div>
            <div class="upload-hint">JPG/PNG, maks. 5MB<br>Pastikan area mata terlihat jelas</div>
          </div>
          <button class="analyze-btn-small" id="analyze-btn-mata" onclick="analyzePhoto('mata')" disabled>Analisis AI</button>
        </div>

        <!-- Kulit -->
        <div class="photo-upload-card" id="card-kulit" ondragover="handleDragOver(event,'kulit')" ondrop="handleDrop(event,'kulit')" ondragleave="handleDragLeave('kulit')">
          <div class="photo-part-label">🖐️ Kulit</div>
          <input type="file" id="foto-kulit" accept="image/*" onchange="handlePhotoChange('kulit',this)" />
          <div class="photo-preview" id="preview-kulit" style="display:none">
            <img id="preview-img-kulit" src="" alt="Kulit" />
            <div class="photo-preview-overlay">
              <span class="photo-status-badge ok" id="badge-kulit">✓ Terupload</span>
            </div>
          </div>
          <div id="placeholder-kulit">
            <div class="upload-icon">🖐️</div>
            <div class="upload-label">Foto Kulit</div>
            <div class="upload-hint">JPG/PNG, maks. 5MB<br>Telapak atau lengan bawah</div>
          </div>
          <button class="analyze-btn-small" id="analyze-btn-kulit" onclick="analyzePhoto('kulit')" disabled>Analisis AI</button>
        </div>

        <!-- Kuku -->
        <div class="photo-upload-card" id="card-kuku" ondragover="handleDragOver(event,'kuku')" ondrop="handleDrop(event,'kuku')" ondragleave="handleDragLeave('kuku')">
          <div class="photo-part-label">💅 Kuku</div>
          <input type="file" id="foto-kuku" accept="image/*" onchange="handlePhotoChange('kuku',this)" />
          <div class="photo-preview" id="preview-kuku" style="display:none">
            <img id="preview-img-kuku" src="" alt="Kuku" />
            <div class="photo-preview-overlay">
              <span class="photo-status-badge ok" id="badge-kuku">✓ Terupload</span>
            </div>
          </div>
          <div id="placeholder-kuku">
            <div class="upload-icon">💅</div>
            <div class="upload-label">Foto Kuku</div>
            <div class="upload-hint">JPG/PNG, maks. 5MB<br>Jari tangan, pencahayaan terang</div>
          </div>
          <button class="analyze-btn-small" id="analyze-btn-kuku" onclick="analyzePhoto('kuku')" disabled>Analisis AI</button>
        </div>
      </div>

      <!-- Analysis Results -->
      <div class="analysis-result" id="result-mata">
        <div class="analysis-result-header">
          <span style="font-size:1.2rem">👁️</span>
          <div class="analysis-result-title">Hasil Analisis Mata</div>
        </div>
        <div class="analysis-text" id="result-mata-text"></div>
      </div>
      <div class="analysis-result" id="result-kulit" style="background:rgba(249,168,212,0.06);border-color:rgba(249,168,212,0.2)">
        <div class="analysis-result-header">
          <span style="font-size:1.2rem">🖐️</span>
          <div class="analysis-result-title" style="color:#F9A8D4">Hasil Analisis Kulit</div>
        </div>
        <div class="analysis-text" id="result-kulit-text"></div>
      </div>
      <div class="analysis-result" id="result-kuku" style="background:rgba(134,239,172,0.06);border-color:rgba(134,239,172,0.2)">
        <div class="analysis-result-header">
          <span style="font-size:1.2rem">💅</span>
          <div class="analysis-result-title" style="color:#86EFAC">Hasil Analisis Kuku</div>
        </div>
        <div class="analysis-text" id="result-kuku-text"></div>
      </div>

      <div class="disclaimer" style="margin-top: 24px;">
        <div class="disclaimer-icon">💡</div>
        <div class="disclaimer-text">
          <strong>Tips foto terbaik:</strong> Gunakan pencahayaan alami (sinar matahari tidak langsung), hindari flash yang berlebihan. Pastikan area yang difoto tampak jelas dan tidak buram.
        </div>
      </div>

      <div class="btn-row">
        <button class="btn btn-ghost" onclick="goStep(1)">← Kembali</button>
        <button class="btn btn-primary" id="btnStep2Next" onclick="goStep(3)">
          Lanjut ke Kuesioner
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </button>
      </div>
    </div>
  </div>

  <!-- ======================== STEP 3: Kuesioner Gizi ======================== -->
  <div class="section" id="section-3">
    <div class="card">
      <div class="card-header">
        <div class="card-icon violet">📋</div>
        <div>
          <div class="card-title">Kuesioner Pola Makan & Gizi</div>
          <div class="card-subtitle">Jawab semua pertanyaan untuk mendapat analisis yang lebih akurat</div>
        </div>
      </div>

      <!-- GROUP 1: Konsumsi Protein -->
      <div class="q-group">
        <div class="q-group-title">🥚 Konsumsi Protein Hewani</div>

        <div class="q-item">
          <div class="q-text">Berapa kali anak mengonsumsi <strong>telur</strong> dalam seminggu?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_telur" value="Tidak pernah" /><span class="q-opt-label">Tidak pernah</span></label>
            <label class="q-opt"><input type="radio" name="q_telur" value="1-2x/minggu" /><span class="q-opt-label">1–2x/minggu</span></label>
            <label class="q-opt"><input type="radio" name="q_telur" value="3-4x/minggu" /><span class="q-opt-label">3–4x/minggu</span></label>
            <label class="q-opt"><input type="radio" name="q_telur" value="Hampir setiap hari" /><span class="q-opt-label">Hampir tiap hari</span></label>
          </div>
        </div>

        <div class="q-item">
          <div class="q-text">Berapa kali anak mengonsumsi <strong>daging ayam/sapi/ikan</strong> dalam seminggu?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_daging" value="Tidak pernah" /><span class="q-opt-label">Tidak pernah</span></label>
            <label class="q-opt"><input type="radio" name="q_daging" value="1-2x/minggu" /><span class="q-opt-label">1–2x/minggu</span></label>
            <label class="q-opt"><input type="radio" name="q_daging" value="3-4x/minggu" /><span class="q-opt-label">3–4x/minggu</span></label>
            <label class="q-opt"><input type="radio" name="q_daging" value="Hampir setiap hari" /><span class="q-opt-label">Hampir tiap hari</span></label>
          </div>
        </div>

        <div class="q-item">
          <div class="q-text">Apakah anak mengonsumsi <strong>susu atau produk olahan susu</strong> (keju, yogurt)?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_susu" value="Tidak pernah" /><span class="q-opt-label">Tidak pernah</span></label>
            <label class="q-opt"><input type="radio" name="q_susu" value="Kadang-kadang" /><span class="q-opt-label">Kadang-kadang</span></label>
            <label class="q-opt"><input type="radio" name="q_susu" value="Setiap hari" /><span class="q-opt-label">Setiap hari</span></label>
          </div>
        </div>
      </div>

      <!-- GROUP 2: Sayur & Buah -->
      <div class="q-group">
        <div class="q-group-title">🥦 Sayur & Buah</div>

        <div class="q-item">
          <div class="q-text">Apakah anak mengonsumsi <strong>sayuran hijau</strong> (bayam, kangkung, brokoli) setiap hari?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_sayur" value="Tidak pernah" /><span class="q-opt-label">Tidak pernah</span></label>
            <label class="q-opt"><input type="radio" name="q_sayur" value="Sesekali saja" /><span class="q-opt-label">Sesekali saja</span></label>
            <label class="q-opt"><input type="radio" name="q_sayur" value="Hampir setiap hari" /><span class="q-opt-label">Hampir tiap hari</span></label>
            <label class="q-opt"><input type="radio" name="q_sayur" value="Setiap hari" /><span class="q-opt-label">Setiap hari</span></label>
          </div>
        </div>

        <div class="q-item">
          <div class="q-text">Apakah anak mengonsumsi <strong>buah berwarna kuning/oranye</strong> (pepaya, mangga, wortel)?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_buah_kuning" value="Tidak pernah" /><span class="q-opt-label">Tidak pernah</span></label>
            <label class="q-opt"><input type="radio" name="q_buah_kuning" value="Sesekali" /><span class="q-opt-label">Sesekali</span></label>
            <label class="q-opt"><input type="radio" name="q_buah_kuning" value="1-2x/minggu" /><span class="q-opt-label">1–2x/minggu</span></label>
            <label class="q-opt"><input type="radio" name="q_buah_kuning" value="Hampir setiap hari" /><span class="q-opt-label">Hampir tiap hari</span></label>
          </div>
        </div>

        <div class="q-item">
          <div class="q-text">Apakah anak mengonsumsi <strong>buah kaya Vitamin C</strong> (jeruk, jambu, kiwi)?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_buah_c" value="Tidak pernah" /><span class="q-opt-label">Tidak pernah</span></label>
            <label class="q-opt"><input type="radio" name="q_buah_c" value="Sesekali" /><span class="q-opt-label">Sesekali</span></label>
            <label class="q-opt"><input type="radio" name="q_buah_c" value="1-2x/minggu" /><span class="q-opt-label">1–2x/minggu</span></label>
            <label class="q-opt"><input type="radio" name="q_buah_c" value="Hampir setiap hari" /><span class="q-opt-label">Hampir tiap hari</span></label>
          </div>
        </div>
      </div>

      <!-- GROUP 3: Kebiasaan & Gejala -->
      <div class="q-group">
        <div class="q-group-title">☀️ Paparan Sinar Matahari & Gejala</div>

        <div class="q-item">
          <div class="q-text">Berapa lama anak <strong>terpapar sinar matahari</strong> setiap hari?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_matahari" value="Hampir tidak pernah keluar" /><span class="q-opt-label">Hampir tidak pernah</span></label>
            <label class="q-opt"><input type="radio" name="q_matahari" value="< 15 menit/hari" /><span class="q-opt-label">&lt; 15 menit</span></label>
            <label class="q-opt"><input type="radio" name="q_matahari" value="15-30 menit/hari" /><span class="q-opt-label">15–30 menit</span></label>
            <label class="q-opt"><input type="radio" name="q_matahari" value="> 30 menit/hari" /><span class="q-opt-label">&gt; 30 menit</span></label>
          </div>
        </div>

        <div class="q-item">
          <div class="q-text">Apakah anak <strong>sering terlihat lelah</strong> atau lemas meski sudah cukup tidur?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_lelah" value="Ya" /><span class="q-opt-label">Ya, sering</span></label>
            <label class="q-opt"><input type="radio" name="q_lelah" value="Kadang-kadang" /><span class="q-opt-label">Kadang-kadang</span></label>
            <label class="q-opt"><input type="radio" name="q_lelah" value="Tidak" /><span class="q-opt-label">Tidak</span></label>
          </div>
        </div>

        <div class="q-item">
          <div class="q-text">Apakah anak <strong>sering sakit</strong> (flu, batuk, infeksi) lebih dari 3x dalam 3 bulan terakhir?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_sakit" value="Ya" /><span class="q-opt-label">Ya</span></label>
            <label class="q-opt"><input type="radio" name="q_sakit" value="Kadang-kadang" /><span class="q-opt-label">Kadang-kadang</span></label>
            <label class="q-opt"><input type="radio" name="q_sakit" value="Tidak" /><span class="q-opt-label">Tidak</span></label>
          </div>
        </div>

        <div class="q-item">
          <div class="q-text">Apakah anak mengalami <strong>bibir/sudut mulut pecah-pecah</strong> (stomatitis angular)?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_bibir" value="Ya, sering" /><span class="q-opt-label">Ya, sering</span></label>
            <label class="q-opt"><input type="radio" name="q_bibir" value="Kadang-kadang" /><span class="q-opt-label">Kadang-kadang</span></label>
            <label class="q-opt"><input type="radio" name="q_bibir" value="Tidak" /><span class="q-opt-label">Tidak</span></label>
          </div>
        </div>
      </div>

      <!-- GROUP 4: Pola Makan -->
      <div class="q-group">
        <div class="q-group-title">🍽️ Pola & Kebiasaan Makan</div>

        <div class="q-item">
          <div class="q-text">Apakah anak <strong>susah makan</strong> atau sering menolak makan?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_susah_makan" value="Ya, sangat susah" /><span class="q-opt-label">Ya, sangat susah</span></label>
            <label class="q-opt"><input type="radio" name="q_susah_makan" value="Kadang-kadang" /><span class="q-opt-label">Kadang-kadang</span></label>
            <label class="q-opt"><input type="radio" name="q_susah_makan" value="Tidak, makan dengan baik" /><span class="q-opt-label">Tidak, makan baik</span></label>
          </div>
        </div>

        <div class="q-item">
          <div class="q-text">Seberapa sering anak mengonsumsi <strong>makanan ultra proses</strong> (mie instan, snack kemasan, minuman manis)?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_junk" value="Hampir setiap hari" /><span class="q-opt-label">Hampir tiap hari</span></label>
            <label class="q-opt"><input type="radio" name="q_junk" value="3-4x/minggu" /><span class="q-opt-label">3–4x/minggu</span></label>
            <label class="q-opt"><input type="radio" name="q_junk" value="1-2x/minggu" /><span class="q-opt-label">1–2x/minggu</span></label>
            <label class="q-opt"><input type="radio" name="q_junk" value="Jarang sekali" /><span class="q-opt-label">Jarang sekali</span></label>
          </div>
        </div>

        <div class="q-item">
          <div class="q-text">Apakah anak mendapat <strong>suplemen vitamin</strong> secara rutin?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_suplemen" value="Ya, rutin setiap hari" /><span class="q-opt-label">Ya, rutin</span></label>
            <label class="q-opt"><input type="radio" name="q_suplemen" value="Kadang-kadang saja" /><span class="q-opt-label">Kadang-kadang</span></label>
            <label class="q-opt"><input type="radio" name="q_suplemen" value="Tidak sama sekali" /><span class="q-opt-label">Tidak</span></label>
          </div>
        </div>

        <div class="q-item">
          <div class="q-text">Bagaimana <strong>nafsu makan</strong> anak secara keseluruhan dibanding anak seusianya?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_nafsu" value="Sangat kurang" /><span class="q-opt-label">Sangat kurang</span></label>
            <label class="q-opt"><input type="radio" name="q_nafsu" value="Di bawah rata-rata" /><span class="q-opt-label">Di bawah rata-rata</span></label>
            <label class="q-opt"><input type="radio" name="q_nafsu" value="Normal" /><span class="q-opt-label">Normal</span></label>
            <label class="q-opt"><input type="radio" name="q_nafsu" value="Di atas rata-rata" /><span class="q-opt-label">Di atas rata-rata</span></label>
          </div>
        </div>
      </div>

      <!-- GROUP 5: Gejala Fisik -->
      <div class="q-group">
        <div class="q-group-title">🩺 Gejala Fisik Lain</div>

        <div class="q-item">
          <div class="q-text">Apakah anak memiliki <strong>kulit yang sangat kering atau bersisik</strong>?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_kulit_kering" value="Ya, parah" /><span class="q-opt-label">Ya, parah</span></label>
            <label class="q-opt"><input type="radio" name="q_kulit_kering" value="Sedikit" /><span class="q-opt-label">Sedikit</span></label>
            <label class="q-opt"><input type="radio" name="q_kulit_kering" value="Tidak" /><span class="q-opt-label">Tidak</span></label>
          </div>
        </div>

        <div class="q-item">
          <div class="q-text">Apakah anak pernah didiagnosis <strong>anemia</strong> atau terlihat pucat?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_anemia" value="Ya, didiagnosis anemia" /><span class="q-opt-label">Ya, didiagnosis</span></label>
            <label class="q-opt"><input type="radio" name="q_anemia" value="Terlihat pucat tapi belum cek" /><span class="q-opt-label">Terlihat pucat</span></label>
            <label class="q-opt"><input type="radio" name="q_anemia" value="Tidak" /><span class="q-opt-label">Tidak</span></label>
          </div>
        </div>

        <div class="q-item">
          <div class="q-text">Apakah anak memiliki <strong>rambut yang mudah rontok</strong> atau tipis?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_rambut" value="Ya, sangat rontok" /><span class="q-opt-label">Ya, sangat rontok</span></label>
            <label class="q-opt"><input type="radio" name="q_rambut" value="Sedikit lebih rontok dari biasa" /><span class="q-opt-label">Sedikit lebih rontok</span></label>
            <label class="q-opt"><input type="radio" name="q_rambut" value="Normal" /><span class="q-opt-label">Normal</span></label>
          </div>
        </div>

        <div class="q-item">
          <div class="q-text">Apakah anak mengalami <strong>gangguan penglihatan di malam hari</strong> (rabun senja)?</div>
          <div class="q-options">
            <label class="q-opt"><input type="radio" name="q_rabun_senja" value="Ya" /><span class="q-opt-label">Ya</span></label>
            <label class="q-opt"><input type="radio" name="q_rabun_senja" value="Tidak yakin" /><span class="q-opt-label">Tidak yakin</span></label>
            <label class="q-opt"><input type="radio" name="q_rabun_senja" value="Tidak" /><span class="q-opt-label">Tidak</span></label>
          </div>
        </div>
      </div>

      <div class="btn-row">
        <button class="btn btn-ghost" onclick="goStep(2)">← Kembali</button>
        <button class="btn btn-green" id="btnSubmit" onclick="submitAll()">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 6v4l3 3"/></svg>
          Analisis Gizi Sekarang
        </button>
      </div>
    </div>
  </div>

  <!-- ======================== STEP 4: Hasil ======================== -->
  <div class="section" id="section-4">
    <div class="result-hero">
      <div style="font-size:3rem; margin-bottom:16px">🔬</div>
      <h2 style="font-size:1.8rem;font-weight:800;color:var(--text-primary);margin-bottom:8px">Hasil Analisis AI</h2>
      <p style="color:var(--text-secondary);font-size:0.95rem">Berdasarkan foto fisik dan kuesioner gizi anak</p>
      <div id="riskBadgeWrap" style="margin-top:20px"></div>
      <div id="savedChip" class="saved-chip" style="display:none">
        ✓ Data tersimpan di database
      </div>
    </div>

    <div class="result-grid" id="resultGrid">
      <!-- Filled by JS -->
    </div>

    <div class="disclaimer" id="finalDisclaimer">
      <div class="disclaimer-icon">⚕️</div>
      <div class="disclaimer-text">
        <strong>Penting:</strong> Hasil ini adalah skrining awal berbasis AI dan <strong>bukan diagnosis medis</strong>.
        Segera konsultasikan dengan dokter spesialis anak atau ahli gizi jika ditemukan indikasi defisiensi, terutama dengan level risiko Sedang–Tinggi.
        Pemeriksaan laboratorium darah lengkap tetap diperlukan untuk konfirmasi.
      </div>
    </div>

    <div style="text-align:center;margin-top:32px">
      <button class="new-detection-btn" onclick="resetAll()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
        Pemeriksaan Baru
      </button>
    </div>
  </div>
</div>

<script>
// ==================== STATE ====================
const state = {
  currentStep: 1,
  photos: { mata: null, kulit: null, kuku: null },
  analyses: { mata: '', kulit: '', kuku: '' },
  recordId: null,
};

const API_BASE = '/api';

// ==================== NAVIGATION ====================
function goStep(n) {
  document.getElementById(`section-${state.currentStep}`).classList.remove('active');
  document.getElementById(`step-nav-${state.currentStep}`).classList.remove('active');

  // Mark previous steps as done
  for (let i = 1; i < n; i++) {
    document.getElementById(`step-nav-${i}`).classList.add('done');
    document.getElementById(`step-nav-${i}`).classList.remove('active');
  }

  state.currentStep = n;
  document.getElementById(`section-${n}`).classList.add('active');
  document.getElementById(`step-nav-${n}`).classList.add('active');
  document.getElementById(`step-nav-${n}`).classList.remove('done');

  // Update progress bar
  const pct = { 1: 25, 2: 50, 3: 75, 4: 100 }[n];
  document.getElementById('progressFill').style.width = pct + '%';

  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ==================== PHOTO HANDLING ====================
function handleDragOver(e, bagian) {
  e.preventDefault();
  document.getElementById(`card-${bagian}`).classList.add('drag-over');
}
function handleDragLeave(bagian) {
  document.getElementById(`card-${bagian}`).classList.remove('drag-over');
}
function handleDrop(e, bagian) {
  e.preventDefault();
  handleDragLeave(bagian);
  const file = e.dataTransfer.files[0];
  if (file && file.type.startsWith('image/')) {
    setPhoto(bagian, file);
  }
}
function handlePhotoChange(bagian, input) {
  if (input.files[0]) setPhoto(bagian, input.files[0]);
}

function setPhoto(bagian, file) {
  state.photos[bagian] = file;
  const reader = new FileReader();
  reader.onload = (e) => {
    document.getElementById(`preview-img-${bagian}`).src = e.target.result;
    document.getElementById(`preview-${bagian}`).style.display = 'block';
    document.getElementById(`placeholder-${bagian}`).style.display = 'none';
    document.getElementById(`card-${bagian}`).classList.add('uploaded');
    document.getElementById(`badge-${bagian}`).textContent = '✓ Siap dianalisis';
    document.getElementById(`analyze-btn-${bagian}`).disabled = false;
    // Reset analysis
    state.analyses[bagian] = '';
    const resEl = document.getElementById(`result-${bagian}`);
    resEl.classList.remove('show');
  };
  reader.readAsDataURL(file);
}

// ==================== PHOTO ANALYSIS ====================
async function analyzePhoto(bagian) {
  const file = state.photos[bagian];
  if (!file) return alert('Pilih foto terlebih dahulu.');

  const btn = document.getElementById(`analyze-btn-${bagian}`);
  btn.disabled = true;
  btn.textContent = '⏳ Menganalisis…';

  const card = document.getElementById(`card-${bagian}`);
  card.classList.add('analyzing');
  document.getElementById(`badge-${bagian}`).className = 'photo-status-badge analyzing';
  document.getElementById(`badge-${bagian}`).textContent = '🔄 Menganalisis…';

  const formData = new FormData();
  formData.append('foto', file);
  formData.append('bagian', bagian);

  try {
    const resp = await fetch(`${API_BASE}/vitamin-detection/analyze-photo`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': getCsrf(), 'Accept': 'application/json' },
      body: formData,
    });
    const data = await resp.json();

    if (data.success) {
      state.analyses[bagian] = data.analisis;
      const resultEl = document.getElementById(`result-${bagian}`);
      document.getElementById(`result-${bagian}-text`).innerHTML = formatAnalysis(data.analisis);
      resultEl.classList.add('show');

      card.classList.remove('analyzing');
      card.classList.add('uploaded');
      document.getElementById(`badge-${bagian}`).className = 'photo-status-badge ok';
      document.getElementById(`badge-${bagian}`).textContent = '✓ Teranalisis';
      btn.textContent = '✓ Selesai';
    } else {
      throw new Error('Analisis gagal');
    }
  } catch (err) {
    alert('Gagal menganalisis foto. Pastikan API key valid dan coba lagi.');
    btn.disabled = false;
    btn.textContent = 'Analisis AI';
    card.classList.remove('analyzing');
  }
}

// ==================== SUBMIT ALL ====================
async function submitAll() {
  const btn = document.getElementById('btnSubmit');
  btn.disabled = true;
  btn.innerHTML = '<div class="spinner"></div> Menganalisis…';

  showLoading('Mengirim data & menganalisis gizi…', 'AI Gemini sedang menganalisis foto dan kuesioner. Proses ini membutuhkan 20–60 detik. Mohon tunggu.');

  const formData = new FormData();
  formData.append('nama_anak', document.getElementById('namaAnak').value);
  formData.append('usia_anak', document.getElementById('usiaAnak').value);
  formData.append('jenis_kelamin', document.getElementById('jenisKelamin').value);

  // Attach photos
  ['mata', 'kulit', 'kuku'].forEach(bagian => {
    if (state.photos[bagian]) formData.append(`foto_${bagian}`, state.photos[bagian]);
  });

  // Collect questionnaire answers
  const qNames = [
    'q_telur','q_daging','q_susu','q_sayur','q_buah_kuning','q_buah_c',
    'q_matahari','q_lelah','q_sakit','q_bibir','q_susah_makan','q_junk',
    'q_suplemen','q_nafsu','q_kulit_kering','q_anemia','q_rambut','q_rabun_senja'
  ];
  const qLabels = {
    q_telur: 'Konsumsi telur per minggu',
    q_daging: 'Konsumsi daging/ikan per minggu',
    q_susu: 'Konsumsi susu/produk susu',
    q_sayur: 'Konsumsi sayuran hijau',
    q_buah_kuning: 'Konsumsi buah kuning/oranye',
    q_buah_c: 'Konsumsi buah kaya Vitamin C',
    q_matahari: 'Paparan sinar matahari',
    q_lelah: 'Sering terlihat lelah/lemas',
    q_sakit: 'Sering sakit (3+ kali / 3 bulan)',
    q_bibir: 'Bibir/sudut mulut pecah-pecah',
    q_susah_makan: 'Susah makan',
    q_junk: 'Konsumsi makanan ultra proses',
    q_suplemen: 'Mendapat suplemen vitamin',
    q_nafsu: 'Nafsu makan keseluruhan',
    q_kulit_kering: 'Kulit kering/bersisik',
    q_anemia: 'Riwayat anemia/terlihat pucat',
    q_rambut: 'Rambut mudah rontok',
    q_rabun_senja: 'Gangguan penglihatan malam hari',
  };

  const jawaban = {};
  qNames.forEach(name => {
    const el = document.querySelector(`input[name="${name}"]:checked`);
    if (el) jawaban[qLabels[name] || name] = el.value;
    else jawaban[qLabels[name] || name] = 'Tidak dijawab';
  });
  formData.append('jawaban_kuesioner', JSON.stringify(jawaban));

  try {
    const resp = await fetch(`${API_BASE}/vitamin-detection/submit`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': getCsrf(), 'Accept': 'application/json' },
      body: formData,
    });
    const data = await resp.json();

    hideLoading();
    if (data.success || data.id) {
      state.recordId = data.id;
      renderResults(data);
      goStep(4);
    } else {
      throw new Error(JSON.stringify(data));
    }
  } catch (err) {
    hideLoading();
    console.error(err);
    alert('Terjadi kesalahan saat menganalisis. Periksa koneksi dan API key, lalu coba lagi.');
  } finally {
    btn.disabled = false;
    btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 6v4l3 3"/></svg> Analisis Gizi Sekarang`;
  }
}

// ==================== RENDER RESULTS ====================
function renderResults(data) {
  const grid = document.getElementById('resultGrid');
  grid.innerHTML = '';

  // Risk badge
  const risk = data.level_risiko || 'sedang';
  const riskLabel = { rendah: '✅ Risiko Rendah', sedang: '⚠️ Risiko Sedang', tinggi: '🚨 Risiko Tinggi' }[risk] || risk;
  document.getElementById('riskBadgeWrap').innerHTML = `<div class="risk-badge ${risk}">${riskLabel}</div>`;

  // Saved chip
  if (data.id) {
    document.getElementById('savedChip').style.display = 'inline-flex';
  }

  // Photo analyses
  const parts = [
    { key: 'analisis_mata',  emoji: '👁️', label: 'Analisis Mata',  cls: 'mata-color',  text: data.analisis_mata },
    { key: 'analisis_kulit', emoji: '🖐️', label: 'Analisis Kulit', cls: 'kulit-color', text: data.analisis_kulit },
    { key: 'analisis_kuku',  emoji: '💅', label: 'Analisis Kuku',  cls: 'kuku-color',  text: data.analisis_kuku },
  ];

  parts.forEach(p => {
    if (!p.text) return;
    const card = document.createElement('div');
    card.className = 'result-card';
    card.innerHTML = `
      <div class="result-card-title ${p.cls}">${p.emoji} ${p.label}</div>
      <div class="result-text">${formatAnalysis(p.text)}</div>
    `;
    grid.appendChild(card);
  });

  // Nutrition analysis (full width)
  if (data.analisis_gizi) {
    const card = document.createElement('div');
    card.className = 'result-card full';
    card.innerHTML = `
      <div class="result-card-title gizi-color">🩺 Analisis Gizi Komprehensif</div>
      <div class="result-text">${formatAnalysis(data.analisis_gizi)}</div>
    `;
    grid.appendChild(card);
  }
}

// ==================== FORMAT TEXT ====================
function formatAnalysis(text) {
  if (!text) return '';
  // Bold **text**
  text = text.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
  // Bullet points with -
  text = text.replace(/^- (.+)$/gm, '• $1');
  // Line breaks
  text = text.replace(/\n/g, '<br>');
  return text;
}

// ==================== UTILS ====================
function getCsrf() {
  const m = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
  return m ? decodeURIComponent(m[1]) : '';
}

function showLoading(text, sub) {
  document.getElementById('loadingText').textContent = text;
  document.getElementById('loadingSub').textContent = sub;
  document.getElementById('loadingOverlay').classList.add('show');
}
function hideLoading() {
  document.getElementById('loadingOverlay').classList.remove('show');
}

function resetAll() {
  state.currentStep = 1;
  state.photos = { mata: null, kulit: null, kuku: null };
  state.analyses = { mata: '', kulit: '', kuku: '' };
  state.recordId = null;

  ['mata','kulit','kuku'].forEach(b => {
    document.getElementById(`card-${b}`).className = 'photo-upload-card';
    document.getElementById(`preview-${b}`).style.display = 'none';
    document.getElementById(`placeholder-${b}`).style.display = '';
    document.getElementById(`result-${b}`).classList.remove('show');
    document.getElementById(`analyze-btn-${b}`).disabled = true;
    document.getElementById(`analyze-btn-${b}`).textContent = 'Analisis AI';
    document.getElementById(`foto-${b}`).value = '';
  });

  document.getElementById('namaAnak').value = '';
  document.getElementById('usiaAnak').value = '';
  document.getElementById('jenisKelamin').value = '';
  document.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);

  // Reset step nav
  for (let i = 1; i <= 4; i++) {
    document.getElementById(`step-nav-${i}`).classList.remove('active','done');
  }

  goStep(1);
}

// Fetch CSRF cookie on load
fetch('/sanctum/csrf-cookie').catch(() => {});
</script>
</body>
</html>
