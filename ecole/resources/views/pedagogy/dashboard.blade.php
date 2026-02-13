<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Tableau de bord — Notes & bulletins</h2>
            <p class="text-sm text-gray-500">
                Vue d'ensemble du parcours Évaluations → Saisie → Résultats → Documents pour la période en cours.
            </p>
        </div>
    </x-slot>

    <div class="py-6" data-async-page>
        <div class="mx-auto max-w-7xl space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <form method="GET" data-filter-form class="flex flex-wrap items-end gap-4">
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Année scolaire</label>
                        <select name="academic_year_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}" @selected($selectedAcademicYearId === $year->id)>{{ $year->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Période active</label>
                        <select name="period_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            @foreach ($periods as $period)
                                <option value="{{ $period->id }}" @selected($selectedPeriodId === $period->id)>{{ $period->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($activePeriodType)
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-slate-500">Type de période</span>
                            <span class="mt-2 inline-flex w-fit rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                {{ ucfirst(strtolower($activePeriodType)) }}
                            </span>
                        </div>
                    @endif
                    <button type="submit" class="primary-button">Mettre à jour</button>
                </form>
            </div>

            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase text-slate-400">Évaluations créées</p>
                    <p class="text-2xl font-semibold text-slate-800">{{ $dashboardStats['evaluations_count'] }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase text-slate-400">En attente de saisie</p>
                    <p class="text-2xl font-semibold text-slate-800">{{ $dashboardStats['pending_count'] }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase text-slate-400">Classes concernées</p>
                    <p class="text-2xl font-semibold text-slate-800">{{ $dashboardStats['classes_count'] }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase text-slate-400">Alertes</p>
                    <p class="text-2xl font-semibold text-slate-800">{{ $dashboardStats['alerts_count'] }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-800">Évaluations déjà créées</h3>
                        <p class="text-sm text-slate-500">Les dernières évaluations disponibles pour la période sélectionnée.</p>
                    </div>
                    <div class="px-5 py-4">
                        @if ($latestEvaluations->isEmpty())
                            <p class="text-sm text-slate-500">Aucune évaluation pour cette période.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead>
                                        <tr class="border-b border-slate-200 text-xs uppercase text-slate-400">
                                            <th class="px-4 py-3">Évaluation</th>
                                            <th class="px-4 py-3">Classe</th>
                                            <th class="px-4 py-3">Période</th>
                                            <th class="px-4 py-3">Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($latestEvaluations as $entry)
                                            <tr class="border-b border-slate-100">
                                                <td class="px-4 py-3">
                                                    <p class="font-semibold text-slate-800">{{ $entry['evaluation']->titre ?: 'Évaluation' }}</p>
                                                    <p class="text-xs text-slate-500">
                                                        {{ $entry['subject']?->name ?? '—' }}
                                                        • {{ $entry['evaluation']->date_evaluation?->format('d/m/Y') ?? '—' }}
                                                    </p>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-slate-700">{{ $entry['class']?->name ?? '—' }}</td>
                                                <td class="px-4 py-3 text-sm text-slate-700">{{ $entry['period']?->libelle ?? '—' }}</td>
                                                <td class="px-4 py-3 text-xs font-semibold text-slate-500">
                                                    {{ ucfirst(strtolower($entry['evaluation']->statut)) }}
                                                </td>
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
                        <h3 class="text-base font-semibold text-slate-800">Saisies en attente</h3>
                        <p class="text-sm text-slate-500">Évaluations avec notes manquantes à compléter.</p>
                    </div>
                    <div class="px-5 py-4">
                        @if ($pendingEvaluations->isEmpty())
                            <p class="text-sm text-slate-500">Aucune saisie en attente pour cette période.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($pendingEvaluations as $entry)
                                    <div class="rounded-lg border border-slate-200 p-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-semibold text-slate-800">{{ $entry['evaluation']->titre ?: 'Évaluation' }}</p>
                                                <p class="text-xs text-slate-500">
                                                    {{ $entry['class']?->name ?? '—' }} • {{ $entry['subject']?->name ?? '—' }}
                                                </p>
                                            </div>
                                            <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">
                                                {{ $entry['missing_count'] }} manquante(s)
                                            </span>
                                        </div>
                                        <p class="mt-2 text-xs text-slate-500">
                                            {{ $entry['students_count'] }} élèves attendus · Période {{ $entry['period']?->libelle ?? '—' }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-800">Classes concernées</h3>
                        <p class="text-sm text-slate-500">Classes ayant des évaluations sur la période sélectionnée.</p>
                    </div>
                    <div class="px-5 py-4">
                        @if ($classesConcerned->isEmpty())
                            <p class="text-sm text-slate-500">Aucune classe concernée pour cette période.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($classesConcerned as $entry)
                                    <div class="flex items-center justify-between rounded-lg border border-slate-200 p-3">
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $entry['class']?->name ?? '—' }}</p>
                                            <p class="text-xs text-slate-500">
                                                {{ $entry['evaluation_count'] }} évaluation(s) · {{ $entry['pending_count'] }} en attente
                                            </p>
                                        </div>
                                        <a href="{{ route('pedagogy.evaluations.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                                            Voir
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-800">Alertes & rappels</h3>
                        <p class="text-sm text-slate-500">Points de vigilance à traiter rapidement.</p>
                    </div>
                    <div class="px-5 py-4">
                        @if ($alerts->isEmpty())
                            <p class="text-sm text-slate-500">Aucune alerte pour l'instant.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($alerts as $alert)
                                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-3">
                                        <p class="text-sm font-semibold text-amber-800">{{ $alert['title'] }}</p>
                                        <p class="text-xs text-amber-700">{{ $alert['message'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
