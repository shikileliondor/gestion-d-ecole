<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/classes/index.css') }}">

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Vue graphique globale des résultats</h2>
                <p class="text-sm text-gray-500">Lecture rapide des tendances et de la performance par classe.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('pedagogy.results-dashboard.index') }}" class="secondary-button">Retour au tableau de bord</a>
                <a href="{{ route('pedagogy.report-cards.index') }}" class="ghost-button">Calculer moyennes</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6" data-async-page>
        <div class="mx-auto max-w-7xl space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <form method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Année scolaire</label>
                        <select name="academic_year_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}" @selected($selectedAcademicYearId === $year->id)>{{ $year->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Classe</label>
                        <select name="class_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Toutes</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected($selectedClassId === $class->id)>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Trimestre</label>
                        <select name="period_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Automatique</option>
                            @foreach ($periods as $period)
                                <option value="{{ $period->id }}" @selected($selectedPeriodId === $period->id)>{{ $period->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="primary-button">Actualiser</button>
                </form>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-800">Moyenne générale par classe</h3>
                        <p class="text-sm text-slate-500">Comparaison rapide des classes sur la période active.</p>
                    </div>
                    <div class="px-5 py-4">
                        <div class="space-y-3">
                            @foreach ($classAverages as $class)
                                @php
                                    $progress = $class['average'] ? min(100, ($class['average'] / 20) * 100) : 0;
                                @endphp
                                <div>
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span>{{ $class['name'] }}</span>
                                        <span class="font-semibold text-slate-700">{{ $class['average'] ?? '—' }}</span>
                                    </div>
                                    <div class="mt-2 h-2 w-full rounded-full bg-slate-100">
                                        <div class="h-2 rounded-full bg-indigo-500" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-800">Évaluations par classe</h3>
                        <p class="text-sm text-slate-500">Nombre d’évaluations validées enregistrées.</p>
                    </div>
                    <div class="px-5 py-4">
                        <div class="grid gap-3 md:grid-cols-2">
                            @foreach ($classAverages as $class)
                                <div class="rounded-lg border border-slate-100 px-4 py-3">
                                    <p class="text-sm font-semibold text-slate-800">{{ $class['name'] }}</p>
                                    <p class="text-xs text-slate-400">{{ $class['evaluation_count'] }} évaluation(s)</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-800">Évolution des moyennes</h3>
                        <p class="text-sm text-slate-500">Tendance par trimestre pour la classe sélectionnée.</p>
                    </div>
                    <div class="px-5 py-4">
                        @if ($trendSeries->isEmpty())
                            <div class="text-sm text-slate-500">Sélectionnez une classe pour afficher la tendance.</div>
                        @else
                            <div class="space-y-3">
                                @foreach ($trendSeries as $trend)
                                    @php
                                        $progress = $trend['average'] ? min(100, ($trend['average'] / 20) * 100) : 0;
                                    @endphp
                                    <div>
                                        <div class="flex items-center justify-between text-xs text-slate-500">
                                            <span>{{ $trend['label'] }}</span>
                                            <span class="font-semibold text-slate-700">{{ $trend['average'] ?? '—' }}</span>
                                        </div>
                                        <div class="mt-2 h-2 w-full rounded-full bg-slate-100">
                                            <div class="h-2 rounded-full bg-emerald-500" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-800">Top élèves</h3>
                        <p class="text-sm text-slate-500">Classements dynamiques sur la période active.</p>
                    </div>
                    <div class="px-5 py-4">
                        @if ($topStudents->isEmpty())
                            <div class="text-sm text-slate-500">Sélectionnez une classe pour afficher le top.</div>
                        @else
                            <ul class="space-y-3">
                                @foreach ($topStudents as $index => $student)
                                    <li class="flex items-center justify-between rounded-lg border border-slate-100 px-4 py-3">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-800">
                                                {{ $student['student']?->nom }} {{ $student['student']?->prenoms }}
                                            </p>
                                            <p class="text-xs text-slate-400">Rang #{{ $index + 1 }}</p>
                                        </div>
                                        <span class="text-sm font-semibold text-slate-800">{{ $student['average'] ?? '—' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
