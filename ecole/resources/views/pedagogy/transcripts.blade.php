<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/classes/index.css') }}">

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Relevés de notes</h2>
            <p class="text-sm text-gray-500">Consultez les relevés individuels par période ou année complète.</p>
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
                        <label class="text-xs font-semibold text-slate-500">Élève</label>
                        <select name="student_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Sélectionner</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" @selected($selectedStudentId === $student->id)>
                                    {{ $student->nom }} {{ $student->prenoms }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Période</label>
                        <select name="period_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Année complète</option>
                            @foreach ($periods as $period)
                                <option value="{{ $period->id }}" @selected($selectedPeriodId === $period->id)>{{ $period->libelle }}</option>
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
                            <h3 class="text-base font-semibold text-slate-800">Relevé individuel</h3>
                        </div>
                        @if ($selectedStudentId)
                            <a
                                href="{{ route('pedagogy.transcripts.pdf', ['student' => $selectedStudentId, 'academic_year_id' => $selectedAcademicYearId, 'period_id' => $selectedPeriodId]) }}"
                                class="secondary-button"
                            >
                                Export PDF
                            </a>
                        @endif
                    </div>
                </div>
                <div class="px-5 py-4">
                    @if ($reportData->isEmpty())
                        <div class="text-sm text-slate-500">Sélectionnez un élève pour afficher son relevé.</div>
                    @else
                        @foreach ($reportData as $entry)
                            <div class="mb-6 rounded-lg border border-slate-200 p-4">
                                <h4 class="text-sm font-semibold text-slate-700">Classe : {{ $entry['class']->nom }}</h4>
                                <p class="text-xs text-slate-400">Moyenne générale : {{ $entry['average'] ?? '—' }}</p>
                                <div class="mt-4 overflow-x-auto">
                                    <table class="min-w-full text-left text-sm">
                                        <thead>
                                            <tr class="border-b border-slate-200 text-xs uppercase text-slate-400">
                                                <th class="px-4 py-2">Matière</th>
                                                <th class="px-4 py-2">Coefficient</th>
                                                <th class="px-4 py-2">Moyenne</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($entry['subjects'] as $subject)
                                                <tr class="border-b border-slate-100">
                                                    <td class="px-4 py-2 text-sm text-slate-700">{{ $subject['subject'] }}</td>
                                                    <td class="px-4 py-2 text-sm text-slate-500">{{ $subject['coefficient'] ?? '—' }}</td>
                                                    <td class="px-4 py-2 text-sm font-semibold text-slate-800">{{ $subject['average'] ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
