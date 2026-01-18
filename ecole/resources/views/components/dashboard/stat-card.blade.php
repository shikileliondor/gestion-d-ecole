@props([
    'title',
    'value',
    'iconClass' => 'text-[#1b2b42]',
    'titleClass' => 'text-[#1b2b42]/80',
    'valueClass' => 'text-[#1b2b42]'
])

<div {{ $attributes->merge(['class' => 'rounded-2xl px-5 py-4 shadow-sm']) }}>
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm font-medium {{ $titleClass }}">{{ $title }}</p>
            <p class="mt-1 text-2xl font-semibold {{ $valueClass }}">{{ $value }}</p>
        </div>
        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white/40">
            <svg class="h-4 w-4 {{ $iconClass }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 17l10-10M14 7h3v3" />
            </svg>
        </div>
    </div>
</div>
