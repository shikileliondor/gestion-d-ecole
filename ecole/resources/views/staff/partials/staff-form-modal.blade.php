<div
    class="staff-form-modal"
    id="staff-form-modal"
    aria-hidden="true"
    role="dialog"
    aria-labelledby="staff-form-title"
    data-open-on-load="{{ $isOpen ? 'true' : 'false' }}"
>
    <div class="staff-form-modal__overlay" data-form-modal-close></div>
    <div class="staff-form-modal__content">
        <header class="staff-form-modal__header">
            <div>
                <p class="eyebrow" data-form-eyebrow>{{ $formEyebrow ?? 'Nouveau personnel' }}</p>
                <h2 id="staff-form-title" data-form-title>{{ $formTitle ?? 'Ajouter un membre du personnel' }}</h2>
            </div>
            <button class="icon-button" type="button" data-form-modal-close aria-label="Fermer">
                <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                    <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
        </header>

        <form
            class="staff-form"
            method="POST"
            action="{{ route('staff.store') }}"
            data-default-action="{{ route('staff.store') }}"
            enctype="multipart/form-data"
        >
            @csrf
            <input type="hidden" name="_method" value="PUT" data-form-method disabled>

            @if ($errors->any())
                <div class="form-alert">
                    <h3>Veuillez corriger les erreurs</h3>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="staff-form__tabs" role="tablist">
                <button class="tab-button is-active" type="button" data-form-tab="identity" role="tab" aria-selected="true">Identité</button>
                <button class="tab-button" type="button" data-form-tab="contact" role="tab" aria-selected="false">Contacts</button>
                <button class="tab-button" type="button" data-form-tab="hr" role="tab" aria-selected="false">RH</button>
                <button class="tab-button" type="button" data-form-tab="documents" role="tab" aria-selected="false">Documents</button>
            </div>

            <div class="staff-form__panel is-active" data-form-panel="identity" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="code_personnel">Code personnel (auto-généré)</label>
                        <input type="text" id="code_personnel" name="code_personnel" value="{{ old('code_personnel') }}" readonly>
                    </div>
                    <div class="form-field">
                        <label for="nom">Nom *</label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required>
                    </div>
                    <div class="form-field">
                        <label for="prenoms">Prénoms *</label>
                        <input type="text" id="prenoms" name="prenoms" value="{{ old('prenoms') }}" required>
                    </div>
                    <div class="form-field">
                        <label for="photo">Photo</label>
                        <input type="file" id="photo" name="photo" accept="image/*" data-photo-input>
                        <div class="photo-preview is-hidden" data-photo-preview-wrapper>
                            <img src="" alt="Aperçu de la photo" data-photo-preview>
                        </div>
                    </div>
                </div>
            </div>

            <div class="staff-form__panel" data-form-panel="contact" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>
            </div>

            <div class="staff-form__panel" data-form-panel="hr" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="poste">Poste occupé</label>
                        <input type="text" id="poste" name="poste" value="{{ old('poste') }}" placeholder="Ex : Responsable administratif">
                    </div>
                    <div class="form-field">
                        <label for="categorie_personnel">Catégorie du personnel</label>
                        <input type="text" id="categorie_personnel" name="categorie_personnel" value="{{ old('categorie_personnel') }}" placeholder="Ex : Administratif, Technique">
                    </div>
                    <div class="form-field">
                        <label for="type_contrat">Type de contrat</label>
                        <select id="type_contrat" name="type_contrat">
                            <option value="">Sélectionner</option>
                            <option value="CDI" @selected(old('type_contrat') === 'CDI')>CDI</option>
                            <option value="CDD" @selected(old('type_contrat') === 'CDD')>CDD</option>
                            <option value="Stage" @selected(old('type_contrat') === 'Stage')>Stage</option>
                            <option value="Consultant" @selected(old('type_contrat') === 'Consultant')>Consultant</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label for="date_entree">Date d'entrée</label>
                        <input type="date" id="date_entree" name="date_entree" value="{{ old('date_entree') }}">
                    </div>
                    <div class="form-field">
                        <label for="date_fin_contrat">Date fin de contrat</label>
                        <input type="date" id="date_fin_contrat" name="date_fin_contrat" value="{{ old('date_fin_contrat') }}">
                    </div>
                    <div class="form-field">
                        <label for="statut_rh">Statut RH</label>
                        <select id="statut_rh" name="statut_rh">
                            <option value="">Sélectionner</option>
                            <option value="ACTIF" @selected(old('statut_rh') === 'ACTIF')>Actif</option>
                            <option value="SUSPENDU" @selected(old('statut_rh') === 'SUSPENDU')>Suspendu</option>
                            <option value="FIN_DE_CONTRAT" @selected(old('statut_rh') === 'FIN_DE_CONTRAT')>Fin de contrat</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label for="numero_cnps">Numéro CNPS</label>
                        <input type="text" id="numero_cnps" name="numero_cnps" value="{{ old('numero_cnps') }}" placeholder="Ex : 123456789">
                    </div>
                </div>
            </div>

            <div class="staff-form__panel" data-form-panel="documents" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="documents">Documents RH (PDF, image, Word)</label>
                        <input type="file" id="documents" name="documents[]" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" multiple data-documents-input>
                        <p class="helper-text">Ajoutez un libellé pour chaque document afin de mieux les retrouver.</p>
                        <div class="file-list" data-documents-list></div>
                        <p class="helper-text">Vous pouvez sélectionner plusieurs fichiers.</p>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button class="secondary-button" type="button" data-form-modal-close>Annuler</button>
                <button class="primary-button" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
