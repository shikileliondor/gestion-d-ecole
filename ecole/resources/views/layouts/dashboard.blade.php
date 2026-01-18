<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Dashboard</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100">
        <div class="flex min-h-screen flex-col">
            <header class="border-b border-slate-800 bg-slate-950/80">
                <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-500/20 text-indigo-300">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M4 3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V3Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Gestion d'ecole</p>
                            <p class="text-lg font-semibold text-white">Tableau de bord</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 rounded-full border border-slate-700 bg-slate-900 px-4 py-2 text-sm">
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 3.529 9.712l3.63 3.63a.75.75 0 1 0 1.061-1.06l-3.63-3.631A5.5 5.5 0 0 0 9 3.5Zm-4 5.5a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-slate-400">Recherche</span>
                        </div>
                        <div class="flex items-center gap-2 rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-200">
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            Azerty
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1">
                @yield('content')
            </main>
        </div>
    </body>
</html>
