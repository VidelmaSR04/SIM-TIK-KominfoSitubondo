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
      <li><a href="#konteks">Kenapa SIMTIK</a></li>
      <li><a href="#layanan">Layanan</a></li>
      <li><a href="#alur">Alur Kerja</a></li>
      <li><a href="#statistik">Cakupan</a></li>
      <li><a href="#kontak">Kontak</a></li>
    </ul></nav>
    <div class="nav-actions">
      <a href="/login" class="btn btn-ghost">Masuk</a>
      <a href="/register" class="btn btn-primary">Daftar untuk Titip Server</a>
    </div>
    <button class="menu-toggle" aria-label="Buka menu">☰</button>
  </div>
</header>

<section class="hero">
  <div class="rings-bg" id="hero-rings-bg" aria-hidden="true"></div>
  <div class="wrap hero-grid">
    <div>
      <span class="eyebrow"><span class="dot"></span> Sistem Informasi Manajemen SDTIK</span>
      <h1>Kelola tenaga teknis, server, dan aplikasi TIK Situbondo <em>dalam satu sistem.</em></h1>
      <p class="lead">SDTIK menghimpun data tenaga teknis TIK, server, hingga aplikasi dari seluruh OPD Kabupaten Situbondo — kini penitipan server pun bisa diisi mandiri oleh pengirim, admin tinggal melengkapi datanya.</p>
      <div class="hero-ctas">
        <a href="/login" class="btn btn-primary">Mulai</a>
        <a href="/register" class="btn btn-ghost">Daftar untuk Titip Server →</a>
      </div>
      <div class="hero-meta">
        <div><div class="num">17</div><div class="lbl">Kecamatan tercakup</div></div>
        <div><div class="num">1</div><div class="lbl">Basis data terpusat</div></div>
        <div><div class="num">24/7</div><div class="lbl">Akses status data</div></div>
      </div>
    </div>
  </div>
</section>

<section class="konteks" id="konteks">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow-dark">Kenapa SIMTIK dibutuhkan</span>
      <h2>Sebelumnya, data tenaga teknis TIK tersebar di banyak tempat.</h2>
      <p>Setiap OPD mencatat tenaga teknisnya sendiri-sendiri — sebagian di kertas, sebagian di spreadsheet, sebagian hanya di grup WhatsApp. Diskominfo kesulitan melihat gambaran utuh.</p>
    </div>
    <div class="konteks-grid">
      <div class="kartu">
        <div class="ic">⌗</div>
        <h3>Data tercecer</h3>
        <p>Setiap OPD menyimpan data tenaga teknisnya sendiri, dengan format dan tingkat kelengkapan yang berbeda-beda.</p>
      </div>
      <div class="kartu">
        <div class="ic">↻</div>
        <h3>Update manual</h3>
        <p>Perubahan data — penempatan, kontak, keahlian — sering hanya dikabarkan lewat pesan singkat, mudah terlewat.</p>
      </div>
      <div class="kartu">
        <div class="ic">◎</div>
        <h3>Sulit dipantau</h3>
        <p>Diskominfo tidak punya cara cepat untuk melihat sebaran dan ketersediaan tenaga teknis di seluruh kecamatan.</p>
      </div>
    </div>
  </div>
</section>

