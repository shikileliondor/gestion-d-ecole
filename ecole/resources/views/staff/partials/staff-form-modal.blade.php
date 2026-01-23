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

        <form class="staff-form" method="POST" action="{{ route('staff.store') }}" enctype="multipart/form-data">
            @csrf

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
                <button class="tab-button is-active" type="button" data-form-tab="identity" role="tab" aria-selected="true">
                    Identité
                </button>
                <button class="tab-button" type="button" data-form-tab="rh" role="tab" aria-selected="false">
                    RH
                </button>
                <button class="tab-button" type="button" data-form-tab="urgence" role="tab" aria-selected="false">
                    Urgence
                </button>
                <button class="tab-button" type="button" data-form-tab="paie" role="tab" aria-selected="false">
                    Paie
                </button>
                <button class="tab-button" type="button" data-form-tab="documents" role="tab" aria-selected="false">
                    Documents
                </button>
            </div>

            <div class="staff-form__panel is-active" data-form-panel="identity" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="code_personnel">Code personnel *</label>
                        <input type="text" id="code_personnel" name="code_personnel" value="{{ old('code_personnel') }}" required>
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
                        <label for="sexe">Sexe</label>
                        <select id="sexe" name="sexe">
                            <option value="">Sélectionner</option>
                            <option value="M" @selected(old('sexe') === 'M')>M</option>
                            <option value="F" @selected(old('sexe') === 'F')>F</option>
                            <option value="AUTRE" @selected(old('sexe') === 'AUTRE')>Autre</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label for="date_naissance">Date de naissance</label>
                        <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}">
                    </div>
                    <div class="form-field">
                        <label for="photo_url">Photo (URL)</label>
                        <input type="text" id="photo_url" name="photo_url" value="{{ old('photo_url') }}">
                    </div>
                    <div class="form-field">
                        <label for="telephone_1">Téléphone principal *</label>
                        <input type="text" id="telephone_1" name="telephone_1" value="{{ old('telephone_1') }}" required>
                    </div>
                    <div class="form-field">
                        <label for="telephone_2">Téléphone secondaire</label>
                        <input type="text" id="telephone_2" name="telephone_2" value="{{ old('telephone_2') }}">
                    </div>
                    <div class="form-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}">
                    </div>
                    <div class="form-field">
                        <label for="adresse">Adresse</label>
                        <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}">
                    </div>
                    <div class="form-field">
                        <label for="commune">Commune</label>
                        <input type="text" id="commune" name="commune" value="{{ old('commune') }}">
                    </div>
                    <div class="form-field">
                        <label for="categorie_personnel">Catégorie *</label>
                        <select id="categorie_personnel" name="categorie_personnel" required>
                            <option value="">Sélectionner</option>
                            <option value="ADMINISTRATION" @selected(old('categorie_personnel') === 'ADMINISTRATION')>Administration</option>
                            <option value="SURVEILLANCE" @selected(old('categorie_personnel') === 'SURVEILLANCE')>Surveillance</option>
                            <option value="INTENDANCE" @selected(old('categorie_personnel') === 'INTENDANCE')>Intendance</option>
                            <option value="COMPTABILITE" @selected(old('categorie_personnel') === 'COMPTABILITE')>Comptabilité</option>
                            <option value="TECHNIQUE" @selected(old('categorie_personnel') === 'TECHNIQUE')>Technique</option>
                            <option value="SERVICE" @selected(old('categorie_personnel') === 'SERVICE')>Service</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label for="poste">Poste *</label>
                        <input type="text" id="poste" name="poste" value="{{ old('poste') }}" required>
                    </div>
                    <div class="form-field">
                        <label for="statut">Statut *</label>
                        <select id="statut" name="statut" required>
                            <option value="">Sélectionner</option>
                            <option value="ACTIF" @selected(old('statut') === 'ACTIF')>Actif</option>
                            <option value="SUSPENDU" @selected(old('statut') === 'SUSPENDU')>Suspendu</option>
                            <option value="PARTI" @selected(old('statut') === 'PARTI')>Parti</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="staff-form__panel" data-form-panel="rh" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="type_contrat">Type de contrat *</label>
                        <select id="type_contrat" name="type_contrat" required>
                            <option value="">Sélectionner</option>
                            <option value="CDI" @selected(old('type_contrat') === 'CDI')>CDI</option>
                            <option value="CDD" @selected(old('type_contrat') === 'CDD')>CDD</option>
                            <option value="VACATAIRE" @selected(old('type_contrat') === 'VACATAIRE')>Vacataire</option>
                            <option value="STAGE" @selected(old('type_contrat') === 'STAGE')>Stage</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label for="date_debut_service">Date début service *</label>
                        <input type="date" id="date_debut_service" name="date_debut_service" value="{{ old('date_debut_service') }}" required>
                    </div>
                    <div class="form-field">
                        <label for="date_fin_service">Date fin service</label>
                        <input type="date" id="date_fin_service" name="date_fin_service" value="{{ old('date_fin_service') }}">
                    </div>
                    <div class="form-field">
                        <label for="num_cni">Numéro CNI</label>
                        <input type="text" id="num_cni" name="num_cni" value="{{ old('num_cni') }}">
                    </div>
                    <div class="form-field">
                        <label for="date_expiration_cni">Expiration CNI</label>
                        <input type="date" id="date_expiration_cni" name="date_expiration_cni" value="{{ old('date_expiration_cni') }}">
                    </div>
                </div>
            </div>

            <div class="staff-form__panel" data-form-panel="urgence" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="contact_urgence_nom">Nom du contact</label>
                        <input type="text" id="contact_urgence_nom" name="contact_urgence_nom" value="{{ old('contact_urgence_nom') }}">
                    </div>
                    <div class="form-field">
                        <label for="contact_urgence_lien">Lien</label>
                        <select id="contact_urgence_lien" name="contact_urgence_lien">
                            <option value="">Sélectionner</option>
                            <option value="PERE" @selected(old('contact_urgence_lien') === 'PERE')>Père</option>
                            <option value="MERE" @selected(old('contact_urgence_lien') === 'MERE')>Mère</option>
                            <option value="CONJOINT" @selected(old('contact_urgence_lien') === 'CONJOINT')>Conjoint</option>
                            <option value="FRERE_SOEUR" @selected(old('contact_urgence_lien') === 'FRERE_SOEUR')>Frère/Soeur</option>
                            <option value="TUTEUR" @selected(old('contact_urgence_lien') === 'TUTEUR')>Tuteur</option>
                            <option value="AUTRE" @selected(old('contact_urgence_lien') === 'AUTRE')>Autre</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label for="contact_urgence_tel">Téléphone</label>
                        <input type="text" id="contact_urgence_tel" name="contact_urgence_tel" value="{{ old('contact_urgence_tel') }}">
                    </div>
                </div>
            </div>

            <div class="staff-form__panel" data-form-panel="paie" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="mode_paiement">Mode de paiement</label>
                        <select id="mode_paiement" name="mode_paiement">
                            <option value="">Sélectionner</option>
                            <option value="MOBILE_MONEY" @selected(old('mode_paiement') === 'MOBILE_MONEY')>Mobile Money</option>
                            <option value="VIREMENT" @selected(old('mode_paiement') === 'VIREMENT')>Virement</option>
                            <option value="CASH" @selected(old('mode_paiement') === 'CASH')>Cash</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label for="numero_paiement">Numéro de paiement</label>
                        <input type="text" id="numero_paiement" name="numero_paiement" value="{{ old('numero_paiement') }}">
                    </div>
                    <div class="form-field">
                        <label for="salaire_base">Salaire de base</label>
                        <input type="number" id="salaire_base" name="salaire_base" min="0" step="0.01" value="{{ old('salaire_base') }}">
                    </div>
                </div>
            </div>

            <div class="staff-form__panel" data-form-panel="documents" role="tabpanel">
                <div class="documents-stack" data-documents-stack>
                    <div class="document-row" data-document-row>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Type de document</label>
                                <select name="documents[0][type_document]">
                                    <option value="">Sélectionner</option>
                                    <option value="CNI">CNI</option>
                                    <option value="CONTRAT">Contrat</option>
                                    <option value="DIPLOME">Diplôme</option>
                                    <option value="CV">CV</option>
                                    <option value="ATTESTATION">Attestation</option>
                                    <option value="AUTRE">Autre</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label>Libellé</label>
                                <input type="text" name="documents[0][libelle]" value="">
                            </div>
                            <div class="form-field">
                                <label>Description</label>
                                <input type="text" name="documents[0][description]" value="">
                            </div>
                            <div class="form-field">
                                <label>Fichier (PDF, image, docx)</label>
                                <input type="file" name="documents[0][fichier]" accept="application/pdf,image/*,.doc,.docx">
                            </div>
                        </div>
                        <div class="document-actions">
                            <button class="secondary-button" type="button" data-document-remove>Retirer</button>
                        </div>
                    </div>
                </div>
                <button class="primary-button" type="button" data-document-add>+ Ajouter un document</button>
                <p class="helper-text">Formats autorisés : PDF, images, DOC/DOCX. Taille max 5MB.</p>
            </div>

            <div class="form-actions">
                <button class="secondary-button" type="button" data-form-modal-close>Annuler</button>
                <button class="primary-button" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
