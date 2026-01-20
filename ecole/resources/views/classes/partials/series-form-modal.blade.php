@php
    $seriesList = collect($seriesOptions ?? [])->implode(', ');
@endphp

<div
    class="modal"
    data-modal="series"
    data-open-on-load="{{ $isOpen ? 'true' : 'false' }}"
    aria-hidden="true"
    role="dialog"
>
    <div class="modal__overlay" data-modal-close></div>
    <div class="modal__content">
        <div class="modal__header">
            <div>
                <h2>Gérer les séries</h2>
                <p>Définissez vos séries (A, C, D, etc.) pour les classes de Seconde à Terminale.</p>
            </div>
            <button type="button" class="icon-button" data-modal-close aria-label="Fermer">
                ✕
            </button>
        </div>

        <form class="modal__form" method="POST" action="{{ route('classes.series.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-field form-field--full">
                    <label for="series_list">Liste des séries (séparées par des virgules)</label>
                    <textarea id="series_list" name="series_list" rows="3" placeholder="A, C, D, Littéraire, Scientifique">{{ old('series_list', $seriesList) }}</textarea>
                    <p class="helper-text">Vous pourrez ensuite les utiliser dans la création des classes et des matières.</p>
                </div>
            </div>

            <div class="modal__actions">
                <button type="button" class="ghost-button" data-modal-close>Annuler</button>
                <button type="submit" class="primary-button">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
