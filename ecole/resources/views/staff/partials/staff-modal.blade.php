<div
    class="staff-modal"
    id="staff-modal"
    aria-hidden="true"
    role="dialog"
    aria-labelledby="staff-modal-title"
    data-modal-title="{{ $profileTitle ?? 'Fiche personnel' }}"
>
    <div class="staff-modal__overlay" data-staff-modal-close></div>
    <div class="staff-modal__content">
        <header class="staff-modal__header">
            <div>
                <p class="eyebrow">{{ $profileTitle ?? 'Fiche personnel' }}</p>
                <h2 id="staff-modal-title">{{ $profileTitle ?? 'Fiche personnel' }}</h2>
            </div>
            <button class="icon-button" type="button" data-staff-modal-close aria-label="Fermer">
                <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                    <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
        </header>

        <div class="staff-modal__tabs" role="tablist">
            <button class="tab-button is-active" type="button" data-tab="info" role="tab" aria-selected="true">Informations</button>
            <button class="tab-button" type="button" data-tab="assignments" role="tab" aria-selected="false">Affectations</button>
        </div>

        <div class="staff-modal__panel is-active" data-panel="info" role="tabpanel">
            <div class="info-grid">
                <div>
                    <p class="label">{{ $identifierLabel ?? 'ID employé' }}</p>
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
                    <p class="label">Email</p>
                    <p class="value" data-field="email">—</p>
                </div>
                <div>
                    <p class="label">Statut</p>
                    <p class="value" data-field="statut">—</p>
                </div>
            </div>
        </div>

        <div class="staff-modal__panel" data-panel="assignments" role="tabpanel">
            <div class="list-stack" data-field="assignments"></div>
        </div>
    </div>
</div>
