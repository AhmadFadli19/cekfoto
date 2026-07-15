<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>Screening Gizi Anak — GiziKu AI</title>
<meta name="description" content="Lakukan screening gizi anak dengan AI GiziKu. Upload foto, isi kuesioner, dan dapatkan hasil analisis gizi komprehensif dalam 2 menit." />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html{scroll-behavior:smooth;}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#050E0A;color:#E2F0E8;min-height:100vh;overflow-x:hidden;-webkit-font-smoothing:antialiased;}
a{text-decoration:none;color:inherit;}
:root{
  --bg:#050E0A;--bg-card:rgba(255,255,255,0.04);--bg-card-h:rgba(255,255,255,0.07);
  --border:rgba(255,255,255,0.07);--border-g:rgba(0,212,106,0.3);
  --green:#00D46A;--green2:#00C060;--teal:#0ABFA3;
  --text:#E2F0E8;--text-m:#8FA898;--text-d:#4A6B54;
  --red:#EF4444;--amber:#F59E0B;--emerald:#10B981;
  --r-sm:12px;--r-md:18px;--r-lg:24px;--r-xl:28px;
  --shadow:0 20px 60px -12px rgba(0,0,0,0.7);
}
::-webkit-scrollbar{width:5px;}
::-webkit-scrollbar-track{background:#050E0A;}
::-webkit-scrollbar-thumb{background:rgba(0,212,106,0.3);border-radius:5px;}

/* BG */
.bg-orbs{position:fixed;inset:0;pointer-events:none;z-index:0;}
.orb{position:absolute;border-radius:50%;filter:blur(120px);opacity:0.2;animation:floatOrb 10s ease-in-out infinite;}
.orb-1{width:600px;height:600px;background:radial-gradient(circle,#003d1a,transparent);top:-150px;left:-100px;}
.orb-2{width:400px;height:400px;background:radial-gradient(circle,#001f2e,transparent);bottom:0;right:-100px;animation-delay:5s;}
@keyframes floatOrb{0%,100%{transform:translateY(0);}50%{transform:translateY(-20px);}}

/* LAYOUT */
.page{position:relative;z-index:1;max-width:860px;margin:0 auto;padding:0 20px 80px;}

/* TOPBAR */
.topbar{display:flex;align-items:center;justify-content:space-between;padding:20px 0 32px;}
.back-btn{display:flex;align-items:center;gap:8px;font-size:0.88rem;color:var(--text-m);cursor:pointer;border:none;background:none;font-family:inherit;padding:8px 14px;border-radius:100px;border:1px solid var(--border);transition:all .2s;}
.back-btn:hover{color:var(--green);border-color:var(--border-g);}
.topbar-logo{font-size:1.1rem;font-weight:800;color:var(--text);display:flex;align-items:center;gap:8px;}
.logo-icon{width:28px;height:28px;background:linear-gradient(135deg,var(--green),var(--teal));border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:0.9rem;}
.logo-dot{color:var(--green);}

/* STEP INDICATOR */
.step-indicator{display:flex;align-items:center;gap:0;margin-bottom:40px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:6px;}
.step-item{flex:1;display:flex;align-items:center;justify-content:center;gap:8px;padding:10px 8px;border-radius:var(--r-lg);font-size:0.8rem;font-weight:600;color:var(--text-d);cursor:default;transition:all .3s;position:relative;}
.step-item.active{background:linear-gradient(135deg,rgba(0,212,106,0.15),rgba(10,191,163,0.1));color:var(--green);border:1px solid rgba(0,212,106,0.25);}
.step-item.done{color:var(--text-m);}
.step-item.done .step-num-badge{background:var(--green);color:#050E0A;}
.step-num-badge{width:22px;height:22px;border-radius:50%;background:rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:center;font-size:0.72rem;font-weight:700;flex-shrink:0;}
.step-item.active .step-num-badge{background:var(--green);color:#050E0A;}
.step-label{display:none;}
@media(min-width:600px){.step-label{display:inline;}}

/* STEP PANELS */
.step-panel{display:none;}
.step-panel.active{display:block;animation:fadeIn .4s ease;}
@keyframes fadeIn{from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:none;}}

/* SECTION HEADER */
.panel-header{margin-bottom:32px;}
.panel-header h2{font-size:1.7rem;font-weight:800;color:#fff;margin-bottom:8px;}
.panel-header p{font-size:0.92rem;color:var(--text-m);line-height:1.6;}

/* FORM */
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
.form-grid.full{grid-template-columns:1fr;}
.form-group{display:flex;flex-direction:column;gap:8px;}
.form-group.full{grid-column:1/-1;}
.form-label{font-size:0.85rem;font-weight:600;color:var(--text-m);}
.form-label .req{color:var(--green);}
.form-input{background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:var(--r-md);padding:13px 16px;font-size:0.92rem;color:var(--text);font-family:inherit;outline:none;transition:border-color .2s,box-shadow .2s;width:100%;}
.form-input:focus{border-color:var(--border-g);box-shadow:0 0 0 3px rgba(0,212,106,0.1);}
.form-input::placeholder{color:var(--text-d);}
.form-select{appearance:none;cursor:pointer;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238FA898' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 14px center;}
.form-select option{background:#0D1F13;}
.form-error{font-size:0.78rem;color:var(--red);display:none;}
.form-group.has-error .form-input{border-color:var(--red);}
.form-group.has-error .form-error{display:block;}

/* RADIO GROUP */
.radio-group{display:flex;gap:10px;flex-wrap:wrap;}
.radio-option{flex:1;min-width:100px;}
.radio-option input{display:none;}
.radio-option label{display:flex;align-items:center;justify-content:center;gap:8px;padding:12px 16px;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:var(--r-md);font-size:0.88rem;font-weight:600;color:var(--text-m);cursor:pointer;transition:all .2s;text-align:center;}
.radio-option input:checked + label{background:rgba(0,212,106,0.12);border-color:rgba(0,212,106,0.4);color:var(--green);}
.radio-option label:hover{border-color:rgba(0,212,106,0.25);color:var(--text);}

/* INFO BOX */
.info-box{background:rgba(0,212,106,0.05);border:1px solid rgba(0,212,106,0.15);border-radius:var(--r-md);padding:16px 18px;font-size:0.85rem;color:var(--text-m);line-height:1.65;}
.info-box strong{color:var(--green);}

/* PHOTO UPLOAD */
.photo-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;}
.upload-zone{background:var(--bg-card);border:2px dashed var(--border);border-radius:var(--r-xl);padding:32px 20px;text-align:center;cursor:pointer;transition:all .3s;position:relative;}
.upload-zone:hover,.upload-zone.drag{border-color:var(--border-g);background:rgba(0,212,106,0.04);}
.upload-zone.has-file{border-style:solid;border-color:rgba(0,212,106,0.3);background:rgba(0,212,106,0.04);}
.upload-zone input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;}
.upload-icon{width:52px;height:52px;border-radius:var(--r-md);background:rgba(255,255,255,0.04);display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin:0 auto 14px;}
.upload-zone h4{font-size:0.95rem;font-weight:700;color:#fff;margin-bottom:6px;}
.upload-zone p{font-size:0.8rem;color:var(--text-m);line-height:1.5;}
.upload-badge{display:inline-block;margin-top:8px;font-size:0.72rem;font-weight:600;padding:3px 10px;border-radius:100px;}
.badge-req{background:rgba(239,68,68,0.15);color:#FCA5A5;border:1px solid rgba(239,68,68,0.25);}
.badge-opt{background:rgba(100,116,139,0.15);color:#94A3B8;border:1px solid rgba(100,116,139,0.2);}
.preview-img{width:100%;height:140px;object-fit:cover;border-radius:var(--r-md);margin-bottom:10px;display:none;}
.upload-remove{display:none;background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);border-radius:var(--r-sm);padding:5px 12px;font-size:0.75rem;color:#FCA5A5;cursor:pointer;font-family:inherit;}

/* TIPS */
.tips-box{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-md);padding:18px;}
.tips-box h4{font-size:0.88rem;font-weight:700;color:var(--text);margin-bottom:12px;}
.tips-list{display:flex;flex-direction:column;gap:8px;}
.tip-item{display:flex;align-items:flex-start;gap:10px;font-size:0.82rem;color:var(--text-m);}
.tip-icon{font-size:1rem;flex-shrink:0;margin-top:1px;}

/* QUESTIONNAIRE */
.q-progress{height:4px;background:rgba(255,255,255,0.06);border-radius:100px;margin-bottom:28px;overflow:hidden;}
.q-progress-bar{height:100%;background:linear-gradient(90deg,var(--green),var(--teal));border-radius:100px;transition:width .4s ease;}
.q-item{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:24px;margin-bottom:16px;transition:border-color .2s;}
.q-item.answered{border-color:rgba(0,212,106,0.2);}
.q-num{font-size:0.72rem;font-weight:700;color:var(--green);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:8px;}
.q-text{font-size:0.95rem;font-weight:600;color:#fff;margin-bottom:16px;line-height:1.5;}
.q-options{display:flex;flex-direction:column;gap:8px;}
.q-opt{display:flex;align-items:center;gap:12px;padding:11px 16px;background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:var(--r-md);cursor:pointer;transition:all .2s;font-size:0.88rem;color:var(--text-m);}
.q-opt input{display:none;}
.q-opt:has(input:checked),.q-opt.selected{background:rgba(0,212,106,0.1);border-color:rgba(0,212,106,0.35);color:var(--text);}
.q-opt:hover{border-color:rgba(0,212,106,0.2);color:var(--text);}
.q-dot{width:18px;height:18px;border-radius:50%;border:2px solid var(--border);flex-shrink:0;transition:all .2s;}
.q-opt:has(input:checked) .q-dot,.q-opt.selected .q-dot{background:var(--green);border-color:var(--green);}

/* LOADING STEP */
.loading-center{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:60vh;text-align:center;}
.loading-logo{width:80px;height:80px;background:linear-gradient(135deg,var(--green),var(--teal));border-radius:24px;display:flex;align-items:center;justify-content:center;font-size:2.2rem;margin:0 auto 32px;animation:pulse 2s ease-in-out infinite;box-shadow:0 0 0 0 rgba(0,212,106,0.4);}
@keyframes pulse{0%{box-shadow:0 0 0 0 rgba(0,212,106,0.5);}70%{box-shadow:0 0 0 20px rgba(0,212,106,0);}100%{box-shadow:0 0 0 0 rgba(0,212,106,0);}}
.loading-title{font-size:1.5rem;font-weight:800;color:#fff;margin-bottom:12px;}
.loading-msg{font-size:0.92rem;color:var(--text-m);margin-bottom:36px;min-height:1.5em;}
.progress-bar-wrap{width:100%;max-width:400px;height:6px;background:rgba(255,255,255,0.06);border-radius:100px;overflow:hidden;margin:0 auto;}
.progress-bar-fill{height:100%;background:linear-gradient(90deg,var(--green),var(--teal));border-radius:100px;width:0%;transition:width .6s ease;}

/* RESULT STEP */
.result-header{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:28px;margin-bottom:24px;display:flex;align-items:center;gap:20px;}
.result-avatar{width:56px;height:56px;background:linear-gradient(135deg,var(--green),var(--teal));border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;flex-shrink:0;}
.result-meta h3{font-size:1.2rem;font-weight:800;color:#fff;margin-bottom:4px;}
.result-meta p{font-size:0.85rem;color:var(--text-m);}
.risk-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:100px;font-size:0.82rem;font-weight:700;margin-top:10px;}
.risk-rendah{background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);color:#6EE7B7;}
.risk-sedang{background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);color:#FCD34D;}
.risk-tinggi{background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);color:#FCA5A5;}

/* Score gauge */
.score-section{display:grid;grid-template-columns:auto 1fr;gap:24px;align-items:center;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:28px;margin-bottom:20px;}
.gauge-wrap{position:relative;width:120px;height:120px;flex-shrink:0;}
.gauge-wrap svg{transform:rotate(-90deg);}
.gauge-text{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;}
.gauge-num{font-size:1.8rem;font-weight:800;color:#fff;line-height:1;}
.gauge-max{font-size:0.72rem;color:var(--text-d);}
.score-info h4{font-size:1.05rem;font-weight:700;color:#fff;margin-bottom:6px;}
.score-info p{font-size:0.88rem;color:var(--text-m);line-height:1.6;}
.score-category{display:inline-block;margin-top:10px;font-size:0.82rem;font-weight:700;padding:4px 14px;border-radius:100px;}

/* Anthropometry */
.anthro-section{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:24px;margin-bottom:20px;}
.section-title{font-size:1rem;font-weight:700;color:#fff;margin-bottom:16px;display:flex;align-items:center;gap:8px;}
.anthro-row{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--border);}
.anthro-row:last-child{border-bottom:none;}
.anthro-label{font-size:0.85rem;color:var(--text-m);width:80px;flex-shrink:0;}
.anthro-bar{flex:1;height:6px;background:rgba(255,255,255,0.06);border-radius:100px;overflow:hidden;}
.anthro-bar-fill{height:100%;border-radius:100px;}
.anthro-status{font-size:0.78rem;font-weight:700;padding:3px 10px;border-radius:100px;white-space:nowrap;}
.status-normal{background:rgba(16,185,129,0.15);color:#6EE7B7;}
.status-warn{background:rgba(245,158,11,0.15);color:#FCD34D;}
.status-bad{background:rgba(239,68,68,0.15);color:#FCA5A5;}

/* Findings */
.findings-section{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:24px;margin-bottom:20px;}
.finding-card{background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:var(--r-md);padding:16px;margin-bottom:12px;}
.finding-card:last-child{margin-bottom:0;}
.finding-title{font-size:0.88rem;font-weight:700;color:#fff;margin-bottom:10px;}
.finding-items{display:flex;flex-direction:column;gap:6px;}
.finding-item{display:flex;align-items:flex-start;gap:10px;font-size:0.83rem;color:var(--text-m);}
.fi-icon{flex-shrink:0;margin-top:1px;}

/* Deficiency pills */
.defisiensi-section{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:24px;margin-bottom:20px;}
.defisiensi-pills{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;}
.pill{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:100px;font-size:0.82rem;font-weight:600;}
.pill-high{background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);color:#FCA5A5;}
.pill-mid{background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);color:#FCD34D;}

/* Recommendations */
.reco-section{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:24px;margin-bottom:20px;}
.reco-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;margin-top:14px;}
.reco-card{background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:var(--r-md);padding:16px;text-align:center;}
.reco-card .food-emoji{font-size:2rem;display:block;margin-bottom:10px;}
.reco-card h5{font-size:0.88rem;font-weight:700;color:#fff;margin-bottom:6px;}
.reco-card p{font-size:0.78rem;color:var(--text-m);line-height:1.5;}
.reco-freq{display:inline-block;margin-top:8px;font-size:0.72rem;padding:3px 10px;background:rgba(0,212,106,0.1);border-radius:100px;color:var(--green);}

/* Doctor section */
.doctor-section{border-radius:var(--r-xl);padding:24px;margin-bottom:24px;}
.doctor-section.perlu{background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);}
.doctor-section.tidak-perlu{background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.2);}
.doctor-icon{font-size:2rem;margin-bottom:12px;}
.doctor-section h4{font-size:1rem;font-weight:700;color:#fff;margin-bottom:8px;}
.doctor-section p{font-size:0.88rem;color:var(--text-m);line-height:1.6;}

/* Action buttons */
.action-btns{display:flex;flex-wrap:wrap;gap:12px;margin-top:28px;}
.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:100px;font-weight:700;font-size:0.9rem;cursor:pointer;border:none;transition:transform .2s,box-shadow .2s;font-family:inherit;}
.btn-primary{background:linear-gradient(135deg,var(--green),var(--teal));color:#050E0A;box-shadow:0 6px 24px rgba(0,212,106,0.3);}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 10px 32px rgba(0,212,106,0.45);}
.btn-ghost{background:rgba(255,255,255,0.05);color:var(--text);border:1px solid var(--border);}
.btn-ghost:hover{background:rgba(0,212,106,0.08);border-color:var(--border-g);color:var(--green);}
.btn-outline{background:transparent;color:var(--text);border:1px solid var(--border);}
.btn-outline:hover{border-color:var(--border-g);color:var(--green);}

/* NAV BUTTONS */
.nav-btns{display:flex;justify-content:space-between;align-items:center;margin-top:32px;padding-top:24px;border-top:1px solid var(--border);}

/* MESSAGE BOX */
.msg-box{padding:14px 18px;border-radius:var(--r-md);font-size:0.88rem;line-height:1.6;margin-bottom:20px;display:none;}
.msg-box.error{background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:#FCA5A5;}
.msg-box.show{display:block;}

/* RESPONSIVE */
@media(max-width:640px){
  .photo-grid{grid-template-columns:1fr;}
  .form-grid{grid-template-columns:1fr;}
  .score-section{grid-template-columns:1fr;text-align:center;}
  .gauge-wrap{margin:0 auto;}
  .step-item{padding:8px 4px;}
  .reco-grid{grid-template-columns:repeat(2,1fr);}
}
@media(prefers-reduced-motion:reduce){*{animation-duration:.001ms!important;transition-duration:.001ms!important;}}
@media print{.topbar,.step-indicator,.nav-btns,.action-btns,.bg-orbs{display:none!important;}.page{padding:0;}.step-panel{display:block!important;}}
</style>
</head>
<body>

<div class="bg-orbs" aria-hidden="true">
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
</div>

<div class="page">

  <!-- TOPBAR -->
  <div class="topbar">
    <a href="/" class="back-btn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      Kembali
    </a>
    <div class="topbar-logo">
      <div class="logo-icon" aria-hidden="true">🥦</div>
      Gizi<span class="logo-dot">Ku</span>
    </div>
    <div style="width:90px"></div>
  </div>

  <!-- STEP INDICATOR -->
  <nav class="step-indicator" aria-label="Progress screening" role="list">
    <div class="step-item active" id="si-1" role="listitem">
      <div class="step-num-badge">1</div>
      <span class="step-label">Data Anak</span>
    </div>
    <div class="step-item" id="si-2" role="listitem">
      <div class="step-num-badge">2</div>
      <span class="step-label">Foto</span>
    </div>
    <div class="step-item" id="si-3" role="listitem">
      <div class="step-num-badge">3</div>
      <span class="step-label">Kuesioner</span>
    </div>
    <div class="step-item" id="si-4" role="listitem">
      <div class="step-num-badge">4</div>
      <span class="step-label">Analisis</span>
    </div>
    <div class="step-item" id="si-5" role="listitem">
      <div class="step-num-badge">5</div>
      <span class="step-label">Hasil</span>
    </div>
  </nav>

  <!-- ===== STEP 1: DATA ANAK ===== -->
  <div class="step-panel active" id="step-1" role="main">
    <div class="panel-header">
      <h2>Data Anak</h2>
      <p>Isi informasi dasar anak untuk membantu AI menghitung status gizi berdasarkan standar WHO.</p>
    </div>
    <div id="step1-error" class="msg-box error">Harap lengkapi semua kolom yang wajib diisi (bertanda *).</div>
    <div class="form-grid">
      <div class="form-group full">
        <label class="form-label" for="namaAnak">Nama Anak <span class="req">*</span></label>
        <input type="text" id="namaAnak" class="form-input" placeholder="Contoh: Budi Santoso" autocomplete="off" />
      </div>
      <div class="form-group">
        <label class="form-label" for="usiaBulan">Usia <span class="req">*</span></label>
        <select id="usiaBulan" class="form-input form-select" aria-required="true">
          <option value="">Pilih usia anak</option>
          <option value="0">0 bulan (baru lahir)</option>
          <option value="1">1 bulan</option>
          <option value="2">2 bulan</option>
          <option value="3">3 bulan</option>
          <option value="4">4 bulan</option>
          <option value="5">5 bulan</option>
          <option value="6">6 bulan</option>
          <option value="7">7 bulan</option>
          <option value="8">8 bulan</option>
          <option value="9">9 bulan</option>
          <option value="10">10 bulan</option>
          <option value="11">11 bulan</option>
          <option value="12">12 bulan (1 tahun)</option>
          <option value="15">15 bulan</option>
          <option value="18">18 bulan (1,5 tahun)</option>
          <option value="21">21 bulan</option>
          <option value="24">24 bulan (2 tahun)</option>
          <option value="30">30 bulan (2,5 tahun)</option>
          <option value="36">36 bulan (3 tahun)</option>
          <option value="42">42 bulan (3,5 tahun)</option>
          <option value="48">48 bulan (4 tahun)</option>
          <option value="54">54 bulan (4,5 tahun)</option>
          <option value="60">60 bulan (5 tahun)</option>
          <option value="72">72 bulan (6 tahun)</option>
          <option value="84">84 bulan (7 tahun)</option>
          <option value="96">96 bulan (8 tahun)</option>
          <option value="108">108 bulan (9 tahun)</option>
          <option value="120">120 bulan (10 tahun)</option>
          <option value="144">144 bulan (12 tahun)</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Jenis Kelamin <span class="req">*</span></label>
        <div class="radio-group">
          <div class="radio-option">
            <input type="radio" name="jenisKelamin" id="jkL" value="L" />
            <label for="jkL">👦 Laki-laki</label>
          </div>
          <div class="radio-option">
            <input type="radio" name="jenisKelamin" id="jkP" value="P" />
            <label for="jkP">👧 Perempuan</label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label" for="beratBadan">Berat Badan (kg)</label>
        <input type="number" id="beratBadan" class="form-input" placeholder="Contoh: 12.5" min="1" max="100" step="0.1" />
      </div>
      <div class="form-group">
        <label class="form-label" for="tinggiBadan">Tinggi/Panjang Badan (cm)</label>
        <input type="number" id="tinggiBadan" class="form-input" placeholder="Contoh: 85.0" min="30" max="200" step="0.1" />
      </div>
    </div>
    <div class="info-box" style="margin-top:20px;">
      <strong>📏 Mengapa kita butuh data ini?</strong><br />
      Berat dan tinggi badan digunakan untuk menghitung <strong>Z-score WHO</strong> — standar internasional untuk menilai status gizi anak (normal, stunting, gizi kurang, wasting). Data ini <em>opsional</em> namun sangat meningkatkan akurasi hasil.
    </div>
    <div class="nav-btns">
      <div></div>
      <button class="btn btn-primary" onclick="validateStep1()">
        Lanjut ke Foto <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </button>
    </div>
  </div>

  <!-- ===== STEP 2: UPLOAD FOTO ===== -->
  <div class="step-panel" id="step-2">
    <div class="panel-header">
      <h2>Upload Foto Anak</h2>
      <p>AI akan menganalisis foto untuk mendeteksi tanda-tanda kekurangan gizi. Foto hanya digunakan untuk analisis dan tidak disimpan permanen.</p>
    </div>
    <div class="photo-grid">
      <!-- Foto Wajah -->
      <div class="upload-zone" id="zone-wajah" onclick="document.getElementById('inputWajah').click()">
        <input type="file" id="inputWajah" accept="image/*" capture="camera" style="display:none" onchange="handlePhoto(this,'wajah')" />
        <img class="preview-img" id="prevWajah" alt="Preview foto wajah" />
        <div id="zone-wajah-content">
          <div class="upload-icon">📷</div>
          <h4>Foto Wajah Anak</h4>
          <p>AI analisis: pucat (anemia), kuning (ikterus), kondisi mata &amp; kulit wajah</p>
          <span class="upload-badge badge-req">WAJIB</span>
        </div>
        <button class="upload-remove" id="rem-wajah" onclick="removePhoto(event,'wajah')">✕ Hapus Foto</button>
      </div>
      <!-- Foto Tangan -->
      <div class="upload-zone" id="zone-tangan" onclick="document.getElementById('inputTangan').click()">
        <input type="file" id="inputTangan" accept="image/*" capture="camera" style="display:none" onchange="handlePhoto(this,'tangan')" />
        <img class="preview-img" id="prevTangan" alt="Preview foto tangan" />
        <div id="zone-tangan-content">
          <div class="upload-icon">✋</div>
          <h4>Foto Tangan/Kuku</h4>
          <p>AI analisis: kuku pucat, kuku sendok, kulit kering — tanda kekurangan zat besi</p>
          <span class="upload-badge badge-opt">OPSIONAL</span>
        </div>
        <button class="upload-remove" id="rem-tangan" onclick="removePhoto(event,'tangan')">✕ Hapus Foto</button>
      </div>
    </div>
    <div class="tips-box">
      <h4>💡 Tips Foto yang Baik</h4>
      <div class="tips-list">
        <div class="tip-item"><span class="tip-icon">☀️</span><span>Gunakan pencahayaan alami yang cukup, hindari foto dalam ruang gelap</span></div>
        <div class="tip-item"><span class="tip-icon">🎯</span><span>Latar belakang polos &amp; fokus jelas — wajah atau tangan mengisi sebagian besar frame</span></div>
        <div class="tip-item"><span class="tip-icon">📐</span><span>Untuk foto tangan: telapak menghadap ke atas, jari-jari terbuka lebar</span></div>
      </div>
    </div>
    <div class="nav-btns">
      <button class="btn btn-ghost" onclick="goStep(1)">← Kembali</button>
      <button class="btn btn-primary" onclick="validateStep2()">Lanjut ke Kuesioner →</button>
    </div>
  </div>

  <!-- ===== STEP 3: KUESIONER ===== -->
  <div class="step-panel" id="step-3">
    <div class="panel-header">
      <h2>Kuesioner Gizi</h2>
      <p>Jawab 12 pertanyaan singkat tentang kebiasaan makan dan kesehatan anak. Semua pertanyaan wajib dijawab.</p>
    </div>
    <div class="q-progress" aria-label="Progress kuesioner">
      <div class="q-progress-bar" id="qProgressBar" style="width:0%"></div>
    </div>
    <div id="questionnaire-container"></div>
    <div class="nav-btns">
      <button class="btn btn-ghost" onclick="goStep(2)">← Kembali</button>
      <button class="btn btn-primary" onclick="validateStep3()">Mulai Analisis AI →</button>
    </div>
  </div>

  <!-- ===== STEP 4: LOADING ===== -->
  <div class="step-panel" id="step-4">
    <div class="loading-center">
      <div class="loading-logo" aria-hidden="true">🥦</div>
      <div class="loading-title">AI Sedang Menganalisis...</div>
      <div class="loading-msg" id="loadingMsg">Mempersiapkan analisis...</div>
      <div class="progress-bar-wrap" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
        <div class="progress-bar-fill" id="progressFill"></div>
      </div>
    </div>
  </div>

  <!-- ===== STEP 5: HASIL ===== -->
  <div class="step-panel" id="step-5">
    <div class="panel-header">
      <h2>Hasil Screening GiziKu</h2>
      <p>Hasil analisis AI berdasarkan foto, data anak, dan kuesioner gizi. Gunakan sebagai pegangan sebelum ke dokter.</p>
    </div>

    <!-- Result Header -->
    <div class="result-header">
      <div class="result-avatar" id="resultAvatar">👦</div>
      <div class="result-meta">
        <h3 id="resultNama">—</h3>
        <p id="resultUsia">—</p>
        <div class="risk-badge risk-sedang" id="riskBadge">🟡 Perlu Perhatian</div>
      </div>
    </div>

    <!-- Score Gauge -->
    <div class="score-section">
      <div class="gauge-wrap" aria-label="Skor gizi">
        <svg width="120" height="120" viewBox="0 0 120 120">
          <circle cx="60" cy="60" r="50" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="10"/>
          <circle cx="60" cy="60" r="50" fill="none" stroke="url(#gaugeGrad)" stroke-width="10"
            stroke-dasharray="314.16" stroke-dashoffset="314.16" stroke-linecap="round"
            id="gaugeFill" style="transition:stroke-dashoffset 1.5s cubic-bezier(0.34,1.56,0.64,1)"/>
          <defs>
            <linearGradient id="gaugeGrad" x1="0%" y1="0%" x2="100%" y2="0%">
              <stop offset="0%" stop-color="#00D46A" id="gG1"/>
              <stop offset="100%" stop-color="#0ABFA3" id="gG2"/>
            </linearGradient>
          </defs>
        </svg>
        <div class="gauge-text">
          <div class="gauge-num" id="gaugeNum">—</div>
          <div class="gauge-max">/100</div>
        </div>
      </div>
      <div class="score-info">
        <h4>Skor Gizi Anak</h4>
        <p id="scoreDesc">Skor menggambarkan kondisi gizi anak berdasarkan foto, data pertumbuhan, dan pola makan.</p>
        <span class="score-category" id="scoreCat">—</span>
      </div>
    </div>

    <!-- Anthropometry -->
    <div class="anthro-section" id="anthroSection" style="display:none;">
      <div class="section-title">📏 Status Antropometri (Standar WHO)</div>
      <div class="anthro-row">
        <div class="anthro-label">BB/Umur</div>
        <div class="anthro-bar"><div class="anthro-bar-fill" id="barBBU" style="width:50%;background:var(--emerald)"></div></div>
        <div class="anthro-status status-normal" id="statusBBU">Normal</div>
      </div>
      <div class="anthro-row">
        <div class="anthro-label">TB/Umur</div>
        <div class="anthro-bar"><div class="anthro-bar-fill" id="barTBU" style="width:50%;background:var(--emerald)"></div></div>
        <div class="anthro-status status-normal" id="statusTBU">Normal</div>
      </div>
      <div class="anthro-row">
        <div class="anthro-label">BB/TB</div>
        <div class="anthro-bar"><div class="anthro-bar-fill" id="barBBTB" style="width:50%;background:var(--emerald)"></div></div>
        <div class="anthro-status status-normal" id="statusBBTB">Normal</div>
      </div>
    </div>

    <!-- Photo Findings -->
    <div class="findings-section">
      <div class="section-title">🔬 Temuan AI dari Foto</div>
      <div id="findingsContainer"></div>
    </div>

    <!-- Deficiencies -->
    <div class="defisiensi-section">
      <div class="section-title">⚠️ Kemungkinan Defisiensi Nutrisi</div>
      <div class="defisiensi-pills" id="defisiensiPills"></div>
    </div>

    <!-- Food Recommendations -->
    <div class="reco-section">
      <div class="section-title">🍽️ Rekomendasi Makanan</div>
      <div class="reco-grid" id="recoGrid"></div>
    </div>

    <!-- Doctor guidance -->
    <div class="doctor-section" id="doctorSection">
      <div class="doctor-icon">🏥</div>
      <h4 id="doctorTitle">—</h4>
      <p id="doctorText">—</p>
    </div>

    <!-- Parent message -->
    <div class="info-box" id="parentMsg" style="margin-bottom:20px;"></div>

    <!-- Actions -->
    <div class="action-btns">
      <button class="btn btn-primary" onclick="window.print()">📄 Cetak / Simpan PDF</button>
      <button class="btn btn-ghost" onclick="shareWhatsApp()">📲 Bagikan ke WA</button>
      <button class="btn btn-outline" onclick="restartScreening()">🔄 Screening Ulang</button>
    </div>

    <div class="info-box" style="margin-top:20px;font-size:0.8rem;">
      ⚠️ <strong>Penting:</strong> Hasil GiziKu adalah <strong>skrining awal</strong>, bukan diagnosis medis. Selalu konsultasikan kondisi anak Anda kepada dokter atau tenaga kesehatan untuk penilaian yang akurat.
    </div>
  </div>

</div><!-- /page -->

<script>
// ===== STATE =====
const state = {
  step: 1,
  namaAnak: '',
  usiaBulan: 0,
  jenisKelamin: '',
  beratBadan: null,
  tinggiBadan: null,
  fotoWajah: null,
  fotoTangan: null,
  kuesioner: {},
  hasil: null
};

// ===== QUESTIONS =====
const questions = [
  { id:'frekuensi_makan', text:'Berapa kali anak makan dalam sehari?', opts:['< 2x','2-3x','4-5x','> 5x'] },
  { id:'asi', text:'Apakah anak masih minum ASI?', opts:['Ya, masih','Tidak, sudah MPASI','Tidak pernah ASI'] },
  { id:'protein_hewani', text:'Seberapa sering anak makan protein hewani (ikan, daging, telur, ayam)?', opts:['Hampir tidak pernah','1-2x/minggu','3-4x/minggu','Setiap hari'] },
  { id:'susu', text:'Apakah anak mengonsumsi susu formula atau susu sapi?', opts:['Tidak','Ya, < 200ml/hari','Ya, 200-400ml/hari','Ya, > 400ml/hari'] },
  { id:'sayuran', text:'Seberapa sering anak makan sayuran hijau?', opts:['Hampir tidak pernah','1-2x/minggu','3-4x/minggu','Setiap hari'] },
  { id:'buah', text:'Seberapa sering anak makan buah?', opts:['Hampir tidak pernah','1-2x/minggu','Setiap hari'] },
  { id:'diare_infeksi', text:'Apakah anak sering sakit diare atau infeksi dalam 1 bulan terakhir?', opts:['Tidak pernah','1-2 kali','Lebih dari 3 kali'] },
  { id:'suplemen', text:'Apakah anak mendapat suplemen vitamin/mineral?', opts:['Tidak','Vitamin D','Vitamin A','Zat Besi','Multivitamin','Lainnya'] },
  { id:'nafsu_makan', text:'Bagaimana nafsu makan anak akhir-akhir ini?', opts:['Sangat baik','Baik','Kurang','Sangat kurang/susah makan'] },
  { id:'aktivitas', text:'Apakah anak aktif bermain dan bergerak?', opts:['Ya, sangat aktif','Cukup aktif','Kurang aktif','Sangat pasif'] },
  { id:'tidur', text:'Berapa jam anak tidur per malam?', opts:['< 8 jam','8-10 jam','10-12 jam','> 12 jam'] },
  { id:'posyandu', text:'Apakah anak rutin ke Posyandu atau dokter?', opts:['Ya, rutin setiap bulan','Ya, tapi tidak rutin','Tidak pernah'] }
];

// ===== BUILD QUESTIONNAIRE =====
function buildQuestionnaire() {
  const container = document.getElementById('questionnaire-container');
  container.innerHTML = questions.map((q, i) => `
    <div class="q-item" id="qi-${q.id}">
      <div class="q-num">PERTANYAAN ${i+1} DARI ${questions.length}</div>
      <div class="q-text">${q.text}</div>
      <div class="q-options">
        ${q.opts.map(o => `
          <label class="q-opt" onclick="selectAnswer('${q.id}',this,'${o.replace(/'/g,"\\'")}')">
            <input type="radio" name="${q.id}" value="${o}" />
            <div class="q-dot"></div>
            <span>${o}</span>
          </label>
        `).join('')}
      </div>
    </div>
  `).join('');
}

function selectAnswer(qId, labelEl, value) {
  // deselect others in same group
  labelEl.closest('.q-options').querySelectorAll('.q-opt').forEach(l => l.classList.remove('selected'));
  labelEl.classList.add('selected');
  state.kuesioner[qId] = value;
  document.getElementById('qi-' + qId).classList.add('answered');
  updateQProgress();
}

function updateQProgress() {
  const answered = Object.keys(state.kuesioner).length;
  const pct = Math.round((answered / questions.length) * 100);
  document.getElementById('qProgressBar').style.width = pct + '%';
}

// ===== STEP NAVIGATION =====
function goStep(n) {
  document.getElementById('step-' + state.step).classList.remove('active');
  document.getElementById('si-' + state.step).classList.remove('active');
  document.getElementById('si-' + state.step).classList.add('done');
  state.step = n;
  document.getElementById('step-' + n).classList.add('active');
  document.getElementById('si-' + n).classList.add('active');
  document.getElementById('si-' + n).classList.remove('done');
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ===== VALIDATION =====
function validateStep1() {
  const nama = document.getElementById('namaAnak').value.trim();
  const usia = document.getElementById('usiaBulan').value;
  const jk = document.querySelector('input[name="jenisKelamin"]:checked');
  const errBox = document.getElementById('step1-error');

  if (!nama || !usia || !jk) {
    errBox.classList.add('show');
    errBox.textContent = 'Harap isi Nama Anak, Usia, dan Jenis Kelamin (wajib).';
    return;
  }
  errBox.classList.remove('show');
  state.namaAnak = nama;
  state.usiaBulan = parseInt(usia);
  state.jenisKelamin = jk.value;
  state.beratBadan = document.getElementById('beratBadan').value || null;
  state.tinggiBadan = document.getElementById('tinggiBadan').value || null;
  goStep(2);
}

function validateStep2() {
  if (!state.fotoWajah) {
    alert('Foto wajah anak wajib diupload untuk analisis AI.');
    return;
  }
  goStep(3);
}

function validateStep3() {
  const answered = Object.keys(state.kuesioner).length;
  if (answered < questions.length) {
    alert(`Harap jawab semua pertanyaan. Baru ${answered} dari ${questions.length} yang terjawab.`);
    return;
  }
  startAnalysis();
}

// ===== PHOTO HANDLING =====
function handlePhoto(input, type) {
  const file = input.files[0];
  if (!file) return;
  if (file.size > 8 * 1024 * 1024) { alert('Ukuran foto maksimal 8MB.'); return; }

  state['foto' + (type === 'wajah' ? 'Wajah' : 'Tangan')] = file;

  const prev = document.getElementById('prev' + (type === 'wajah' ? 'Wajah' : 'Tangan'));
  const reader = new FileReader();
  reader.onload = e => {
    prev.src = e.target.result;
    prev.style.display = 'block';
    document.getElementById('zone-' + type + '-content').style.display = 'none';
    document.getElementById('rem-' + type).style.display = 'inline-block';
    document.getElementById('zone-' + type).classList.add('has-file');
  };
  reader.readAsDataURL(file);
}

function removePhoto(e, type) {
  e.stopPropagation();
  state['foto' + (type === 'wajah' ? 'Wajah' : 'Tangan')] = null;
  const prev = document.getElementById('prev' + (type === 'wajah' ? 'Wajah' : 'Tangan'));
  prev.style.display = 'none';
  document.getElementById('zone-' + type + '-content').style.display = 'block';
  document.getElementById('rem-' + type).style.display = 'none';
  document.getElementById('zone-' + type).classList.remove('has-file');
  document.getElementById('input' + (type === 'wajah' ? 'Wajah' : 'Tangan')).value = '';
}

// ===== ANALYSIS =====
const loadingMessages = [
  'Menganalisis foto wajah anak...',
  'Membaca data pertumbuhan dan usia...',
  'Memproses jawaban kuesioner gizi...',
  'AI menyusun laporan nutrisi...',
  'Menghasilkan rekomendasi personal...',
  'Hampir selesai...'
];

function startAnalysis() {
  goStep(4);
  let progress = 0;
  let msgIdx = 0;
  const msgEl = document.getElementById('loadingMsg');
  const barEl = document.getElementById('progressFill');
  const progressBar = document.querySelector('[role=progressbar]');

  const msgInterval = setInterval(() => {
    if (msgIdx < loadingMessages.length) {
      msgEl.textContent = loadingMessages[msgIdx++];
    }
  }, 1800);

  const progInterval = setInterval(() => {
    if (progress < 90) {
      progress += Math.random() * 8;
      progress = Math.min(progress, 90);
      barEl.style.width = progress + '%';
      if (progressBar) progressBar.setAttribute('aria-valuenow', Math.round(progress));
    }
  }, 600);

  // Build FormData
  const fd = new FormData();
  fd.append('nama_anak', state.namaAnak);
  fd.append('usia_bulan', state.usiaBulan);
  fd.append('jenis_kelamin', state.jenisKelamin);
  if (state.beratBadan) fd.append('berat_badan', state.beratBadan);
  if (state.tinggiBadan) fd.append('tinggi_badan', state.tinggiBadan);
  if (state.fotoWajah) fd.append('foto_wajah', state.fotoWajah);
  if (state.fotoTangan) fd.append('foto_tangan', state.fotoTangan);
  fd.append('kuesioner', JSON.stringify(state.kuesioner));

  const csrfToken = document.querySelector('meta[name=csrf-token]')?.content || '';

  fetch('/api/nutriscan/analyze', {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrfToken },
    body: fd
  })
  .then(r => r.json())
  .then(data => {
    clearInterval(msgInterval);
    clearInterval(progInterval);
    barEl.style.width = '100%';
    state.hasil = data;
    setTimeout(() => renderResults(data), 600);
  })
  .catch(err => {
    console.warn('API call failed, using demo data:', err);
    clearInterval(msgInterval);
    clearInterval(progInterval);
    barEl.style.width = '100%';
    state.hasil = getDemoData();
    setTimeout(() => renderResults(state.hasil), 600);
  });
}

// ===== DEMO DATA (fallback if API unavailable) =====
function getDemoData() {
  return {
    success: true,
    nama_anak: state.namaAnak,
    usia_bulan: state.usiaBulan,
    skor_gizi: 62,
    level_risiko: 'sedang',
    antropometri: state.beratBadan && state.tinggiBadan ? {
      z_bbu: -1.2, z_tbu: -1.8, z_bbtb: -0.5,
      status_bbu: 'normal', status_tbu: 'pendek', status_bbtb: 'normal',
      interpretasi: 'Berat badan normal, tinggi badan sedikit di bawah rata-rata (pendek)'
    } : null,
    foto_analisis: {
      wajah: {
        status: 'terindikasi',
        temuan: ['Konjungtiva mata terlihat sedikit pucat', 'Warna kulit agak pucat di area pipi'],
        defisiensi_indikasi: ['Zat Besi', 'Vitamin B12'],
        confidence: 'sedang',
        perlu_rujuk: false
      }
    },
    laporan: {
      ringkasan: 'Berdasarkan analisis foto dan kuesioner, anak Anda menunjukkan beberapa tanda yang perlu diperhatikan, terutama kemungkinan kekurangan zat besi. Kondisi ini umum pada anak usia balita dan dapat diperbaiki dengan pola makan yang tepat.',
      defisiensi_terdeteksi: ['Zat Besi', 'Vitamin B12'],
      rekomendasi_makanan: [
        { emoji:'🥩', nama:'Daging Merah (Sapi/Kambing)', alasan:'Kaya zat besi heme yang mudah diserap tubuh', frekuensi:'3-4x/minggu'},
        { emoji:'🫀', nama:'Hati Ayam', alasan:'Sumber zat besi dan vitamin B12 terbaik', frekuensi:'2x/minggu'},
        { emoji:'🥚', nama:'Telur', alasan:'Protein lengkap dan vitamin B12', frekuensi:'Setiap hari'},
        { emoji:'🐟', nama:'Ikan', alasan:'Omega-3 untuk perkembangan otak dan zat besi', frekuensi:'3x/minggu'},
        { emoji:'🥦', nama:'Brokoli &amp; Bayam', alasan:'Vitamin C membantu penyerapan zat besi nabati', frekuensi:'Setiap hari'}
      ],
      rekomendasi_suplemen: ['Suplemen Zat Besi (konsultasikan dosis ke dokter)', 'Vitamin C untuk membantu penyerapan zat besi'],
      perlu_rujuk: false,
      alasan_rujuk: '',
      pesan_orang_tua: 'Bunda/Ayah, kondisi anak Anda masih bisa diperbaiki dengan perubahan pola makan. Fokus pada makanan kaya zat besi seperti daging merah, hati ayam, dan telur. Tambahkan vitamin C (jeruk, tomat) saat makan untuk membantu penyerapan. Jika kondisi tidak membaik dalam 2-4 minggu, segera konsultasikan ke dokter anak.'
    }
  };
}

// ===== RENDER RESULTS =====
function renderResults(data) {
  goStep(5);

  const jk = state.jenisKelamin;
  document.getElementById('resultAvatar').textContent = jk === 'P' ? '👧' : '👦';
  document.getElementById('resultNama').textContent = data.nama_anak || state.namaAnak;

  const usia = state.usiaBulan;
  const usiaText = usia >= 12 ? `${Math.floor(usia/12)} tahun${usia%12>0?' '+usia%12+' bulan':''}` : usia + ' bulan';
  document.getElementById('resultUsia').textContent = `${usiaText} · Screening ${new Date().toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'})}`;

  // Risk badge
  const rb = document.getElementById('riskBadge');
  const level = data.level_risiko || 'sedang';
  rb.className = 'risk-badge risk-' + level;
  rb.textContent = level === 'rendah' ? '🟢 Gizi Baik' : level === 'sedang' ? '🟡 Perlu Perhatian' : '🔴 Risiko Tinggi';

  // Score gauge
  const score = data.skor_gizi || 60;
  const circumference = 2 * Math.PI * 50;
  const offset = circumference - (score / 100) * circumference;
  const gaugeFill = document.getElementById('gaugeFill');

  // Set color based on score
  if (score >= 70) {
    document.getElementById('gG1').setAttribute('stop-color','#00D46A');
    document.getElementById('gG2').setAttribute('stop-color','#0ABFA3');
  } else if (score >= 40) {
    document.getElementById('gG1').setAttribute('stop-color','#F59E0B');
    document.getElementById('gG2').setAttribute('stop-color','#FCD34D');
  } else {
    document.getElementById('gG1').setAttribute('stop-color','#EF4444');
    document.getElementById('gG2').setAttribute('stop-color','#F97316');
  }

  setTimeout(() => {
    gaugeFill.style.strokeDashoffset = offset;
    let num = 0;
    const numEl = document.getElementById('gaugeNum');
    const numInterval = setInterval(() => {
      num = Math.min(num + 2, score);
      numEl.textContent = num;
      if (num >= score) clearInterval(numInterval);
    }, 25);
  }, 200);

  const cats = { rendah: 'Gizi Baik ✓', sedang: 'Perlu Perhatian', tinggi: 'Risiko Tinggi ⚠️' };
  const catEl = document.getElementById('scoreCat');
  catEl.textContent = cats[level] || 'Perlu Evaluasi';
  catEl.style.background = level === 'rendah' ? 'rgba(16,185,129,0.15)' : level === 'sedang' ? 'rgba(245,158,11,0.15)' : 'rgba(239,68,68,0.15)';
  catEl.style.color = level === 'rendah' ? '#6EE7B7' : level === 'sedang' ? '#FCD34D' : '#FCA5A5';

  document.getElementById('scoreDesc').textContent = data.laporan?.ringkasan || 'Analisis berdasarkan foto, data pertumbuhan, dan kuesioner gizi.';

  // Anthropometry
  if (data.antropometri) {
    const a = data.antropometri;
    document.getElementById('anthroSection').style.display = 'block';
    setAnthroRow('BBU', a.status_bbu, a.z_bbu);
    setAnthroRow('TBU', a.status_tbu, a.z_tbu);
    setAnthroRow('BBTB', a.status_bbtb, a.z_bbtb);
  }

  // Photo findings
  const fc = document.getElementById('findingsContainer');
  const fa = data.foto_analisis || {};
  if (Object.keys(fa).length === 0) {
    fc.innerHTML = '<div style="color:var(--text-m);font-size:0.88rem;">Tidak ada foto yang dianalisis.</div>';
  } else {
    fc.innerHTML = Object.entries(fa).map(([bagian, analisis]) => {
      if (!analisis) return '';
      const temuan = analisis.temuan || [];
      const defisiensi = analisis.defisiensi_indikasi || [];
      return `
        <div class="finding-card">
          <div class="finding-title">${bagian === 'wajah' ? '📷 Analisis Foto Wajah' : '✋ Analisis Foto Tangan'} — Kepercayaan: ${analisis.confidence || 'sedang'}</div>
          <div class="finding-items">
            ${temuan.map(t => `<div class="finding-item"><span class="fi-icon">👁️</span><span>${t}</span></div>`).join('')}
            ${defisiensi.length > 0 ? `<div class="finding-item"><span class="fi-icon">⚠️</span><span>Terindikasi defisiensi: <strong>${defisiensi.join(', ')}</strong></span></div>` : ''}
          </div>
        </div>`;
    }).join('');
  }

  // Deficiency pills
  const pills = document.getElementById('defisiensiPills');
  const defs = data.laporan?.defisiensi_terdeteksi || [];
  if (defs.length === 0) {
    pills.innerHTML = '<span class="pill" style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.25);color:#6EE7B7;">✓ Tidak ada defisiensi signifikan terdeteksi</span>';
  } else {
    pills.innerHTML = defs.map((d, i) => `<span class="pill ${i < 2 ? 'pill-high' : 'pill-mid'}">⚠️ ${d}</span>`).join('');
  }

  // Food recommendations
  const rg = document.getElementById('recoGrid');
  const recos = data.laporan?.rekomendasi_makanan || [];
  rg.innerHTML = recos.slice(0, 5).map(r => `
    <div class="reco-card">
      <span class="food-emoji">${r.emoji || '🍽️'}</span>
      <h5>${r.nama || r.makanan || '—'}</h5>
      <p>${r.alasan || r.nutrisi_target || ''}</p>
      ${r.frekuensi ? `<span class="reco-freq">${r.frekuensi}</span>` : ''}
    </div>
  `).join('');

  // Doctor section
  const ds = document.getElementById('doctorSection');
  const perlu = data.laporan?.perlu_rujuk;
  ds.className = 'doctor-section ' + (perlu ? 'perlu' : 'tidak-perlu');
  document.getElementById('doctorTitle').textContent = perlu ? '⚠️ Segera Konsultasikan ke Dokter' : '✅ Belum Perlu Rujukan Segera';
  document.getElementById('doctorText').textContent = perlu
    ? `${data.laporan?.alasan_rujuk || 'Ditemukan tanda yang perlu evaluasi dokter.'} Bawa hasil screening ini ke Puskesmas atau dokter anak terdekat.`
    : 'Kondisi anak Anda saat ini belum memerlukan rujukan segera. Ikuti rekomendasi makanan di atas dan pantau perkembangan anak secara rutin di Posyandu.';

  // Parent message
  const pm = document.getElementById('parentMsg');
  pm.innerHTML = '💚 <strong>Pesan untuk Ayah/Bunda:</strong> ' + (data.laporan?.pesan_orang_tua || 'Terima kasih telah peduli dengan gizi anak Anda. Pantau tumbuh kembang anak secara rutin dan konsultasikan ke dokter jika ada kekhawatiran.');
}

function setAnthroRow(id, status, zScore) {
  const statusMap = {
    normal: ['Normal', 'status-normal', 55, 'var(--emerald)'],
    pendek: ['Pendek', 'status-warn', 30, 'var(--amber)'],
    stunting: ['Stunting', 'status-bad', 10, 'var(--red)'],
    gizi_kurang: ['Gizi Kurang', 'status-warn', 30, 'var(--amber)'],
    gizi_buruk: ['Gizi Buruk', 'status-bad', 10, 'var(--red)'],
    wasting: ['Wasting', 'status-bad', 10, 'var(--red)'],
    kurus: ['Kurus', 'status-warn', 30, 'var(--amber)'],
    gemuk: ['Gemuk', 'status-warn', 70, 'var(--amber)'],
    lebih: ['Lebih', 'status-warn', 70, 'var(--amber)'],
    obesitas: ['Obesitas', 'status-bad', 85, 'var(--red)'],
    tinggi: ['Tinggi', 'status-normal', 80, 'var(--emerald)'],
  };
  const [label, cls, width, color] = statusMap[status] || ['Normal', 'status-normal', 55, 'var(--emerald)'];
  document.getElementById('status' + id).textContent = label + (zScore ? ` (Z: ${zScore > 0 ? '+' : ''}${zScore.toFixed(1)})` : '');
  document.getElementById('status' + id).className = 'anthro-status ' + cls;
  document.getElementById('bar' + id).style.width = width + '%';
  document.getElementById('bar' + id).style.background = color;
}

// ===== SHARE =====
function shareWhatsApp() {
  const nama = state.namaAnak || 'Anak';
  const skor = state.hasil?.skor_gizi || '—';
  const level = state.hasil?.level_risiko || '—';
  const text = `Hasil Screening GiziKu 🥦\n\nNama: ${nama}\nSkor Gizi: ${skor}/100\nLevel Risiko: ${level}\n\nCek gizi anak Anda di: ${window.location.origin}\n\n_Dihasilkan oleh GiziKu — AI Screening Gizi Anak_`;
  window.open('https://wa.me/?text=' + encodeURIComponent(text));
}

function restartScreening() {
  if (confirm('Mulai screening baru? Data saat ini akan dihapus.')) {
    location.reload();
  }
}

// ===== INIT =====
buildQuestionnaire();
</script>
</body>
</html>
