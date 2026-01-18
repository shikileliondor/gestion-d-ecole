<header class="flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-semibold text-[#1b2b42]">Dashboard</h1>
    </div>

    <div class="flex flex-1 items-center justify-end gap-4">
        <div class="relative w-full max-w-[280px]">
            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[#a1aec8]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" />
                </svg>
            </span>
            <input
                type="text"
                placeholder="Search"
                class="w-full rounded-xl border border-transparent bg-white py-2.5 pl-9 pr-4 text-sm text-[#4c5b77] shadow-sm outline-none focus:border-[#d5e1fb]"
            />
        </div>
        <button class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#ffbf4d] text-white shadow-sm">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a3 3 0 0 1 3 3v6a3 3 0 1 1-6 0V5a3 3 0 0 1 3-3Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 0 1-14 0" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v4" />
            </svg>
        </button>
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 overflow-hidden rounded-full border border-white shadow-sm">
                <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=facearea&w=80&h=80" alt="Avatar" class="h-full w-full object-cover" />
            </div>
            <div class="text-right text-sm">
                <p class="font-semibold text-[#1b2b42]">Brandon Septimus</p>
                <p class="text-xs text-[#7d8aa5]">Admin</p>
            </div>
        </div>
        <button class="relative flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#7d8aa5] shadow-sm">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 0 0-5-5.9V4a1 1 0 1 0-2 0v1.1A6 6 0 0 0 6 11v3.2a2 2 0 0 1-.6 1.4L4 17h5" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 0 0 6 0" />
            </svg>
            <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-[#ff5c5c]"></span>
        </button>
    </div>
</header>
