<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIMTIK — Situbondo</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
<style>
  :root{
    --navy-950:#0B1526;
    --navy-800:#142238;
    --navy-700:#1B2C48;
    --teal-400:#2DD4BF;
    --teal-300:#7FE9DA;
    --amber-500:#F5A623;
    --paper:#F6F4EE;
    --ink:#101826;
    --slate-500:#64748B;
    --slate-300:#B9C2D0;
    --line:rgba(255,255,255,0.10);
    --line-dark:rgba(16,24,38,0.10);
    --radius:14px;
  }
  *{box-sizing:border-box;margin:0;padding:0;}
  html{scroll-behavior:smooth;}
  body{
    font-family:'Inter',sans-serif;
    background:var(--paper);
    color:var(--ink);
    line-height:1.55;
    -webkit-font-smoothing:antialiased;
  }
  h1,h2,h3,.display{font-family:'Space Grotesk',sans-serif;font-weight:600;letter-spacing:-0.01em;}
  .mono{font-family:'IBM Plex Mono',monospace;}
  a{color:inherit;text-decoration:none;}
  img,svg{display:block;max-width:100%;}
  .wrap{max-width:1180px;margin:0 auto;padding:0 28px;}
  :focus-visible{outline:2.5px solid var(--amber-500);outline-offset:3px;border-radius:4px;}

  /* ---------- Header ---------- */
  header{
    position:sticky;top:0;z-index:50;
    background:rgba(11,21,38,0.92);
    backdrop-filter:blur(10px);
    border-bottom:1px solid var(--line);
  }
  .nav{display:flex;align-items:center;justify-content:space-between;padding:16px 28px;color:var(--paper);}
  .brand{display:flex;align-items:center;gap:10px;font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.05rem;color:#fff;}
  .brand .mark{
    width:32px;height:32px;border-radius:8px;
    background:linear-gradient(135deg,var(--teal-400),var(--navy-700));
    display:flex;align-items:center;justify-content:center;
    font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:.75rem;color:var(--navy-950);
  }
  .brand small{display:block;font-family:'Inter',sans-serif;font-weight:400;font-size:.68rem;color:var(--slate-300);letter-spacing:.02em;}
  nav ul{display:flex;gap:30px;list-style:none;}
  nav ul a{font-size:.9rem;color:var(--slate-300);transition:color .2s;}
  nav ul a:hover{color:#fff;}
  .nav-actions{display:flex;gap:10px;align-items:center;}
  .btn{
    display:inline-flex;align-items:center;gap:8px;
    padding:10px 20px;border-radius:999px;
    font-size:.88rem;font-weight:600;cursor:pointer;border:1px solid transparent;
    transition:transform .15s ease, box-shadow .15s ease, background .15s ease;
  }
  .btn:hover{transform:translateY(-1px);}
  .btn-primary{background:var(--amber-500);color:var(--navy-950);}
  .btn-primary:hover{box-shadow:0 8px 20px -6px rgba(245,166,35,.5);}
  .btn-ghost{border-color:var(--line);color:#fff;}
  .btn-ghost:hover{background:rgba(255,255,255,.06);}
  .menu-toggle{display:none;background:none;border:0;color:#fff;font-size:1.4rem;cursor:pointer;}

  /* ---------- Hero ---------- */
  .hero{
    background:radial-gradient(ellipse 90% 60% at 50% 0%, var(--navy-700) 0%, var(--navy-950) 60%);
    color:#fff;padding:88px 0 60px;position:relative;overflow:hidden;
  }
  .rings-bg{position:absolute;inset:0;z-index:0;pointer-events:none;}
  .rings-bg canvas{display:block;width:100%;height:100%;}
  .hero .wrap{position:relative;z-index:1;}
  .hero-grid{display:flex;flex-direction:column;align-items:center;text-align:center;max-width:740px;margin:0 auto;}
  .eyebrow{
    display:inline-flex;align-items:center;gap:8px;
    font-family:'IBM Plex Mono',monospace;font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;
    color:var(--teal-300);border:1px solid rgba(45,212,191,.35);background:rgba(45,212,191,.08);
    padding:6px 12px;border-radius:999px;margin-bottom:22px;
  }
  .eyebrow .dot{width:6px;height:6px;border-radius:50%;background:var(--teal-400);box-shadow:0 0 0 3px rgba(45,212,191,.25);}
  .hero h1{font-size:clamp(2rem,4.4vw,3.1rem);line-height:1.1;margin-bottom:20px;}
  .hero h1 em{font-style:normal;color:var(--teal-300);}
  .hero p.lead{font-size:1.06rem;color:var(--slate-300);max-width:520px;margin-bottom:32px;}
  .hero-ctas{display:flex;gap:14px;flex-wrap:wrap;justify-content:center;margin-bottom:40px;}
  .hero-meta{display:flex;gap:28px;flex-wrap:wrap;justify-content:center;padding-top:26px;border-top:1px solid var(--line);}
  .hero-meta div{font-family:'IBM Plex Mono',monospace;}
  .hero-meta .num{font-size:1.4rem;color:#fff;font-weight:500;}
  .hero-meta .lbl{font-size:.72rem;color:var(--slate-500);letter-spacing:.02em;}

  /* network illustration */
  .cakupan-visual{position:relative;width:100%;max-width:440px;aspect-ratio:1/1;margin:0 auto 48px;}
  .netmap{position:relative;width:100%;height:100%;}
  .netmap svg{width:100%;height:100%;}
  .statistik .section-head{margin-left:auto;margin-right:auto;text-align:center;}
  .node-pulse{animation:pulse 2.6s ease-in-out infinite;}
  @keyframes pulse{0%,100%{opacity:.55;} 50%{opacity:1;}}
  .flow-line{stroke-dasharray:6 8;animation:flow 3.2s linear infinite;}
  @keyframes flow{to{stroke-dashoffset:-140;}}
  @media (prefers-reduced-motion: reduce){
    .node-pulse,.flow-line{animation:none;}
  }

  /* ---------- Sections generic ---------- */
  section{padding:88px 0;}
  .section-head{max-width:640px;margin-bottom:52px;}
  .section-head .eyebrow-dark{
    font-family:'IBM Plex Mono',monospace;font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;
    color:var(--teal-400) ;background:rgba(45,212,191,.10);border:1px solid rgba(45,212,191,.3);
    display:inline-block;padding:5px 12px;border-radius:999px;margin-bottom:16px;
  }
  .section-head h2{font-size:clamp(1.6rem,3vw,2.2rem);margin-bottom:14px;}
  .section-head p{color:var(--slate-500);font-size:1.02rem;}

  /* ---------- Konteks / masalah ---------- */
  .konteks{background:var(--paper);}
  .konteks-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;}
  .kartu{
    background:#fff;border:1px solid var(--line-dark);border-radius:var(--radius);
    padding:26px 24px;
  }
  .kartu .ic{
    width:38px;height:38px;border-radius:10px;background:var(--navy-950);
    display:flex;align-items:center;justify-content:center;margin-bottom:16px;color:var(--teal-400);
  }
  .kartu h3{font-size:1.02rem;margin-bottom:8px;}
  .kartu p{font-size:.92rem;color:var(--slate-500);}

  /* ---------- Layanan ---------- */
  .layanan{background:var(--navy-950);color:#fff;}
  .layanan .section-head p{color:var(--slate-300);}
  .fitur-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--line);border:1px solid var(--line);border-radius:var(--radius);overflow:hidden;}
  .fitur{background:var(--navy-800);padding:30px 24px;display:block;transition:background .2s;}
  .fitur:not(.fitur-disabled):hover{background:var(--navy-700);}
  .fitur .tag{font-family:'IBM Plex Mono',monospace;font-size:.72rem;color:var(--teal-300);margin-bottom:14px;display:block;}
  .fitur h3{font-size:1.02rem;margin-bottom:10px;}
  .fitur p{font-size:.88rem;color:var(--slate-300);}
  .badge-new{margin-left:8px;font-family:'Inter',sans-serif;font-size:.6rem;font-weight:700;letter-spacing:.03em;text-transform:uppercase;background:var(--amber-500);color:var(--navy-950);padding:2px 8px;border-radius:999px;vertical-align:middle;}
  .fitur-disabled{opacity:.5;cursor:default;pointer-events:none;}

  /* ---------- Alur kerja ---------- */
  .alur{background:var(--paper);}
  .alur-list{display:grid;grid-template-columns:repeat(4,1fr);gap:0;position:relative;}
  .alur-list::before{
    content:"";position:absolute;top:23px;left:6%;right:6%;height:1px;background:var(--line-dark);
  }
  .langkah{position:relative;padding-right:18px;}
  .langkah .idx{
    width:46px;height:46px;border-radius:50%;background:#fff;border:1.5px solid var(--navy-950);
    display:flex;align-items:center;justify-content:center;font-family:'IBM Plex Mono',monospace;
    font-size:.85rem;color:var(--navy-950);margin-bottom:20px;position:relative;z-index:1;
  }
  .langkah h3{font-size:.98rem;margin-bottom:8px;}
  .langkah p{font-size:.88rem;color:var(--slate-500);}

  /* ---------- Statistik ---------- */
  .statistik{background:var(--navy-950);color:#fff;padding:70px 0;}
  .stat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--line);border:1px solid var(--line);border-radius:var(--radius);overflow:hidden;}
  .stat{background:var(--navy-800);padding:34px 26px;text-align:left;}
  .stat .num{font-family:'Space Grotesk',sans-serif;font-size:2.1rem;color:var(--teal-300);margin-bottom:6px;}
  .stat .lbl{font-size:.85rem;color:var(--slate-300);}

  /* ---------- CTA akhir ---------- */
  .cta-akhir{background:var(--paper);padding:80px 0;}
  .cta-box{
    background:linear-gradient(120deg,var(--navy-950),var(--navy-700));
    border-radius:20px;padding:56px;color:#fff;
    display:flex;align-items:center;justify-content:space-between;gap:40px;flex-wrap:wrap;
  }
  .cta-box h2{font-size:1.7rem;max-width:420px;}
  .cta-box p{color:var(--slate-300);margin-top:10px;max-width:420px;font-size:.95rem;}

  /* ---------- Footer ---------- */
  footer{background:var(--navy-950);color:var(--slate-300);padding:56px 0 28px;border-top:1px solid var(--line);}
  .footer-grid{display:grid;grid-template-columns:1.2fr 1fr 1fr 1fr;gap:32px;margin-bottom:40px;}
  .footer-grid h4{color:#fff;font-size:.85rem;margin-bottom:16px;font-family:'Space Grotesk',sans-serif;}
  .footer-grid ul{list-style:none;}
  .footer-grid li{margin-bottom:9px;font-size:.88rem;}
  .footer-grid li a:hover{color:#fff;}
  .footer-bottom{
    display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;
    border-top:1px solid var(--line);padding-top:22px;font-size:.78rem;color:var(--slate-500);
  }
  .placeholder{color:var(--amber-500);}

  /* ---------- Responsive ---------- */
  @media (max-width:980px){
    nav ul,.nav-actions .btn-ghost{display:none;}
    .menu-toggle{display:block;}
    .hero-grid{grid-template-columns:1fr;}
    .konteks-grid,.fitur-grid,.alur-list,.stat-grid,.footer-grid{grid-template-columns:repeat(2,1fr);}
    .alur-list::before{display:none;}
  }
  @media (max-width:560px){
    .konteks-grid,.fitur-grid,.alur-list,.stat-grid,.footer-grid{grid-template-columns:1fr;}
    .cta-box{flex-direction:column;align-items:flex-start;}
    .hero{padding:70px 0 44px;}
  }

  /* ---------- SIMTIK landing page ---------- */
  :root{
    --navy-950:#03132b;
    --navy-800:#08234a;
    --navy-700:#0b3470;
    --teal-400:#00d8d0;
    --teal-300:#38e4dc;
    --amber-500:#00a8ff;
    --paper:#03132b;
    --ink:#f4f8ff;
    --slate-500:#b5c8e4;
    --slate-300:#c9d9ef;
    --line:rgba(62,155,255,.2);
    --line-dark:rgba(62,155,255,.2);
    --radius:16px;
  }
  body{background:#03132b;color:#f4f8ff;}
  header{background:rgba(2,15,35,.88);border-color:rgba(69,161,255,.18);}
  .nav{min-height:68px;padding:10px 28px;gap:22px;}
  .brand{font-size:1.08rem;line-height:1.2;white-space:nowrap;}
  .brand .mark{width:36px;height:36px;background:linear-gradient(135deg,#00e6dc,#0079ed);color:#03214a;}
  nav ul{gap:8px;}
  nav ul a{display:block;padding:8px 12px;border-radius:999px;font-size:.83rem;color:#c1d5ef;}
  nav ul a:hover,nav ul a[href="#beranda"]{background:rgba(0,120,255,.28);color:#0bbdff;}
  .mobile-register{display:none;}
  .nav-actions{gap:8px;}
  .nav-actions .btn{font-size:.76rem;padding:8px 13px;}
  .nav-actions .btn-ghost:first-child{border-color:transparent;}
  .nav-actions #daftar-server-btn{border-color:#008cff;color:#f4f8ff;background:rgba(0,125,255,.06);}
  .nav-actions #daftar-server-btn:hover{background:rgba(0,150,255,.17);box-shadow:0 0 22px rgba(0,151,255,.18);}
  .menu-toggle{font-size:0;width:34px;height:34px;position:relative;flex:0 0 34px;}
  .menu-toggle::before,.menu-toggle::after{content:"";position:absolute;left:6px;width:22px;height:1.5px;background:#d5e5f8;}
  .menu-toggle::before{top:11px;box-shadow:0 6px 0 #d5e5f8;}
  .menu-toggle::after{top:23px;}
  main{position:relative;isolation:isolate;overflow:hidden;background:radial-gradient(ellipse 80% 55% at 50% 0%,#092c64 0%,#03132b 76%);}
  .rings-bg{inset:0;z-index:-1;}
  .rings-bg canvas{opacity:.9;}
  .hero{padding:32px 0 24px;background:transparent;overflow:visible;}
  .hero-grid{max-width:850px;}
  .eyebrow{margin-bottom:15px;font-size:.67rem;color:#27e8e0;border-color:rgba(0,216,208,.38);background:rgba(0,216,208,.08);}
  .hero h1{max-width:830px;margin:0 auto 14px;font-size:clamp(2.15rem,4.4vw,3.35rem);line-height:1.08;letter-spacing:-.035em;}
  .hero h1 em{color:#38e4dc;}
  .hero p.lead{max-width:740px;margin:0 auto 19px;font-size:.98rem;line-height:1.62;color:#c5d8ef;}
  .hero-ctas{margin:0;gap:0;}
  .hero-ctas .btn-primary{background:#00a8ff;color:#03132b;padding:10px 22px;box-shadow:0 0 24px rgba(0,157,255,.22);}
  .section-head{max-width:none;margin:0 auto 12px;text-align:center;}
  .section-head h2{font-size:1.42rem;line-height:1.2;margin:0;}
  .section-head .eyebrow-dark{display:none;}
  .konteks,.layanan{position:relative;padding:16px 0;background:transparent;color:#f4f8ff;}
  .konteks-grid{grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;}
  .kartu{
    min-height:126px;padding:17px 18px;display:grid;grid-template-columns:56px minmax(0,1fr);grid-template-rows:auto 1fr;column-gap:14px;
    position:relative;overflow:hidden;border:1px solid rgba(0,137,255,.48);border-radius:15px;
    background:linear-gradient(125deg,rgba(5,43,96,.78),rgba(5,28,66,.72));box-shadow:inset 0 1px rgba(255,255,255,.025);
  }
  .kartu:nth-child(2){border-color:rgba(0,208,190,.43);}
  .kartu .ic{width:56px;height:56px;grid-row:1 / span 2;margin:0;border-radius:16px;background:rgba(0,121,255,.3);color:#16b8ff;box-shadow:0 0 18px rgba(0,118,255,.15);}
  .kartu:nth-child(2) .ic{background:rgba(0,198,174,.22);color:#16e5cc;box-shadow:0 0 18px rgba(0,211,186,.12);}
  .kartu:nth-child(3) .ic{background:rgba(255,140,44,.2);color:#ffae4d;box-shadow:0 0 18px rgba(255,140,44,.12);}
  .kartu h3{align-self:center;margin:0 0 5px;font-size:.96rem;color:#f7faff;}
  .kartu p{font-size:.77rem;line-height:1.48;color:#bfd1e9;}
  .informasi{padding:4px 0 24px;}
  .informasi-panel{max-width:960px;margin:0 auto;display:grid;grid-template-columns:56px minmax(130px,180px) 1fr;align-items:center;gap:16px;padding:12px 20px;border:1px solid rgba(0,137,255,.46);border-radius:15px;background:linear-gradient(110deg,rgba(5,43,96,.8),rgba(5,28,66,.78));}
  .informasi-icon{width:44px;height:44px;display:grid;place-items:center;border-radius:50%;background:rgba(0,121,255,.32);color:#e5f5ff;box-shadow:0 0 18px rgba(0,140,255,.22);}
  .informasi-panel h2{font-family:'Space Grotesk',sans-serif;font-size:.96rem;line-height:1.3;}
  .informasi-panel p{border-left:1px solid rgba(0,145,255,.55);padding-left:20px;color:#c4d7ef;font-size:.79rem;line-height:1.55;}
  @media (max-width:980px){
    nav ul{display:none;position:absolute;top:100%;left:0;right:0;padding:12px 20px 18px;background:rgba(2,15,35,.97);border-bottom:1px solid var(--line);flex-direction:column;gap:4px;}
    nav ul.is-open{display:flex;}
    .nav-actions .btn-ghost:first-child{display:none;}
      .nav-actions #daftar-server-btn{display:inline-flex;}
      .menu-toggle{display:block;}
      .konteks-grid{grid-template-columns:repeat(3,minmax(0,1fr));}
    }
    @media (max-width:700px){
    .wrap{padding:0 20px;}
    .nav{padding:10px 18px;gap:10px;}
    .brand small{max-width:145px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
    .nav-actions #daftar-server-btn{display:none;}
    .mobile-register{display:block;}
    .mobile-register a{margin-top:8px;border:1px solid #008cff;color:#f4f8ff;background:rgba(0,125,255,.08);}
    .nav-actions #daftar-server-btn{font-size:.68rem;padding:8px 10px;gap:5px;}
    .nav-actions #daftar-server-btn svg{width:14px;height:14px;}
    .hero{padding:38px 0 22px;}
    .hero h1{font-size:clamp(2rem,7vw,2.65rem);}
    .hero p.lead{font-size:.91rem;}
    .konteks-grid{grid-template-columns:1fr;gap:12px;}
    .kartu{min-height:unset;padding:16px;}
    .informasi-panel{grid-template-columns:46px 1fr;gap:12px;padding:15px;}
    .informasi-icon{width:42px;height:42px;grid-row:1;}
    .informasi-panel p{grid-column:1 / -1;border-left:0;border-top:1px solid rgba(0,145,255,.45);padding:11px 0 0;}
  }
  @media (max-width:380px){
    .brand small{font-size:.58rem;}
    .nav-actions #daftar-server-btn{font-size:.61rem;padding:7px 8px;}
    .nav-actions #daftar-server-btn svg{display:none;}
    .wrap{padding:0 16px;}
  }
</style>
</head>
<body>

<header>
  <div class="nav">
    <div class="brand">
      <span class="mark">ST</span>
      <span>SIMTIK<small>Diskominfo Kabupaten Situbondo</small></span>
    </div>
    <nav><ul>
      <li class="mobile-register"><a href="/register">Daftar untuk Tiap Server</a></li>
    </ul></nav>
    <div class="nav-actions">
      <a href="/login" class="btn btn-ghost">Masuk</a>
      <a href="/register" class="btn btn-ghost" id="daftar-server-btn">
        <!-- Simple server icon: rectangle with two lines inside -->
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="2" y="4" width="12" height="8" rx="2" stroke="currentColor" stroke-width="1.5"/>
          <line x1="4" y1="6" x2="12" y2="6" stroke="currentColor" stroke-width="1.5"/>
          <line x1="4" y1="10" x2="12" y2="10" stroke="currentColor" stroke-width="1.5"/>
        </svg>
        Daftar untuk Tiap Server
      </a>
    </div>
    <button class="menu-toggle" type="button" aria-label="Buka menu" aria-expanded="false">Menu</button>
  </div>
</header>

<main>
  <div class="rings-bg" id="hero-rings-bg" aria-hidden="true"></div>
  <section class="hero" id="beranda">
    <div class="wrap hero-grid">
      <span class="eyebrow"><span class="dot"></span> SISTEM INFORMASI MANAJEMEN SDTIK</span>
      <h1>Sistem Informasi Manajemen <em>Infrastruktur dan Aset TIK</em></h1>
      <p class="lead">Pantau dan kelola informasi Jaringan Intra, Server, dan Aset TIK Kabupaten Situbondo melalui satu dashboard terintegrasi untuk mendukung pengelolaan infrastruktur digital yang efektif, tertata, dan berkelanjutan.</p>
      <div class="hero-ctas">
        <a href="/login" class="btn btn-primary">Mulai <span aria-hidden="true">→</span></a>
      </div>
    </div>
  </section>

  <section class="konteks" id="cakupan">
    <div class="wrap">
      <div class="section-head">
        <h2>Cakupan Pengelolaan</h2>
      </div>
      <div class="konteks-grid">
        <article class="kartu">
          <div class="ic" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="6" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
              <circle cx="12" cy="6" r="3" stroke="currentColor" stroke-width="2"/>
              <circle cx="18" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
              <circle cx="12" cy="18" r="3" stroke="currentColor" stroke-width="2"/>
              <path d="m8.5 10.5 1.4-2.1m4.2 0 1.4 2.1m-7 3 1.4 2.1m4.2 0 1.4-2.1" stroke="currentColor" stroke-width="2"/>
            </svg>
          </div>
          <h3>Jaringan Intra</h3>
          <p>Informasi jaringan intra pemerintah daerah untuk mendukung pemantauan infrastruktur konektivitas dan keterhubungan antarperangkat atau lokasi layanan.</p>
        </article>
        <article class="kartu">
          <div class="ic" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="4" y="4" width="16" height="7" rx="2" stroke="currentColor" stroke-width="2"/>
              <rect x="4" y="13" width="16" height="7" rx="2" stroke="currentColor" stroke-width="2"/>
              <circle cx="8" cy="7.5" r="1" fill="currentColor"/><circle cx="8" cy="16.5" r="1" fill="currentColor"/>
              <path d="M12 7.5h5M12 16.5h5" stroke="currentColor" stroke-width="1.5"/>
            </svg>
          </div>
          <h3>Server</h3>
          <p>Pengelolaan informasi server, meliputi identitas, spesifikasi teknis, penempatan, dan status data server.</p>
        </article>
        <article class="kartu">
          <div class="ic" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="3" y="4" width="18" height="13" rx="2" stroke="currentColor" stroke-width="2"/>
              <path d="M8 21h8m-4-4v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <h3>Aset TIK</h3>
          <p>Pendataan dan pemantauan aset teknologi informasi dan komunikasi untuk mendukung pengelolaan inventaris yang tertib dan terstruktur.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="informasi" id="informasi">
    <div class="wrap">
      <div class="informasi-panel">
        <div class="informasi-icon" aria-hidden="true">
          <svg width="25" height="25" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 18h6m-5 3h4m-2-20a7 7 0 0 0-4 12.745c.63.42 1 1.12 1 1.755h6c0-.635.37-1.335 1-1.755A7 7 0 0 0 12 1Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <h2>Informasi Terpadu</h2>
        <p>SIMTIK menyediakan akses terpusat terhadap informasi infrastruktur dan aset TIK sebagai dukungan bagi pemantauan, evaluasi, perencanaan, dan pengambilan keputusan.</p>
      </div>
    </div>
  </section>
</main>

<script>
  const menuToggle = document.querySelector('.menu-toggle');
  const navMenu = document.querySelector('nav ul');
  menuToggle?.addEventListener('click', () => {
    const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
    menuToggle.setAttribute('aria-expanded', String(!isExpanded));
    menuToggle.setAttribute('aria-label', isExpanded ? 'Buka menu' : 'Tutup menu');
    navMenu?.classList.toggle('is-open', !isExpanded);
  });
  navMenu?.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
      navMenu.classList.remove('is-open');
      menuToggle?.setAttribute('aria-expanded', 'false');
      menuToggle?.setAttribute('aria-label', 'Buka menu');
    });
  });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>
/* Adaptasi vanilla-JS dari komponen React "MagicRings" — tema warna diganti biru */
(function () {
  const vertexShader = `
    void main() {
      gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
    }
  `;
  const fragmentShader = `
    precision highp float;
    uniform float uTime, uAttenuation, uLineThickness;
    uniform float uBaseRadius, uRadiusStep, uScaleRate;
    uniform float uOpacity, uNoiseAmount, uRotation, uRingGap;
    uniform float uFadeIn, uFadeOut;
    uniform float uMouseInfluence, uHoverAmount, uHoverScale, uParallax, uBurst;
    uniform float uCoverageAlpha;
    uniform vec2 uResolution, uMouse;
    uniform vec3 uColor, uColorTwo;
    uniform int uRingCount;

    const float HP = 1.5707963;
    const float CYCLE = 3.45;

    float fade(float t) {
      return t < uFadeIn ? smoothstep(0.0, uFadeIn, t) : 1.0 - smoothstep(uFadeOut, CYCLE - 0.2, t);
    }

    float ring(vec2 p, float ri, float cut, float t0, float px) {
      float t = mod(uTime + t0, CYCLE);
      float r = ri + t / CYCLE * uScaleRate;
      float d = abs(length(p) - r);
      float a = atan(abs(p.y), abs(p.x)) / HP;
      float th = max(1.0 - a, 0.5) * px * uLineThickness;
      float h = (1.0 - smoothstep(th, th * 1.5, d)) + 1.0;
      d += pow(cut * a, 3.0) * r;
      return h * exp(-uAttenuation * d) * fade(t);
    }

    void main() {
      float px = 1.0 / min(uResolution.x, uResolution.y);
      vec2 p = (gl_FragCoord.xy - 0.5 * uResolution.xy) * px;
      float cr = cos(uRotation), sr = sin(uRotation);
      p = mat2(cr, -sr, sr, cr) * p;
      p -= uMouse * uMouseInfluence;
      float sc = mix(1.0, uHoverScale, uHoverAmount) + uBurst * 0.3;
      p /= sc;
      vec3 c = vec3(0.0);
      float coverage = 0.0;
      float rcf = max(float(uRingCount) - 1.0, 1.0);
      for (int i = 0; i < 10; i++) {
        if (i >= uRingCount) break;
        float fi = float(i);
        vec2 pr = p - fi * uParallax * uMouse;
        vec3 rc = mix(uColor, uColorTwo, fi / rcf);
        float ringAmount = ring(pr, uBaseRadius + fi * uRadiusStep, pow(uRingGap, fi), i == 0 ? 0.0 : 2.95 * fi, px);
        c = mix(c, rc, vec3(ringAmount));
        coverage = max(coverage, ringAmount);
      }
      c *= 1.0 + uBurst * 2.0;
      float n = fract(sin(dot(gl_FragCoord.xy + uTime * 100.0, vec2(12.9898, 78.233))) * 43758.5453);
      c += (n - 0.5) * uNoiseAmount;
      float intensity = max(c.r, max(c.g, c.b));
      vec3 emissiveColor = intensity > 0.0001 ? clamp(c / intensity, 0.0, 1.0) : vec3(0.0);
      vec3 outputColor = mix(emissiveColor, clamp(c, 0.0, 1.0), uCoverageAlpha);
      float outputAlpha = mix(intensity, coverage, uCoverageAlpha);
      gl_FragColor = vec4(outputColor, clamp(outputAlpha * uOpacity, 0.0, 1.0));
    }
  `;

  function initMagicRings(container, opts) {
    if (!window.THREE || !container) return;

    let renderer;
    try {
      renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    } catch (e) {
      return;
    }
    renderer.setClearColor(0x000000, 0);
    container.appendChild(renderer.domElement);

    const scene = new THREE.Scene();
    const camera = new THREE.OrthographicCamera(-0.5, 0.5, 0.5, -0.5, 0.1, 10);
    camera.position.z = 1;

    const uniforms = {
      uTime: { value: 0 },
      uAttenuation: { value: opts.attenuation },
      uResolution: { value: new THREE.Vector2() },
      uColor: { value: new THREE.Color(opts.color) },
      uColorTwo: { value: new THREE.Color(opts.colorTwo) },
      uLineThickness: { value: opts.lineThickness },
      uBaseRadius: { value: opts.baseRadius },
      uRadiusStep: { value: opts.radiusStep },
      uScaleRate: { value: opts.scaleRate },
      uRingCount: { value: opts.ringCount },
      uOpacity: { value: opts.opacity },
      uNoiseAmount: { value: opts.noiseAmount },
      uRotation: { value: (opts.rotation * Math.PI) / 180 },
      uRingGap: { value: opts.ringGap },
      uFadeIn: { value: opts.fadeIn },
      uFadeOut: { value: opts.fadeOut },
      uMouse: { value: new THREE.Vector2() },
      uMouseInfluence: { value: opts.followMouse ? opts.mouseInfluence : 0 },
      uHoverAmount: { value: 0 },
      uHoverScale: { value: opts.hoverScale },
      uParallax: { value: opts.parallax },
      uBurst: { value: 0 },
      uCoverageAlpha: { value: opts.alphaMode === 'coverage' ? 1 : 0 },
    };

    const material = new THREE.ShaderMaterial({ vertexShader, fragmentShader, uniforms, transparent: true });
    const quad = new THREE.Mesh(new THREE.PlaneGeometry(1, 1), material);
    scene.add(quad);

    function resize() {
      const w = container.clientWidth, h = container.clientHeight;
      const dpr = Math.min(window.devicePixelRatio || 1, 2);
      renderer.setSize(w, h);
      renderer.setPixelRatio(dpr);
      uniforms.uResolution.value.set(w * dpr, h * dpr);
    }
    resize();
    window.addEventListener('resize', resize);
    if (window.ResizeObserver) {
      new ResizeObserver(resize).observe(container);
    }

    let elapsed = 0, lastT = 0, frameId = 0, isVisible = false, isPageVisible = !document.hidden;

    function animate(t) {
      frameId = requestAnimationFrame(animate);
      const dt = lastT === 0 ? 0 : Math.min(t - lastT, 100);
      lastT = t;
      elapsed += dt * 0.001 * opts.speed;
      uniforms.uTime.value = elapsed;
      renderer.render(scene, camera);
    }
    function tryStart() { if (isVisible && isPageVisible && frameId === 0) { lastT = 0; frameId = requestAnimationFrame(animate); } }
    function tryStop() { if (frameId !== 0) { cancelAnimationFrame(frameId); frameId = 0; } }

    if (window.IntersectionObserver) {
      new IntersectionObserver(([entry]) => {
        isVisible = entry.isIntersecting;
        isVisible ? tryStart() : tryStop();
      }, { threshold: 0 }).observe(container);
    } else {
      isVisible = true;
    }
    document.addEventListener('visibilitychange', () => {
      isPageVisible = !document.hidden;
      isPageVisible ? tryStart() : tryStop();
    });

    tryStart();
  }

  window.addEventListener('DOMContentLoaded', function () {
    initMagicRings(document.querySelector('#hero-rings-bg'), {
      color: '#2E6FF2',
      colorTwo: '#8FD3FF',
      speed: 0.8,
      ringCount: 6,
      attenuation: 6.5,
      lineThickness: 1.6,
      baseRadius: 0.42,
      radiusStep: 0.13,
      scaleRate: 0.12,
      opacity: 0.9,
      noiseAmount: 0.05,
      rotation: 0,
      ringGap: 1.3,
      fadeIn: 0.7,
      fadeOut: 0.5,
      followMouse: false,
      mouseInfluence: 0.2,
      hoverScale: 1.2,
      parallax: 0.05,
      alphaMode: 'luminance',
    });
  });
})();
</script>

</body>
</html>
