<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/classes/index.css') }}">

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Bulletins / Classements</h2>
                <p class="text-sm text-gray-500">Générez les bulletins par classe et période avec coefficients officiels.</p>
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
                            <option value="">Sélectionner</option>
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
                    <button type="submit" class="primary-button">Générer</button>
                </form>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-slate-800">Classement de la classe</h3>
                            <p class="text-sm text-slate-500">Classement basé sur les moyennes pondérées.</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if ($selectedClassId && $selectedPeriodId)
                                <a
                                    href="{{ route('pedagogy.report-cards.pdf', ['class' => $selectedClassId, 'period' => $selectedPeriodId, 'academic_year_id' => $selectedAcademicYearId]) }}"
                                    class="secondary-button"
                                >
                                    Export PDF
                                </a>
                            @endif
                            @if ($selectedClassId && $selectedPeriodId)
                                <form method="POST" action="{{ route('pedagogy.report-cards.lock') }}" data-async-form data-lock-toggle>
                                    @csrf
                                    <input type="hidden" name="academic_year_id" value="{{ $selectedAcademicYearId }}">
                                    <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                                    <input type="hidden" name="period_id" value="{{ $selectedPeriodId }}">
                                    <button type="submit" class="ghost-button" data-lock-button>
                                        {{ $lockStatus?->verrouille ? 'Déverrouiller la période' : 'Verrouiller la période' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="px-5 py-4">
                    <div class="alert success mb-4" data-feedback-success hidden></div>
                    <div class="alert error mb-4" data-feedback-error hidden></div>

                    @if ($reportData->isEmpty())
                        <div class="text-sm text-slate-500">Sélectionnez une classe et une période pour afficher les bulletins.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead>
                                    <tr class="border-b border-slate-200 text-xs uppercase text-slate-400">
                                        <th class="px-4 py-3">Rang</th>
                                        <th class="px-4 py-3">Élève</th>
                                        <th class="px-4 py-3">Moyenne générale</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reportData as $index => $entry)
                                        <tr class="border-b border-slate-100">
                                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $index + 1 }}</td>
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
        </div>
    </div>

    <script src="{{ asset('js/pedagogy/async-actions.js') }}" defer></script>
</x-app-layout>
