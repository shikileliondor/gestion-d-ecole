<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <span class="inline-flex items-center gap-2 rounded-full bg-blue-600/10 px-4 py-1 text-xs font-semibold uppercase tracking-wide text-blue-600">
                Tableau de bord
            </span>
            <h1 class="text-3xl font-semibold text-slate-900">Bienvenue sur votre espace de pilotage</h1>
            <p class="text-sm text-slate-500">
                Vue d'ensemble des inscriptions, paiements et priorités opérationnelles de l'établissement.
            </p>
        </div>
    </x-slot>

    <div
        id="dashboard-charts"
        data-trend-labels='@json($trendData->pluck("label"))'
        data-trend-entries='@json($trendData->pluck("entries"))'
        data-trend-payments='@json($trendData->pluck("payments"))'
        data-class-labels='@json($classBreakdown->pluck("name"))'
        data-class-values='@json($classBreakdown->pluck("total"))'
    ></div>

    <div class="space-y-8">
        <section class="grid gap-6 lg:grid-cols-4">
            @foreach ($kpis as $kpi)
                @php
                    $toneBorder = match ($kpi['tone']) {
                        'blue' => 'border-blue-500',
                        'purple' => 'border-purple-500',
                        'red' => 'border-rose-500',
                        'emerald' => 'border-emerald-500',
                        default => 'border-slate-300',
                    };
                    $toneIcon = match ($kpi['tone']) {
                        'blue' => 'bg-blue-100 text-blue-600',
                        'purple' => 'bg-purple-100 text-purple-600',
                        'red' => 'bg-rose-100 text-rose-600',
                        'emerald' => 'bg-emerald-100 text-emerald-600',
                        default => 'bg-slate-100 text-slate-600',
                    };
                    $toneAccent = match ($kpi['tone']) {
                        'blue' => 'text-blue-600',
                        'purple' => 'text-purple-600',
                        'red' => 'text-rose-600',
                        'emerald' => 'text-emerald-600',
                        default => 'text-slate-500',
                    };
                @endphp
                <div class="relative overflow-hidden rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="absolute inset-x-0 top-0 h-1.5 {{ $toneBorder }}"></div>
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-500">{{ $kpi['title'] }}</p>
                            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $kpi['value'] }}</p>
                            <p class="mt-2 text-sm font-semibold {{ $toneAccent }}">{{ $kpi['subtitle'] }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $toneIcon }}">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                {!! $kpi['icon'] !!}
                            </svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>

        <section class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Activité récente</h2>
                        <p class="text-sm text-slate-500">Dernières opérations enregistrées</p>
                    </div>
                    <a href="{{ route('accounting.dashboard') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                        Tout voir
                    </a>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($activities as $activity)
                        <div class="flex items-start gap-4 rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                            <div class="mt-1 flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-500/10 text-rose-500">
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
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Synthèse établissement</h2>
                            <p class="text-sm text-slate-500">Ressources humaines & finances</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-4">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Effectif du personnel</p>
                                <p class="text-2xl font-semibold text-slate-800">{{ $staffCount }}</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600">
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
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m0 0l3-3m-3 3l-3-3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Évolution des inscriptions</h2>
                        <p class="text-sm text-slate-500">Comparatif mensuel inscriptions & paiements</p>
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
                    <div class="mt-6 h-72">
                        <canvas id="enrollmentChart" class="h-full w-full"></canvas>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Répartition par classe</h2>
                    <p class="text-sm text-slate-500">Poids des effectifs par niveau</p>
                </div>

                <div class="mt-6 flex flex-col gap-6">
                    <div class="mx-auto flex h-52 w-52 items-center justify-center">
                        <canvas id="classChart" class="h-full w-full"></canvas>
                    </div>

                    <div class="space-y-3">
                        @forelse ($classBreakdown as $class)
                            <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                                    <span class="text-sm font-semibold text-slate-700">{{ $class['name'] }}</span>
                                </div>
                                <div class="text-sm font-semibold text-slate-700">{{ $class['total'] }}</div>
                                <div class="text-sm text-slate-500">{{ $class['percentage'] }}%</div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                                Aucune classe enregistrée.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Statut des élèves</h2>
                    <p class="text-sm text-slate-500">Répartition des statuts d'inscription</p>
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($statusBreakdown as $status)
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-slate-700">{{ $status['label'] }}</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $status['total'] }}</p>
                        </div>
                        <div class="mt-3 h-2 w-full rounded-full bg-white">
                            <div class="h-2 rounded-full bg-blue-600" style="width: {{ $status['percentage'] }}%"></div>
                        </div>
                        <p class="mt-2 text-xs text-slate-400">{{ $status['percentage'] }}% du total</p>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                        Aucun statut disponible.
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    @vite('resources/js/dashboard.js')
</x-app-layout>
