<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/classes/index.css') }}">

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Programme de la classe</h2>
                <p class="text-sm text-gray-500">Programme de la classe avec coefficients officiels (Paramètres).</p>
            </div>
            <button class="primary-button" type="button" data-modal-open="programme">+ Ajouter une matière</button>
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
                    <h3 class="text-base font-semibold text-slate-800">Programme de la classe</h3>
                    <p class="text-sm text-slate-500">Les coefficients sont officiels et restent gérés dans Paramètres.</p>
                </div>
                <div class="px-5 py-4">
                    <div class="alert success mb-4" data-feedback-success hidden></div>
                    <div class="alert error mb-4" data-feedback-error hidden></div>
                    <div data-programme-list>
                        {!! $programmeListHtml !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" data-modal="programme" aria-hidden="true" role="dialog">
        <div class="modal__overlay" data-modal-close></div>
        <div class="modal__content">
            <div class="modal__header">
                <div>
                    <h2>Ajouter une matière</h2>
                    <p>Programme de la classe sélectionnée.</p>
                </div>
                <button class="icon-button" type="button" data-modal-close aria-label="Fermer">✕</button>
            </div>
            <form
                class="modal__form"
                method="POST"
                action="{{ route('pedagogy.programme.store') }}"
                data-async-form
                data-async-target="[data-programme-list]"
            >
                @csrf
                <div class="form-grid">
                    <div class="form-field form-field--full">
                        <label>Année scolaire</label>
                        <select name="academic_year_id" required>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}" @selected($selectedAcademicYearId === $year->id)>
                                    {{ $year->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field form-field--full">
                        <label>Classe</label>
                        <select name="class_id" required>
                            <option value="">Sélectionner</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected($selectedClassId === $class->id)>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field form-field--full">
                        <label>Matière</label>
                        <select name="subject_id" required>
                            <option value="">Sélectionner</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal__actions">
                    <button type="button" class="ghost-button" data-modal-close>Annuler</button>
                    <button type="submit" class="primary-button">Ajouter au programme</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/classes/modals.js') }}" defer></script>
    <script src="{{ asset('js/pedagogy/async-actions.js') }}" defer></script>
</x-app-layout>
