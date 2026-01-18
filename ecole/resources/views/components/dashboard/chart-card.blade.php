@props([
    'title',
    'action' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-2xl bg-white p-4 shadow-sm']) }}>
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-semibold text-[#1b2b42]">{{ $title }}</h3>
        @if($action)
            <span class="rounded-full bg-[#f4f7ff] px-2.5 py-1 text-xs font-medium text-[#7d8aa5]">{{ $action }}</span>
        @endif
    </div>
    <div class="mt-4">
        {{ $slot }}
    </div>
</div>
