<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>GiziKu — Deteksi Dini Gizi Anak, Cegah Stunting dari Rumah</title>
<meta name="description" content="GiziKu membantu orang tua mendeteksi dini status gizi anak melalui foto dan kuesioner singkat menggunakan AI. Gratis, mudah, hasil dalam 2 menit." />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet" />
<style>
/* === RESET & ROOT === */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html{scroll-behavior:smooth;font-size:16px;}
body{
  font-family:'Plus Jakarta Sans',sans-serif;
  background:#050E0A;
  color:#E8F5EE;
  overflow-x:hidden;
  -webkit-font-smoothing:antialiased;
  -moz-osx-font-smoothing:grayscale;
}
a{text-decoration:none;color:inherit;}
ul{list-style:none;}
img{max-width:100%;display:block;}
button{font-family:inherit;cursor:pointer;}
@media(prefers-reduced-motion:reduce){
  *{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;}
}

/* === DESIGN TOKENS === */
:root{
  --shadow:0 25px 60px -15px rgba(0,0,0,0.7);
  --glow-g:0 0 40px rgba(0,212,106,0.15);
  --glow-t:0 0 40px rgba(10,191,163,0.15);
}

/* SCROLLBAR */
::-webkit-scrollbar{width:6px;}
::-webkit-scrollbar-track{background:#050E0A;}
::-webkit-scrollbar-thumb{background:rgba(0,212,106,0.3);border-radius:6px;}

/* ===== ANIMATED BG ORBS ===== */
.bg-orbs{position:fixed;inset:0;pointer-events:none;z-index:0;overflow:hidden;}
.orb{position:absolute;border-radius:50%;filter:blur(120px);opacity:0.25;animation:floatOrb 10s ease-in-out infinite;}
.orb-1{width:700px;height:700px;background:radial-gradient(circle,#003d1a,transparent);top:-200px;left:-150px;animation-delay:0s;}
.orb-2{width:500px;height:500px;background:radial-gradient(circle,#00312a,transparent);top:40%;right:-100px;animation-delay:4s;}
.orb-3{width:450px;height:450px;background:radial-gradient(circle,#001a2c,transparent);bottom:-100px;left:35%;animation-delay:7s;}
@keyframes floatOrb{0%,100%{transform:translateY(0)scale(1);}50%{transform:translateY(-30px)scale(1.06);}}

/* ===== NAVBAR ===== */
.navbar{
  position:fixed;top:16px;left:50%;transform:translateX(-50%);
  width:min(1100px,calc(100% - 32px));z-index:200;
  background:rgba(5,14,10,0.7);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
  border:1px solid rgba(0,212,106,0.12);border-radius:100px;
  display:flex;align-items:center;justify-content:space-between;padding:10px 12px 10px 24px;
  transition:box-shadow .3s,background .3s;
}
.navbar.scrolled{box-shadow:0 8px 40px rgba(0,0,0,0.5),0 0 0 1px rgba(0,212,106,0.15);}
.nav-logo{display:flex;align-items:center;gap:10px;font-weight:800;font-size:1.15rem;color:var(--text);}
.logo-icon{width:32px;height:32px;background:linear-gradient(135deg,var(--green),var(--teal));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1rem;}
.logo-dot{color:var(--green);}
.nav-links{display:flex;gap:4px;align-items:center;}
.nav-links a{font-size:0.88rem;font-weight:500;color:var(--text-m);padding:8px 16px;border-radius:100px;transition:background .2s,color .2s;}
.nav-links a:hover{background:rgba(0,212,106,0.1);color:var(--green);}
.nav-cta{background:linear-gradient(135deg,var(--green),var(--teal));color:#050E0A!important;padding:10px 22px!important;border-radius:100px;font-weight:700!important;white-space:nowrap;box-shadow:0 4px 20px rgba(0,212,106,0.3);transition:transform .2s,box-shadow .2s!important;}
.nav-cta:hover{transform:translateY(-1px);box-shadow:0 6px 28px rgba(0,212,106,0.45)!important;}
.nav-toggle{display:none;background:none;border:none;cursor:pointer;padding:8px;color:var(--text);}
.nav-toggle svg{width:22px;height:22px;}

/* ===== HERO ===== */
.hero{position:relative;z-index:1;padding:160px 0 100px;min-height:100vh;display:flex;align-items:center;}
.wrap{max-width:1100px;margin:0 auto;padding:0 24px;}
.hero-inner{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;}
.hero-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(0,212,106,0.08);border:1px solid rgba(0,212,106,0.25);border-radius:100px;padding:6px 16px;margin-bottom:24px;font-size:0.75rem;font-weight:600;color:var(--green);letter-spacing:0.08em;text-transform:uppercase;}
.badge-dot{width:7px;height:7px;background:var(--green);border-radius:50%;animation:badgePulse 2s ease-in-out infinite;}
@keyframes badgePulse{0%{box-shadow:0 0 0 0 rgba(0,212,106,0.5);}70%{box-shadow:0 0 0 8px rgba(0,212,106,0);}100%{box-shadow:0 0 0 0 rgba(0,212,106,0);}}
.hero h1{font-size:clamp(2.4rem,4vw,3.6rem);font-weight:800;line-height:1.1;margin-bottom:20px;color:#fff;}
.hero h1 .accent{background:linear-gradient(135deg,var(--green),var(--teal));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.hero-sub{font-size:1.05rem;color:var(--text-m);line-height:1.7;margin-bottom:36px;max-width:460px;}
.hero-btns{display:flex;gap:14px;flex-wrap:wrap;margin-bottom:44px;}
.btn{display:inline-flex;align-items:center;gap:8px;padding:14px 28px;border-radius:100px;font-weight:700;font-size:0.95rem;cursor:pointer;border:none;transition:transform .2s,box-shadow .2s;font-family:inherit;}
.btn-primary{background:linear-gradient(135deg,var(--green),var(--teal));color:#050E0A;box-shadow:0 8px 28px rgba(0,212,106,0.35);}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 12px 36px rgba(0,212,106,0.5);}
.btn-ghost{background:rgba(255,255,255,0.05);color:var(--text);border:1px solid var(--border);}
.btn-ghost:hover{background:rgba(0,212,106,0.08);border-color:rgba(0,212,106,0.3);color:var(--green);}
.hero-badges{display:flex;gap:12px;flex-wrap:wrap;}
.h-badge{display:flex;align-items:center;gap:8px;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:100px;padding:8px 16px;font-size:0.82rem;font-weight:500;color:var(--text-m);}
.h-badge .dot{width:8px;height:8px;border-radius:50%;}
.dot-green{background:var(--green);}
.dot-amber{background:var(--amber);}
.dot-teal{background:var(--teal);}

/* Hero visual */
.hero-visual{position:relative;}
.hero-card{background:rgba(255,255,255,0.04);border:1px solid rgba(0,212,106,0.15);border-radius:var(--r-xl);padding:28px;box-shadow:var(--shadow),var(--glow-g);}
.hcard-top{display:flex;align-items:center;gap:14px;margin-bottom:22px;}
.hcard-avatar{width:44px;height:44px;background:linear-gradient(135deg,var(--green),var(--teal));border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;}
.hcard-info h4{font-size:0.95rem;font-weight:700;color:#fff;}
.hcard-info p{font-size:0.78rem;color:var(--text-m);}
.hcard-score{display:flex;justify-content:space-between;align-items:center;background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:var(--r-md);padding:16px 20px;margin-bottom:16px;}
.score-label{font-size:0.82rem;color:var(--text-m);}
.score-val{font-size:2rem;font-weight:800;background:linear-gradient(135deg,var(--green),var(--teal));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.score-ring{width:60px;height:60px;position:relative;}
.score-ring svg{transform:rotate(-90deg);}
.hcard-items{display:flex;flex-direction:column;gap:10px;}
.hcard-item{display:flex;align-items:center;gap:12px;font-size:0.85rem;color:var(--text-m);}
.hcard-item .chk{width:20px;height:20px;border-radius:6px;background:rgba(0,212,106,0.15);border:1px solid rgba(0,212,106,0.3);display:flex;align-items:center;justify-content:center;font-size:0.65rem;color:var(--green);flex-shrink:0;}
.hcard-item.warn .chk{background:rgba(245,158,11,0.15);border-color:rgba(245,158,11,0.3);color:var(--amber);}
.floating-badge{position:absolute;background:rgba(5,14,10,0.9);border:1px solid rgba(0,212,106,0.3);border-radius:var(--r-md);padding:12px 16px;box-shadow:0 8px 32px rgba(0,0,0,0.4);animation:float 5s ease-in-out infinite;}
.fb-1{top:-20px;left:-30px;animation-delay:0s;}
.fb-2{bottom:-16px;right:-24px;animation-delay:2.5s;}
@keyframes float{0%,100%{transform:translateY(0);}50%{transform:translateY(-10px);}}
.fb-label{font-size:0.7rem;color:var(--text-m);margin-bottom:4px;}
.fb-val{font-size:0.95rem;font-weight:700;color:var(--green);}

/* ===== SECTION COMMON ===== */
section{position:relative;z-index:1;}
.section-tag{display:inline-block;font-size:0.72rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:var(--green);margin-bottom:12px;}
.section-head{text-align:center;margin-bottom:56px;}
.section-head h2{font-size:clamp(1.9rem,3vw,2.5rem);font-weight:800;color:#fff;margin-bottom:14px;line-height:1.2;}
.section-head p{color:var(--text-m);font-size:1rem;max-width:560px;margin:0 auto;line-height:1.7;}

/* ===== STATS SECTION ===== */
.stats-section{padding:80px 0;}
.stats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
.stat-card{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:36px 28px;position:relative;overflow:hidden;transition:transform .3s,box-shadow .3s;}
.stat-card:hover{transform:translateY(-6px);box-shadow:var(--shadow),var(--glow-g);}
.stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--green),var(--teal));}
.stat-card.high::before{background:linear-gradient(90deg,var(--red),#F97316);}
.stat-card.mid::before{background:linear-gradient(90deg,var(--amber),#F97316);}
.stat-num{font-size:3rem;font-weight:800;color:#fff;margin-bottom:6px;line-height:1;}
.stat-num span{font-size:1.5rem;color:var(--green);}
.stat-card.high .stat-num span{color:var(--red);}
.stat-card.mid .stat-num span{color:var(--amber);}
.stat-label{font-size:1.05rem;font-weight:700;color:var(--text);margin-bottom:8px;}
.stat-desc{font-size:0.85rem;color:var(--text-m);line-height:1.6;}
.stat-src{display:inline-block;margin-top:16px;font-size:0.72rem;color:var(--text-d);font-weight:600;letter-spacing:0.06em;background:rgba(255,255,255,0.04);padding:4px 10px;border-radius:100px;}

/* ===== MAP SECTION ===== */
.map-section{padding:80px 0 100px;}
.map-wrapper{display:grid;grid-template-columns:1.1fr 0.9fr;gap:32px;align-items:start;}
.map-container{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:28px;min-height:520px;}
.map-toolbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:gap;}
.map-toolbar h3{font-size:1rem;font-weight:700;color:#fff;}
.map-filter{background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:var(--r-md);padding:6px 14px;font-size:0.82rem;color:var(--text-m);cursor:pointer;font-family:inherit;outline:none;}
.map-filter option{background:#0D1F13;}
.map-legend{display:flex;gap:16px;margin-bottom:20px;flex-wrap:wrap;}
.legend-item{display:flex;align-items:center;gap:6px;font-size:0.75rem;color:var(--text-m);}
.legend-dot{width:10px;height:10px;border-radius:50%;}

/* Province bubble grid */
.province-grid{display:flex;flex-direction:column;gap:8px;}
.region-row{display:flex;flex-direction:column;gap:6px;}
.region-label{font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-d);padding:0 4px;}
.region-bubbles{display:flex;flex-wrap:wrap;gap:6px;}
.province-bubble{
  display:flex;align-items:center;justify-content:center;
  border-radius:100px;padding:5px 12px;
  font-size:0.72rem;font-weight:600;cursor:pointer;
  transition:transform .2s,box-shadow .2s,opacity .2s;
  border:1px solid transparent;white-space:nowrap;
  position:relative;
}
.province-bubble:hover{transform:translateY(-2px);opacity:1!important;}
.province-bubble.active{border-color:rgba(255,255,255,0.5)!important;box-shadow:0 0 0 2px rgba(255,255,255,0.2);}
.province-bubble.high{background:rgba(239,68,68,0.18);border-color:rgba(239,68,68,0.3);color:#FCA5A5;}
.province-bubble.high:hover{box-shadow:0 4px 20px rgba(239,68,68,0.3);}
.province-bubble.mid{background:rgba(245,158,11,0.18);border-color:rgba(245,158,11,0.3);color:#FCD34D;}
.province-bubble.mid:hover{box-shadow:0 4px 20px rgba(245,158,11,0.3);}
.province-bubble.low{background:rgba(16,185,129,0.18);border-color:rgba(16,185,129,0.3);color:#6EE7B7;}
.province-bubble.low:hover{box-shadow:0 4px 20px rgba(16,185,129,0.3);}

/* Map Info Panel */
.map-info{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:28px;position:sticky;top:100px;}
.info-header{margin-bottom:20px;}
.info-province-name{font-size:1.4rem;font-weight:800;color:#fff;margin-bottom:4px;}
.info-region{font-size:0.8rem;color:var(--text-m);}
.info-main-stat{background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:var(--r-md);padding:20px;margin-bottom:16px;text-align:center;}
.info-pct{font-size:3.5rem;font-weight:800;line-height:1;margin-bottom:6px;}
.info-pct.high-val{color:var(--red);}
.info-pct.mid-val{color:var(--amber);}
.info-pct.low-val{color:var(--emerald);}
.info-pct-label{font-size:0.85rem;color:var(--text-m);}
.info-bar{height:8px;background:rgba(255,255,255,0.06);border-radius:100px;margin:10px 0 4px;overflow:hidden;}
.info-bar-fill{height:100%;border-radius:100px;transition:width .8s cubic-bezier(0.34,1.56,0.64,1);}
.info-bar-fill.high-fill{background:linear-gradient(90deg,var(--red),#F97316);}
.info-bar-fill.mid-fill{background:linear-gradient(90deg,var(--amber),#FCD34D);}
.info-bar-fill.low-fill{background:linear-gradient(90deg,var(--emerald),var(--teal));}
.info-secondary{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;}
.info-sec-card{background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:var(--r-sm);padding:14px;}
.info-sec-val{font-size:1.4rem;font-weight:700;color:#fff;margin-bottom:2px;}
.info-sec-label{font-size:0.75rem;color:var(--text-m);}
.info-context{font-size:0.83rem;color:var(--text-m);line-height:1.6;padding:14px;background:rgba(255,255,255,0.02);border-radius:var(--r-sm);border-left:3px solid var(--green);}
.info-source{margin-top:14px;font-size:0.72rem;color:var(--text-d);}
.info-cta{margin-top:20px;width:100%;text-align:center;}
.info-btn{display:block;background:linear-gradient(135deg,var(--green),var(--teal));color:#050E0A;font-weight:700;font-size:0.9rem;padding:12px 20px;border-radius:var(--r-md);transition:transform .2s,box-shadow .2s;}
.info-btn:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,212,106,0.4);}

/* tooltip */
.prov-tooltip{position:fixed;background:rgba(5,14,10,0.95);border:1px solid rgba(0,212,106,0.3);border-radius:var(--r-sm);padding:8px 14px;font-size:0.78rem;color:var(--text);pointer-events:none;z-index:999;opacity:0;transition:opacity .15s;white-space:nowrap;}
.prov-tooltip.show{opacity:1;}
.prov-tooltip strong{color:var(--green);}

/* ===== HOW IT WORKS ===== */
.how-section{padding:80px 0;}
.steps-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:28px;position:relative;}
.steps-grid::before{content:'';position:absolute;top:40px;left:calc(16.67% + 16px);right:calc(16.67% + 16px);height:2px;background:linear-gradient(90deg,var(--green),var(--teal));z-index:0;}
.step-card{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:32px 24px;text-align:center;position:relative;z-index:1;transition:transform .3s,box-shadow .3s;}
.step-card:hover{transform:translateY(-8px);box-shadow:var(--shadow),var(--glow-g);}
.step-num{width:56px;height:56px;background:linear-gradient(135deg,var(--green),var(--teal));border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin:0 auto 20px;box-shadow:0 8px 24px rgba(0,212,106,0.3);}
.step-badge{position:absolute;top:16px;right:16px;background:rgba(0,212,106,0.1);border:1px solid rgba(0,212,106,0.25);border-radius:100px;padding:3px 10px;font-size:0.68rem;font-weight:700;color:var(--green);}
.step-card h3{font-size:1.1rem;font-weight:700;color:#fff;margin-bottom:10px;}
.step-card p{font-size:0.88rem;color:var(--text-m);line-height:1.65;}

/* ===== FEATURES ===== */
.features-section{padding:80px 0;}
.features-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;}
.feat-card{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-xl);padding:32px;display:flex;gap:20px;align-items:flex-start;transition:transform .3s,box-shadow .3s,background .3s,border-color .3s;}
.feat-card:hover{transform:translateY(-6px);box-shadow:var(--shadow),var(--glow-g);background:var(--bg-card-h);border-color:rgba(0,212,106,0.2);}
.feat-icon{width:52px;height:52px;border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;}
.feat-icon.green{background:rgba(0,212,106,0.12);border:1px solid rgba(0,212,106,0.25);}
.feat-icon.teal{background:rgba(10,191,163,0.12);border:1px solid rgba(10,191,163,0.25);}
.feat-icon.amber{background:rgba(245,158,11,0.12);border:1px solid rgba(245,158,11,0.25);}
.feat-icon.blue{background:rgba(99,179,237,0.12);border:1px solid rgba(99,179,237,0.25);}
.feat-body h3{font-size:1.05rem;font-weight:700;color:#fff;margin-bottom:8px;}
.feat-body p{font-size:0.88rem;color:var(--text-m);line-height:1.65;}

/* ===== CTA SECTION ===== */
.cta-section{padding:80px 0 120px;}
.cta-box{background:linear-gradient(135deg,rgba(0,212,106,0.12),rgba(10,191,163,0.08));border:1px solid rgba(0,212,106,0.2);border-radius:var(--r-xl);padding:80px 40px;text-align:center;position:relative;overflow:hidden;}
.cta-box::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at center,rgba(0,212,106,0.07),transparent 70%);}
.cta-box h2{font-size:clamp(1.8rem,3vw,2.5rem);font-weight:800;color:#fff;margin-bottom:16px;position:relative;}
.cta-box p{font-size:1.05rem;color:var(--text-m);max-width:480px;margin:0 auto 36px;line-height:1.7;position:relative;}
.cta-box .btn-primary{position:relative;font-size:1.05rem;padding:16px 36px;}
.cta-note{margin-top:20px;font-size:0.82rem;color:var(--text-d);position:relative;}

/* ===== FOOTER ===== */
footer{padding:60px 0 30px;border-top:1px solid var(--border);}
.footer-grid{display:grid;grid-template-columns:1.5fr 1fr 1fr;gap:40px;margin-bottom:48px;}
.footer-brand .nav-logo{margin-bottom:14px;}
.footer-brand p{font-size:0.88rem;color:var(--text-m);line-height:1.65;max-width:280px;}
.footer-disclaimer{margin-top:14px;font-size:0.78rem;color:var(--text-d);line-height:1.6;background:rgba(255,255,255,0.02);border:1px solid var(--border);border-radius:var(--r-sm);padding:12px;}
.footer-col h4{font-size:0.85rem;font-weight:700;color:var(--text);margin-bottom:16px;letter-spacing:0.05em;}
.footer-col a,.footer-col span{display:block;font-size:0.88rem;color:var(--text-m);margin-bottom:10px;transition:color .2s;}
.footer-col a:hover{color:var(--green);}
.footer-bottom{border-top:1px solid var(--border);padding-top:24px;display:flex;justify-content:space-between;align-items:center;gap:12px;font-size:0.82rem;color:var(--text-d);}
.footer-bottom .logo-mark{color:var(--green);}

/* ===== REVEAL ANIMATION ===== */
.reveal{opacity:0;transform:translateY(24px);transition:opacity .7s ease,transform .7s ease;}
.reveal.visible{opacity:1;transform:none;}

/* ===== RESPONSIVE ===== */
@media(max-width:960px){
  .hero-inner,.map-wrapper,.features-grid,.footer-grid{grid-template-columns:1fr;}
  .hero{padding:130px 0 80px;}
  .hero-visual{order:-1;}
  .steps-grid{grid-template-columns:1fr;}
  .steps-grid::before{display:none;}
  .stats-grid{grid-template-columns:1fr;}
  .map-info{position:static;}
}
@media(max-width:640px){
  .nav-links{position:fixed;top:76px;left:16px;right:16px;background:rgba(5,14,10,0.95);border:1px solid var(--border);border-radius:var(--r-lg);padding:12px;flex-direction:column;align-items:stretch;display:none;gap:4px;}
  .nav-links.open{display:flex;}
  .nav-toggle{display:block;}
  .hero h1{font-size:2rem;}
  .section-head h2{font-size:1.7rem;}
  .cta-box{padding:50px 24px;}
  .footer-grid{grid-template-columns:1fr;}
}
@media(prefers-reduced-motion:reduce){*{animation-duration:.001ms!important;transition-duration:.001ms!important;}}
</style>
</head>
<body>

<!-- Background Orbs -->
<div class="bg-orbs" aria-hidden="true">
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>
</div>

<!-- Tooltip -->
<div class="prov-tooltip" id="provTooltip"></div>

<!-- ===== NAVBAR ===== -->
<nav class="navbar" id="navbar" role="navigation" aria-label="Navigasi utama">
  <a href="/" class="nav-logo" aria-label="GiziKu Beranda">
    <div class="logo-icon" aria-hidden="true">🥦</div>
    Gizi<span class="logo-dot">Ku</span>
  </a>
  <div class="nav-links" id="navLinks">
    <a href="#tentang">Tentang</a>
    <a href="#statistik">Statistik</a>
    <a href="#cara-kerja">Cara Kerja</a>
    <a href="#fitur">Fitur</a>
    <a href="/screening" class="nav-cta">Cek Gizi Anak</a>
  </div>
  <button class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
      <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
    </svg>
  </button>
</nav>

<!-- ===== HERO ===== -->
<section class="hero" id="beranda">
  <div class="wrap">
    <div class="hero-inner">
      <div class="hero-copy">
        <div class="hero-badge"><span class="badge-dot" aria-hidden="true"></span> AI-Powered Nutrition Screening</div>
        <h1>Deteksi Dini Gizi Anak dengan <span class="accent">Kecerdasan Buatan</span></h1>
        <p class="hero-sub">GiziKu membantu orang tua memahami kondisi gizi anak melalui foto dan kuesioner singkat — sebagai pegangan sebelum ke dokter. Gratis, mudah, hasil dalam 2 menit.</p>
        <div class="hero-btns">
          <a href="/screening" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Mulai Screening Gratis
          </a>
          <a href="#cara-kerja" class="btn btn-ghost">
            Pelajari Lebih Lanjut
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
        <div class="hero-badges">
          <div class="h-badge"><span class="dot dot-red" style="background:var(--red)"></span>21.5% anak stunting</div>
          <div class="h-badge"><span class="dot dot-green"></span>AI Vision Powered</div>
          <div class="h-badge"><span class="dot dot-teal"></span>Hasil &lt; 2 menit</div>
        </div>
      </div>
      <div class="hero-visual">
        <div class="floating-badge fb-1">
          <div class="fb-label">Skor Gizi Terdeteksi</div>
          <div class="fb-val">72 / 100 ✓</div>
        </div>
        <div class="hero-card">
          <div class="hcard-top">
            <div class="hcard-avatar">👦</div>
            <div class="hcard-info">
              <h4>Hasil Screening — Anak, 3 tahun</h4>
              <p>Dianalisis 2 menit lalu · AI Gemini Vision</p>
            </div>
          </div>
          <div class="hcard-score">
            <div>
              <div class="score-label">Skor Gizi</div>
              <div class="score-val">72</div>
            </div>
            <div>
              <svg class="score-ring" viewBox="0 0 60 60">
                <circle cx="30" cy="30" r="24" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="6"/>
                <circle cx="30" cy="30" r="24" fill="none" stroke="url(#grd)" stroke-width="6" stroke-dasharray="150.8" stroke-dashoffset="42" stroke-linecap="round"/>
                <defs><linearGradient id="grd" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#00D46A"/><stop offset="100%" stop-color="#0ABFA3"/></linearGradient></defs>
              </svg>
            </div>
          </div>
          <div class="hcard-items">
            <div class="hcard-item"><span class="chk">✓</span> Tinggi badan normal untuk usia</div>
            <div class="hcard-item warn"><span class="chk">!</span> Kemungkinan kekurangan Zat Besi</div>
            <div class="hcard-item warn"><span class="chk">!</span> Asupan protein hewani kurang</div>
            <div class="hcard-item"><span class="chk">✓</span> Nafsu makan baik</div>
          </div>
        </div>
        <div class="floating-badge fb-2">
          <div class="fb-label">Rekomendasi AI</div>
          <div class="fb-val">🥩 Tambah daging merah</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== STATS SECTION ===== -->
<section class="stats-section" id="tentang">
  <div class="wrap">
    <div class="section-head reveal">
      <span class="section-tag">Fakta Penting</span>
      <h2>Krisis Gizi Anak Indonesia</h2>
      <p>Data terbaru menunjukkan jutaan anak Indonesia masih mengalami masalah gizi serius yang berdampak jangka panjang pada tumbuh kembang mereka.</p>
    </div>
    <div class="stats-grid">
      <div class="stat-card high reveal">
        <div class="stat-num"><span id="cnt1" data-target="21.5">0</span><span>%</span></div>
        <div class="stat-label">Prevalensi Stunting Nasional</div>
        <div class="stat-desc">1 dari 5 anak balita Indonesia mengalami stunting — tubuh pendek akibat kekurangan gizi kronis yang menghambat perkembangan otak dan fisik.</div>
        <span class="stat-src">📊 Riskesdas 2023, Kemenkes RI</span>
      </div>
      <div class="stat-card mid reveal">
        <div class="stat-num"><span id="cnt2" data-target="17.7">0</span><span>%</span></div>
        <div class="stat-label">Anak Gizi Kurang (Underweight)</div>
        <div class="stat-desc">Jutaan anak dengan berat badan di bawah standar usia yang meningkatkan risiko infeksi, gangguan belajar, dan produktivitas rendah di masa dewasa.</div>
        <span class="stat-src">📊 SSGI 2022, UNICEF Indonesia</span>
      </div>
      <div class="stat-card reveal">
        <div class="stat-num"><span id="cnt3" data-target="7.7">0</span><span>%</span></div>
        <div class="stat-label">Balita Wasting (Kurus Akut)</div>
        <div class="stat-desc">Kondisi gizi buruk akut yang memerlukan intervensi segera. Anak wasting memiliki risiko kematian 11x lebih tinggi dibanding anak dengan gizi baik.</div>
        <span class="stat-src">📊 WHO Global Nutrition Report 2023</span>
      </div>
    </div>
  </div>
</section>

<!-- ===== MAP SECTION ===== -->
<section class="map-section" id="statistik">
  <div class="wrap">
    <div class="section-head reveal">
      <span class="section-tag">Peta Interaktif</span>
      <h2>Peta Gizi Anak Indonesia</h2>
      <p>Klik provinsi untuk melihat data stunting dan gizi kurang terkini berdasarkan data Riskesdas 2023 dan SSGI 2022.</p>
    </div>
    <div class="map-wrapper reveal">
      <!-- Map Left -->
      <div class="map-container">
        <div class="map-toolbar">
          <h3>34 Provinsi Indonesia</h3>
          <select class="map-filter" id="regionFilter" aria-label="Filter wilayah">
            <option value="all">Semua Wilayah</option>
            <option value="Sumatera">Sumatera</option>
            <option value="Jawa">Jawa</option>
            <option value="Bali & Nusa Tenggara">Bali & Nusa Tenggara</option>
            <option value="Kalimantan">Kalimantan</option>
            <option value="Sulawesi">Sulawesi</option>
            <option value="Maluku & Papua">Maluku & Papua</option>
          </select>
        </div>
        <div class="map-legend" aria-label="Keterangan warna">
          <div class="legend-item"><div class="legend-dot" style="background:var(--red)"></div> Tinggi &gt;30%</div>
          <div class="legend-item"><div class="legend-dot" style="background:var(--amber)"></div> Sedang 20-30%</div>
          <div class="legend-item"><div class="legend-dot" style="background:var(--emerald)"></div> Rendah &lt;20%</div>
        </div>
        <div class="province-grid" id="provinceGrid" role="list" aria-label="Daftar provinsi"></div>
      </div>

      <!-- Map Right Panel -->
      <div class="map-info" id="mapInfo" role="region" aria-label="Detail provinsi">
        <div class="info-header">
          <div class="info-province-name" id="infoName">Indonesia (Nasional)</div>
          <div class="info-region" id="infoRegion">Rata-rata seluruh provinsi · 2023</div>
        </div>
        <div class="info-main-stat">
          <div class="info-pct mid-val" id="infoPct">21.5%</div>
          <div class="info-pct-label">Prevalensi Stunting</div>
          <div class="info-bar"><div class="info-bar-fill mid-fill" id="infoBar" style="width:21.5%"></div></div>
          <div style="font-size:0.72rem;color:var(--text-d)">Target RPJMN 2024: 14%</div>
        </div>
        <div class="info-secondary">
          <div class="info-sec-card">
            <div class="info-sec-val" id="infoGiziKurang">17.7%</div>
            <div class="info-sec-label">Gizi Kurang</div>
          </div>
          <div class="info-sec-card">
            <div class="info-sec-val" id="infoWasting">7.7%</div>
            <div class="info-sec-label">Wasting</div>
          </div>
        </div>
        <div class="info-context" id="infoContext">
          Secara nasional, Indonesia masih berjuang menurunkan prevalensi stunting dari 24.4% (2021) menuju target 14% di tahun 2024. Kondisi gizi sangat bervariasi antar provinsi.
        </div>
        <div class="info-source" id="infoSource">Sumber: Riskesdas 2023, SSGI 2022, Kemenkes RI</div>
        <div class="info-cta">
          <a href="/screening" class="info-btn">🔍 Cek Gizi Anak Anda Sekarang</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== HOW IT WORKS ===== -->
<section class="how-section" id="cara-kerja">
  <div class="wrap">
    <div class="section-head reveal">
      <span class="section-tag">Cara Kerja</span>
      <h2>Mudah, Cepat, dan Akurat</h2>
      <p>Tiga langkah sederhana untuk mendapatkan analisis gizi anak yang komprehensif langsung dari smartphone Anda.</p>
    </div>
    <div class="steps-grid">
      <div class="step-card reveal">
        <span class="step-badge">Langkah 1</span>
        <div class="step-num">📸</div>
        <h3>Foto &amp; Data Anak</h3>
        <p>Upload foto wajah dan tangan anak, lalu isi data dasar seperti usia, berat, dan tinggi badan. Tidak perlu alat khusus — cukup kamera smartphone.</p>
      </div>
      <div class="step-card reveal">
        <span class="step-badge">Langkah 2</span>
        <div class="step-num">📋</div>
        <h3>Kuesioner Singkat</h3>
        <p>Jawab 12 pertanyaan tentang pola makan, kebiasaan, dan riwayat kesehatan anak. Dirancang singkat dan mudah dipahami semua orang tua.</p>
      </div>
      <div class="step-card reveal">
        <span class="step-badge">Langkah 3</span>
        <div class="step-num">🤖</div>
        <h3>Hasil AI Instan</h3>
        <p>Dapatkan skor gizi, status antropometri, temuan dari foto, dan rekomendasi makanan personal — dalam kurang dari 2 menit. Bawa hasilnya ke dokter!</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== FEATURES ===== -->
<section class="features-section" id="fitur">
  <div class="wrap">
    <div class="section-head reveal">
      <span class="section-tag">Keunggulan</span>
      <h2>Kenapa GiziKu?</h2>
      <p>Teknologi AI mutakhir yang dirancang khusus untuk membantu orang tua Indonesia memahami dan meningkatkan gizi anak.</p>
    </div>
    <div class="features-grid">
      <div class="feat-card reveal">
        <div class="feat-icon green">🔬</div>
        <div class="feat-body">
          <h3>Analisis Foto AI</h3>
          <p>AI Gemini Vision menganalisis foto wajah dan tangan anak untuk mendeteksi tanda-tanda defisiensi seperti anemia, kekurangan Vitamin A, dan masalah gizi lainnya.</p>
        </div>
      </div>
      <div class="feat-card reveal">
        <div class="feat-icon teal">📊</div>
        <div class="feat-body">
          <h3>Skor Gizi Terukur</h3>
          <p>Dapatkan skor gizi 0-100 beserta status antropometri berdasarkan standar WHO (stunting, gizi kurang, wasting) yang mudah dipahami.</p>
        </div>
      </div>
      <div class="feat-card reveal">
        <div class="feat-icon amber">🍽️</div>
        <div class="feat-body">
          <h3>Rekomendasi Personal</h3>
          <p>Rekomendasi makanan dan suplemen yang disesuaikan dengan kondisi, usia, dan temuan spesifik anak Anda. Berbasis panduan gizi Kemenkes dan WHO.</p>
        </div>
      </div>
      <div class="feat-card reveal">
        <div class="feat-icon blue">🏥</div>
        <div class="feat-body">
          <h3>Panduan ke Tenaga Kesehatan</h3>
          <p>Tahu kapan harus segera ke dokter dan hasil apa yang perlu dibawa. GiziKu bukan pengganti dokter, tapi pegangan kuat sebelum konsultasi.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== CTA ===== -->
<section class="cta-section">
  <div class="wrap">
    <div class="cta-box reveal">
      <h2>Siap Cek Gizi Anak Sekarang?</h2>
      <p>Gratis, mudah, hasil dalam 2 menit. Tidak perlu mendaftar. Mulai deteksi dini gizi anak Anda hari ini.</p>
      <a href="/screening" class="btn btn-primary">
        🚀 Mulai Screening Gratis
      </a>
      <p class="cta-note">Tersedia gratis · Tanpa daftar · 100% aman &amp; privat</p>
    </div>
  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer>
  <div class="wrap">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="nav-logo">
          <div class="logo-icon">🥦</div>
          Gizi<span class="logo-dot">Ku</span>
        </div>
        <p>Platform AI screening gizi anak untuk orang tua Indonesia. Deteksi dini, cegah stunting dari rumah.</p>
        <div class="footer-disclaimer">
          ⚠️ <strong>Disclaimer:</strong> GiziKu adalah alat bantu skrining, bukan pengganti diagnosis dokter. Selalu konsultasikan kondisi anak Anda kepada tenaga kesehatan.
        </div>
      </div>
      <div class="footer-col">
        <h4>Navigasi</h4>
        <a href="#tentang">Tentang GiziKu</a>
        <a href="#cara-kerja">Cara Kerja</a>
        <a href="#statistik">Peta Gizi Indonesia</a>
        <a href="/screening">Mulai Screening</a>
      </div>
      <div class="footer-col">
        <h4>Informasi</h4>
        <span>📊 Data: Riskesdas 2023</span>
        <span>🌐 Sumber: Kemenkes RI</span>
        <span>🤖 AI: Google Gemini</span>
        <span>🇮🇩 Hackathon Project 2025</span>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2025 <span class="logo-mark">GiziKu</span> · Hackathon Project · Untuk Indonesia yang Lebih Sehat</span>
      <span>Made with 💚 for Indonesia</span>
    </div>
  </div>
</footer>

<script>
// ===== PROVINCE DATA (Riskesdas 2023 / SSGI 2022) =====
const provinceData = {
  'Aceh':               { stunting:31.2, giziKurang:18.2, wasting:12.1, region:'Sumatera' },
  'Sumatera Utara':     { stunting:25.8, giziKurang:16.5, wasting:8.3,  region:'Sumatera' },
  'Sumatera Barat':     { stunting:23.3, giziKurang:14.1, wasting:7.2,  region:'Sumatera' },
  'Riau':               { stunting:17.5, giziKurang:12.3, wasting:6.1,  region:'Sumatera' },
  'Jambi':              { stunting:22.4, giziKurang:14.8, wasting:8.5,  region:'Sumatera' },
  'Sumatera Selatan':   { stunting:24.8, giziKurang:16.2, wasting:9.1,  region:'Sumatera' },
  'Bengkulu':           { stunting:22.1, giziKurang:15.4, wasting:8.8,  region:'Sumatera' },
  'Lampung':            { stunting:18.5, giziKurang:13.2, wasting:7.4,  region:'Sumatera' },
  'Kep. Bangka Belitung': { stunting:18.9, giziKurang:11.8, wasting:6.5, region:'Sumatera' },
  'Kepulauan Riau':     { stunting:15.4, giziKurang:10.2, wasting:5.8,  region:'Sumatera' },
  'DKI Jakarta':        { stunting:14.8, giziKurang:9.8,  wasting:5.2,  region:'Jawa' },
  'Jawa Barat':         { stunting:20.2, giziKurang:13.8, wasting:7.1,  region:'Jawa' },
  'Jawa Tengah':        { stunting:20.9, giziKurang:14.2, wasting:7.8,  region:'Jawa' },
  'DI Yogyakarta':      { stunting:16.4, giziKurang:10.5, wasting:5.9,  region:'Jawa' },
  'Jawa Timur':         { stunting:19.2, giziKurang:13.5, wasting:7.3,  region:'Jawa' },
  'Banten':             { stunting:20.0, giziKurang:13.6, wasting:7.2,  region:'Jawa' },
  'Bali':               { stunting:10.9, giziKurang:8.2,  wasting:4.5,  region:'Bali & Nusa Tenggara' },
  'Nusa Tenggara Barat':{ stunting:32.7, giziKurang:21.3, wasting:14.2, region:'Bali & Nusa Tenggara' },
  'Nusa Tenggara Timur':{ stunting:35.3, giziKurang:24.5, wasting:15.8, region:'Bali & Nusa Tenggara' },
  'Kalimantan Barat':   { stunting:27.8, giziKurang:17.5, wasting:9.8,  region:'Kalimantan' },
  'Kalimantan Tengah':  { stunting:26.9, giziKurang:17.1, wasting:9.4,  region:'Kalimantan' },
  'Kalimantan Selatan': { stunting:30.0, giziKurang:19.8, wasting:11.2, region:'Kalimantan' },
  'Kalimantan Timur':   { stunting:22.8, giziKurang:14.9, wasting:8.1,  region:'Kalimantan' },
  'Kalimantan Utara':   { stunting:27.5, giziKurang:17.8, wasting:9.6,  region:'Kalimantan' },
  'Sulawesi Utara':     { stunting:20.5, giziKurang:13.8, wasting:7.5,  region:'Sulawesi' },
  'Sulawesi Tengah':    { stunting:28.2, giziKurang:18.4, wasting:10.5, region:'Sulawesi' },
  'Sulawesi Selatan':   { stunting:27.4, giziKurang:18.1, wasting:10.2, region:'Sulawesi' },
  'Sulawesi Tenggara':  { stunting:30.2, giziKurang:19.5, wasting:11.5, region:'Sulawesi' },
  'Gorontalo':          { stunting:29.7, giziKurang:19.2, wasting:11.1, region:'Sulawesi' },
  'Sulawesi Barat':     { stunting:35.0, giziKurang:23.8, wasting:14.5, region:'Sulawesi' },
  'Maluku':             { stunting:26.1, giziKurang:17.2, wasting:9.5,  region:'Maluku & Papua' },
  'Maluku Utara':       { stunting:27.4, giziKurang:18.0, wasting:10.1, region:'Maluku & Papua' },
  'Papua Barat':        { stunting:30.0, giziKurang:19.8, wasting:11.8, region:'Maluku & Papua' },
  'Papua':              { stunting:34.6, giziKurang:22.5, wasting:13.2, region:'Maluku & Papua' },
};

const regionOrder = ['Sumatera','Jawa','Bali & Nusa Tenggara','Kalimantan','Sulawesi','Maluku & Papua'];

function getLevel(stunting) {
  if (stunting >= 30) return 'high';
  if (stunting >= 20) return 'mid';
  return 'low';
}

function buildProvinceGrid(filter = 'all') {
  const grid = document.getElementById('provinceGrid');
  grid.innerHTML = '';

  const grouped = {};
  regionOrder.forEach(r => grouped[r] = []);
  Object.entries(provinceData).forEach(([name, d]) => {
    if (filter === 'all' || d.region === filter) {
      if (grouped[d.region]) grouped[d.region].push({ name, ...d });
    }
  });

  regionOrder.forEach(region => {
    const provs = grouped[region];
    if (!provs || provs.length === 0) return;

    const row = document.createElement('div');
    row.className = 'region-row';
    row.innerHTML = `<div class="region-label">${region}</div><div class="region-bubbles" role="list"></div>`;
    const bubblesDiv = row.querySelector('.region-bubbles');

    provs.sort((a,b) => b.stunting - a.stunting).forEach(p => {
      const lvl = getLevel(p.stunting);
      const btn = document.createElement('button');
      btn.className = `province-bubble ${lvl}`;
      btn.setAttribute('data-province', p.name);
      btn.setAttribute('data-stunting', p.stunting);
      btn.setAttribute('role', 'listitem');
      btn.setAttribute('aria-label', `${p.name}: ${p.stunting}% stunting`);

      // Short label
      let label = p.name;
      if (label.length > 14) label = label.replace('Kalimantan','Kal.').replace('Sulawesi','Sul.').replace('Sumatera','Sum.').replace('Nusa Tenggara','NT').replace('Kepulauan','Kep.');
      btn.textContent = label;

      btn.addEventListener('click', () => selectProvince(p.name));
      btn.addEventListener('mouseenter', e => showTooltip(e, p.name, p.stunting));
      btn.addEventListener('mouseleave', hideTooltip);
      btn.addEventListener('mousemove', e => moveTooltip(e));

      bubblesDiv.appendChild(btn);
    });
    grid.appendChild(row);
  });
}

function showTooltip(e, name, stunting) {
  const tt = document.getElementById('provTooltip');
  tt.innerHTML = `<strong>${name}</strong>: ${stunting}% stunting`;
  tt.classList.add('show');
  moveTooltip(e);
}
function moveTooltip(e) {
  const tt = document.getElementById('provTooltip');
  tt.style.left = (e.clientX + 14) + 'px';
  tt.style.top  = (e.clientY - 36) + 'px';
}
function hideTooltip() {
  document.getElementById('provTooltip').classList.remove('show');
}

function selectProvince(name) {
  const d = provinceData[name];
  if (!d) return;

  // Update active state
  document.querySelectorAll('.province-bubble').forEach(b => b.classList.remove('active'));
  const btn = document.querySelector(`[data-province="${name}"]`);
  if (btn) btn.classList.add('active');

  const lvl = getLevel(d.stunting);
  const pctEl = document.getElementById('infoPct');
  const barEl = document.getElementById('infoBar');

  document.getElementById('infoName').textContent = name;
  document.getElementById('infoRegion').textContent = `${d.region} · SSGI 2022 / Riskesdas 2023`;
  pctEl.textContent = d.stunting + '%';
  pctEl.className = 'info-pct ' + (lvl === 'high' ? 'high-val' : lvl === 'mid' ? 'mid-val' : 'low-val');
  barEl.style.width = d.stunting + '%';
  barEl.className = 'info-bar-fill ' + (lvl === 'high' ? 'high-fill' : lvl === 'mid' ? 'mid-fill' : 'low-fill');
  document.getElementById('infoGiziKurang').textContent = d.giziKurang + '%';
  document.getElementById('infoWasting').textContent = d.wasting + '%';

  const contexts = {
    high: `${name} termasuk dalam kategori <strong>stunting tinggi</strong> (≥30%). Intervensi gizi intensif sangat diperlukan di wilayah ini.`,
    mid: `${name} berada di kategori <strong>stunting sedang</strong> (20-30%). Perlu upaya konsisten untuk menurunkan angka stunting.`,
    low: `${name} termasuk wilayah dengan stunting <strong>relatif rendah</strong> (<20%), namun tetap perlu pemantauan berkelanjutan.`
  };
  document.getElementById('infoContext').innerHTML = contexts[lvl];
}

// Filter handler
document.getElementById('regionFilter').addEventListener('change', e => {
  buildProvinceGrid(e.target.value);
});

// ===== COUNT-UP ANIMATION =====
function countUp(el, target, decimals = 1, duration = 2000) {
  const start = performance.now();
  const update = (time) => {
    const progress = Math.min((time - start) / duration, 1);
    const ease = 1 - Math.pow(1 - progress, 3);
    el.textContent = (ease * target).toFixed(decimals);
    if (progress < 1) requestAnimationFrame(update);
    else el.textContent = target.toFixed(decimals);
  };
  requestAnimationFrame(update);
}

// ===== INTERSECTION OBSERVER =====
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');

      // Trigger count-up for stat numbers
      const target = entry.target.querySelector('[data-target]');
      if (target && !target.dataset.animated) {
        target.dataset.animated = 'true';
        countUp(target, parseFloat(target.dataset.target));
      }
    }
  });
}, { threshold: 0.15, rootMargin: '0px 0px -50px 0px' });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// ===== NAVBAR SCROLL =====
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
  navbar.classList.toggle('scrolled', window.scrollY > 50);
});

// ===== MOBILE NAV =====
const toggle = document.getElementById('navToggle');
const navLinks = document.getElementById('navLinks');
toggle.addEventListener('click', () => {
  const open = navLinks.classList.toggle('open');
  toggle.setAttribute('aria-expanded', open);
});
document.querySelectorAll('.nav-links a').forEach(link => {
  link.addEventListener('click', () => navLinks.classList.remove('open'));
});

// ===== INIT =====
buildProvinceGrid();
</script>
</body>
</html>