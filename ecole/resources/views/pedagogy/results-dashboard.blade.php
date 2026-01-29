<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/classes/index.css') }}">

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Tableau de bord des résultats</h2>
            <p class="text-sm text-gray-500">Vue synthétique des moyennes et classements pour les enseignants.</p>
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
                    <p class="text-xs uppercase text-slate-400">Élèves affichés</p>
                    <p class="text-2xl font-semibold text-slate-800">{{ $dashboardStats['student_count'] }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase text-slate-400">Évaluations comptées</p>
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
                        <h3 class="text-base font-semibold text-slate-800">Synthèse des élèves</h3>
                        <p class="text-sm text-slate-500">Moyennes générales calculées automatiquement par élève.</p>
                    </div>
                    <div class="px-5 py-4">
                        @if ($scorecards->isEmpty())
                            <div class="text-sm text-slate-500">Sélectionnez une période pour afficher les résultats.</div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead>
                                        <tr class="border-b border-slate-200 text-xs uppercase text-slate-400">
                                            <th class="px-4 py-3">Élève</th>
                                            <th class="px-4 py-3">Moyenne générale</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($scorecards as $entry)
                                            <tr class="border-b border-slate-100">
                                                <td class="px-4 py-3 text-sm text-slate-700">{{ $entry['student']?->nom }} {{ $entry['student']?->prenoms }}</td>
                                                <td class="px-4 py-3 text-sm font-semibold text-slate-800">{{ $entry['average'] ?? '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-800">Moyennes par matière</h3>
                        <p class="text-sm text-slate-500">Vue rapide pour comparer les matières.</p>
                    </div>
                    <div class="px-5 py-4">
                        @if ($subjectAverages->isEmpty())
                            <div class="text-sm text-slate-500">Sélectionnez une classe et une période.</div>
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
    </div>
</x-app-layout>
