<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-100 text-slate-900">
        @php
            $now = now();
            $user = auth()->user();
            $userInitials = $user
                ? collect(explode(' ', $user->name))
                    ->map(fn ($segment) => mb_substr($segment, 0, 1))
                    ->take(2)
                    ->implode('')
                : '';
            $notificationCount = \App\Models\JournalAction::whereDate('created_at', $now->toDateString())->count();
        @endphp
        <div x-data="{ sidebarOpen: false }" class="min-h-screen">
            <div class="flex min-h-screen">
                <!-- Sidebar -->
                @include('layouts.sidebar', ['classes' => 'hidden lg:flex lg:w-72 lg:flex-col'])

                <div class="flex min-h-screen flex-1 flex-col">
                    <!-- Mobile Header -->
                    <header class="flex items-center justify-between border-b border-slate-200 bg-white px-4 py-4 shadow-sm lg:hidden">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700"
                            @click="sidebarOpen = true"
                        >
                            <span class="sr-only">Open sidebar</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <a href="{{ route('dashboard') }}" class="text-base font-semibold text-slate-900">
                            {{ config('app.name', 'Schermo ERP') }}
                        </a>
                    </header>

                    <!-- Mobile Sidebar -->
                    <div
                        x-show="sidebarOpen"
                        x-transition.opacity
                        class="fixed inset-0 z-40 flex lg:hidden"
                        role="dialog"
                        aria-modal="true"
                    >
                        <div class="fixed inset-0 bg-slate-900/70" @click="sidebarOpen = false"></div>
                        <div class="relative flex w-full max-w-xs flex-1">
                            @include('layouts.sidebar', ['classes' => 'flex w-full flex-col'])
                            <button
                                type="button"
                                class="absolute right-0 top-0 m-4 inline-flex items-center justify-center rounded-lg p-2 text-white hover:bg-white/10"
                                @click="sidebarOpen = false"
                            >
                                <span class="sr-only">Close sidebar</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <main class="flex-1 bg-slate-100">
                        <div class="sticky top-0 z-20 border-b border-slate-200/70 bg-slate-100/80 backdrop-blur">
                            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Aujourd'hui</p>
                                        <p class="text-sm font-semibold text-slate-800">
                                            {{ $now->translatedFormat('l d F Y') }}
                                        </p>
                                        <p class="text-sm font-semibold text-blue-600">{{ $now->format('H:i') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button class="relative inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 shadow-sm hover:text-slate-700">
                                        <span class="sr-only">Notifications</span>
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-5-5.9V4a1 1 0 10-2 0v1.1A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5" />
                                        </svg>
                                        @if ($notificationCount > 0)
                                            <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white">
                                                {{ $notificationCount }}
                                            </span>
                                        @endif
                                    </button>
                                    <a
                                        href="{{ route('profile.edit') }}"
                                        class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-2 shadow-sm transition hover:border-blue-200 hover:bg-blue-50/40"
                                    >
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">
                                            {{ $userInitials }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-800">{{ $user?->name ?? 'Utilisateur' }}</p>
                                            <p class="text-xs text-slate-400">{{ $user?->email }}</p>
                                        </div>
                                    </a>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="inline-flex h-11 items-center justify-center rounded-2xl border border-rose-200 bg-white px-4 text-sm font-semibold text-rose-600 shadow-sm transition hover:bg-rose-50"
                                        >
                                            Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="mx-auto max-w-7xl px-6 pb-12">
                            @isset($header)
                                <div class="py-8">
                                    {{ $header }}
                                </div>
                            @endisset

                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