<section class="layanan" id="layanan">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow-dark">Modul dalam SDTIK</span>
      <h2>Enam modul, satu Sistem Informasi Manajemen SDTIK.</h2>
      <p>Dari data tenaga teknis, server, dan aplikasi, sampai pemantauan NOC — semua berjalan di satu sistem milik Diskominfo Situbondo.</p>
    </div>
    <div class="fitur-grid">
      <a href="/login" class="fitur">
        <span class="tag">01</span>
        <h3>SDM Tenaga TIK</h3>
        <p>Pengelolaan data Tenaga Teknis TIK Dinas Komunikasi dan Informatika Kabupaten Situbondo.</p>
      </a>
      <a href="/register" class="fitur">
        <span class="tag">02<span class="badge-new">Baru</span></span>
        <h3>Server &amp; Aplikasi</h3>
        <p>Pengelolaan data aplikasi dan server pada Pusat Server Kabupaten Situbondo — kini bisa dititipkan sendiri oleh pengirim server, admin tinggal melengkapi.</p>
      </a>
      <a href="/aplikasi" class="fitur">
        <span class="tag">03</span>
        <h3>Asesmen Aplikasi</h3>
        <p>Pengukuran kebutuhan sistem suatu aplikasi dengan melakukan wawancara kepada penanggung jawab aplikasi.</p>
      </a>
      <a href="/laporan-tugas" class="fitur">
        <span class="tag">04</span>
        <h3>Assignment</h3>
        <p>Pengelolaan penugasan dari Pejabat Struktural kepada Tenaga Teknis TIK Dinas Komunikasi dan Informatika Kabupaten Situbondo.</p>
      </a>
      <a href="/laporan-noc" class="fitur">
        <span class="tag">05</span>
        <h3>Monitoring NOC</h3>
        <p>Penjadwalan dan laporan kondisi Network Operation Control (NOC).</p>
      </a>
      <div class="fitur fitur-disabled">
        <span class="tag">06</span>
        <h3>Coming Soon</h3>
        <p>Modul berikutnya sedang disiapkan Diskominfo Situbondo.</p>
      </div>
    </div>
  </div>
</section>

<section class="alur" id="alur">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow-dark">Alur kerja</span>
      <h2>Dari input mandiri sampai data resmi, empat langkah.</h2>
    </div>
    <div class="alur-list">
      <div class="langkah">
        <div class="idx">01</div>
        <h3>Input Mandiri</h3>
        <p>Tenaga teknis maupun pengirim server mendaftar akun terlebih dahulu, baru bisa mengisi data awal lewat sistem.</p>
      </div>
      <div class="langkah">
        <div class="idx">02</div>
        <h3>Masuk Antrean</h3>
        <p>Data awal — jenis, dinas, penanggung jawab — otomatis tersimpan dan menunggu dilengkapi.</p>
      </div>
      <div class="langkah">
        <div class="idx">03</div>
        <h3>Admin Melengkapi</h3>
        <p>Tim Diskominfo mengisi field teknis (serial number, IP, RAM, dst.) lewat menu Lengkapi Data.</p>
      </div>
      <div class="langkah">
        <div class="idx">04</div>
        <h3>Status Resmi</h3>
        <p>Status kelengkapan otomatis berubah dari "Dilengkapi" menjadi "Lengkap" begitu semua data terisi.</p>
      </div>
    </div>
  </div>
</section>

