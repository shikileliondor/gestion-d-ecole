@props(['tabs' => [], 'active' => 'informations'])

{{-- Reusable tab list component. Requires an "activeTab" Alpine variable in scope. --}}
<div
    class="flex flex-wrap items-center gap-2 rounded-full bg-gray-100 p-1"
    x-init="activeTab = activeTab || '{{ $active }}'"
>
    @foreach ($tabs as $tab)
        <button
            type="button"
            class="rounded-full px-4 py-2 text-sm font-semibold transition"
            @click="activeTab = '{{ $tab['key'] }}'"
            :class="activeTab === '{{ $tab['key'] }}' ? 'bg-white text-gray-900 shadow' : 'text-gray-500 hover:text-gray-900'"
        >
            {{ $tab['label'] }}
        </button>
    @endforeach
</div>
