<div
    class="staff-form-modal"
    id="teacher-form-modal"
    aria-hidden="true"
    role="dialog"
    aria-labelledby="teacher-form-title"
    data-open-on-load="{{ $isOpen ? 'true' : 'false' }}"
>
    <div class="staff-form-modal__overlay" data-teacher-form-modal-close></div>
    <div class="staff-form-modal__content">
        <header class="staff-form-modal__header">
            <div>
                <p class="eyebrow" data-form-eyebrow>Nouvel enseignant</p>
                <h2 id="teacher-form-title" data-form-title>Ajouter un enseignant</h2>
            </div>
            <button class="icon-button" type="button" data-teacher-form-modal-close aria-label="Fermer">
                <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                    <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
        </header>

        <form class="staff-form" method="POST" action="{{ route('teachers.store') }}" enctype="multipart/form-data">
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
                <button class="tab-button is-active" type="button" data-form-tab="identity" role="tab" aria-selected="true">Identité</button>
                <button class="tab-button" type="button" data-form-tab="contact" role="tab" aria-selected="false">Contacts</button>
                <button class="tab-button" type="button" data-form-tab="pedagogy" role="tab" aria-selected="false">Pédagogie</button>
                <button class="tab-button" type="button" data-form-tab="hr" role="tab" aria-selected="false">RH</button>
                <button class="tab-button" type="button" data-form-tab="documents" role="tab" aria-selected="false">Documents</button>
            </div>

            <div class="staff-form__panel is-active" data-form-panel="identity" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="code_enseignant">Code enseignant (auto-généré)</label>
                        <input type="text" id="code_enseignant" name="code_enseignant" value="" readonly>
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
                            @foreach (\App\Models\Enseignant::SEXES as $sexe)
                                <option value="{{ $sexe }}" @selected(old('sexe') === $sexe)>{{ $sexe }}</option>
                            @endforeach
                        </select>
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
                </div>
            </div>

            <div class="staff-form__panel" data-form-panel="pedagogy" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="specialite">Spécialité *</label>
                        <input type="text" id="specialite" name="specialite" value="{{ old('specialite') }}" required>
                    </div>
                </div>
            </div>

            <div class="staff-form__panel" data-form-panel="hr" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="type_enseignant">Type enseignant *</label>
                        <select id="type_enseignant" name="type_enseignant" required>
                            <option value="">Sélectionner</option>
                            @foreach (\App\Models\Enseignant::TYPES as $type)
                                <option value="{{ $type }}" @selected(old('type_enseignant') === $type)>{{ $type }}</option>
                            @endforeach
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
                        <label for="statut">Statut *</label>
                        <select id="statut" name="statut" required>
                            <option value="">Sélectionner</option>
                            @foreach (\App\Models\Enseignant::STATUTS as $statut)
                                <option value="{{ $statut }}" @selected(old('statut') === $statut)>{{ $statut }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="staff-form__panel" data-form-panel="documents" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="documents">Documents (PDF, image, Word)</label>
                        <input type="file" id="documents" name="documents[]" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" multiple data-documents-input>
                        <div class="file-list" data-documents-list></div>
                        <p class="helper-text">Vous pouvez sélectionner plusieurs fichiers.</p>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button class="secondary-button" type="button" data-teacher-form-modal-close>Annuler</button>
                <button class="primary-button" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
