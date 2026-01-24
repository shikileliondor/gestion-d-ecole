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
    </div>
</div>

<div class="form-section">
    <h2>Profil pédagogique</h2>
    <div class="form-grid">
        <div class="form-field">
            <label for="specialite">Spécialité *</label>
            <input type="text" id="specialite" name="specialite" value="{{ old('specialite', $enseignant->specialite ?? '') }}" required>
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
    </div>
</div>
