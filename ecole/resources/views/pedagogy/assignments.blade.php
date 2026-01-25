<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/classes/index.css') }}">

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Affectations enseignants</h2>
                <p class="text-sm text-gray-500">Affectez les enseignants par classe ou consultez leur charge.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{ tab: '{{ $selectedTeacherId ? 'teacher' : 'class' }}' }" data-async-page>
        <div class="mx-auto max-w-7xl space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap gap-3">
                    <button
                        type="button"
                        class="rounded-full px-4 py-2 text-xs font-semibold"
                        :class="tab === 'class' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600'"
                        @click="tab = 'class'"
                    >
                        Vue par classe
                    </button>
                    <button
                        type="button"
                        class="rounded-full px-4 py-2 text-xs font-semibold"
                        :class="tab === 'teacher' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600'"
                        @click="tab = 'teacher'"
                    >
                        Vue par enseignant
                    </button>
                </div>
            </div>

            <div x-show="tab === 'class'" x-transition class="space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <form method="GET" class="flex flex-wrap items-end gap-4">
                        <div class="flex flex-col">
                            <label class="text-xs font-semibold text-slate-500">Année scolaire</label>
                            <select name="academic_year_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                                @foreach ($academicYears as $year)
                                    <option value="{{ $year->id }}" @selected($selectedAcademicYearId === $year->id)>
                                        {{ $year->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-xs font-semibold text-slate-500">Classe</label>
                            <select name="class_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                                <option value="">Sélectionner</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" @selected($selectedClassId === $class->id)>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="primary-button">Afficher</button>
                    </form>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-800">Affectations par matière</h3>
                        <p class="text-sm text-slate-500">Les matières sans enseignant apparaissent en alerte.</p>
                    </div>
                    <div class="px-5 py-4">
                        <div class="alert success mb-4" data-feedback-success hidden></div>
                        <div class="alert error mb-4" data-feedback-error hidden></div>
                        <div data-assignments-list>
                            {!! $assignmentsHtml !!}
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="tab === 'teacher'" x-transition class="space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <form method="GET" class="flex flex-wrap items-end gap-4">
                        <input type="hidden" name="academic_year_id" value="{{ $selectedAcademicYearId }}">
                        <div class="flex flex-col">
                            <label class="text-xs font-semibold text-slate-500">Enseignant</label>
                            <select name="teacher_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                                <option value="">Sélectionner</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" @selected($selectedTeacherId === $teacher->id)>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="primary-button">Afficher</button>
                    </form>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-800">Affectations de l'enseignant</h3>
                    </div>
                    <div class="px-5 py-4">
                        {!! $teacherAssignmentsHtml !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/pedagogy/async-actions.js') }}" defer></script>
</x-app-layout>
