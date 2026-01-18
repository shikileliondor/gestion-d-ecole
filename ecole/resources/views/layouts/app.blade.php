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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white text-gray-900">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen">
            <div class="flex min-h-screen">
                <!-- Sidebar -->
                @include('layouts.sidebar', ['classes' => 'hidden lg:flex lg:w-64 lg:flex-col'])

                <div class="flex min-h-screen flex-1 flex-col">
                    <!-- Mobile Header -->
                    <header class="flex items-center justify-between border-b border-gray-200 bg-white px-4 py-4 shadow-sm lg:hidden">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                            @click="sidebarOpen = true"
                        >
                            <span class="sr-only">Open sidebar</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <a href="{{ route('dashboard') }}" class="text-base font-semibold text-gray-800">
                            {{ config('app.name', 'Laravel') }}
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
                        <div class="fixed inset-0 bg-gray-900/60" @click="sidebarOpen = false"></div>
                        <div class="relative flex w-full max-w-xs flex-1">
                            @include('layouts.sidebar', ['classes' => 'flex w-full flex-col'])
                            <button
                                type="button"
                                class="absolute right-0 top-0 m-4 inline-flex items-center justify-center rounded-md p-2 text-white hover:bg-white/10"
                                @click="sidebarOpen = false"
                            >
                                <span class="sr-only">Close sidebar</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white shadow">
                            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main class="flex-1 bg-white">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
