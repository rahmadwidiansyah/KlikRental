<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KlikRental') }} - Digital Modern Tanpa Ribet</title>

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        /* Menggunakan CSS Variables dari :root dan .dark */
                        "primary": "var(--color-primary)",
                        "secondary": "var(--color-secondary)",
                        "forest-green": "var(--color-forest-green)",
                        "forest-light": "var(--color-forest-light)",
                        "background": "var(--color-background)",
                        "surface": "var(--color-surface)",
                        "surface-container-lowest": "var(--color-surface-container-lowest)",
                        "surface-container": "var(--color-surface-container)",
                        "on-surface": "var(--color-on-surface)",
                        "on-surface-variant": "var(--color-on-surface-variant)",
                        "outline-variant": "var(--color-outline-variant)",
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
        /* TEMA LIGHT (Terang) */
        :root {
            --color-primary: #6D5EF7;
            --color-secondary: #3B82F6;
            --color-forest-green: #476428;
            --color-forest-light: #f1f6ed;
            --color-background: #F9F9FB;
            --color-surface: #FFFFFF;
            --color-surface-container-lowest: #FFFFFF;
            --color-surface-container: #F3F3F5;
            --color-on-surface: #1A1C1D;
            --color-on-surface-variant: #44483D;
            --color-outline-variant: #C4C8B9;
        }

        /* TEMA DARK (Gelap) */
        .dark {
            --color-primary: #8174f8;
            /* Ungu sedikit lebih terang biar kontras */
            --color-secondary: #60a5fa;
            --color-forest-green: #8cd95c;
            --color-forest-light: #162411;
            /* Background hijau gelap */
            --color-background: #0f1115;
            /* Latar utama super gelap */
            --color-surface: #1a1d21;
            /* Latar kartu agak terang */
            --color-surface-container-lowest: #14171a;
            --color-surface-container: #262a2f;
            /* Latar kontainer */
            --color-on-surface: #f1f5f9;
            /* Teks putih terang */
            --color-on-surface-variant: #94a3b8;
            /* Teks abu-abu mute */
            --color-outline-variant: #334155;
            /* Garis border gelap */
        }

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

        .dark .premium-shadow {
            box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.2);
            /* Shadow lebih pekat di dark mode */
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
            background: linear-gradient(90deg, var(--color-primary), var(--color-secondary));
            transition: width 0.3s ease-in-out;
            border-radius: 2px;
        }

        .nav-link-hover:hover::after,
        .nav-link-hover.active::after {
            width: 100%;
        }
    </style>
</head>

<body class="bg-background text-on-surface font-inter text-[16px] antialiased overflow-x-hidden flex flex-col min-h-screen transition-colors duration-300">

    @include('layouts.navigation')

    <main class="flex-grow pt-16 pb-20 lg:pb-0">
        {{ $slot }}
    </main>

    <footer class="w-full py-6 border-t border-outline-variant/30 dark:border-outline-variant/50 bg-surface mt-auto transition-colors duration-300 mb-16 lg:mb-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0 text-center md:text-left">
                <span class="font-montserrat text-[18px] font-bold text-primary block mb-1">KlikRental</span>
                <p class="font-inter text-[13px] text-on-surface-variant dark:text-gray-400">© {{ date('Y') }} KlikRental Indonesia. Solusi Sewa Kendaraan Modern.</p>
            </div>
            <nav class="flex gap-6">
                <a class="font-inter text-[13px] text-on-surface-variant dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors" href="{{ route('privacy') }}">Kebijakan Privasi</a>
                <a class="font-inter text-[13px] text-on-surface-variant dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors" href="{{ route('terms') }}">Syarat & Ketentuan</a>
            </nav>
        </div>
    </footer>

</body>

</html>