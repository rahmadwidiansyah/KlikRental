<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KlikRental') }} - Masuk</title>

        <!-- Fonts & Icons (Disamakan dengan app.blade.php) -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Tailwind Config via CDN (Ini kunci biar warnanya muncul!) -->
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <script id="tailwind-config">
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        colors: {
                            "primary": "#6D5EF7",
                            "secondary": "#3B82F6",
                            "forest-green": "#476428",
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
    </head>
    <body class="font-inter text-on-surface antialiased bg-background">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/">
                    <!-- Bisa ganti pakai logo KlikRental kamu di sini -->
                    <span class="font-montserrat text-3xl font-bold text-primary">KlikRental</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-surface shadow-[0px_8px_24px_rgba(0,0,0,0.04)] overflow-hidden sm:rounded-2xl border border-outline-variant/30">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>