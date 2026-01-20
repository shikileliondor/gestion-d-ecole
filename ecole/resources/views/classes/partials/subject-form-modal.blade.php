@php
    $subjectFormErrors = $errors->getBag('subjectForm');
@endphp

<div
    class="modal"
    data-modal="subject"
    data-open-on-load="{{ $isOpen ? 'true' : 'false' }}"
    aria-hidden="true"
    role="dialog"
>
    <div class="modal__overlay" data-modal-close></div>
    <div class="modal__content">
        <div class="modal__header">
            <div>
                <h2>Créer une matière</h2>
                <p>Ajoutez une matière commune ou spécifique à un niveau ou une série.</p>
            </div>
            <button type="button" class="icon-button" data-modal-close aria-label="Fermer">
                ✕
            </button>
        </div>

        <form class="modal__form" method="POST" action="{{ route('classes.subjects.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-field">
                    <label for="subject_code">Code</label>
                    <input id="subject_code" name="code" type="text" value="{{ old('code') }}" required>
                    @if ($subjectFormErrors->has('code'))
                        <span class="error-text">{{ $subjectFormErrors->first('code') }}</span>
                    @endif
                </div>
                <div class="form-field">
                    <label for="subject_name">Nom</label>
                    <input id="subject_name" name="name" type="text" value="{{ old('name') }}" required>
                </div>
                <div class="form-field">
                    <label for="subject_level">Niveau (optionnel)</label>
                    <input id="subject_level" name="level" type="text" value="{{ old('level') }}" placeholder="Ex: 6e, Seconde, Terminale">
                </div>
                <div class="form-field">
                    <label for="subject_series">Série ciblée (optionnel)</label>
                    <input id="subject_series" name="series" type="text" value="{{ old('series') }}" list="series-options" placeholder="A, C, D, Littéraire">
                </div>
                <div class="form-field">
                    <label for="subject_hours">Crédits / heures</label>
                    <input id="subject_hours" name="credit_hours" type="number" min="1" value="{{ old('credit_hours') }}">
                </div>
                <div class="form-field form-field--full">
                    <label for="subject_description">Description</label>
                    <textarea id="subject_description" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="form-field">
                    <label for="subject_status">Statut</label>
                    <select id="subject_status" name="status">
                        <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                        <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="modal__actions">
                <button type="button" class="ghost-button" data-modal-close>Annuler</button>
                <button type="submit" class="primary-button">Créer la matière</button>
            </div>
        </form>

    </div>
</div>
