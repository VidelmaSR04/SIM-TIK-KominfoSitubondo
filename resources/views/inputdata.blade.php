<!DOCTYPE html><html lang="id" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Buat Perangkat Server Baru - SIM TIK</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "surface-container-low": "#f2f4f6",
                      "on-tertiary-fixed-variant": "#653e00",
                      "surface-variant": "#e0e3e5",
                      "on-primary-container": "#eeefff",
                      "error-container": "#ffdad6",
                      "on-primary": "#ffffff",
                      "secondary-fixed-dim": "#bec6e0",
                      "primary-fixed-dim": "#b4c5ff",
                      "primary-container": "#2563eb",
                      "background": "#f7f9fb",
                      "on-tertiary-container": "#ffeedd",
                      "secondary-fixed": "#dae2fd",
                      "tertiary-container": "#996100",
                      "inverse-surface": "#2d3133",
                      "surface-container-high": "#e6e8ea",
                      "primary": "#004ac6",
                      "on-surface-variant": "#434655",
                      "error": "#ba1a1a",
                      "surface": "#f7f9fb",
                      "on-error-container": "#93000a",
                      "secondary": "#565e74",
                      "inverse-primary": "#b4c5ff",
                      "surface-container-highest": "#e0e3e5",
                      "on-primary-fixed-variant": "#003ea8",
                      "tertiary-fixed": "#ffddb8",
                      "surface-bright": "#f7f9fb",
                      "surface-container": "#eceef0",
                      "outline": "#737686",
                      "on-background": "#191c1e",
                      "on-primary-fixed": "#00174b",
                      "on-secondary-container": "#5c647a",
                      "surface-tint": "#0053db",
                      "outline-variant": "#c3c6d7",
                      "on-tertiary": "#ffffff",
                      "surface-dim": "#d8dadc",
                      "primary-fixed": "#dbe1ff",
                      "on-secondary": "#ffffff",
                      "surface-container-lowest": "#ffffff",
                      "secondary-container": "#dae2fd",
                      "tertiary": "#784b00",
                      "on-secondary-fixed-variant": "#3f465c",
                      "inverse-on-surface": "#eff1f3",
                      "on-surface": "#191c1e",
                      "tertiary-fixed-dim": "#ffb95f",
                      "on-error": "#ffffff",
                      "on-secondary-fixed": "#131b2e",
                      "on-tertiary-fixed": "#2a1700",
                      "brand-teal": "#0D9488"
              },
              "borderRadius": {
                      "DEFAULT": "0.25rem",
                      "lg": "0.5rem",
                      "xl": "0.75rem",
                      "full": "9999px"
              },
              "spacing": {
                      "topbar-height": "64px",
                      "gutter": "16px",
                      "container-padding": "24px",
                      "sidebar-width": "260px",
                      "base": "4px"
              },
              "fontFamily": {
                      "body-md": ["Inter"],
                      "headline-lg": ["Inter"],
                      "data-tabular": ["Inter"],
                      "label-md": ["Inter"],
                      "headline-md": ["Inter"],
                      "display": ["Inter"],
                      "body-lg": ["Inter"]
              },
              "fontSize": {
                      "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                      "headline-lg": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                      "data-tabular": ["13px", {"lineHeight": "18px", "fontWeight": "400"}],
                      "label-md": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                      "headline-md": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                      "display": ["36px", {"lineHeight": "44px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                      "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}]
              }
            },
          },
        }
    </script>
<style>
        .input-group {
            display: flex;
            align-items: stretch;
            border: 1px solid #CBD5E1;
            border-radius: 0.375rem;
            overflow: hidden;
            background: white;
            transition: all 0.2s;
        }
        .input-group:focus-within {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }
        .input-group-addon {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #F1F5F9;
            border-right: 1px solid #CBD5E1;
            padding: 0 12px;
            color: #64748B;
        }
        .input-group-input {
            flex: 1;
            border: none;
            padding: 8px 12px;
            outline: none;
            font-size: 14px;
        }
        .form-label {
            font-weight: 600;
            color: #191c1e;
            margin-bottom: 4px;
            display: block;
            font-size: 14px;
        }
        .standard-input {
            width: 100%;
            border: 1px solid #CBD5E1;
            border-radius: 0.375rem;
            padding: 8px 12px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
        }
        .standard-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }
        .standard-select {
            width: 100%;
            border: 1px solid #CBD5E1;
            border-radius: 0.375rem;
            padding: 8px 12px;
            font-size: 14px;
            outline: none;
            background-color: white;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748B%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
            background-repeat: no-repeat;
            background-position: right 12px top 50%;
            background-size: 10px auto;
        }
        .standard-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }
    </style>
