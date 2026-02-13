<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/classes/index.css') }}">

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Classements des élèves</h2>
            <p class="text-sm text-gray-500">Analysez les rangs par matière ou moyenne générale.</p>
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
                            <option value="">Sélectionner</option>
                            @foreach ($periods as $period)
                                <option value="{{ $period->id }}" @selected($selectedPeriodId === $period->id)>{{ $period->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Matière (optionnel)</label>
                        <select name="subject_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Moyenne générale</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected($selectedSubjectId === $subject->id)>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="primary-button">Afficher</button>
                </form>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-slate-800">
                                {{ $rankingMode === 'subject' ? 'Classement par matière' : 'Classement général' }}
                            </h3>
                            <p class="text-sm text-slate-500">Classement basé sur les moyennes calculées automatiquement.</p>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-4">
                    @if ($rankingData->isEmpty())
                        <div class="text-sm text-slate-500">Sélectionnez une classe et une période pour générer le classement.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead>
                                    <tr class="border-b border-slate-200 text-xs uppercase text-slate-400">
                                        <th class="px-4 py-3">Rang</th>
                                        <th class="px-4 py-3">Élève</th>
                                        <th class="px-4 py-3">Moyenne</th>
                                        @if ($rankingMode === 'subject')
                                            <th class="px-4 py-3">Matière</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rankingData as $index => $entry)
                                        <tr class="border-b border-slate-100">
                                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ $entry['student']?->nom }} {{ $entry['student']?->prenoms }}</td>
                                            <td class="px-4 py-3 text-sm font-semibold text-slate-800">{{ $entry['average'] ?? '—' }}</td>
                                            @if ($rankingMode === 'subject')
                                                <td class="px-4 py-3 text-sm text-slate-500">{{ $entry['subject'] ?? '—' }}</td>
                                            @endif
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
