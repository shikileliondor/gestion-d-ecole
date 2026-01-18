<div class="student-modal" id="student-modal" aria-hidden="true">
    <div class="student-modal__overlay" data-student-modal-close></div>
    <div class="student-modal__content" role="dialog" aria-modal="true" aria-labelledby="student-modal-title">
        <div class="student-modal__header">
            <h2 id="student-modal-title">Fiche élève</h2>
            <button class="student-modal__close" type="button" data-student-modal-close aria-label="Fermer">
                ×
            </button>
        </div>

        <div class="student-modal__tabs" role="tablist">
            <button class="student-modal__tab is-active" type="button" data-tab="info" role="tab" aria-selected="true">
                Informations
            </button>
            <button class="student-modal__tab" type="button" data-tab="grades" role="tab" aria-selected="false">
                Notes
            </button>
            <button class="student-modal__tab" type="button" data-tab="payments" role="tab" aria-selected="false">
                Paiements
            </button>
            <button class="student-modal__tab" type="button" data-tab="documents" role="tab" aria-selected="false">
                Documents
            </button>
        </div>

        <div class="student-modal__body">
            <div class="student-modal__panel is-active" data-panel="info" role="tabpanel">
                <div class="student-info-grid">
                    <div>
                        <p class="label">Matricule</p>
                        <p class="value" data-field="admission_number">—</p>
                    </div>
                    <div>
                        <p class="label">Date de naissance</p>
                        <p class="value" data-field="date_of_birth">—</p>
                    </div>
                    <div>
                        <p class="label">Classe</p>
                        <p class="value" data-field="class_name">—</p>
                    </div>
                    <div>
                        <p class="label">Téléphone</p>
                        <p class="value" data-field="phone">—</p>
                    </div>
                    <div>
                        <p class="label">Parent/Tuteur</p>
                        <p class="value" data-field="parent_name">—</p>
                    </div>
                    <div>
                        <p class="label">Téléphone parent</p>
                        <p class="value" data-field="parent_phone">—</p>
                    </div>
                    <div>
                        <p class="label">Adresse</p>
                        <p class="value" data-field="address">—</p>
                    </div>
                    <div>
                        <p class="label">Email</p>
                        <p class="value" data-field="email">—</p>
                    </div>
                </div>
            </div>

            <div class="student-modal__panel" data-panel="grades" role="tabpanel">
                <div class="student-list" data-field="grades">
                    <p class="empty">Aucune note disponible.</p>
                </div>
            </div>

            <div class="student-modal__panel" data-panel="payments" role="tabpanel">
                <div class="student-list" data-field="payments">
                    <p class="empty">Aucun paiement enregistré.</p>
                </div>
            </div>

            <div class="student-modal__panel" data-panel="documents" role="tabpanel">
                <div class="student-list" data-field="documents">
                    <p class="empty">Aucun document disponible.</p>
                </div>
            </div>
        </div>
    </div>
</div>
