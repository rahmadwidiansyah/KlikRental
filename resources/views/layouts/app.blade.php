<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KlikRental') }} - Digital Modern Tanpa Ribet</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Scripts (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind Config via CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#6D5EF7",
                        /* Ungu Dominan */
                        "secondary": "#3B82F6",
                        /* Biru Kombinasi */
                        "forest-green": "#476428",
                        /* Hijau Forest (Aksen Positif/Tersedia) */
                        "forest-light": "#f1f6ed",
                        "background": "#F9F9FB",
                        "surface": "#FFFFFF",
                        "surface-container-lowest": "#FFFFFF",
                        "surface-container": "#F3F3F5",
                        "on-surface": "#1A1C1D",
                        "on-surface-variant": "#44483D",
                        "outline-variant": "#C4C8B9",
                    },
                    fontFamily: {
                        "montserrat": ["Montserrat", "sans-serif"],
                        "inter": ["Inter", "sans-serif"],
                    }
                }
            }
        }
    </script>

    <style>
        html {
            scroll-behavior: smooth;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .premium-shadow {
            box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.04);
        }

        .vehicle-card:hover {
            transform: translateY(-3px);
            box-shadow: 0px 12px 32px rgba(109, 94, 247, 0.15);
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .icon-fill {
            font-variation-settings: 'FILL' 1;
        }

        .nav-link-hover {
            position: relative;
            display: inline-block;
        }

        .nav-link-hover::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background: linear-gradient(90deg, #6D5EF7, #3B82F6);
            transition: width 0.3s ease-in-out;
            border-radius: 2px;
        }

        .nav-link-hover:hover::after,
        .nav-link-hover.active::after {
            width: 100%;
        }

        .hero-bg {
            background-size: cover;
            background-position: center;
        }

        .gradient-overlay {
            background: linear-gradient(180deg, rgba(109, 94, 247, 0.2) 0%, rgba(0, 0, 0, 0.8) 100%);
        }


    </style>
</head>

<body class="bg-background text-on-surface font-inter text-[16px] antialiased overflow-x-hidden flex flex-col min-h-screen">

    @include('layouts.navigation')

    <main class="flex-grow pt-16"> <!-- Padding atas dikurangi agar lebih compact -->
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="w-full py-6 border-t border-outline-variant/30 bg-surface mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0 text-center md:text-left">
                <span class="font-montserrat text-[18px] font-bold text-primary block mb-1">KlikRental</span>
                <p class="font-inter text-[13px] text-on-surface-variant">© {{ date('Y') }} KlikRental Indonesia. Solusi Sewa Kendaraan Modern.</p>
            </div>
            <nav class="flex gap-6">
                <a class="font-inter text-[13px] text-on-surface-variant hover:text-primary transition-colors" href="#">Kebijakan Privasi</a>
                <a class="font-inter text-[13px] text-on-surface-variant hover:text-primary transition-colors" href="#">Syarat & Ketentuan</a>
            </nav>
        </div>
    </footer>

</body>

</html>