<section class="statistik" id="statistik">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow-dark">Cakupan wilayah</span>
      <h2>Dirancang untuk seluruh Kabupaten Situbondo.</h2>
    </div>
    <div class="cakupan-visual">
      <div class="netmap" aria-hidden="true">
        <svg viewBox="0 0 400 400">
        <defs>
          <radialGradient id="hubGlow" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stop-color="#2DD4BF" stop-opacity="0.35"/>
            <stop offset="100%" stop-color="#2DD4BF" stop-opacity="0"/>
          </radialGradient>
        </defs>
        <circle cx="200" cy="200" r="110" fill="url(#hubGlow)"/>
        <!-- connecting lines from outer nodes to hub -->
        <g stroke="#2DD4BF" stroke-opacity="0.5" stroke-width="1.4" fill="none">
          <path class="flow-line" d="M60,90 L200,200"/>
          <path class="flow-line" d="M340,80 L200,200"/>
          <path class="flow-line" d="M60,320 L200,200"/>
          <path class="flow-line" d="M350,310 L200,200"/>
          <path class="flow-line" d="M40,200 L200,200"/>
          <path class="flow-line" d="M200,30 L200,200"/>
        </g>
        <!-- outer nodes: OPD -->
        <g fill="#7FE9DA">
          <circle class="node-pulse" cx="60" cy="90" r="6"/>
          <circle class="node-pulse" cx="340" cy="80" r="6" style="animation-delay:.4s"/>
          <circle class="node-pulse" cx="60" cy="320" r="6" style="animation-delay:.8s"/>
          <circle class="node-pulse" cx="350" cy="310" r="6" style="animation-delay:1.2s"/>
          <circle class="node-pulse" cx="40" cy="200" r="6" style="animation-delay:1.6s"/>
          <circle class="node-pulse" cx="200" cy="30" r="6" style="animation-delay:2s"/>
        </g>
        <text x="60" y="75" text-anchor="middle" font-family="IBM Plex Mono" font-size="10" fill="#B9C2D0">OPD</text>
        <text x="340" y="65" text-anchor="middle" font-family="IBM Plex Mono" font-size="10" fill="#B9C2D0">OPD</text>
        <text x="60" y="342" text-anchor="middle" font-family="IBM Plex Mono" font-size="10" fill="#B9C2D0">OPD</text>
        <text x="350" y="332" text-anchor="middle" font-family="IBM Plex Mono" font-size="10" fill="#B9C2D0">OPD</text>
        <text x="40" y="188" text-anchor="middle" font-family="IBM Plex Mono" font-size="10" fill="#B9C2D0">OPD</text>
        <text x="200" y="20" text-anchor="middle" font-family="IBM Plex Mono" font-size="10" fill="#B9C2D0">OPD</text>
        <!-- hub -->
        <circle cx="200" cy="200" r="34" fill="#0B1526" stroke="#2DD4BF" stroke-width="1.6"/>
        <text x="200" y="196" text-anchor="middle" font-family="Space Grotesk" font-weight="700" font-size="13" fill="#fff">SIM</text>
        <text x="200" y="211" text-anchor="middle" font-family="Space Grotesk" font-weight="700" font-size="13" fill="#7FE9DA">TIK</text>
      </svg>
      </div>
    </div>
    <div class="stat-grid">
      <div class="stat"><div class="num mono">17</div><div class="lbl">Kecamatan di Kabupaten Situbondo</div></div>
      <div class="stat"><div class="num mono">1 Pintu</div><div class="lbl">Basis data tenaga teknis, server &amp; aplikasi terpusat</div></div>
      <div class="stat"><div class="num mono">Real-time</div><div class="lbl">Status verifikasi dapat dipantau langsung</div></div>
    </div>
  </div>
</section>

<section class="cta-akhir">
  <div class="wrap">
    <div class="cta-box">
      <div>
        <h2>Siap pakai SDTIK untuk OPD Anda?</h2>
        <p>Daftar akun untuk mulai menitipkan data server atau melengkapi profil tenaga teknis Anda.</p>
      </div>
      <a href="/login" class="btn btn-primary">Mulai</a>
    </div>
  </div>
</section>

<footer id="kontak">
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <div class="brand" style="margin-bottom:14px;"><span class="mark">ST</span><span style="color:#fff">SIMTIK</span></div>
        <p style="font-size:.88rem;max-width:280px;">Sistem Informasi Manajemen SDTIK — Dinas Komunikasi dan Informatika Kabupaten Situbondo.</p>
      </div>
      <div>
        <h4>Tautan</h4>
        <ul>
          <li><a href="#konteks">Kenapa SIMTIK</a></li>
          <li><a href="#layanan">Layanan</a></li>
          <li><a href="#alur">Alur Kerja</a></li>
        </ul>
      </div>
      <div>
        <h4>Bantuan</h4>
        <ul>
          <li><a href="#">Contact</a></li>
          <li><a href="#">About us</a></li>
          <li><a href="#">FAQ's</a></li>
          <li><a href="#">Support</a></li>
        </ul>
      </div>
      <div>
        <h4>Kontak</h4>
        <ul>
          <li>Diskominfo Kabupaten Situbondo</li>
          <li class="placeholder">[alamat kantor — isi sesuai data resmi]</li>
          <li class="placeholder">[nomor telepon]</li>
          <li class="placeholder">[email resmi]</li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 Diskominfo Kabupaten Situbondo — Sistem Informasi Manajemen SDTIK.</span>
      <span><a href="#">Facebook</a> · <a href="#">Twitter</a> · <a href="#">Google</a></span>
    </div>
  </div>
</footer>

<script>
  document.querySelector('.menu-toggle')?.addEventListener('click', () => {
    const ul = document.querySelector('nav ul');
    ul.style.display = ul.style.display === 'flex' ? 'none' : 'flex';
    ul.style.cssText += 'position:absolute;top:64px;left:0;right:0;background:#0B1526;flex-direction:column;padding:20px 28px;gap:18px;';
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
    // versi penuh untuk background hero (home)
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
