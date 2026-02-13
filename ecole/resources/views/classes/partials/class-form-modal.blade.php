@php
    $classFormErrors = $errors->getBag('classForm');
@endphp

<div
    class="modal"
    data-modal="class"
    data-open-on-load="{{ $isOpen ? 'true' : 'false' }}"
    aria-hidden="true"
    role="dialog"
>
    <div class="modal__overlay" data-modal-close></div>
    <div class="modal__content">
        <div class="modal__header">
            <div>
                <h2>Créer une classe</h2>
                <p>Définissez le niveau (6e à Terminale), la série et l'effectif.</p>
            </div>
            <button type="button" class="icon-button" data-modal-close aria-label="Fermer">
                ✕
            </button>
        </div>

        <form
            class="modal__form"
            method="POST"
            action="{{ route('classes.store') }}"
            data-async-form
            data-async-action="class-create"
            data-lycee-levels='@json($lyceeLevelCodes ?? [])'
        >
            @csrf
            <div class="form-grid">
                <div class="form-field">
                    <label>Année scolaire (globale)</label>
                    @if (! empty($activeAcademicYear))
                        <p class="form-static">{{ $activeAcademicYear->name ?? $activeAcademicYear->libelle }}</p>
                    @else
                        <p class="error-text">Aucune année scolaire active n'est configurée. Définissez-la dans Paramètres.</p>
                    @endif
                    @if ($classFormErrors->has('settings'))
                        <span class="error-text">{{ $classFormErrors->first('settings') }}</span>
                    @endif
                </div>
                <div class="form-field">
                    <label for="name">Nom de la classe</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                    @if ($classFormErrors->has('name'))
                        <span class="error-text">{{ $classFormErrors->first('name') }}</span>
                    @endif
                </div>
                <div class="form-field">
                    <label for="level">Niveau</label>
                    <select id="level" name="level" required data-level-select>
                        <option value="">Sélectionner</option>
                        @foreach ($levels as $level)
                            <option value="{{ $level->code }}" @selected(old('level') === $level->code)>
                                {{ $level->code }}
                            </option>
                        @endforeach
                    </select>
                    @if ($classFormErrors->has('level'))
                        <span class="error-text">{{ $classFormErrors->first('level') }}</span>
                    @endif
                </div>
                <div class="form-field" data-series-field>
                    <label for="series">Série / Filière (lycée)</label>
                    <select id="series" name="series">
                        <option value="">Sélectionner</option>
                        @foreach ($series as $serie)
                            <option value="{{ $serie->code }}" @selected(old('series') === $serie->code)>
                                {{ $serie->code }}
                            </option>
                        @endforeach
                    </select>
                    <span class="helper-text">Disponible uniquement pour les niveaux lycée.</span>
                </div>
                <div class="form-field">
                    <label for="section">Groupe</label>
                    <input id="section" name="section" type="text" value="{{ old('section') }}" placeholder="A, B, C">
                </div>
                <div class="form-field">
                    <label for="room">Salle</label>
                    <input id="room" name="room" type="text" value="{{ old('room') }}" placeholder="Salle 12">
                </div>
                <div class="form-field">
                    <label for="capacity">Capacité</label>
                    <input id="capacity" name="capacity" type="number" min="1" value="{{ old('capacity') }}">
                </div>
                <div class="form-field">
                    <label for="manual_headcount">Effectif déclaré</label>
                    <input id="manual_headcount" name="manual_headcount" type="number" min="0" value="{{ old('manual_headcount') }}">
                </div>
                <div class="form-field">
                    <label for="status">Statut</label>
                    <select id="status" name="status">
                        <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                        <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                    </select>
                </div>
            </div>


            <div class="modal__actions">
                <button type="button" class="ghost-button" data-modal-close>Annuler</button>
                <button type="submit" class="primary-button">Créer la classe</button>
            </div>
        </form>
    </div>
</div>
