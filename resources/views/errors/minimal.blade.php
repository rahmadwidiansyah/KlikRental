<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <script id="tailwind-config">
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        colors: {
                            "primary": "var(--color-primary)",
                            "secondary": "var(--color-secondary)",
                            "background": "var(--color-background)",
                            "surface": "var(--color-surface)",
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
            :root {
                --color-primary: #6D5EF7;
                --color-secondary: #3B82F6;
                --color-background: #F9F9FB;
                --color-surface: #FFFFFF;
                --color-on-surface: #1A1C1D;
                --color-on-surface-variant: #44483D;
                --color-outline-variant: #C4C8B9;
            }

            .dark {
                --color-primary: #8174f8;
                --color-secondary: #60a5fa;
                --color-background: #0f1115;
                --color-surface: #1a1d21;
                --color-on-surface: #f1f5f9;
                --color-on-surface-variant: #94a3b8;
                --color-outline-variant: #334155;
            }
        </style>
    </head>
    <body class="antialiased bg-background text-on-surface font-inter transition-colors duration-300">
        <div class="relative flex items-top justify-center min-h-screen bg-background sm:items-center sm:pt-0">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                    <h1 class="px-4 text-3xl font-montserrat font-bold text-primary border-r border-outline-variant/60 tracking-wider">
                        @yield('code')
                    </h1>

                    <div class="ml-4 text-lg font-inter text-on-surface-variant uppercase tracking-wider">
                        @yield('message')
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>