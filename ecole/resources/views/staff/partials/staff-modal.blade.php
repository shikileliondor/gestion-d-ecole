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
            <button class="tab-button" type="button" data-tab="teacher" role="tab" aria-selected="false">Profil enseignant</button>
        </div>

        <div class="staff-modal__panel is-active" data-panel="info" role="tabpanel">
            <div class="info-grid">
                <div>
                    <p class="label">{{ $identifierLabel ?? 'ID employé' }}</p>
                    <p class="value" data-field="staff_number">—</p>
                </div>
                <div>
                    <p class="label">Nom complet</p>
                    <p class="value" data-field="full_name">—</p>
                </div>
                <div>
                    <p class="label">Fonction</p>
                    <p class="value" data-field="position">—</p>
                </div>
                <div>
                    <p class="label">Contact</p>
                    <p class="value" data-field="contact">—</p>
                </div>
                <div>
                    <p class="label">Contrat</p>
                    <p class="value" data-field="contract">—</p>
                </div>
                <div>
                    <p class="label">Date d'embauche</p>
                    <p class="value" data-field="hire_date">—</p>
                </div>
                <div>
                    <p class="label">Statut</p>
                    <p class="value" data-field="status">—</p>
                </div>
            </div>
        </div>

        <div class="staff-modal__panel" data-panel="assignments" role="tabpanel">
            <div class="list-stack" data-field="assignments"></div>
        </div>

        <div class="staff-modal__panel" data-panel="teacher" role="tabpanel">
            <div class="info-grid">
                <div>
                    <p class="label">Code enseignant</p>
                    <p class="value" data-field="teacher_code">—</p>
                </div>
                <div>
                    <p class="label">Grade / Rang</p>
                    <p class="value" data-field="teacher_grade">—</p>
                </div>
                <div>
                    <p class="label">Spécialité</p>
                    <p class="value" data-field="teacher_speciality">—</p>
                </div>
                <div>
                    <p class="label">Qualification</p>
                    <p class="value" data-field="teacher_qualification">—</p>
                </div>
                <div>
                    <p class="label">Charge horaire</p>
                    <p class="value" data-field="teacher_load">—</p>
                </div>
                <div>
                    <p class="label">Responsabilité pédagogique</p>
                    <p class="value" data-field="teacher_responsibility">—</p>
                </div>
                <div>
                    <p class="label">Début enseignement</p>
                    <p class="value" data-field="teacher_start">—</p>
                </div>
                <div>
                    <p class="label">Années d'expérience</p>
                    <p class="value" data-field="teacher_experience">—</p>
                </div>
                <div>
                    <p class="label">Évaluation</p>
                    <p class="value" data-field="teacher_evaluation">—</p>
                </div>
                <div>
                    <p class="label">Intérêts de recherche</p>
                    <p class="value" data-field="teacher_research">—</p>
                </div>
                <div>
                    <p class="label">Développement professionnel</p>
                    <p class="value" data-field="teacher_development">—</p>
                </div>
                <div>
                    <p class="label">Notes</p>
                    <p class="value" data-field="teacher_notes">—</p>
                </div>
            </div>

            <div class="section-header">
                <p class="label">Documents pédagogiques</p>
            </div>
            <div class="list-stack" data-field="teacher_documents"></div>
        </div>
    </div>
</div>
