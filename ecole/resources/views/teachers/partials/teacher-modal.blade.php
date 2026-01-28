<div
    class="staff-modal"
    id="teacher-modal"
    aria-hidden="true"
    role="dialog"
    aria-labelledby="teacher-modal-title"
    data-modal-title="Fiche enseignant"
>
    <div class="staff-modal__overlay" data-teacher-modal-close></div>
    <div class="staff-modal__content">
        <header class="staff-modal__header">
            <div>
                <p class="eyebrow">Fiche enseignant</p>
                <h2 id="teacher-modal-title">Fiche enseignant</h2>
            </div>
            <div class="action-buttons">
                <form method="POST" data-archive-form>
                    @csrf
                    @method('PUT')
                    <button class="secondary-button" type="submit">Archiver</button>
                </form>
                <button class="icon-button" type="button" data-teacher-modal-close aria-label="Fermer">
                    <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                        <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
        </header>

        <div class="staff-modal__tabs" role="tablist">
            <button class="tab-button is-active" type="button" data-tab="info" role="tab" aria-selected="true">Informations</button>
            <button class="tab-button" type="button" data-tab="documents" role="tab" aria-selected="false">Documents</button>
        </div>

        <div class="staff-modal__panel is-active" data-panel="info" role="tabpanel">
            <div class="profile-header">
                <div class="profile-avatar" data-photo-wrapper>
                    <img src="" alt="Photo de l'enseignant" data-photo-preview>
                    <span class="profile-avatar__fallback" data-photo-fallback>—</span>
                </div>
                <div>
                    <p class="label">Email</p>
                    <p class="value" data-field="email">—</p>
                </div>
            </div>
            <div class="info-grid">
                <div>
                    <p class="label">Code enseignant</p>
                    <p class="value" data-field="staff_number">—</p>
                </div>
                <div>
                    <p class="label">Nom</p>
                    <p class="value" data-field="last_name">—</p>
                </div>
                <div>
                    <p class="label">Prénoms</p>
                    <p class="value" data-field="first_name">—</p>
                </div>
                <div>
                    <p class="label">Téléphone</p>
                    <p class="value" data-field="telephone_1">—</p>
                </div>
                <div>
                    <p class="label">Spécialité</p>
                    <p class="value" data-field="specialite">—</p>
                </div>
                <div>
                    <p class="label">Type</p>
                    <p class="value" data-field="type_enseignant">—</p>
                </div>
                <div>
                    <p class="label">Statut</p>
                    <p class="value" data-field="statut">—</p>
                </div>
            </div>
        </div>

        <div class="staff-modal__panel" data-panel="documents" role="tabpanel">
            <div class="documents-list" data-field="documents"></div>
        </div>
    </div>
</div>