</head>
<body class="bg-background min-h-screen text-on-surface font-body-md flex">
<!-- SideNavBar -->
<nav class="fixed left-0 top-0 h-full w-sidebar-width bg-[#0F172A] hidden md:flex flex-col shadow-sm z-20">
<div class="p-6 border-b border-slate-800 flex items-center gap-4">
<div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center overflow-hidden">
<span class="material-symbols-outlined text-white" data-icon="person">person</span>
</div>
<div>
<div class="font-headline-lg text-headline-lg font-bold text-white">SIM TIK</div>
<div class="text-xs text-slate-400">Admin Data Center</div>
</div>
</div>
<div class="flex-1 overflow-y-auto py-4 flex flex-col">
<a class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:text-white transition-colors hover:bg-slate-800" href="#">
<span class="material-symbols-outlined" data-icon="dns">dns</span>
<span class="">Server &amp; Aplikasi</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:text-white transition-colors hover:bg-slate-800" href="#">
<span class="material-symbols-outlined" data-icon="settings_applications">settings_applications</span>
<span class="">cPanel</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 border-l-4 border-primary bg-primary/10 text-primary font-semibold transition-all duration-200 ease-in-out" href="#">
<span class="material-symbols-outlined" data-icon="apps">apps</span>
<span class="">Aplikasi</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:text-white transition-colors hover:bg-slate-800" href="#">
<span class="material-symbols-outlined" data-icon="swap_horiz">swap_horiz</span>
<span class="">SPLP</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:text-white transition-colors hover:bg-slate-800" href="#">
<span class="material-symbols-outlined" data-icon="assignment">assignment</span>
<span class="">Laporan Tugas</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:text-white transition-colors hover:bg-slate-800" href="#">
<span class="material-symbols-outlined" data-icon="monitor_heart">monitor_heart</span>
<span class="">Laporan NOC</span>
</a>
</div>
</nav>
<!-- Main Content Wrapper -->
<div class="flex-1 flex flex-col md:ml-sidebar-width min-w-0">
<!-- TopNavBar -->
<header class="bg-surface dark:bg-surface-container docked full-width top-0 h-topbar-height border-b border-outline-variant shadow-sm flex justify-between items-center px-container-padding sticky z-10">
<!-- Mobile Menu Button & Title -->
<div class="flex items-center gap-4 md:hidden">
<button class="text-on-surface-variant p-2 -ml-2 rounded-full hover:bg-surface-container-low transition-colors">
<span class="material-symbols-outlined" data-icon="menu">menu</span>
</button>
<span class="font-headline-md text-headline-md font-bold text-primary">SIM TIK</span>
</div>
<!-- Desktop Search (Left aligned) -->
<div class="hidden md:flex flex-1 max-w-md items-center">
<div class="relative w-full">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" data-icon="search">search</span>
<input class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-transparent rounded-full text-body-md focus:outline-none focus:border-primary focus:bg-white transition-colors" placeholder="Pencarian..." type="text">
</div>
</div>
<!-- Actions -->
<div class="flex items-center gap-2 ml-auto">
<button class="text-on-surface-variant p-2 rounded-full hover:bg-surface-container-low transition-colors">

</button>
<button class="text-on-surface-variant p-2 rounded-full hover:bg-surface-container-low transition-colors relative">
<span class="material-symbols-outlined" data-icon="notifications">notifications</span>
<span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full"></span>
</button>
<button class="text-on-surface-variant p-2 rounded-full hover:bg-surface-container-low transition-colors">
<span class="material-symbols-outlined" data-icon="account_circle">account_circle</span>
</button>
</div>
</header>
<!-- Page Content -->
<main class="flex-1 p-container-padding">
<!-- Breadcrumb -->
<nav aria-label="Breadcrumb" class="flex text-sm text-secondary mb-4 font-body-md">
<ol class="inline-flex items-center space-x-1 md:space-x-3">
<li class="inline-flex items-center">
<a class="inline-flex items-center hover:text-primary transition-colors" href="#">
                            Dashboard
                        </a>
