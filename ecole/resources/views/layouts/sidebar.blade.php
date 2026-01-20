@php
    $links = [
        [
            'label' => 'Tableau de bord',
            'route' => 'dashboard',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h6.5v6.5h-6.5zM13.75 3.75h6.5v6.5h-6.5zM3.75 13.75h6.5v6.5h-6.5zM13.75 13.75h6.5v6.5h-6.5z" />',
            'active' => request()->routeIs('dashboard'),
        ],
        [
            'label' => 'Élèves',
            'route' => 'students.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.25 19.5V17.625A3.375 3.375 0 0013.875 14.25H10.125A3.375 3.375 0 006.75 17.625V19.5M15 7.875A3.375 3.375 0 118.25 7.875 3.375 3.375 0 0115 7.875z" />',
            'active' => request()->routeIs('students.*'),
        ],
        [
            'label' => 'Personnel',
            'route' => 'staff.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 8.25a3 3 0 11-6 0 3 3 0 016 0zM19.5 19.5a6 6 0 00-15 0" />',
            'active' => request()->routeIs('staff.*'),
        ],
        [
            'label' => 'Classes & Matières',
            'route' => 'classes.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 5.25h7.5m-7.5 0v13.5m0-13.5L15 4.5m-3 0v13.5m0-13.5h7.5m-7.5 0v13.5" />',
            'active' => request()->routeIs('classes.*'),
        ],
        [
            'label' => 'Notes & Bulletins',
            'url' => '#',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5l7.5 4.125L12 12.75 4.5 8.625 12 4.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 8.625V15.75L12 19.5l7.5-3.75V8.625" />',
        ],
        [
            'label' => 'Frais & Paiements',
            'url' => '#',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75h18M4.5 10.5h15m-15 6h6" />',
        ],
        [
            'label' => 'Documents',
            'url' => '#',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.5h7.5L18 8.25v11.25a1.5 1.5 0 01-1.5 1.5h-9a1.5 1.5 0 01-1.5-1.5V4.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 4.5v3.75H18" />',
        ],
        [
            'label' => 'Patrimoine',
            'url' => '#',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5L12 3l7.5 4.5v9L12 21l-7.5-4.5v-9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18" />',
        ],
        [
            'label' => 'Statistiques',
            'url' => '#',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5V9m5.25 10.5V4.5M15 19.5v-7.5M19.5 19.5v-3" />',
        ],
        [
            'label' => 'Messagerie',
            'url' => '#',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 9.75h9m-9 3h5.25M6 18.75h6l4.5 3v-3h1.5A3.75 3.75 0 0021.75 15V7.5A3.75 3.75 0 0018 3.75H6A3.75 3.75 0 002.25 7.5V15A3.75 3.75 0 006 18.75z" />',
        ],
        [
            'label' => 'Paramètres',
            'route' => 'settings.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h3m-7.5 0h1.5m9 0h1.5m-12 6h3m6 0h3m-12 6h1.5m9 0h1.5m-7.5 0h3" />',
            'active' => request()->routeIs('settings.*'),
        ],
    ];
@endphp

<aside class="{{ $classes ?? '' }} bg-white text-slate-900">
    <div class="flex h-full flex-col border-r border-slate-200 px-6 py-8">
        <a href="{{ route('dashboard') }}" class="space-y-1">
            <div class="text-lg font-semibold text-slate-900">Schermo ERP</div>
            <div class="text-sm text-slate-500">Gestion Scolaire</div>
        </a>

        <nav class="mt-10 flex-1 space-y-2">
            @foreach ($links as $link)
                @php
                    $isActive = $link['active'] ?? false;
                    $href = isset($link['route']) ? route($link['route']) : ($link['url'] ?? '#');
                @endphp
                <a
                    href="{{ $href }}"
                    class="{{ $isActive ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }} flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition"
                    @if ($isActive) aria-current="page" @endif
                >
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        {!! $link['icon'] !!}
                    </svg>
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</aside>
