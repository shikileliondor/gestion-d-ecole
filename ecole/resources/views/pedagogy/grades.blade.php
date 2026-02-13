<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/classes/index.css') }}">

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Saisie des notes</h2>
                <p class="text-sm text-gray-500">Saisissez les notes par évaluation avec contrôle du barème et calculs automatiques.</p>
            </div>
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
                        <label class="text-xs font-semibold text-slate-500">Classe</label>
                        <select name="class_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Sélectionner</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected($selectedClassId === $class->id)>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Période</label>
                        <select name="period_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Toutes</option>
                            @foreach ($periods as $period)
                                <option value="{{ $period->id }}" @selected($selectedPeriodId === $period->id)>{{ $period->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Évaluation</label>
                        <select name="evaluation_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Sélectionner</option>
                            @foreach ($evaluations as $evaluation)
                                <option value="{{ $evaluation->id }}" @selected($selectedEvaluation?->id === $evaluation->id)>
                                    {{ $evaluation->titre ?: $evaluation->type }} - {{ $evaluation->date_evaluation?->format('d/m') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="primary-button">Afficher</button>
                </form>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h3 class="text-base font-semibold text-slate-800">Tableau de saisie</h3>
                    @if ($selectedEvaluation)
                        <p class="text-sm text-slate-500">Barème : {{ $selectedEvaluation->note_sur }} • Statut : {{ $selectedEvaluation->statut }} • Saisie en masse activée.</p>
                    @endif
                </div>
                <div class="px-5 py-4">
                    <div class="alert success mb-4" data-feedback-success hidden></div>
                    <div class="alert error mb-4" data-feedback-error hidden></div>

                    @if (! $selectedEvaluation)
                        <div class="text-sm text-slate-500">Sélectionnez une évaluation pour afficher les élèves.</div>
                    @elseif ($selectedEvaluation->statut === 'CLOTUREE')
                        <div class="rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-700">
                            Cette évaluation est clôturée. La saisie est bloquée.
                        </div>
                    @endif

                    @if ($selectedEvaluation)
                        <form method="POST" action="{{ route('pedagogy.grades.store', $selectedEvaluation) }}" data-async-form>
                            @csrf
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead>
                                    <tr class="border-b border-slate-200 text-xs uppercase text-slate-400">
                                        <th class="px-4 py-3">Élève</th>
                                        <th class="px-4 py-3">Note</th>
                                        <th class="px-4 py-3">Statut</th>
                                        <th class="px-4 py-3">Moyenne matière</th>
                                        <th class="px-4 py-3">Moyenne générale</th>
                                        <th class="px-4 py-3">Date saisie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $inscription)
                                        @php
                                            $note = $notes->get($inscription->id);
                                            $student = $inscription->eleve;
                                            $insight = $studentInsights->get($inscription->id);
                                        @endphp
                                        <tr class="border-b border-slate-100">
                                            <td class="px-4 py-3 text-sm font-semibold text-slate-800">
                                                {{ $student?->nom }} {{ $student?->prenoms }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input
                                                        type="number"
                                                        name="notes[{{ $inscription->id }}][valeur]"
                                                        value="{{ $note?->valeur }}"
                                                        min="0"
                                                        step="0.5"
                                                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm"
                                                        @disabled($selectedEvaluation->statut === 'CLOTUREE')
                                                    >
                                                </td>
                                                <td class="px-4 py-3">
                                                    <select name="notes[{{ $inscription->id }}][statut]" class="rounded-lg border border-slate-200 px-3 py-2 text-sm" @disabled($selectedEvaluation->statut === 'CLOTUREE')>
                                                        <option value="">--</option>
                                                        <option value="ABS" @selected($note?->statut === 'ABS')>ABS</option>
                                                        <option value="EXC" @selected($note?->statut === 'EXC')>EXC</option>
                                                        <option value="DISP" @selected($note?->statut === 'DISP')>DISP</option>
                                                    </select>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-slate-700">
                                                    {{ $insight['subject_average'] ?? '—' }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-slate-700">
                                                    {{ $insight['general_average'] ?? '—' }}
                                                </td>
                                                <td class="px-4 py-3 text-xs text-slate-400">
                                                    {{ $note?->date_saisie?->format('d/m/Y') ?? '—' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 flex justify-end">
                                <button type="submit" class="primary-button" @disabled($selectedEvaluation->statut === 'CLOTUREE')>
                                    Enregistrer les notes
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/pedagogy/async-actions.js') }}" defer></script>
</x-app-layout>