</li>
<li class="">
<div class="flex items-center">
<span class="material-symbols-outlined text-sm mx-1" data-icon="chevron_right">chevron_right</span>
<a class="hover:text-primary transition-colors ml-1 md:ml-2" href="#">Perangkat &amp; Server</a>
</div>
</li>
<li aria-current="page" class="">
<div class="flex items-center">
<span class="material-symbols-outlined text-sm mx-1" data-icon="chevron_right">chevron_right</span>
<span class="text-on-surface ml-1 md:ml-2">Buat Perangkat Baru</span>
</div>
</li>
</ol>
</nav>
<!-- Page Title -->
<h1 class="font-headline-lg text-headline-lg text-on-surface mb-6">Buat Perangkat Server Baru</h1>
<!-- Form Card -->
<div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant overflow-hidden mb-8">
<!-- Card Header -->
<div class="px-6 py-4 bg-primary-container"><h2 class="text-white font-headline-md text-headline-md m-0">Form Biodata</h2></div>
<!-- Card Body (Form) -->
<div class="p-6">
<form action="#" method="POST">
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<!-- 1. Nama Perangkat (Full Width) -->
<div class="col-span-1 md:col-span-2">
<label class="form-label" for="nama_perangkat">Nama Perangkat</label>
<div class="input-group">
<div class="input-group-addon">
<span class="material-symbols-outlined text-xl" data-icon="list">list</span>
</div>
<input class="input-group-input" id="nama_perangkat" name="nama_perangkat" placeholder="Masukkan nama perangkat" type="text">
</div>
</div>
<!-- 2. Row 1: Jenis Perangkat & Serial Number -->
<div>
<label class="form-label" for="jenis_perangkat">Jenis Perangkat</label>
<select class="standard-select text-on-surface" id="jenis_perangkat" name="jenis_perangkat">
<option disabled="" selected="" value="">-- Pilih Perangkat --</option>
<option value="router">Router</option>
<option value="switch">Switch</option>
<option value="server">Server</option>
</select>
</div>
<div>
<label class="form-label" for="serial_number">Serial Number</label>
<div class="input-group">
<div class="input-group-addon">
<span class="material-symbols-outlined text-xl" data-icon="key">key</span>
</div>
<input class="input-group-input" id="serial_number" name="serial_number" placeholder="Masukkan serial number" type="text">
</div>
</div>
<!-- 3. Row 2: Merk Perangkat & TYPE -->
<div>
<label class="form-label" for="merk_perangkat">Merk Perangkat</label>
<select class="standard-select text-on-surface" id="merk_perangkat" name="merk_perangkat">
<option selected="" value="MIKROTIK">MIKROTIK</option>
<option value="CISCO">CISCO</option>
<option value="DELL">DELL</option>
</select>
</div>
<div>
<label class="form-label" for="type">TYPE</label>
<input class="standard-input" id="type" name="type" placeholder="Masukkan tipe spesifik" type="text">
</div>
<!-- 4. Row 3: Kondisi Server & Spesifikasi -->
<div>
<label class="form-label">Kondisi Server</label>
<div class="grid grid-cols-2 gap-2">
<select class="standard-select text-on-surface" name="kondisi_1">
<option selected="" value="Standard">Standard</option>
<option value="High Performance">High Performance</option>
</select>
<select class="standard-select text-on-surface" name="kondisi_2">
<option selected="" value="Baru">Baru</option>
<option value="Bekas">Bekas</option>
</select>
</div>
</div>
<div>
<label class="form-label" for="spesifikasi">Spesifikasi</label>
<input class="standard-input" id="spesifikasi" name="spesifikasi" placeholder="Detail spesifikasi" type="text">
</div>
<!-- 5. Tipe Perangkat (Full Width) -->
<div class="col-span-1 md:col-span-2">
<label class="form-label" for="tipe_perangkat">Tipe Perangkat</label>
<select class="standard-select text-on-surface" id="tipe_perangkat" name="tipe_perangkat">
<option selected="" value="RACK MOUNT">RACK MOUNT</option>
<option value="TOWER">TOWER</option>
<option value="BLADE">BLADE</option>
</select>
</div>
<!-- 6. Status Kepemilikan (Full Width) -->
<div class="col-span-1 md:col-span-2">
<label class="form-label" for="status_kepemilikan">Status Kepemilikan</label>
<select class="standard-select text-on-surface" id="status_kepemilikan" name="status_kepemilikan">
<option selected="" value="Kominfo">Kominfo</option>
<option value="OPD Lain">OPD Lain</option>
</select>
</div>
<!-- 7. Pemilik Perangkat (Full Width) -->
<div class="col-span-1 md:col-span-2">
<label class="form-label" for="pemilik_perangkat">Pemilik Perangkat</label>
<select class="standard-select bg-surface-container-high text-on-surface-variant cursor-not-allowed" disabled="" id="pemilik_perangkat" name="pemilik_perangkat">
<option selected="" value="Dinas Pendidikan dan Kebudayaan">Dinas Pendidikan dan Kebudayaan</option>
</select>
</div>
<!-- 8. Row 4: IP Perangkat & Status Perangkat -->
<div>
<label class="form-label" for="ip_perangkat">IP Perangkat</label>
<div class="input-group">
<div class="input-group-addon">
<span class="material-symbols-outlined text-xl" data-icon="public">public</span>
</div>
<input class="input-group-input" id="ip_perangkat" name="ip_perangkat" placeholder="192.168.x.x" type="text">
</div>
</div>
<div>
<label class="form-label" for="status_perangkat">Status Perangkat</label>
<select class="standard-select text-on-surface" id="status_perangkat" name="status_perangkat">
<option selected="" value="Aktif">Aktif</option>
<option value="Non-Aktif">Non-Aktif</option>
<option value="Maintenance">Maintenance</option>
</select>
</div>
<!-- 9. Row 5: Ukuran HDD & Ukuran RAM -->
<div>
<label class="form-label" for="ukuran_hdd">Ukuran HDD</label>
<select class="standard-select text-on-surface" id="ukuran_hdd" name="ukuran_hdd">
<option selected="" value="300 GB">300 GB</option>
<option value="500 GB">500 GB</option>
<option value="1 TB">1 TB</option>
</select>
</div>
<div class="max-w-md">
<label class="form-label" for="ukuran_ram">Ukuran RAM</label>
<select class="standard-select text-on-surface" id="ukuran_ram" name="ukuran_ram">
<option selected="" value="4 GB">4 GB</option>
<option value="8 GB">8 GB</option>
<option value="16 GB">16 GB</option>
<option value="32 GB">32 GB</option>
</select>
</div>
<!-- 10. Row 6: Nomor RACK & Jumlah Core -->
<div>
<label class="form-label" for="nomor_rack">Nomor RACK</label>
<select class="standard-select text-on-surface" id="nomor_rack" name="nomor_rack">
<option disabled="" selected="" value="">-- Pilih Rack --</option>
<option value="RACK-01">RACK-01</option>
<option value="RACK-02">RACK-02</option>
</select>
</div>
<div>
<label class="form-label" for="jumlah_core">Jumlah Core</label>
<select class="standard-select text-on-surface" id="jumlah_core" name="jumlah_core">
<option value="2">2</option>
<option selected="" value="4">4</option>
<option value="8">8</option>
<option value="16">16</option>
</select>
</div>
<!-- 11. Preview Box (Full Width) -->
<div class="col-span-1 md:col-span-2">
<label class="form-label">Preview Rack Mount</label>
<div class="border border-dashed border-outline-variant rounded-lg p-6 bg-surface-container-low flex flex-col items-center justify-center min-h-[150px]">
<span class="text-secondary font-medium mb-4 text-sm">Rack Mount</span>
<div class="w-full max-w-md h-12 bg-white border border-outline rounded flex items-center justify-center shadow-sm">
<span class="text-xs text-outline-variant">Posisi Perangkat Akan Muncul Disini</span>
</div>
</div>
</div>
<!-- 12. Peruntukan Perangkat (Full Width) -->
<div class="col-span-1 md:col-span-2">
<label class="form-label" for="peruntukan_perangkat">Peruntukan Perangkat</label>
<div class="input-group">
<div class="input-group-addon">
<span class="material-symbols-outlined text-xl" data-icon="list">list</span>
</div>
<input class="input-group-input" id="peruntukan_perangkat" name="peruntukan_perangkat" placeholder="Masukkan peruntukan perangkat" type="text">
</div>
</div>
<!-- 13. Row 7: Nama Pengirim & Nama Penerima -->
<div>
<label class="form-label" for="nama_pengirim">Nama Pengirim</label>
<input class="standard-input" id="nama_pengirim" name="nama_pengirim" placeholder="Nama pengirim perangkat" type="text">
</div>
<div>
<label class="form-label" for="nama_penerima">Nama Penerima</label>
<select class="standard-select text-on-surface" id="nama_penerima" name="nama_penerima">
<option disabled="" selected="" value="">-- Pilih penerima --</option>
<option value="Admin 1">Admin NOC 1</option>
<option value="Admin 2">Admin Server 1</option>
</select>
</div>
<!-- 14. Jam Pengisian (Full Width) -->
<div class="col-span-1 md:col-span-2 mb-4">
<label class="form-label" for="jam_pengisian">Jam Pengisian</label>
<div class="relative">
<input class="w-full border border-[#CBD5E1] rounded-md py-2 pl-3 pr-10 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" id="jam_pengisian" name="jam_pengisian" type="time">

</div>
</div>
</div> <!-- End Grid -->
<!-- Submit Button -->
<div class="mt-8"><div class="flex gap-3"><button class="bg-primary-container hover:bg-primary text-white font-medium py-2 px-6 rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" type="submit">Simpan</button><button class="bg-surface-container-high text-on-surface-variant cursor-not-allowed font-medium py-2 px-6 rounded-lg transition-colors shadow-sm" type="button" disabled="">Create PDF</button></div></div>
</form>
</div>
</div>
</main>
<!-- Footer -->
<footer class="bg-surface-container-lowest full-width py-4 border-t border-outline-variant flex justify-center items-center mt-auto z-10 relative">
<span class="font-body-md text-body-md text-secondary">Copyright © 2026 Dinas Komunikasi dan Informatika Kab. Situbondo. All rights reserved.</span>
</footer>
</div>




</body></html>
