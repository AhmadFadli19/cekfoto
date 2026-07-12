<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>cobaWeb — Website Profesional untuk Mengembangkan Bisnis Anda</title>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
  href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap"
  rel="stylesheet"
/>
<style>
  :root{
    --ink:#0E1630; --navy:#16234D; --blue-700:#2947C7; --blue-600:#3557E8;
    --blue-500:#4B6EF0; --blue-400:#7C9BFF; --blue-100:#EAF0FF; --blue-50:#F5F8FF;
    --white:#FFFFFF; --gray-500:#5B6478; --gray-300:#B7BFD6;
    --shadow-lg: 0 30px 60px -20px rgba(22,35,77,0.25);
    --shadow-sm: 0 10px 25px -10px rgba(22,35,77,0.18);
  }
  *{box-sizing:border-box; margin:0; padding:0;}
  html{scroll-behavior:smooth;}
  body{font-family:'Inter', sans-serif; color:var(--ink); background:var(--white); overflow-x:hidden; -webkit-font-smoothing:antialiased;}
  h1,h2,h3,.display{font-family:'Space Grotesk', sans-serif; color:var(--navy); letter-spacing:-0.02em;}
  .eyebrow{font-family:'IBM Plex Mono', monospace; font-size:0.72rem; letter-spacing:0.18em; text-transform:uppercase; color:var(--blue-600); font-weight:500;}
  a{text-decoration:none; color:inherit;}
  ul{list-style:none;}
  img{max-width:100%; display:block;}
  section{position:relative;}
  .wrap{max-width:1200px; margin:0 auto; padding:0 32px;}

  @media (prefers-reduced-motion: reduce){
    *{animation-duration:0.001ms !important; animation-iteration-count:1 !important; transition-duration:0.001ms !important;}
  }

  .burst{position:absolute; pointer-events:none; opacity:0.55; filter:blur(0.5px); z-index:0;}
  .burst svg{width:100%; height:100%;}
  .burst.drift{animation:drift 9s ease-in-out infinite;}
  @keyframes drift{0%,100%{transform:translateY(0) rotate(0deg);} 50%{transform:translateY(-14px) rotate(4deg);}}

  .navbar{
    position:fixed; top:20px; left:50%; transform:translateX(-50%);
    width:min(920px, calc(100% - 40px)); z-index:100;
    background:rgba(255,255,255,0.75); backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px);
    border:1px solid rgba(41,71,199,0.12); border-radius:100px; box-shadow:var(--shadow-sm);
    display:flex; align-items:center; justify-content:space-between; padding:10px 12px 10px 22px;
    transition:box-shadow .3s ease, background .3s ease;
  }
  .navbar.scrolled{box-shadow:var(--shadow-lg);}
  .logo{font-family:'Space Grotesk', sans-serif; font-weight:700; font-size:1.15rem; color:var(--navy); display:flex; align-items:center; gap:6px;}
  .logo .dot{color:var(--blue-600);}
  .nav-links{display:flex; gap:6px; align-items:center;}
  .nav-links a{font-size:0.92rem; font-weight:500; color:var(--navy); padding:9px 16px; border-radius:100px; transition:background .2s ease, color .2s ease;}
  .nav-links a:hover, .nav-links a.active{background:var(--blue-100); color:var(--blue-700);}
  .nav-cta{background:var(--navy); color:var(--white) !important; padding:10px 20px !important; border-radius:100px; font-weight:600 !important; white-space:nowrap;}
  .nav-cta:hover{background:var(--blue-700) !important;}
  .nav-toggle{display:none; background:none; border:none; cursor:pointer; padding:8px;}
  .nav-toggle span{display:block; width:22px; height:2px; background:var(--navy); margin:5px 0; transition:transform .25s ease, opacity .25s ease;}

  .hero{padding:170px 0 120px; background:linear-gradient(180deg, var(--white) 0%, var(--blue-50) 100%); min-height:90vh; display:flex; align-items:center;}
  .hero .wrap{display:grid; grid-template-columns:1.05fr 0.95fr; gap:60px; align-items:center;}
  .hero-copy h1{font-size:clamp(2.4rem, 4.5vw, 3.6rem); line-height:1.08; font-weight:600; margin-bottom:22px;}
  .hero-copy h1 .accent{color:var(--blue-600);}
  .hero-copy p{font-size:1.05rem; color:var(--gray-500); max-width:440px; margin-bottom:34px; line-height:1.65;}
  .hero-actions{display:flex; gap:14px; flex-wrap:wrap; margin-bottom:44px;}
  .btn{display:inline-flex; align-items:center; gap:8px; padding:14px 26px; border-radius:100px; font-weight:600; font-size:0.95rem; transition:transform .2s ease, box-shadow .2s ease, background .2s ease; cursor:pointer; border:none;}
  .btn-primary{background:var(--blue-600); color:var(--white); box-shadow:0 14px 30px -10px rgba(53,87,232,0.55);}
  .btn-primary:hover{transform:translateY(-2px); box-shadow:0 18px 36px -8px rgba(53,87,232,0.6);}
  .btn-ghost{background:transparent; color:var(--navy); border:1.5px solid rgba(22,35,77,0.15);}
  .btn-ghost:hover{background:var(--blue-100); border-color:transparent;}

  .quick-nav{display:flex; gap:14px;}
  .quick-card{flex:1; background:var(--white); border:1px solid rgba(41,71,199,0.1); border-radius:18px; padding:20px 16px; box-shadow:var(--shadow-sm); cursor:pointer; transition:transform .25s ease, box-shadow .25s ease, background .25s ease; display:flex; flex-direction:column; gap:26px;}
  .quick-card:hover{transform:translateY(-6px); background:var(--navy); box-shadow:var(--shadow-lg);}
  .quick-card:hover span, .quick-card:hover svg{color:var(--white) !important; stroke:var(--white) !important;}
  .quick-card span{font-weight:600; font-size:0.95rem; color:var(--navy);}
  .quick-card svg{width:22px; height:22px; stroke:var(--blue-600); align-self:flex-end;}

  .hero-visual{position:relative; height:420px;}
  .hero-photo{position:absolute; top:0; right:2%; width:78%; height:88%; border-radius:24px; overflow:hidden; transform:rotate(5deg); box-shadow:var(--shadow-lg);}
  .hero-photo img{width:100%; height:100%; object-fit:cover; filter:saturate(0.9);}
  .hero-photo::after{content:''; position:absolute; inset:0; background:linear-gradient(160deg, rgba(53,87,232,0.5), rgba(22,35,77,0.6)); mix-blend-mode:multiply;}
  .browser-card{position:absolute; top:30px; left:10%; width:88%; background:var(--white); border-radius:16px; box-shadow:var(--shadow-lg); transform:rotate(-6deg); transition:transform .15s ease-out; overflow:hidden; border:1px solid rgba(22,35,77,0.06); z-index:1;}
  .browser-bar{background:linear-gradient(120deg, var(--blue-600), var(--blue-500)); padding:12px 16px; display:flex; gap:6px;}
  .browser-bar span{width:9px; height:9px; border-radius:50%; background:rgba(255,255,255,0.55);}
  .browser-body{padding:46px 30px 56px; text-align:center;}
  .browser-body .wordmark{font-family:'Space Grotesk', sans-serif; font-weight:700; font-size:2.1rem; color:var(--navy);}
  .browser-body .wordmark .dot{color:var(--blue-600);}
  .browser-body p{margin-top:10px; font-size:0.82rem; color:var(--gray-300); font-family:'IBM Plex Mono', monospace; letter-spacing:0.08em;}

  .section-head{text-align:center; max-width:600px; margin:0 auto 56px;}
  .section-head h2{font-size:clamp(1.9rem, 3vw, 2.4rem); margin:10px 0 14px;}
  .section-head p{color:var(--gray-500); line-height:1.6;}

  .about{padding:120px 0;}
  .about-visual{border-radius:24px; overflow:hidden; height:320px; margin-bottom:56px; box-shadow:var(--shadow-lg); position:relative;}
  .about-visual img{width:100%; height:100%; object-fit:cover; filter:saturate(0.95);}
  .about-visual::after{content:''; position:absolute; inset:0; background:linear-gradient(180deg, rgba(22,35,77,0) 40%, rgba(14,22,48,0.55) 100%);}
  .about-grid{display:grid; grid-template-columns:repeat(3, 1fr); gap:24px;}
  .value-card{background:var(--blue-50); border:1px solid rgba(41,71,199,0.08); border-radius:20px; padding:34px 28px; transition:transform .3s ease, box-shadow .3s ease, background .3s ease;}
  .value-card:hover{transform:translateY(-8px); box-shadow:var(--shadow-lg); background:var(--white);}
  .value-icon{width:46px; height:46px; border-radius:12px; background:var(--blue-600); display:flex; align-items:center; justify-content:center; margin-bottom:20px;}
  .value-icon svg{width:22px; height:22px; stroke:var(--white);}
  .value-card h3{font-size:1.15rem; margin-bottom:10px;}
  .value-card p{color:var(--gray-500); font-size:0.94rem; line-height:1.6;}

  .services{padding:120px 0; background:var(--navy); color:var(--white); clip-path: polygon(0 40px, 100% 0, 100% calc(100% - 40px), 0% 100%); margin:60px 0;}
  .services .section-head h2{color:var(--white);}
  .services .section-head p{color:rgba(255,255,255,0.6);}
  .services-top{display:grid; grid-template-columns:1.1fr 0.9fr; gap:50px; align-items:center; margin-bottom:56px; text-align:left;}
  .services-top .section-head{text-align:left; margin:0; max-width:none;}
  .services-photo{border-radius:20px; overflow:hidden; height:280px; position:relative; border:1px solid rgba(255,255,255,0.12);}
  .services-photo img{width:100%; height:100%; object-fit:cover; filter:saturate(0.9);}
  .services-photo::after{content:''; position:absolute; inset:0; background:linear-gradient(160deg, rgba(53,87,232,0.35), rgba(22,35,77,0.5)); mix-blend-mode:multiply;}
  .services-grid{display:grid; grid-template-columns:repeat(2, 1fr); gap:20px;}
  .service-card{background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:20px; padding:30px; display:flex; gap:20px; align-items:flex-start; transition:background .3s ease, transform .3s ease;}
  .service-card:hover{background:rgba(255,255,255,0.1); transform:translateY(-4px);}
  .service-icon{width:44px; height:44px; flex-shrink:0; border-radius:12px; background:var(--blue-500); display:flex; align-items:center; justify-content:center;}
  .service-icon svg{width:20px; height:20px; stroke:var(--white);}
  .service-card h3{color:var(--white); font-size:1.08rem; margin-bottom:6px;}
  .service-card p{color:rgba(255,255,255,0.6); font-size:0.9rem; line-height:1.55;}

  .portfolio{padding:120px 0 100px;}
  .portfolio-grid{display:grid; grid-template-columns:repeat(2, 1fr); gap:26px;}
  .mock{background:var(--white); border-radius:16px; border:1px solid rgba(22,35,77,0.08); box-shadow:var(--shadow-sm); overflow:hidden; transition:transform .3s ease, box-shadow .3s ease;}
  .mock:hover{transform:translateY(-6px); box-shadow:var(--shadow-lg);}
  .mock-bar{background:var(--blue-600); padding:10px 14px; display:flex; gap:6px;}
  .mock-bar span{width:8px; height:8px; border-radius:50%; background:rgba(255,255,255,0.5);}
  .mock-body{height:190px; position:relative; display:flex; align-items:flex-end; padding:18px 22px; overflow:hidden;}
  .mock-body img{position:absolute; inset:0; width:100%; height:100%; object-fit:cover; filter:saturate(0.95);}
  .mock-body::before{content:''; position:absolute; inset:0; background:linear-gradient(180deg, rgba(22,35,77,0) 35%, rgba(10,16,38,0.82) 100%); z-index:1;}
  .mock-body > div{position:relative; z-index:2;}
  .mock-body h4{font-size:0.95rem; color:var(--white);}
  .mock-body span{display:block; font-family:'IBM Plex Mono', monospace; font-size:0.7rem; color:var(--blue-400); letter-spacing:0.08em; margin-bottom:4px;}

  .cta{padding:100px 0; text-align:center;}
  .cta-box{background:linear-gradient(120deg, var(--blue-700), var(--blue-500)); border-radius:28px; padding:70px 40px; color:var(--white); position:relative; overflow:hidden;}
  .cta-box h2{color:var(--white); font-size:clamp(1.8rem,3vw,2.3rem); margin-bottom:16px;}
  .cta-box p{color:rgba(255,255,255,0.85); max-width:480px; margin:0 auto 32px; line-height:1.6;}
  .cta-box .btn-primary{background:var(--white); color:var(--blue-700); box-shadow:0 14px 30px -10px rgba(0,0,0,0.25);}

  footer{padding:70px 0 30px; background:var(--blue-50);}
  .footer-grid{display:grid; grid-template-columns:1.4fr 1fr 1fr; gap:40px; margin-bottom:50px;}
  .footer-brand p{color:var(--gray-500); margin-top:14px; max-width:280px; line-height:1.6; font-size:0.92rem;}
  .footer-col h4{font-size:0.9rem; margin-bottom:16px; color:var(--navy);}
  .footer-col a, .footer-col span{display:block; color:var(--gray-500); font-size:0.9rem; margin-bottom:10px; transition:color .2s ease;}
  .footer-col a:hover{color:var(--blue-600);}
  .footer-bottom{border-top:1px solid rgba(22,35,77,0.1); padding-top:24px; display:flex; justify-content:space-between; flex-wrap:wrap; gap:12px; font-size:0.82rem; color:var(--gray-300);}

  .reveal{opacity:0; transform:translateY(26px); transition:opacity .7s ease, transform .7s ease;}
  .reveal.visible{opacity:1; transform:translateY(0);}

  @media (max-width:900px){
    .hero .wrap{grid-template-columns:1fr;}
    .hero{padding:150px 0 80px; text-align:left;}
    .hero-visual{height:320px; order:-1;}
    .about-grid, .services-grid, .portfolio-grid, .services-top{grid-template-columns:1fr;}
    .services-top .section-head{text-align:center;}
    .hero-photo{display:none;}
    .footer-grid{grid-template-columns:1fr; gap:30px;}
    .services{clip-path:none;}
  }
  @media (max-width:640px){
    .nav-links{position:fixed; top:82px; left:20px; right:20px; background:var(--white); border-radius:20px; box-shadow:var(--shadow-lg); flex-direction:column; align-items:stretch; padding:14px; display:none; gap:4px;}
    .nav-links.open{display:flex;}
    .nav-links a{text-align:center;}
    .nav-toggle{display:block;}
    .quick-nav{flex-direction:column;}
    .wrap{padding:0 22px;}
  }
