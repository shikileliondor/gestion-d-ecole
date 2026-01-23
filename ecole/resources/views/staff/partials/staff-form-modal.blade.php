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

            <div class="form-actions">
                <button class="secondary-button" type="button" data-form-modal-close>Annuler</button>
                <button class="primary-button" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
