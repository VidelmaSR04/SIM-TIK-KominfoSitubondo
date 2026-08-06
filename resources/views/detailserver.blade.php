<!DOCTYPE html>
  <html class="light" lang="en" style="">
    <head>
      <meta charset="utf-8">
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <title>
        Detail Server - SIM TIK
      </title>
      <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries">

      </script>
      <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
      <script id="tailwind-config">

                tailwind.config = {
                    darkMode: "class",
                    theme: {
                        extend: {
                            "colors": {
                                "outline": "#737686",
                                "surface-bright": "#f7f9fb",
                                "on-tertiary": "#ffffff",
                                "surface-container-highest": "#e0e3e5",
                                "surface-dim": "#d8dadc",
                                "surface-tint": "#0053db",
                                "on-background": "#191c1e",
                                "on-primary-fixed-variant": "#003ea8",
                                "primary": "#004ac6",
                                "surface-container-lowest": "#ffffff",
                                "inverse-on-surface": "#eff1f3",
                                "tertiary-fixed-dim": "#ffb95f",
                                "inverse-primary": "#b4c5ff",
                                "on-error": "#ffffff",
                                "on-surface": "#191c1e",
                                "tertiary-fixed": "#ffddb8",
                                "primary-fixed-dim": "#b4c5ff",
                                "error": "#ba1a1a",
                                "error-container": "#ffdad6",
                                "inverse-surface": "#2d3133",
                                "on-surface-variant": "#434655",
                                "secondary-fixed-dim": "#bec6e0",
                                "primary-container": "#2563eb",
                                "outline-variant": "#c3c6d7",
                                "surface-variant": "#e0e3e5",
                                "primary-fixed": "#dbe1ff",
                                "on-primary": "#ffffff",
                                "surface-container": "#eceef0",
                                "background": "#f7f9fb",
                                "on-primary-container": "#eeefff",
                                "secondary": "#565e74",
                                "secondary-container": "#dae2fd",
                                "tertiary-container": "#996100",
                                "on-error-container": "#93000a",
                                "secondary-fixed": "#dae2fd",
                                "surface-container-high": "#e6e8ea",
                                "on-secondary-fixed": "#131b2e",
                                "on-tertiary-fixed-variant": "#653e00",
                                "tertiary": "#784b00",
                                "on-primary-fixed": "#00174b",
                                "on-secondary": "#ffffff",
                                "on-tertiary-fixed": "#2a1700",
                                "on-secondary-container": "#5c647a",
                                "on-secondary-fixed-variant": "#3f465c",
                                "surface": "#f7f9fb",
                                "surface-container-low": "#f2f4f6",
                                "on-tertiary-container": "#ffeedd"
                            },
                            "borderRadius": {
                                "DEFAULT": "0.25rem",
                                "lg": "0.5rem",
                                "xl": "0.75rem",
                                "full": "9999px"
                            },
                            "spacing": {
                                "container-padding": "24px",
                                "topbar-height": "64px",
                                "sidebar-width": "260px",
                                "gutter": "16px",
                                "base": "4px"
                            },
                            "fontFamily": {
                                "label-md": ["Inter"],
                                "display": ["Inter"],
                                "headline-lg": ["Inter"],
                                "body-lg": ["Inter"],
                                "body-md": ["Inter"],
                                "data-tabular": ["Inter"],
                                "headline-md": ["Inter"]
                            },
                            "fontSize": {
                                "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                                "display": ["36px", { "lineHeight": "44px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                                "headline-lg": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                                "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                                "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                                "data-tabular": ["13px", { "lineHeight": "18px", "fontWeight": "400" }],
                                "headline-md": ["20px", { "lineHeight": "28px", "fontWeight": "600" }]
                            }
                        }
                    }
                }

      </script>
    </head>
    <body class="bg-background text-on-surface font-body-md min-h-screen">
      <!-- Sidebar -->
      <nav class="fixed left-0 top-0 h-full w-sidebar-width bg-[#0F172A] shadow-sm flex flex-col z-20">
        <div class="p-6 border-b border-slate-800 flex items-center gap-4">
          <img alt="Foto Profil Admin Dinas Kominfo" class="w-12 h-12 rounded-full object-cover" data-alt="A professional headshot of a network administrator, clean modern lighting, corporate aesthetic, isolated on a solid color background. High resolution, clear details." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCoFrSxKGAkCaDg_yRr3TvvS6Vyavba4eldIUdKXuy2pBxf4WHEIWZUreEVx3IGzfcgVDtevoqmFE0xFdtZXxw0hfZaCmPk3B_iLvO_WnSuJFiqh1qEU1kAXFDeYWFef832IVgE5ddY1Sah7MyGwHwd9d9BNX9gzBV3bWITXirFNxTckMCzqYZATYK4ji5JB-M6xxEQrm2Ot9Xp86MewlzE9Wdex6SkFQKHDdTil6z0e_lJauhzNbGE">
          <div>
            <h1 class="font-headline-lg text-headline-lg font-bold text-white leading-tight">
              SIM TIK
            </h1>
            <p class="font-label-md text-label-md text-slate-400">
              Admin Data Center
            </p>
          </div>
        </div>
        <div class="flex-1 overflow-y-auto py-4">
          <a class="flex items-center gap-3 px-4 py-3 border-l-4 border-primary bg-primary/10 text-primary font-semibold transition-all duration-200 ease-in-out" href="#">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">
              dns
            </span>
            Server &amp; Aplikasi
          </a>
          <a class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors" href="#">
            <span class="material-symbols-outlined">
              settings_applications
            </span>
            cPanel
          </a>
          <a class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors" href="#">
            <span class="material-symbols-outlined">
              apps
            </span>
            Aplikasi
          </a>
          <a class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors" href="#">
            <span class="material-symbols-outlined">
              swap_horiz
            </span>
            SPLP
          </a>
          <a class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors" href="#">
            <span class="material-symbols-outlined">
              assignment
            </span>
            Laporan Tugas
          </a>
          <a class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors" href="#">
            <span class="material-symbols-outlined">
              monitor_heart
            </span>
            Laporan NOC
          </a>
        </div>
      </nav>
      <!-- Topbar -->
      <header class="docked full-width top-0 h-topbar-height bg-surface shadow-sm border-b border-outline-variant flex justify-between items-center px-container-padding ml-sidebar-width z-10 fixed right-0 left-0">
        <div class="flex items-center flex-1 max-w-md">
          <div class="relative w-full">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
              search
            </span>
            <input class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary font-body-md text-body-md outline-none" placeholder="Pencarian..." type="text">
          </div>
        </div>
        <div class="flex items-center gap-4">
          <button class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors active:scale-95 duration-150">
          </button>
          <button class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors active:scale-95 duration-150 relative">
            <span class="material-symbols-outlined">
              notifications
            </span>
            <span class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full">
            </span>
          </button>
          <button class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors active:scale-95 duration-150">
            <span class="material-symbols-outlined">
              account_circle
            </span>
          </button>
        </div>
      </header>
      <!-- Main Content -->
      <main class="ml-sidebar-width pt-topbar-height p-container-padding min-h-[calc(100vh-80px)]">
        <!-- Header & Breadcrumb -->
        <div class="flex justify-between items-end mb-6">
          <div>
            <nav aria-label="Breadcrumb" class="flex text-sm text-on-surface-variant mb-2 font-label-md">
              <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                  <a class="hover:text-primary transition-colors" href="#">
                    Server dan Aplikasi
                  </a>
                </li>
                <li class="">
                  <div class="flex items-center">
                    <span class="material-symbols-outlined text-[16px] mx-1">
                      chevron_right
                    </span>
                    <a class="hover:text-primary transition-colors" href="#">
                      Server
                    </a>
                  </div>
                </li>
                <li aria-current="page" class="">
                  <div class="flex items-center">
                    <span class="material-symbols-outlined text-[16px] mx-1">
                      chevron_right
                    </span>
                    <span class="text-on-surface font-semibold">
                      Detail Server
                    </span>
                  </div>
                </li>
              </ol>
            </nav>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">
              Detail Server
            </h2>
          </div>
        </div>
        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-12 gap-gutter mb-6">
          <!-- Left Column: Tentang Server -->
          <div class="col-span-12 lg:col-span-8 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-[0_1px_3px_rgba(0,0,0,0.1)] overflow-hidden flex flex-col md:flex-row">
            <div class="flex-1 flex flex-col">
              <div class="bg-primary text-on-primary px-6 py-4 font-headline-md text-headline-md border-b border-primary/20">
                Tentang Server
              </div>
              <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 flex-1">
                <div class="flex items-start gap-3">
                  <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1">
                    <span class="material-symbols-outlined text-[20px]">
                      dns
                    </span>
                  </div>
                  <div>
                    <p class="font-label-md text-label-md text-on-surface-variant mb-1">
                      Nama Server
                    </p>
                    <p class="font-body-md text-body-md font-semibold text-on-surface">
                      Node-DB-Master-01
                    </p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1">
                    <span class="material-symbols-outlined text-[20px]">
                      inventory_2
                    </span>
                  </div>
                  <div>
                    <p class="font-label-md text-label-md text-on-surface-variant mb-1">
                      Tipe Perangkat
                    </p>
                    <p class="font-body-md text-body-md font-semibold text-on-surface">
                      Rack Mount
                    </p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1">
                    <span class="material-symbols-outlined text-[20px]">
                      account_balance
                    </span>
                  </div>
                  <div>
                    <p class="font-label-md text-label-md text-on-surface-variant mb-1">
                      Status Kepemilikan
                    </p>
                    <p class="font-body-md text-body-md font-semibold text-on-surface">
                      Dinas Kominfo
                    </p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1">
                    <span class="material-symbols-outlined text-[20px]">
                      router
                    </span>
                  </div>
                  <div>
                    <p class="font-label-md text-label-md text-on-surface-variant mb-1">
                      IP Server
                    </p>
                    <p class="font-body-md text-body-md font-semibold text-on-surface">
                      192.168.10.101
                    </p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1">
                    <span class="material-symbols-outlined text-[20px]">
                      info
                    </span>
                  </div>
                  <div>
                    <p class="font-label-md text-label-md text-on-surface-variant mb-1">
                      Status Server
                    </p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                      Active
                    </span>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1">
                    <span class="material-symbols-outlined text-[20px]">
                      target
                    </span>
                  </div>
                  <div>
                    <p class="font-label-md text-label-md text-on-surface-variant mb-1">
                      Peruntukan Server
                    </p>
                    <p class="font-body-md text-body-md font-semibold text-on-surface">
                      Database Production
                    </p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1">
                    <span class="material-symbols-outlined text-[20px]">
                      schedule
                    </span>
                  </div>
                  <div>
                    <p class="font-label-md text-label-md text-on-surface-variant mb-1">
                      Waktu Pengisian
                    </p>
                    <p class="font-body-md text-body-md font-semibold text-on-surface">
                      12 Jan 2026, 10:00
                    </p>
                  </div>
                </div>
                <div class="flex items-start gap-3">
                  <div class="p-2 bg-primary/10 rounded-lg text-primary mt-1">
                    <span class="material-symbols-outlined text-[20px]">
                      swap_horiz
                    </span>
                  </div>
                  <div>
                    <p class="font-label-md text-label-md text-on-surface-variant mb-1">
                      Pengirim/Penerima
                    </p>
                    <p class="font-body-md text-body-md font-semibold text-on-surface">
                      Budi / Andi
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <!-- QR Code Section -->
            <div class="w-full md:w-64 bg-surface-container-low border-t md:border-t-0 md:border-l border-outline-variant p-6 flex flex-col items-center justify-center">
              <button class="flex items-center gap-2 bg-white text-on-surface-variant border border-outline-variant px-4 py-2 rounded-lg font-label-md hover:bg-surface-container-low transition-colors shadow-sm mb-4 w-full justify-center">
                <span class="material-symbols-outlined text-[18px]">
                  download
                </span>
                Unduh Kode QR
              </button>
              <img class="w-40 h-40 object-contain bg-white p-2 rounded-lg shadow-sm border border-outline-variant mb-4" data-alt="A crisp, high-contrast, black and white scannable QR code on a stark white background. Clean, modern, digital vector aesthetic, no artifacts, perfectly square." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBo5cL_-IX66qkUJYb_9ky2aVgpiOm8aq_RzyB2v1PjV5Cp8if2AEbXwPmRJHab066gPscOMTDicSTHQFyVNkGASI7MrvRnYnc5uyHIG327WezRZWCyhxVwvHv2s1qhDYEjjQaN9NQ0Q2wm81hfRDjuTYUmiZLpPWDBgjuGp-CvVApcoBmY-yheui9n8ZEjemuMitV0k4Eu-qdcIamc0bg9SNW-vNeK_XY3NZtbEsFKgfdrfhNJvq0Y">
              <p class="text-center font-label-md text-on-surface-variant">
                Scan untuk info server real-time
              </p>
            </div>
          </div>
          <!-- Right Column: Stats & Specs -->
          <div class="col-span-12 lg:col-span-4 flex flex-col gap-gutter">
            <!-- Summary Boxes Grid -->
            <div class="grid grid-cols-2 gap-gutter">
              <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant shadow-sm flex flex-col items-center justify-center text-center">
                <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 mb-2">
                  <span class="material-symbols-outlined">
                    memory
                  </span>
                </div>
                <p class="font-label-md text-on-surface-variant">
                  RAM
                </p>
                <p class="font-headline-md text-on-surface mt-1">
                  16 GB
                </p>
              </div>
              <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant shadow-sm flex flex-col items-center justify-center text-center">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mb-2">
                  <span class="material-symbols-outlined">
                    hard_drive
                  </span>
                </div>
                <p class="font-label-md text-on-surface-variant">
                  HDD
                </p>
                <p class="font-headline-md text-on-surface mt-1">
                  1 TB
                </p>
              </div>
              <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant shadow-sm flex flex-col items-center justify-center text-center">
                <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 mb-2">
                  <span class="material-symbols-outlined">
                    developer_board
                  </span>
                </div>
                <p class="font-label-md text-on-surface-variant">
                  Core
                </p>
                <p class="font-headline-md text-on-surface mt-1">
                  8 Core
                </p>
              </div>
              <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant shadow-sm flex flex-col items-center justify-center text-center">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mb-2">
                  <span class="material-symbols-outlined">
                    kitchen
                  </span>
                </div>
                <p class="font-label-md text-on-surface-variant">
                  RACK
                </p>
                <p class="font-headline-md text-on-surface mt-1">
                  Rack 2
                </p>
              </div>
            </div>
            <!-- Specs Boxes -->
            <div class="bg-surface-container-low rounded-xl p-4 border border-outline-variant shadow-sm flex flex-col gap-3">
              <div class="flex justify-between items-center bg-surface-container-lowest p-3 rounded border border-outline-variant/50">
                <span class="font-label-md text-on-surface-variant">
                  Merk
                </span>
                <span class="font-body-md font-semibold text-on-surface">
                  DELL PowerEdge
                </span>
              </div>
              <div class="flex justify-between items-center bg-surface-container-lowest p-3 rounded border border-outline-variant/50">
                <span class="font-label-md text-on-surface-variant">
                  Kondisi Server
                </span>
                <span class="font-body-md font-semibold text-on-surface">
                  Standard / Baru
                </span>
              </div>
              <div class="flex justify-between items-center bg-surface-container-lowest p-3 rounded border border-outline-variant/50">
                <span class="font-label-md text-on-surface-variant">
                  Spesifikasi Upgrade
                </span>
                <span class="font-body-md font-semibold text-on-surface">
                  SSD NVMe added
                </span>
              </div>
            </div>
          </div>
        </div>
        <!-- Bottom Section: Table -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-[0_1px_3px_rgba(0,0,0,0.1)] overflow-hidden">
          <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-bright">
            <h3 class="font-headline-md text-headline-md text-on-surface">
              Server using for
            </h3>
            <div class="flex gap-2">
              <button class="p-1.5 text-on-surface-variant hover:bg-surface-container-low rounded transition-colors border border-outline-variant bg-surface-container-lowest">
                <span class="material-symbols-outlined text-[20px]">
                  download
                </span>
              </button>
              <button class="p-1.5 text-on-surface-variant hover:bg-surface-container-low rounded transition-colors border border-outline-variant bg-surface-container-lowest">
                <span class="material-symbols-outlined text-[20px]">
                  more_vert
                </span>
              </button>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-[#F1F5F9] border-b border-outline-variant">
                  <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant">
                    IP Local
                  </th>
                  <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant">
                    IP Public
                  </th>
                  <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant">
                    Nama Aplikasi
                  </th>
                  <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant">
                    URL
                  </th>
                  <th class="px-6 py-3 font-label-md text-label-md text-on-surface-variant text-center">
                    Aksi
                  </th>
                </tr>
              </thead>
              <tbody class="font-data-tabular text-data-tabular">
                <tr class="bg-surface-container-lowest border-b border-outline-variant hover:bg-surface-container-low transition-colors">
                  <td class="px-6 py-4 text-on-surface font-semibold">
                    192.168.10.10
                  </td>
                  <td class="px-6 py-4 text-on-surface">
                    103.11.22.33
                  </td>
                  <td class="px-6 py-4 text-on-surface">
                    Sistem Keuangan
                  </td>
                  <td class="px-6 py-4 text-primary hover:underline cursor-pointer">
                    keuangan.situbondokab.go.id
                  </td>
                  <td class="px-6 py-4 text-center">
                    <button class="p-1.5 bg-primary/10 text-primary hover:bg-primary hover:text-on-primary rounded transition-colors inline-flex items-center justify-center" title="Pindah">
                      <span class="material-symbols-outlined text-[18px]">
                        east
                      </span>
                    </button>
                  </td>
                </tr>
                <tr class="bg-[#F8FAFC] border-b border-outline-variant hover:bg-surface-container-low transition-colors">
                  <td class="px-6 py-4 text-on-surface-variant italic">
                    -
                  </td>
                  <td class="px-6 py-4 text-on-surface-variant italic">
                    -
                  </td>
                  <td class="px-6 py-4 text-on-surface">
                    Portal Berita
                  </td>
                  <td class="px-6 py-4 text-primary hover:underline cursor-pointer">
                    berita.situbondokab.go.id
                  </td>
                  <td class="px-6 py-4 text-center">
                    <button class="p-1.5 bg-primary/10 text-primary hover:bg-primary hover:text-on-primary rounded transition-colors inline-flex items-center justify-center" title="Pindah">
                      <span class="material-symbols-outlined text-[18px]">
                        east
                      </span>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="px-6 py-4 border-t border-outline-variant flex flex-col md:flex-row justify-between items-center gap-4 bg-surface-bright">
            <div class="text-body-md text-on-surface-variant">
              Showing
              <span class="font-semibold text-on-surface">
                1
              </span>
              to
              <span class="font-semibold text-on-surface">
                2
              </span>
              of
              <span class="font-semibold text-on-surface">
                12
              </span>
              entries
            </div>
            <nav class="flex items-center gap-1">
              <button class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded transition-colors disabled:opacity-50" disabled="">
                <span class="material-symbols-outlined text-[20px]">
                  chevron_left
                </span>
              </button>
              <button class="w-8 h-8 flex items-center justify-center rounded bg-primary text-on-primary font-semibold text-body-md shadow-sm">
                1
              </button>
              <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-surface-container-low text-on-surface-variant font-medium text-body-md transition-colors">
                2
              </button>
              <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-surface-container-low text-on-surface-variant font-medium text-body-md transition-colors">
                3
              </button>
              <span class="px-1 text-on-surface-variant">
                ...
              </span>
              <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-surface-container-low text-on-surface-variant font-medium text-body-md transition-colors">
                6
              </button>
              <button class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded transition-colors">
                <span class="material-symbols-outlined text-[20px]">
                  chevron_right
                </span>
              </button>
            </nav>
          </div>
        </div>
      </main>
      <!-- Footer -->
      <footer class="full-width py-4 bg-surface-container-lowest border-t border-outline-variant flex justify-center items-center ml-sidebar-width z-10 relative">
        <p class="font-body-md text-body-md text-secondary">
          Copyright © 2026 Dinas Komunikasi dan Informatika Kab. Situbondo. All rights reserved.
        </p>
      </footer>
    </body>
  </html>