</style>
<style>
/* ── Floating Chatbot ── */
.cobabot *{box-sizing:border-box;margin:0;padding:0;}

.cobabot-btn{position:fixed!important;bottom:28px;right:28px;z-index:9999;width:60px;height:60px;border-radius:50%;border:none;background:linear-gradient(135deg,#3557E8,#4B6EF0);color:#fff;cursor:pointer;box-shadow:0 8px 32px rgba(53,87,232,0.45);display:flex;align-items:center;justify-content:center;transition:transform .3s cubic-bezier(.34,1.56,.64,1),box-shadow .3s ease;}
.cobabot-btn:hover{transform:scale(1.1);box-shadow:0 12px 40px rgba(53,87,232,0.55);}
.cobabot-btn svg{width:26px;height:26px;}
.cobabot-btn.is-open svg.icon-chat{display:none;}
.cobabot-btn.is-open svg.icon-close{display:block;}
.cobabot-btn:not(.is-open) svg.icon-chat{display:block;}
.cobabot-btn:not(.is-open) svg.icon-close{display:none;}
.cobabot-btn .badge{position:absolute;top:-2px;right:-2px;width:22px;height:22px;border-radius:50%;background:#ef4444;color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;font-family:'Inter',sans-serif;border:2px solid #fff;animation:cobabotPulse 2s ease-in-out infinite;}
@keyframes cobabotPulse{0%,100%{transform:scale(1);}50%{transform:scale(1.12);}}

.cobabot-popup{position:fixed!important;bottom:100px;right:28px;z-index:9998;width:400px;height:600px;max-height:calc(100vh - 130px);background:#fff;border-radius:20px;box-shadow:0 24px 80px rgba(14,22,48,0.18),0 0 0 1px rgba(14,22,48,0.04);display:flex;flex-direction:column;overflow:hidden;transform-origin:bottom right;transform:scale(0.9) translateY(20px);opacity:0;visibility:hidden;transition:transform .35s cubic-bezier(.34,1.56,.64,1),opacity .3s ease,visibility .3s ease;}
.cobabot-popup.is-open{transform:scale(1) translateY(0)!important;opacity:1!important;visibility:visible!important;}

.cobabot-header{background:linear-gradient(135deg,#3557E8,#2947C7);color:#fff;padding:18px 20px;display:flex;align-items:center;gap:12px;flex-shrink:0;position:relative;overflow:hidden;}
.cobabot-header::after{content:'';position:absolute;top:-40px;right:-40px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,0.06);}
.cobabot-avatar{width:40px;height:40px;border-radius:14px;background:rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
.cobabot-header-info{flex:1;min-width:0;}
.cobabot-header-info h3{font-family:'Space Grotesk',sans-serif;font-size:1rem;font-weight:700;letter-spacing:-0.01em;}
.cobabot-header-info p{font-size:0.75rem;opacity:0.75;margin-top:2px;display:flex;align-items:center;gap:5px;}
.cobabot-header-info p::before{content:'';width:7px;height:7px;border-radius:50%;background:#34d399;flex-shrink:0;}
.cobabot-close{width:34px;height:34px;border-radius:10px;border:none;background:rgba(255,255,255,0.12);color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px;transition:background .2s ease;flex-shrink:0;position:relative;z-index:1;}
.cobabot-close:hover{background:rgba(255,255,255,0.25);}

.cobabot-messages{flex:1;overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:12px;background:#F7F9FC;}
.cobabot-messages::-webkit-scrollbar{width:4px;}
.cobabot-messages::-webkit-scrollbar-track{background:transparent;}
.cobabot-messages::-webkit-scrollbar-thumb{background:rgba(53,87,232,0.2);border-radius:4px;}

.cobabot-msg{max-width:82%;padding:12px 16px;font-size:0.88rem;line-height:1.6;word-wrap:break-word;overflow-wrap:break-word;white-space:pre-wrap;animation:cobabotSlideUp .3s cubic-bezier(.34,1.56,.64,1);}
@keyframes cobabotSlideUp{from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);}}
.cobabot-msg.bot{align-self:flex-start;background:#fff;color:#0E1630;border-radius:4px 18px 18px 18px;box-shadow:0 1px 6px rgba(14,22,48,0.06);border:1px solid rgba(14,22,48,0.04);}
.cobabot-msg.user{align-self:flex-end;background:linear-gradient(135deg,#3557E8,#4B6EF0);color:#fff;border-radius:18px 4px 18px 18px;}
.cobabot-msg-time{font-size:0.65rem;margin-top:6px;opacity:0.45;}
.cobabot-msg.user .cobabot-msg-time{text-align:right;opacity:0.6;}

.cobabot-typing{align-self:flex-start;background:#fff;padding:14px 20px;border-radius:4px 18px 18px 18px;display:flex;gap:5px;box-shadow:0 1px 6px rgba(14,22,48,0.06);border:1px solid rgba(14,22,48,0.04);animation:cobabotSlideUp .3s cubic-bezier(.34,1.56,.64,1);}
.cobabot-typing span{width:8px;height:8px;border-radius:50%;background:#B7BFD6;animation:cobabotBounce 1.4s ease-in-out infinite;}
.cobabot-typing span:nth-child(2){animation-delay:.2s;}
.cobabot-typing span:nth-child(3){animation-delay:.4s;}
@keyframes cobabotBounce{0%,60%,100%{transform:translateY(0);opacity:0.4;}30%{transform:translateY(-8px);opacity:1;}}

.cobabot-input-wrap{display:flex;gap:10px;padding:14px 16px 16px;background:#fff;border-top:1px solid rgba(14,22,48,0.06);flex-shrink:0;align-items:flex-end;}
.cobabot-input{flex:1;border:1.5px solid rgba(14,22,48,0.1);border-radius:14px;padding:11px 16px;font-size:0.88rem;font-family:'Inter',sans-serif;color:#0E1630;outline:none;transition:border-color .2s ease,box-shadow .2s ease;resize:none;max-height:100px;line-height:1.45;}
.cobabot-input:focus{border-color:#3557E8;box-shadow:0 0 0 3px rgba(53,87,232,0.1);}
.cobabot-input::placeholder{color:#B7BFD6;}
.cobabot-send{width:44px;height:44px;border-radius:14px;border:none;background:linear-gradient(135deg,#3557E8,#4B6EF0);color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:transform .2s ease,box-shadow .2s ease;flex-shrink:0;box-shadow:0 4px 12px rgba(53,87,232,0.3);}
.cobabot-send:hover{transform:scale(1.06);box-shadow:0 6px 16px rgba(53,87,232,0.4);}
.cobabot-send:active{transform:scale(0.94);}
.cobabot-send:disabled{background:#B7BFD6;box-shadow:none;cursor:not-allowed;transform:none;}
.cobabot-send svg{width:18px;height:18px;}

.cobabot-msg.error{align-self:flex-start;background:#FEF2F2;color:#DC2626;border:1px solid rgba(220,38,38,0.12);border-radius:4px 18px 18px 18px;}

.cobabot-welcome{text-align:center;padding:30px 20px 10px;}
.cobabot-welcome-icon{width:56px;height:56px;border-radius:18px;background:linear-gradient(135deg,#3557E8,#4B6EF0);display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 12px;box-shadow:0 8px 24px rgba(53,87,232,0.25);}
.cobabot-welcome h4{font-family:'Space Grotesk',sans-serif;font-size:1rem;color:#0E1630;margin-bottom:4px;}
.cobabot-welcome p{font-size:0.8rem;color:#5B6478;line-height:1.5;}

@media(max-width:768px){
  .cobabot-popup{right:0!important;bottom:0!important;left:0!important;width:100%!important;height:100%!important;max-height:100dvh!important;border-radius:0!important;box-shadow:none!important;}
  .cobabot-popup.is-open{transform:translateY(0)!important;}
  .cobabot-btn{bottom:20px!important;right:20px!important;width:56px!important;height:56px!important;}
  .cobabot-header{padding:16px!important;border-radius:0!important;}
  .cobabot-messages{padding:16px!important;gap:10px!important;}
  .cobabot-msg{max-width:88%!important;font-size:.86rem!important;padding:10px 14px!important;}
  .cobabot-input-wrap{padding:12px 14px 14px!important;padding-bottom:max(14px,env(safe-area-inset-bottom))!important;}
  .cobabot-input{font-size:16px!important;padding:10px 14px!important;}
}
</style>
</head>
<body>

<div class="burst drift" style="top:120px; left:-60px; width:180px; height:180px;">
  <svg viewBox="0 0 100 100"><path d="M50 0 L58 40 L100 50 L58 60 L50 100 L42 60 L0 50 L42 40 Z" fill="#7C9BFF"/></svg>
</div>
<div class="burst drift" style="top:520px; right:-40px; width:140px; height:140px; animation-delay:2s;">
  <svg viewBox="0 0 100 100"><path d="M50 0 L58 40 L100 50 L58 60 L50 100 L42 60 L0 50 L42 40 Z" fill="#3557E8"/></svg>
</div>
<div class="burst drift" style="top:1500px; left:-30px; width:120px; height:120px; animation-delay:1s;">
  <svg viewBox="0 0 100 100"><path d="M50 0 L58 40 L100 50 L58 60 L50 100 L42 60 L0 50 L42 40 Z" fill="#7C9BFF"/></svg>
</div>

<nav class="navbar" id="navbar">
  <div class="logo">adrian<span class="dot">ganteng</span></div>
  <ul class="nav-links" id="navLinks">
    <li><a href="#home" class="active">Home</a></li>
    <li><a href="#about">About</a></li>
    <li><a href="#services">Services</a></li>
    <li><a href="#portfolio">Portofolio</a></li>
    <li><a href="#cta" class="nav-cta">Konsultasi Gratis</a></li>
  </ul>
  <button class="nav-toggle" id="navToggle" aria-label="Buka menu">
    <span></span><span></span><span></span>
  </button>
</nav>

<header class="hero" id="home">
  <div class="wrap">
    <div class="hero-copy reveal">
      <p class="eyebrow">Digital Agency · Website Development</p>
      <h1>Website Profesional untuk <span class="accent">Mengembangkan Bisnis Anda.</span></h1>
      <p>Website yang tampil profesional membangun kepercayaan sejak kunjungan pertama. cobaWeb membantu bisnis Anda tampil lebih meyakinkan di mata calon pelanggan.</p>
      <div class="hero-actions">
        <a href="#cta" class="btn btn-primary">Konsultasi Gratis</a>
        <a href="#portfolio" class="btn btn-ghost">Lihat Portofolio</a>
      </div>
      <div class="quick-nav">
        <a class="quick-card" href="#about"><span>About</span><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M7 17L17 7M17 7H8M17 7V16"/></svg></a>
        <a class="quick-card" href="#services"><span>Services</span><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M7 17L17 7M17 7H8M17 7V16"/></svg></a>
        <a class="quick-card" href="#portfolio"><span>Portofolio</span><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M7 17L17 7M17 7H8M17 7V16"/></svg></a>
      </div>
    </div>
    <div class="hero-visual reveal">
      <div class="hero-photo">
        <img src="https://images.unsplash.com/photo-1742198810079-49bb51d1c5af?fm=jpg&q=70&w=1200&auto=format&fit=crop" alt="Ruang kerja digital modern">
      </div>
      <div class="browser-card" id="browserCard">
        <div class="browser-bar"><span></span><span></span><span></span></div>
        <div class="browser-body">
          <div class="wordmark">coba<span class="dot">Web</span></div>
          <p>PROFESSIONAL WEBSITE DEVELOPMENT</p>
        </div>
      </div>
    </div>
  </div>
</header>

<section class="about" id="about">
  <div class="wrap">
    <div class="section-head reveal">
      <p class="eyebrow">About</p>
      <h2>Kenapa Klien Percaya cobaWeb</h2>
      <p>cobaWeb adalah digital agency yang fokus membangun website profesional sebagai fondasi kredibilitas bisnis, dirancang untuk mendukung pertumbuhan Anda di era digital.</p>
    </div>
    <div class="about-visual reveal">
      <img src="https://images.unsplash.com/photo-1751257983922-a627088d4c21?fm=jpg&q=70&w=1600&auto=format&fit=crop" alt="Tim cobaWeb bekerja di ruang kerja modern">
    </div>
    <div class="about-grid">
      <div class="value-card reveal">
        <div class="value-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/></svg></div>
        <h3>Desain Premium</h3>
        <p>Tampilan modern dan elegan yang disesuaikan dengan identitas bisnis Anda.</p>
      </div>
      <div class="value-card reveal">
        <div class="value-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h7l-1 8 11-14h-8l1-6z"/></svg></div>
        <h3>Performa Tinggi</h3>
        <p>Website ringan, cepat diakses, dan stabil di berbagai perangkat.</p>
      </div>
      <div class="value-card reveal">
        <div class="value-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4"/></svg></div>
        <h3>Pengalaman Pengguna</h3>
        <p>Navigasi yang mudah dan nyaman digunakan oleh setiap pengunjung.</p>
      </div>
    </div>
  </div>
</section>

<section class="services" id="services">
  <div class="wrap">
    <div class="services-top">
      <div class="section-head reveal">
        <p class="eyebrow" style="color:#7C9BFF;">Services</p>
        <h2>Solusi Sesuai Kebutuhan Bisnis Anda</h2>
        <p>Dari identitas korporat hingga optimasi tampilan lama, setiap layanan dirancang untuk hasil yang terukur.</p>
      </div>
      <div class="services-photo reveal">
        <img src="https://images.unsplash.com/photo-1746021535489-00edc5efb203?fm=jpg&q=70&w=1200&auto=format&fit=crop" alt="Proses pengembangan website cobaWeb">
      </div>
    </div>
    <div class="services-grid">
      <div class="service-card reveal">
        <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18"/></svg></div>
        <div><h3>Company Profile</h3><p>Membangun citra perusahaan yang profesional dan mudah dipercaya calon klien.</p></div>
      </div>
      <div class="service-card reveal">
        <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/><path d="M9 18h6"/></svg></div>
        <div><h3>Landing Page</h3><p>Halaman fokus konversi untuk kebutuhan promosi dan campaign digital.</p></div>
      </div>
      <div class="service-card reveal">
        <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="M4 9h16M9 21V9"/></svg></div>
        <div><h3>Website UMKM</h3><p>Identitas digital yang membuat usaha Anda lebih dikenal dan dipercaya.</p></div>
      </div>
      <div class="service-card reveal">
        <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 11-3.5-7.1M21 4v5h-5"/></svg></div>
        <div><h3>Website Redesign</h3><p>Transformasi website lama menjadi lebih modern dan sesuai kebutuhan bisnis.</p></div>
      </div>
    </div>
  </div>
</section>

<section class="portfolio" id="portfolio">
  <div class="wrap">
    <div class="section-head reveal">
      <p class="eyebrow">Portofolio</p>
      <h2>Beberapa Hasil Pengembangan Website</h2>
      <p>Setiap proyek dirancang sesuai kebutuhan klien, dengan fokus pada kredibilitas dan pengalaman terbaik bagi pengunjung.</p>
    </div>
    <div class="portfolio-grid">
      <div class="mock reveal">
        <div class="mock-bar"><span></span><span></span><span></span></div>
        <div class="mock-body">
          <img src="https://images.unsplash.com/photo-1746021535489-00edc5efb203?fm=jpg&q=70&w=900&auto=format&fit=crop" alt="Company profile kantor hukum">
          <div><span>COMPANY PROFILE</span><h4>Kantor Hukum & Notaris</h4></div>
        </div>
      </div>
      <div class="mock reveal">
        <div class="mock-bar"><span></span><span></span><span></span></div>
        <div class="mock-body">
          <img src="https://images.unsplash.com/photo-1755001437609-bb2abcde9755?fm=jpg&q=70&w=900&auto=format&fit=crop" alt="Landing page campaign F&B">
          <div><span>LANDING PAGE</span><h4>Campaign Produk F&amp;B</h4></div>
        </div>
      </div>
      <div class="mock reveal">
        <div class="mock-bar"><span></span><span></span><span></span></div>
        <div class="mock-body">
          <img src="https://images.unsplash.com/photo-1751257983922-a627088d4c21?fm=jpg&q=70&w=900&auto=format&fit=crop" alt="Website UMKM kedai kopi lokal">
          <div><span>WEBSITE UMKM</span><h4>Kedai Kopi Lokal</h4></div>
        </div>
      </div>
      <div class="mock reveal">
        <div class="mock-bar"><span></span><span></span><span></span></div>
        <div class="mock-body">
          <img src="https://images.unsplash.com/photo-1742198810079-49bb51d1c5af?fm=jpg&q=70&w=900&auto=format&fit=crop" alt="Website redesign konsultan bisnis">
          <div><span>WEBSITE REDESIGN</span><h4>Konsultan Bisnis</h4></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="cta" id="cta">
  <div class="wrap">
    <div class="cta-box reveal">
      <h2>Siap Meningkatkan Kredibilitas Bisnis Anda?</h2>
      <p>Mari bangun website profesional yang merepresentasikan kualitas bisnis Anda dan memberikan kesan terbaik kepada setiap calon pelanggan.</p>
      <a href="#" class="btn btn-primary" id="ctaBtn">Jadwalkan Konsultasi Gratis</a>
    </div>
  </div>
</section>

<footer>
  <div class="wrap">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="logo">coba<span class="dot">Web</span></div>
        <p>Membangun website yang profesional, modern, dan terpercaya untuk mendukung pertumbuhan bisnis di era digital.</p>
      </div>
      <div class="footer-col">
        <h4>Navigasi</h4>
        <a href="#home">Home</a>
        <a href="#about">About</a>
        <a href="#services">Services</a>
        <a href="#portfolio">Portofolio</a>
      </div>
      <div class="footer-col">
        <h4>Kontak</h4>
        <span>hello@cobaweb.id</span>
        <span>+62 8xx-xxxx-xxxx</span>
        <span>Jakarta, Indonesia</span>
        <a href="#">@cobaweb.id</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 cobaWeb. Semua hak dilindungi.</span>
      <span>Professional Website Development</span>
    </div>
  </div>
</footer>

<script>
  // ---------- navbar scroll shadow + active link ----------
  const navbar = document.getElementById('navbar');
  const sections = document.querySelectorAll('section, header');
  const navLinks = document.querySelectorAll('.nav-links a[href^="#"]');

  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 30);
    let current = '';
    sections.forEach(sec => {
      const top = sec.offsetTop - 140;
      if (window.scrollY >= top) current = sec.getAttribute('id');
    });
    navLinks.forEach(link => {
      link.classList.toggle('active', link.getAttribute('href') === '#' + current);
    });
  });

  // ---------- mobile menu ----------
  const navToggle = document.getElementById('navToggle');
  const navLinksList = document.getElementById('navLinks');
  navToggle.addEventListener('click', () => navLinksList.classList.toggle('open'));
  navLinksList.querySelectorAll('a').forEach(a => a.addEventListener('click', () => navLinksList.classList.remove('open')));

  // ---------- scroll reveal ----------
  const revealEls = document.querySelectorAll('.reveal');
  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) { entry.target.classList.add('visible'); io.unobserve(entry.target); }
    });
  }, { threshold: 0.15 });
  revealEls.forEach(el => io.observe(el));

  // ---------- hero parallax ----------
  const card = document.getElementById('browserCard');
  const heroVisual = document.querySelector('.hero-visual');
  if (card && heroVisual) {
    heroVisual.addEventListener('mousemove', (e) => {
      const rect = heroVisual.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;
      card.style.transform = `rotate(${-6 + x * 6}deg) translate(${x * 10}px, ${y * 10}px)`;
    });
    heroVisual.addEventListener('mouseleave', () => { card.style.transform = 'rotate(-6deg) translate(0,0)'; });
  }

  // ---------- CTA button ----------
  document.getElementById('ctaBtn').addEventListener('click', (e) => {
    e.preventDefault();
    alert('Terima kasih! Silakan hubungi kami melalui WhatsApp atau email di bagian kontak untuk menjadwalkan konsultasi gratis.');
  });
</script>

<!-- ── Floating Chatbot ── -->
<div class="cobabot">
  <button class="cobabot-btn" id="cobabotBtn" aria-label="Buka chat">
    <svg class="icon-chat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
      <path d="M8 10h.01M12 10h.01M16 10h.01"/>
    </svg>
    <svg class="icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
    </svg>
    <span class="badge" id="cobabotBadge" style="display:none;">1</span>
  </button>

  <div class="cobabot-popup" id="cobabotPopup">
    <div class="cobabot-header">
      <div class="cobabot-avatar">🤖</div>
      <div class="cobabot-header-info">
        <h3>cobaBot</h3>
        <p>Online</p>
      </div>
      <button class="cobabot-close" id="cobabotClose" aria-label="Tutup chat">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <div class="cobabot-messages" id="cobabotMessages">
      <div class="cobabot-welcome">
        <div class="cobabot-welcome-icon">🤖</div>
        <h4>Halo! Saya cobaBot</h4>
        <p>Asisten AI yang siap membantu Anda. Kirim pesan untuk memulai percakapan.</p>
      </div>
    </div>

    <div class="cobabot-input-wrap">
      <textarea class="cobabot-input" id="cobabotInput" rows="1" placeholder="Ketik pesan..." autocomplete="off"></textarea>
      <button class="cobabot-send" id="cobabotSend" aria-label="Kirim">
        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
          <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
        </svg>
      </button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded',()=>{
  const btn=document.getElementById('cobabotBtn');
  const popup=document.getElementById('cobabotPopup');
  const close=document.getElementById('cobabotClose');
  const messages=document.getElementById('cobabotMessages');
  const input=document.getElementById('cobabotInput');
  const send=document.getElementById('cobabotSend');
  const badge=document.getElementById('cobabotBadge');
  let isOpen=false;
  const conversation=[];
  function toggle(){isOpen=!isOpen;btn.classList.toggle('is-open',isOpen);popup.classList.toggle('is-open',isOpen);if(isOpen){input.focus();badge.style.display='none';}}
  function formatTime(){const n=new Date();return n.getHours().toString().padStart(2,'0')+':'+n.getMinutes().toString().padStart(2,'0');}
  function addMessage(text,role){const d=document.createElement('div');d.className='cobabot-msg '+role;d.textContent=text;const t=document.createElement('div');t.className='cobabot-msg-time';t.textContent=formatTime();d.appendChild(t);messages.appendChild(d);messages.scrollTop=messages.scrollHeight;}
  function addTyping(){const d=document.createElement('div');d.className='cobabot-typing';d.id='cobabotTyping';for(let i=0;i<3;i++){const s=document.createElement('span');d.appendChild(s);}messages.appendChild(d);messages.scrollTop=messages.scrollHeight;}
  function removeTyping(){const e=document.getElementById('cobabotTyping');if(e)e.remove();}
  function addError(m){const d=document.createElement('div');d.className='cobabot-msg error';d.textContent=m||'Maaf, terjadi kesalahan. Silakan coba lagi.';messages.appendChild(d);messages.scrollTop=messages.scrollHeight;}
  function autoResize(){input.style.height='auto';input.style.height=Math.min(input.scrollHeight,100)+'px';}
  async function sendMessage(){const text=input.value.trim();if(!text)return;input.value='';input.style.height='auto';send.disabled=true;addMessage(text,'user');conversation.push({role:'user',text});addTyping();try{const res=await fetch('/api/chat',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({conversation})});removeTyping();if(!res.ok){addError();return;}const data=await res.json();const reply=data.output||'';addMessage(reply,'bot');conversation.push({role:'model',text:reply});}catch{removeTyping();addError();}finally{send.disabled=false;input.focus();}}
  btn.addEventListener('click',toggle);close.addEventListener('click',toggle);send.addEventListener('click',sendMessage);input.addEventListener('input',autoResize);input.addEventListener('keydown',e=>{if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();sendMessage();}});
  setTimeout(()=>{if(!isOpen){badge.style.display='flex';}},5000);
});
</script>
</body>
</html>