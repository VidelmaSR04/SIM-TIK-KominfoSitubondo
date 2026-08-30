<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Login - SIM TIK</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-tertiary-container": "#ffeedd",
                        "tertiary-fixed-dim": "#ffb95f",
                        "on-surface": "#191c1e",
                        "secondary-container": "#dae2fd",
                        "surface": "#f7f9fb",
                        "secondary-fixed": "#dae2fd",
                        "on-surface-variant": "#434655",
                        "inverse-on-surface": "#eff1f3",
                        "on-primary-fixed-variant": "#003ea8",
                        "primary-container": "#2563eb",
                        "outline-variant": "#c3c6d7",
                        "on-primary-container": "#eeefff",
                        "primary-fixed-dim": "#b4c5ff",
                        "primary": "#004ac6",
                        "inverse-surface": "#2d3133",
                        "primary-fixed": "#dbe1ff",
                        "outline": "#737686",
                        "on-error-container": "#93000a",
                        "on-primary-fixed": "#00174b",
                        "error-container": "#ffdad6",
                        "tertiary-fixed": "#ffddb8",
                        "on-tertiary-fixed-variant": "#653e00",
                        "tertiary-container": "#996100",
                        "surface-dim": "#d8dadc",
                        "on-background": "#191c1e",
                        "on-tertiary-fixed": "#2a1700",
                        "surface-bright": "#f7f9fb",
                        "surface-container": "#eceef0",
                        "on-secondary": "#ffffff",
                        "surface-variant": "#e0e3e5",
                        "on-secondary-fixed": "#131b2e",
                        "surface-tint": "#0053db",
                        "on-secondary-fixed-variant": "#3f465c",
                        "surface-container-low": "#f2f4f6",
                        "tertiary": "#784b00",
                        "on-error": "#ffffff",
                        "on-tertiary": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "background": "#f7f9fb",
                        "on-primary": "#ffffff",
                        "error": "#ba1a1a",
                        "surface-container-highest": "#e0e3e5",
                        "inverse-primary": "#b4c5ff",
                        "surface-container-high": "#e6e8ea",
                        "secondary-fixed-dim": "#bec6e0",
                        "on-secondary-container": "#5c647a",
                        "secondary": "#565e74"
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        full: "9999px"
                    },
                    spacing: {
                        gutter: "16px",
                        base: "4px",
                        "sidebar-width": "260px",
                        "container-padding": "24px",
                        "topbar-height": "64px"
                    },
                    fontFamily: {
                        "data-tabular": ["Inter"],
                        "headline-lg": ["Inter"],
                        "headline-md": ["Inter"],
                        "label-md": ["Inter"],
                        "body-lg": ["Inter"],
                        display: ["Inter"],
                        "body-md": ["Inter"]
                    },
                    fontSize: {
                        "data-tabular": ["13px", { lineHeight: "18px", fontWeight: "400" }],
                        "headline-lg": ["24px", { lineHeight: "32px", letterSpacing: "-0.01em", fontWeight: "600" }],
                        "headline-md": ["20px", { lineHeight: "28px", fontWeight: "600" }],
                        "label-md": ["12px", { lineHeight: "16px", letterSpacing: "0.05em", fontWeight: "600" }],
                        "body-lg": ["16px", { lineHeight: "24px", fontWeight: "400" }],
                        display: ["36px", { lineHeight: "44px", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "body-md": ["14px", { lineHeight: "20px", fontWeight: "400" }]
                    }
                }
            }
        }
    </script>
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .bg-pattern {
            background-color: #0F172A;
            background-image: radial-gradient(circle at 100% 100%, rgba(37, 99, 235, 0.15) 0, transparent 50%), radial-gradient(circle at 0% 0%, rgba(37, 99, 235, 0.15) 0, transparent 50%);
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center font-body-md text-body-md text-on-surface antialiased p-container-padding">
<!-- Background animasi GhostFibers -->
<div
    data-ghost-fibers
    data-line-color="#0B1440"
    data-glow-color="#2563EB"
    data-speed="0.2"
    data-scale="2.2"
    data-layers="4"
    data-brightness="1.8"
    data-vignette="0.85"
    class="fixed inset-0 z-0 pointer-events-none"
></div>

<main class="w-full max-w-md relative z-10">
    <div class="glass-card rounded-xl p-8 w-full relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-primary"></div>
        <div class="flex flex-col items-center mb-8">
            <div class="w-20 h-20 mb-4 bg-surface-container rounded-full flex items-center justify-center p-2 shadow-sm border border-outline-variant">
                <img alt="Logo SIM TIK" class="w-full h-full object-contain rounded-full" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAQJSb5r5YOrA31aZnifUIzdFyA98SsBowiQK3VHImjbxTnI3ufbabOGPUefNbKEMJIKnACAK8egrHZj0vjhNqQD2KdLKECsK2ZYpJBhblG_nHBjnx0NW7ZXsKPDPFMkzrV1d5iTdy8E95KCgNkkMeb7-lbXIqFzF_Azyv4Qnzz1Qy3dPxpug5ovDQaWC3TaQQMssfytgvQcJgl-Noux0VTl1BQDlEEbS7oyoyzFrdgVWS-LfItyDJD"/>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-center text-on-surface mb-2">Login ke SIM TIK</h1>
            <p class="font-body-md text-body-md text-on-surface-variant text-center">Sistem Informasi Manajemen Data Center</p>
        </div>

        {{-- Session Status (dari Breeze) --}}
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            {{-- Email --}}
            <div class="space-y-2">
                <label class="block font-label-md text-label-md text-on-surface" for="email">Email</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">person</span>
                    <input class="w-full pl-10 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-shadow @error('email') border-red-500 @enderror"
                           id="email" type="email" name="email" value="{{ old('email') }}"
                           required autofocus autocomplete="username" placeholder="Masukkan email"/>
                </div>
                @error('email')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="space-y-2">
                <label class="block font-label-md text-label-md text-on-surface" for="password">Password</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">lock</span>
                    <input class="w-full pl-10 pr-10 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-shadow @error('password') border-red-500 @enderror"
                           id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password"/>
                    <button aria-label="Toggle password visibility" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface focus:outline-none" onclick="togglePassword()" type="button">
                        <span class="material-symbols-outlined" id="togglePasswordIcon">visibility</span>
                    </button>
                </div>
                @error('password')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember me & Forgot password --}}
            <div class="flex items-center justify-between mt-2">
                <div class="flex items-center">
                    <input class="w-4 h-4 text-primary bg-surface-container-lowest border-outline-variant rounded focus:ring-primary focus:ring-2" id="remember" type="checkbox" name="remember">
                    <label class="ml-2 font-body-md text-body-md text-on-surface-variant" for="remember">Ingat saya</label>
                </div>
                @if (Route::has('password.request'))
                    <a class="font-label-md text-label-md text-primary hover:underline" href="{{ route('password.request') }}">Lupa Password?</a>
                @endif
            </div>

            {{-- Submit --}}
            <button class="w-full bg-primary text-on-primary font-label-md text-label-md py-3 px-4 rounded-lg hover:bg-on-primary-fixed-variant focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors shadow-sm flex items-center justify-center gap-2" type="submit">
                <span class="material-symbols-outlined">login</span>
                Login
            </button>
        </form>

        {{-- ============================================= --}}
        {{-- LINK REGISTER USER --}}
        {{-- Link Register --}}
        <div class="mt-8 space-y-3 border-t border-gray-200 pt-6">
            {{-- Link Register User Biasa --}}
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Daftar Akun
                    </a>
                </p>
            </div>
        </div>
    </div>
</main>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.textContent = 'visibility_off';
        } else {
            passwordInput.type = 'password';
            toggleIcon.textContent = 'visibility';
        }
    }
</script>
@vite(['resources/js/ghost-fibers.js'])
</body>
</html>
