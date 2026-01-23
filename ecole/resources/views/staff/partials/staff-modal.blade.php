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
            <button class="tab-button is-active" type="button" data-tab="info" role="tab" aria-selected="true">Identité</button>
            <button class="tab-button" type="button" data-tab="rh" role="tab" aria-selected="false">RH</button>
            <button class="tab-button" type="button" data-tab="urgence" role="tab" aria-selected="false">Urgence</button>
            <button class="tab-button" type="button" data-tab="paie" role="tab" aria-selected="false">Paie</button>
            <button class="tab-button" type="button" data-tab="documents" role="tab" aria-selected="false">Documents</button>
        </div>

        <div class="staff-modal__panel is-active" data-panel="info" role="tabpanel">
            <div class="info-grid">
                <div>
                    <p class="label">{{ $identifierLabel ?? 'ID employé' }}</p>
                    <p class="value" data-field="code_personnel">—</p>
                </div>
                <div>
                    <p class="label">Nom</p>
                    <p class="value" data-field="nom">—</p>
                </div>
                <div>
                    <p class="label">Prénoms</p>
                    <p class="value" data-field="prenoms">—</p>
                </div>
                <div>
                    <p class="label">Sexe</p>
                    <p class="value" data-field="sexe">—</p>
                </div>
                <div>
                    <p class="label">Date de naissance</p>
                    <p class="value" data-field="date_naissance">—</p>
                </div>
                <div>
                    <p class="label">Catégorie</p>
                    <p class="value" data-field="categorie_personnel">—</p>
                </div>
                <div>
                    <p class="label">Poste</p>
                    <p class="value" data-field="poste">—</p>
                </div>
                <div>
                    <p class="label">Contact</p>
                    <p class="value" data-field="contact">—</p>
                </div>
                <div>
                    <p class="label">Adresse</p>
                    <p class="value" data-field="adresse">—</p>
                </div>
                <div>
                    <p class="label">Commune</p>
                    <p class="value" data-field="commune">—</p>
                </div>
                <div>
                    <p class="label">Statut</p>
                    <p class="value" data-field="statut">—</p>
                </div>
            </div>
        </div>

        <div class="staff-modal__panel" data-panel="rh" role="tabpanel">
            <div class="info-grid">
                <div>
                    <p class="label">Type de contrat</p>
                    <p class="value" data-field="type_contrat">—</p>
                </div>
                <div>
                    <p class="label">Début de service</p>
                    <p class="value" data-field="date_debut_service">—</p>
                </div>
                <div>
                    <p class="label">Fin de service</p>
                    <p class="value" data-field="date_fin_service">—</p>
                </div>
                <div>
                    <p class="label">Numéro CNI</p>
                    <p class="value" data-field="num_cni">—</p>
                </div>
                <div>
                    <p class="label">Expiration CNI</p>
                    <p class="value" data-field="date_expiration_cni">—</p>
                </div>
                <div>
                    <p class="label">Photo</p>
                    <p class="value" data-field="photo_url">—</p>
                </div>
            </div>
        </div>

        <div class="staff-modal__panel" data-panel="urgence" role="tabpanel">
            <div class="info-grid">
                <div>
                    <p class="label">Nom du contact</p>
                    <p class="value" data-field="contact_urgence_nom">—</p>
                </div>
                <div>
                    <p class="label">Lien</p>
                    <p class="value" data-field="contact_urgence_lien">—</p>
                </div>
                <div>
                    <p class="label">Téléphone</p>
                    <p class="value" data-field="contact_urgence_tel">—</p>
                </div>
            </div>
        </div>

        <div class="staff-modal__panel" data-panel="paie" role="tabpanel">
            <div class="info-grid">
                <div>
                    <p class="label">Mode de paiement</p>
                    <p class="value" data-field="mode_paiement">—</p>
                </div>
                <div>
                    <p class="label">Numéro paiement</p>
                    <p class="value" data-field="numero_paiement">—</p>
                </div>
                <div>
                    <p class="label">Salaire de base</p>
                    <p class="value" data-field="salaire_base">—</p>
                </div>
            </div>
        </div>

        <div class="staff-modal__panel" data-panel="documents" role="tabpanel">
            <div class="list-stack" data-field="documents"></div>
        </div>
    </div>
</div>
