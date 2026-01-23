@php
    $enseignant = $enseignant ?? null;
@endphp

<div class="form-section">
    <h2>Identité</h2>
    <div class="form-grid">
        <div class="form-field">
            <label for="code_enseignant">Code enseignant *</label>
            <input type="text" id="code_enseignant" name="code_enseignant" value="{{ old('code_enseignant', $enseignant->code_enseignant ?? '') }}" required>
        </div>
        <div class="form-field">
            <label for="nom">Nom *</label>
            <input type="text" id="nom" name="nom" value="{{ old('nom', $enseignant->nom ?? '') }}" required>
        </div>
        <div class="form-field">
            <label for="prenoms">Prénoms *</label>
            <input type="text" id="prenoms" name="prenoms" value="{{ old('prenoms', $enseignant->prenoms ?? '') }}" required>
        </div>
        <div class="form-field">
            <label for="sexe">Sexe</label>
            <select id="sexe" name="sexe">
                <option value="">Sélectionner</option>
                @foreach ($sexes as $sexe)
                    <option value="{{ $sexe }}" @selected(old('sexe', $enseignant->sexe ?? '') === $sexe)>{{ $sexe }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-field">
            <label for="date_naissance">Date de naissance</label>
            <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance', optional($enseignant?->date_naissance)->format('Y-m-d')) }}">
        </div>
        <div class="form-field">
            <label for="photo_url">Photo (URL)</label>
            <input type="text" id="photo_url" name="photo_url" value="{{ old('photo_url', $enseignant->photo_url ?? '') }}">
        </div>
    </div>
</div>

<div class="form-section">
    <h2>Contacts</h2>
    <div class="form-grid">
        <div class="form-field">
            <label for="telephone_1">Téléphone principal *</label>
            <input type="text" id="telephone_1" name="telephone_1" value="{{ old('telephone_1', $enseignant->telephone_1 ?? '') }}" required>
        </div>
        <div class="form-field">
            <label for="telephone_2">Téléphone secondaire</label>
            <input type="text" id="telephone_2" name="telephone_2" value="{{ old('telephone_2', $enseignant->telephone_2 ?? '') }}">
        </div>
        <div class="form-field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $enseignant->email ?? '') }}">
        </div>
        <div class="form-field">
            <label for="adresse">Adresse / Commune</label>
            <input type="text" id="adresse" name="adresse" value="{{ old('adresse', $enseignant->adresse ?? '') }}">
        </div>
    </div>
</div>

<div class="form-section">
    <h2>Profil pédagogique</h2>
    <div class="form-grid">
        <div class="form-field">
            <label for="specialite">Spécialité *</label>
            <input type="text" id="specialite" name="specialite" value="{{ old('specialite', $enseignant->specialite ?? '') }}" required>
        </div>
        <div class="form-field">
            <label for="niveau_enseignement">Niveau d'enseignement</label>
            <select id="niveau_enseignement" name="niveau_enseignement">
                <option value="">Sélectionner</option>
                @foreach ($niveaux as $niveau)
                    <option value="{{ $niveau }}" @selected(old('niveau_enseignement', $enseignant->niveau_enseignement ?? '') === $niveau)>{{ $niveau }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-field">
            <label for="qualification">Qualification</label>
            <input type="text" id="qualification" name="qualification" value="{{ old('qualification', $enseignant->qualification ?? '') }}">
        </div>
    </div>
</div>

<div class="form-section">
    <h2>RH léger</h2>
    <div class="form-grid">
        <div class="form-field">
            <label for="type_enseignant">Type enseignant *</label>
            <select id="type_enseignant" name="type_enseignant" required>
                <option value="">Sélectionner</option>
                @foreach ($types as $type)
                    <option value="{{ $type }}" @selected(old('type_enseignant', $enseignant->type_enseignant ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-field">
            <label for="date_debut_service">Date début service *</label>
            <input type="date" id="date_debut_service" name="date_debut_service" value="{{ old('date_debut_service', optional($enseignant?->date_debut_service)->format('Y-m-d')) }}" required>
        </div>
        <div class="form-field">
            <label for="date_fin_service">Date fin service</label>
            <input type="date" id="date_fin_service" name="date_fin_service" value="{{ old('date_fin_service', optional($enseignant?->date_fin_service)->format('Y-m-d')) }}">
        </div>
        <div class="form-field">
            <label for="statut">Statut *</label>
            <select id="statut" name="statut" required>
                <option value="">Sélectionner</option>
                @foreach ($statuts as $statut)
                    <option value="{{ $statut }}" @selected(old('statut', $enseignant->statut ?? '') === $statut)>{{ $statut }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-field">
            <label for="num_cni">Numéro CNI</label>
            <input type="text" id="num_cni" name="num_cni" value="{{ old('num_cni', $enseignant->num_cni ?? '') }}">
        </div>
        <div class="form-field">
            <label for="date_expiration_cni">Expiration CNI</label>
            <input type="date" id="date_expiration_cni" name="date_expiration_cni" value="{{ old('date_expiration_cni', optional($enseignant?->date_expiration_cni)->format('Y-m-d')) }}">
        </div>
    </div>
</div>

<div class="form-section">
    <h2>Contact urgence</h2>
    <div class="form-grid">
        <div class="form-field">
            <label for="contact_urgence_nom">Nom du contact</label>
            <input type="text" id="contact_urgence_nom" name="contact_urgence_nom" value="{{ old('contact_urgence_nom', $enseignant->contact_urgence_nom ?? '') }}">
        </div>
        <div class="form-field">
            <label for="contact_urgence_lien">Lien</label>
            <select id="contact_urgence_lien" name="contact_urgence_lien">
                <option value="">Sélectionner</option>
                @foreach ($contactLiens as $lien)
                    <option value="{{ $lien }}" @selected(old('contact_urgence_lien', $enseignant->contact_urgence_lien ?? '') === $lien)>{{ $lien }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-field">
            <label for="contact_urgence_tel">Téléphone</label>
            <input type="text" id="contact_urgence_tel" name="contact_urgence_tel" value="{{ old('contact_urgence_tel', $enseignant->contact_urgence_tel ?? '') }}">
        </div>
    </div>
</div>

<div class="form-section">
    <h2>Paie (optionnel)</h2>
    <div class="form-grid">
        <div class="form-field">
            <label for="mode_paiement">Mode de paiement</label>
            <select id="mode_paiement" name="mode_paiement">
                <option value="">Sélectionner</option>
                @foreach ($modesPaiement as $mode)
                    <option value="{{ $mode }}" @selected(old('mode_paiement', $enseignant->mode_paiement ?? '') === $mode)>{{ $mode }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-field">
            <label for="numero_paiement">Numéro paiement</label>
            <input type="text" id="numero_paiement" name="numero_paiement" value="{{ old('numero_paiement', $enseignant->numero_paiement ?? '') }}">
        </div>
        <div class="form-field">
            <label for="salaire_base">Salaire de base</label>
            <input type="number" step="0.01" id="salaire_base" name="salaire_base" value="{{ old('salaire_base', $enseignant->salaire_base ?? '') }}">
        </div>
    </div>
</div>
