<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'SIM TIK - Dashboard')</title>

    <!-- Scripts Breeze (Vite) - DIKOMENTARI SEMENTARA -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Fonts & Icons Custom -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: "#004ac6",
              "primary-container": "#2563eb",
              "on-primary-container": "#eeefff",
              secondary: "#565e74",
              background: "#f7f9fb",
              "on-background": "#191c1e",
              "on-surface": "#191c1e",
              "on-surface-variant": "#434655",
              "surface-bright": "#f7f9fb",
              "surface-container": "#eceef0",
              "surface-container-low": "#f2f4f6",
              "surface-container-lowest": "#ffffff",
              outline: "#737686",
              "outline-variant": "#dde0e8",
            },
            fontFamily: { sans: ["Inter", "sans-serif"] },
            spacing: {
              "sidebar-width": "260px",
              "container-padding": "28px",
              "topbar-height": "68px",
            },
          },
        },
      };
    </script>

    <style>
        /* Menyembunyikan elemen saat mencetak PDF */
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            main { padding: 0 !important; margin: 0 !important; }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-background text-on-background min-h-screen flex">
    @include('partials.sidebar')
    <div class="flex-1 flex flex-col md:ml-sidebar-width min-w-0">
        @include('partials.navbar')
        <main class="flex-1 px-container-padding py-8 space-y-8 max-w-[1400px] w-full mx-auto">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
        <footer class="bg-white py-5 border-t border-outline-variant text-center no-print">
            <p class="text-sm text-secondary">Copyright &copy; {{ date('Y') }} Dinas Komunikasi dan Informatika Kab. Situbondo.</p>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
