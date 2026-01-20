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
                <p>Définissez le niveau et l'effectif manuellement.</p>
            </div>
            <button type="button" class="icon-button" data-modal-close aria-label="Fermer">
                ✕
            </button>
        </div>

        <form class="modal__form" method="POST" action="{{ route('classes.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-field">
                    <label for="academic_year_id">Année scolaire</label>
                    <select id="academic_year_id" name="academic_year_id" required>
                        <option value="">Sélectionner</option>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}" @selected(old('academic_year_id') == $year->id)>
                                {{ $year->name ?? $year->start_date?->format('Y') }}
                            </option>
                        @endforeach
                    </select>
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
                    <input id="level" name="level" type="text" value="{{ old('level') }}" placeholder="Collège / Lycée">
                </div>
                <div class="form-field">
                    <label for="section">Section</label>
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
