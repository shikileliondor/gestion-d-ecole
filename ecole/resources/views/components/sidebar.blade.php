@php
    $linkBaseClasses = 'group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition';
    $activeClasses = 'bg-indigo-50 text-indigo-700 dark:bg-gray-900 dark:text-indigo-300';
    $inactiveClasses = 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white';
    $links = [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'active' => request()->routeIs('dashboard'),
        ],
        [
            'label' => 'Élèves',
            'route' => 'students.index',
            'active' => request()->routeIs('students.*'),
        ],
        [
            'label' => 'Profil',
            'route' => 'profile.edit',
            'active' => request()->routeIs('profile.*'),
        ],
    ];
@endphp

<aside class="hidden lg:flex lg:w-64 lg:flex-col lg:shrink-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
    <div class="px-6 py-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-gray-800 dark:text-gray-100">
            <x-application-logo class="h-8 w-auto fill-current text-indigo-600 dark:text-indigo-300" />
            <span class="text-base font-semibold">Gestion d'école</span>
        </a>
    </div>

    <nav class="flex-1 space-y-1 px-4 pb-6">
        @foreach ($links as $link)
            <a
                href="{{ route($link['route']) }}"
                @class([$linkBaseClasses, $link['active'] ? $activeClasses : $inactiveClasses])
            >
                <span class="h-2 w-2 rounded-full bg-current opacity-60"></span>
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
        Version 1.0
    </div>
</aside>
