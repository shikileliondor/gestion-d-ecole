<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <span class="inline-flex items-center gap-2 rounded-full bg-blue-600/10 px-4 py-1 text-xs font-semibold uppercase tracking-wide text-blue-600">
                Tableau de bord
            </span>
            <h1 class="text-3xl font-semibold text-slate-900">Bienvenue sur votre espace de pilotage</h1>
            <p class="text-sm text-slate-500">
                Aperçu temps réel des effectifs, finances et actions prioritaires de l'établissement.
            </p>
        </div>
    </x-slot>

    <div class="space-y-8">
        <section class="grid gap-6 lg:grid-cols-4">
            @foreach ($kpis as $kpi)
                @php
                    $toneClasses = match ($kpi['tone']) {
                        'blue' => 'bg-blue-100 text-blue-600',
                        'purple' => 'bg-purple-100 text-purple-600',
                        'red' => 'bg-rose-100 text-rose-600',
                        'emerald' => 'bg-emerald-100 text-emerald-600',
                        default => 'bg-slate-100 text-slate-600',
                    };
                    $toneGlow = match ($kpi['tone']) {
                        'blue' => 'bg-blue-100/40',
                        'purple' => 'bg-purple-100/40',
                        'red' => 'bg-rose-100/40',
                        'emerald' => 'bg-emerald-100/40',
                        default => 'bg-slate-100/40',
                    };
                @endphp
                <div class="relative overflow-hidden rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">{{ $kpi['title'] }}</p>
                            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $kpi['value'] }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $toneClasses }}">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                {!! $kpi['icon'] !!}
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-slate-500">{{ $kpi['subtitle'] }}</p>
                    <div class="pointer-events-none absolute -right-6 bottom-0 h-24 w-24 rounded-full {{ $toneGlow }}"></div>
                </div>
            @endforeach
        </section>

        <section class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Activité récente</h2>
                        <p class="text-sm text-slate-500">Historique des dernières opérations enregistrées</p>
                    </div>
                    <a href="{{ route('accounting.dashboard') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                        Tout voir
                    </a>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($activities as $activity)
                        <div class="flex items-start gap-4 rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                            <div class="mt-1 h-10 w-10 rounded-2xl bg-rose-500/10 text-rose-500 flex items-center justify-center">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3h.008M4.5 19.5h15a1.5 1.5 0 001.5-1.5v-9A1.5 1.5 0 0019.5 7.5h-15A1.5 1.5 0 003 9v9a1.5 1.5 0 001.5 1.5z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $activity->description ?: \Illuminate\Support\Str::headline($activity->action) }}
                                </p>
                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $activity->created_at->translatedFormat('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                            Aucune activité enregistrée pour le moment.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Actions rapides</h2>
                            <p class="text-sm text-slate-500">Accès direct aux opérations clés</p>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach ($quickActions as $action)
                            @php
                                $actionClasses = match ($action['tone']) {
                                    'emerald' => 'bg-emerald-500 hover:bg-emerald-600',
                                    'red' => 'bg-rose-500 hover:bg-rose-600',
                                    'slate' => 'bg-slate-900 hover:bg-slate-800',
                                    'purple' => 'bg-purple-500 hover:bg-purple-600',
                                    default => 'bg-blue-500 hover:bg-blue-600',
                                };
                            @endphp
                            <a
                                href="{{ $action['route'] }}"
                                class="flex flex-col items-center justify-center gap-3 rounded-2xl px-4 py-6 text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg {{ $actionClasses }}"
                            >
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/20">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        {!! $action['icon'] !!}
                                    </svg>
                                </span>
                                <span class="text-sm font-semibold">{{ $action['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">Synthèse établissement</h2>
                    <p class="text-sm text-slate-500">Instantané des ressources humaines et finances</p>

                    <div class="mt-6 grid gap-4">
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-4">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Effectif du personnel</p>
                                <p class="text-2xl font-semibold text-slate-800">{{ $staffCount }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 8.25a3 3 0 11-6 0 3 3 0 016 0zM19.5 19.5a6 6 0 00-15 0" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-4">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Soldes à régulariser</p>
                                <p class="text-2xl font-semibold text-slate-800">{{ $totalOutstanding }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m0 0l3-3m-3 3l-3-3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Évolution des inscriptions</h2>
                    <p class="text-sm text-slate-500">Comparatif mensuel des inscriptions et paiements enregistrés</p>
                </div>
                <div class="flex items-center gap-2 rounded-full bg-slate-100 p-1 text-xs font-semibold text-slate-500">
                    <span class="rounded-full bg-white px-3 py-1 text-slate-700">Semaine</span>
                    <span class="rounded-full bg-blue-600 px-3 py-1 text-white">Mois</span>
                    <span class="rounded-full bg-white px-3 py-1 text-slate-700">Année</span>
                </div>
            </div>

            <div class="mt-6">
                <div class="flex items-center gap-6 text-xs font-semibold text-slate-500">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        Inscriptions
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-rose-400"></span>
                        Paiements
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-6 items-end gap-4">
                    @foreach ($trendData as $data)
                        @php
                            $entriesHeight = $maxTrend > 0 ? ($data['entries'] / $maxTrend) * 100 : 0;
                            $paymentsHeight = $maxTrend > 0 ? ($data['payments'] / $maxTrend) * 100 : 0;
                            $entriesHeight = $data['entries'] > 0 ? max($entriesHeight, 8) : 2;
                            $paymentsHeight = $data['payments'] > 0 ? max($paymentsHeight, 8) : 2;
                        @endphp
                        <div class="flex flex-col items-center gap-3">
                            <div class="flex h-40 items-end gap-2">
                                <div class="w-4 rounded-full bg-blue-500/80" style="height: {{ $entriesHeight }}%"></div>
                                <div class="w-4 rounded-full bg-rose-400/80" style="height: {{ $paymentsHeight }}%"></div>
                            </div>
                            <span class="text-xs font-semibold text-slate-400">{{ $data['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
