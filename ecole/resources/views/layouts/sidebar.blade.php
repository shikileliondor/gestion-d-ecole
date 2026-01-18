@php
    $links = [
        [
            'label' => __('Dashboard'),
            'route' => 'dashboard',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4" />',
        ],
    ];
@endphp

<aside class="{{ $classes ?? '' }} bg-white text-gray-800">
    <div class="flex h-full flex-col border-r border-gray-200 px-4 py-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-2">
            <x-application-logo class="h-9 w-auto fill-current text-gray-800" />
            <span class="text-lg font-semibold">{{ config('app.name', 'Laravel') }}</span>
        </a>

        <nav class="mt-8 flex-1 space-y-1">
            @foreach ($links as $link)
                @php
                    $isActive = request()->routeIs($link['route']);
                @endphp
                <a
                    href="{{ route($link['route']) }}"
                    class="{{ $isActive ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }} flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition"
                >
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        {!! $link['icon'] !!}
                    </svg>
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="mt-6 border-t border-gray-200 pt-6">
            <div class="px-3 text-sm font-medium text-gray-500">
                {{ Auth::user()->name }}
            </div>
            <div class="mt-4 space-y-1">
                <a
                    href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900"
                >
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0" />
                    </svg>
                    <span>{{ __('Profile') }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="flex w-full items-center gap-3 rounded-md px-3 py-2 text-left text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900"
                    >
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        <span>{{ __('Log Out') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>
