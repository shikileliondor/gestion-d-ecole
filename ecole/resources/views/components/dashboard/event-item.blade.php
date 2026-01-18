@props([
    'date',
    'time',
    'title',
    'grade',
    'badgeClass' => 'bg-[#ffbf4d] text-[#1b2b42]'
])

<div class="rounded-2xl bg-[#f5f8ff] p-4">
    <div class="flex items-center justify-between">
        <span class="rounded-full px-2 py-1 text-[10px] font-semibold uppercase tracking-wide {{ $badgeClass }}">{{ $date }}</span>
        <span class="text-xs text-[#8b97b1]">{{ $time }}</span>
    </div>
    <p class="mt-3 text-sm font-semibold text-[#1b2b42]">{{ $title }}</p>
    <p class="mt-2 text-xs font-medium text-[#1677ff]">{{ $grade }}</p>
</div>
