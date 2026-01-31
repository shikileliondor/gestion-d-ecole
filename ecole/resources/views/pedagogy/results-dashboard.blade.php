<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/classes/index.css') }}">

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Tableau de bord Notes &amp; Résultats</h2>
                <p class="text-sm text-gray-500">Vision globale des performances, alertes pédagogiques et actions clés.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('pedagogy.evaluations.index', ['create' => 1]) }}" class="primary-button">Nouvelle évaluation</a>
                <a href="{{ route('pedagogy.report-cards.index') }}" class="secondary-button">Calculer moyennes</a>
                <a href="{{ route('pedagogy.student-report-cards.index') }}" class="ghost-button">Générer bulletins</a>
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
                        <label class="text-xs font-semibold text-slate-500">Période</label>
                        <select name="period_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Sélectionner</option>
                            @foreach ($periods as $period)
                                <option value="{{ $period->id }}" @selected($selectedPeriodId === $period->id)>{{ $period->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Matière</label>
                        <select name="subject_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Toutes</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected($selectedSubjectId === $subject->id)>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Recherche rapide</label>
                        <input
                            type="text"
                            name="q"
                            value="{{ $search }}"
                            placeholder="Nom, matricule..."
                            class="rounded-lg border border-slate-200 px-3 py-2 text-sm"
                        >
                    </div>
                    <button type="submit" class="primary-button">Filtrer</button>
                </form>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase text-slate-400">Élèves suivis</p>
                    <p class="text-2xl font-semibold text-slate-800">{{ $dashboardStats['student_count'] }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase text-slate-400">Évaluations validées</p>
                    <p class="text-2xl font-semibold text-slate-800">{{ $dashboardStats['evaluation_count'] }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase text-slate-400">Moyenne générale</p>
                    <p class="text-2xl font-semibold text-slate-800">{{ $dashboardStats['average'] ?? '—' }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-800">Moyenne générale par classe</h3>
                        <p class="text-sm text-slate-500">Synthèse automatique basée sur les évaluations validées.</p>
                    </div>
                    <div class="px-5 py-4">
                        <div class="space-y-3">
                            @foreach ($classAverages as $class)
                                <div class="flex items-center justify-between gap-4 rounded-lg border border-slate-100 px-4 py-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">{{ $class['name'] }}</p>
                                        <p class="text-xs text-slate-400">{{ $class['evaluation_count'] }} évaluation(s) validée(s)</p>
                                    </div>
                                    <div class="text-base font-semibold text-slate-800">{{ $class['average'] ?? '—' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-800">Évolution des moyennes par trimestre</h3>
                        <p class="text-sm text-slate-500">Suivi des tendances pour la classe sélectionnée.</p>
                    </div>
                    <div class="px-5 py-4">
                        @if ($averageTrend->isEmpty())
                            <div class="text-sm text-slate-500">Sélectionnez une classe pour afficher l’évolution.</div>
                        @else
                            <div class="space-y-3">
                                @foreach ($averageTrend as $trend)
                                    @php
                                        $progress = $trend['average'] ? min(100, ($trend['average'] / 20) * 100) : 0;
                                    @endphp
                                    <div>
                                        <div class="flex items-center justify-between text-xs text-slate-500">
                                            <span>{{ $trend['label'] }}</span>
                                            <span class="font-semibold text-slate-700">{{ $trend['average'] ?? '—' }}</span>
                                        </div>
                                        <div class="mt-2 h-2 w-full rounded-full bg-slate-100">
                                            <div class="h-2 rounded-full bg-blue-500" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-800">Top élèves</h3>
                        <p class="text-sm text-slate-500">Classement rapide basé sur la moyenne générale.</p>
                    </div>
                    <div class="px-5 py-4">
                        @if ($topStudents->isEmpty())
                            <div class="text-sm text-slate-500">Sélectionnez une classe et une période pour afficher le top.</div>
                        @else
                            <div class="grid gap-3 md:grid-cols-2">
                                @foreach ($topStudents as $index => $student)
                                    <div class="flex items-center justify-between rounded-lg border border-slate-100 px-4 py-3">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-800">
                                                {{ $student['student']?->nom }} {{ $student['student']?->prenoms }}
                                            </p>
                                            <p class="text-xs text-slate-400">Rang #{{ $index + 1 }}</p>
                                        </div>
                                        <div class="text-base font-semibold text-slate-800">{{ $student['average'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-800">Alertes pédagogiques</h3>
                        <p class="text-sm text-slate-500">Points d’attention basés sur les données validées.</p>
                    </div>
                    <div class="px-5 py-4">
                        @if (empty($alerts))
                            <div class="rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                                Aucune alerte critique détectée.
                            </div>
                        @else
                            <ul class="space-y-2 text-sm text-amber-700">
                                @foreach ($alerts as $alert)
                                    <li class="rounded-lg bg-amber-50 px-4 py-3">{{ $alert }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h3 class="text-base font-semibold text-slate-800">Moyennes par matière</h3>
                    <p class="text-sm text-slate-500">Vue rapide pour comparer les matières et détecter les écarts.</p>
                </div>
                <div class="px-5 py-4">
                    @if ($subjectAverages->isEmpty())
                        <div class="text-sm text-slate-500">Sélectionnez une classe et une période pour afficher les résultats.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead>
                                    <tr class="border-b border-slate-200 text-xs uppercase text-slate-400">
                                        <th class="px-4 py-3">Matière</th>
                                        <th class="px-4 py-3">Moyenne</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subjectAverages as $row)
                                        <tr class="border-b border-slate-100">
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ $row['subject'] }}</td>
                                            <td class="px-4 py-3 text-sm font-semibold text-slate-800">{{ $row['average'] ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
