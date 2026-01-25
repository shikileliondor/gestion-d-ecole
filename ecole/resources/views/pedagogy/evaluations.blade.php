<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/classes/index.css') }}">

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Évaluations</h2>
                <p class="text-sm text-gray-500">Créez, publiez et clôturez les évaluations.</p>
            </div>
            <button class="primary-button" type="button" data-modal-open="evaluation-create">+ Nouvelle évaluation</button>
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
                                <option value="{{ $year->id }}" @selected($filters['academic_year_id'] === $year->id)>
                                    {{ $year->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Classe</label>
                        <select name="class_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Toutes</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected($filters['class_id'] === $class->id)>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Matière</label>
                        <select name="subject_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Toutes</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected($filters['subject_id'] === $subject->id)>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500">Période</label>
                        <select name="period_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <option value="">Toutes</option>
                            @foreach ($periods as $period)
                                <option value="{{ $period->id }}" @selected($filters['period_id'] === $period->id)>{{ $period->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="primary-button">Filtrer</button>
                </form>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h3 class="text-base font-semibold text-slate-800">Liste des évaluations</h3>
                    <p class="text-sm text-slate-500">Les évaluations clôturées sont en lecture seule.</p>
                </div>
                <div class="px-5 py-4">
                    <div class="alert success mb-4" data-feedback-success hidden></div>
                    <div class="alert error mb-4" data-feedback-error hidden></div>
                    <div data-evaluations-list>
                        {!! $evaluationsListHtml !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" data-modal="evaluation-create" aria-hidden="true" role="dialog">
        <div class="modal__overlay" data-modal-close></div>
        <div class="modal__content">
            <div class="modal__header">
                <div>
                    <h2>Créer une évaluation</h2>
                    <p>Les matières proposées doivent être dans le programme de la classe.</p>
                </div>
                <button class="icon-button" type="button" data-modal-close aria-label="Fermer">✕</button>
            </div>
            <form class="modal__form" method="POST" action="{{ route('pedagogy.evaluations.store') }}" data-async-form data-async-target="[data-evaluations-list]">
                @csrf
                <div class="form-grid">
                    <div class="form-field">
                        <label>Année scolaire</label>
                        <select name="academic_year_id" required>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}" @selected($filters['academic_year_id'] === $year->id)>{{ $year->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field">
                        <label>Classe</label>
                        <select name="class_id" required>
                            <option value="">Sélectionner</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field">
                        <label>Matière</label>
                        <select name="subject_id" required>
                            <option value="">Sélectionner</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field">
                        <label>Période</label>
                        <select name="period_id" required>
                            @foreach ($periods as $period)
                                <option value="{{ $period->id }}">{{ $period->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field">
                        <label>Type</label>
                        <select name="type" required>
                            <option value="INTERRO">Interrogation</option>
                            <option value="DEVOIR">Devoir</option>
                            <option value="COMPOSITION">Composition</option>
                            <option value="ORAL">Oral</option>
                            <option value="PRATIQUE">Pratique</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label>Date</label>
                        <input type="date" name="date" required>
                    </div>
                    <div class="form-field">
                        <label>Titre</label>
                        <input type="text" name="title" placeholder="Ex : Devoir 1">
                    </div>
                    <div class="form-field">
                        <label>Barème</label>
                        <input type="number" name="scale" min="1" step="0.5" required>
                    </div>
                </div>
                <div class="modal__actions">
                    <button type="button" class="ghost-button" data-modal-close>Annuler</button>
                    <button type="submit" class="primary-button">Créer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" data-modal="evaluation-edit" aria-hidden="true" role="dialog">
        <div class="modal__overlay" data-modal-close></div>
        <div class="modal__content">
            <div class="modal__header">
                <div>
                    <h2>Modifier l'évaluation</h2>
                    <p>Les évaluations clôturées sont verrouillées.</p>
                </div>
                <button class="icon-button" type="button" data-modal-close aria-label="Fermer">✕</button>
            </div>
            <form class="modal__form" method="POST" action="" data-evaluation-edit-form data-async-form data-async-target="[data-evaluations-list]">
                @csrf
                @method('PATCH')
                <div class="form-grid">
                    <div class="form-field">
                        <label>Période</label>
                        <select name="period_id" data-evaluation-edit-period required>
                            @foreach ($periods as $period)
                                <option value="{{ $period->id }}">{{ $period->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field">
                        <label>Type</label>
                        <select name="type" data-evaluation-edit-type required>
                            <option value="INTERRO">Interrogation</option>
                            <option value="DEVOIR">Devoir</option>
                            <option value="COMPOSITION">Composition</option>
                            <option value="ORAL">Oral</option>
                            <option value="PRATIQUE">Pratique</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label>Date</label>
                        <input type="date" name="date" data-evaluation-edit-date required>
                    </div>
                    <div class="form-field">
                        <label>Titre</label>
                        <input type="text" name="title" data-evaluation-edit-title>
                    </div>
                    <div class="form-field">
                        <label>Barème</label>
                        <input type="number" name="scale" min="1" step="0.5" data-evaluation-edit-scale required>
                    </div>
                </div>
                <div class="modal__actions">
                    <button type="button" class="ghost-button" data-modal-close>Annuler</button>
                    <button type="submit" class="primary-button">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/classes/modals.js') }}" defer></script>
    <script src="{{ asset('js/pedagogy/async-actions.js') }}" defer></script>
    <script src="{{ asset('js/pedagogy/evaluations.js') }}" defer></script>
    <div data-evaluation-edit-route="{{ route('pedagogy.evaluations.update', ['evaluation' => '__ID__']) }}"></div>
</x-app-layout>
