@php
    $isOpen = $isOpen ?? false;
@endphp

<div
    class="student-modal student-form-modal {{ $isOpen ? 'is-open' : '' }}"
    id="student-form-modal"
    aria-hidden="{{ $isOpen ? 'false' : 'true' }}"
    data-open-on-load="{{ $isOpen ? 'true' : 'false' }}"
>
    <div class="student-modal__overlay" data-form-modal-close></div>
    <div class="student-modal__content" role="dialog" aria-modal="true" aria-labelledby="student-form-modal-title">
        <div class="student-modal__header">
            <h2 id="student-form-modal-title">Ajouter un élève</h2>
            <button class="student-modal__close" type="button" data-form-modal-close aria-label="Fermer">
                ×
            </button>
        </div>

        <div class="student-modal__tabs" role="tablist">
            <button class="student-modal__tab is-active" type="button" data-form-tab="identity" role="tab" aria-selected="true">
                Identité
            </button>
            <button class="student-modal__tab" type="button" data-form-tab="contact" role="tab" aria-selected="false">
                Coordonnées
            </button>
            <button class="student-modal__tab" type="button" data-form-tab="school" role="tab" aria-selected="false">
                Scolarité
            </button>
            <button class="student-modal__tab" type="button" data-form-tab="health" role="tab" aria-selected="false">
                Santé & urgence
            </button>
            <button class="student-modal__tab" type="button" data-form-tab="parent" role="tab" aria-selected="false">
                Parent/Tuteur
            </button>
        </div>

        <div class="student-modal__body">
            @if ($errors->any())
                <div class="form-alert">
                    <h2>Veuillez corriger les erreurs ci-dessous :</h2>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="student-form" method="POST" action="{{ route('students.store') }}">
                @csrf

                <div class="student-modal__panel is-active" data-form-panel="identity" role="tabpanel">
                    <section class="form-section">
                        <h2>Identité</h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Matricule</label>
                                <p class="form-static" data-admission-preview>Généré automatiquement</p>
                                <p class="form-helper">Année d'inscription + lettres.</p>
                            </div>
                            <div class="form-field">
                                <label for="status">Statut *</label>
                                <select id="status" name="status" required>
                                    <option value="active" @selected(old('status', 'active') === 'active')>Actif</option>
                                    <option value="suspended" @selected(old('status') === 'suspended')>Suspendu</option>
                                    <option value="transferred" @selected(old('status') === 'transferred')>Transféré</option>
                                    <option value="graduated" @selected(old('status') === 'graduated')>Diplômé</option>
                                    <option value="inactive" @selected(old('status') === 'inactive')>Inactif</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="last_name">Nom *</label>
                                <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required>
                            </div>
                            <div class="form-field">
                                <label for="first_name">Prénom *</label>
                                <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required>
                            </div>
                            <div class="form-field">
                                <label for="middle_name">Deuxième prénom</label>
                                <input id="middle_name" name="middle_name" type="text" value="{{ old('middle_name') }}">
                            </div>
                            <div class="form-field">
                                <label for="gender">Genre</label>
                                <select id="gender" name="gender">
                                    <option value="">Sélectionner</option>
                                    <option value="male" @selected(old('gender') === 'male')>Masculin</option>
                                    <option value="female" @selected(old('gender') === 'female')>Féminin</option>
                                    <option value="other" @selected(old('gender') === 'other')>Autre</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="date_of_birth">Date de naissance</label>
                                <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth') }}">
                            </div>
                            <div class="form-field">
                                <label for="place_of_birth">Lieu de naissance</label>
                                <input id="place_of_birth" name="place_of_birth" type="text" value="{{ old('place_of_birth') }}">
                            </div>
                            <div class="form-field">
                                <label for="nationality">Nationalité</label>
                                <input id="nationality" name="nationality" type="text" value="{{ old('nationality') }}">
                            </div>
                            <div class="form-field">
                                <label for="blood_type">Groupe sanguin</label>
                                <select id="blood_type" name="blood_type">
                                    <option value="">Sélectionner</option>
                                    <option value="A+" @selected(old('blood_type') === 'A+')>A+</option>
                                    <option value="A-" @selected(old('blood_type') === 'A-')>A-</option>
                                    <option value="B+" @selected(old('blood_type') === 'B+')>B+</option>
                                    <option value="B-" @selected(old('blood_type') === 'B-')>B-</option>
                                    <option value="AB+" @selected(old('blood_type') === 'AB+')>AB+</option>
                                    <option value="AB-" @selected(old('blood_type') === 'AB-')>AB-</option>
                                    <option value="O+" @selected(old('blood_type') === 'O+')>O+</option>
                                    <option value="O-" @selected(old('blood_type') === 'O-')>O-</option>
                                </select>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="student-modal__panel" data-form-panel="contact" role="tabpanel">
                    <section class="form-section">
                        <h2>Coordonnées</h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="address">Adresse</label>
                                <input id="address" name="address" type="text" value="{{ old('address') }}">
                            </div>
                            <div class="form-field">
                                <label for="city">Ville</label>
                                <input id="city" name="city" type="text" value="{{ old('city') }}">
                            </div>
                            <div class="form-field">
                                <label for="phone">Téléphone</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone') }}">
                            </div>
                            <div class="form-field">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}">
                            </div>
                        </div>
                    </section>
                </div>

                <div class="student-modal__panel" data-form-panel="school" role="tabpanel">
                    <section class="form-section">
                        <h2>Scolarité</h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="class_id">Classe *</label>
                                <select id="class_id" name="class_id" required>
                                    <option value="">Sélectionner une classe</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="academic_year_id">Année scolaire *</label>
                                <select id="academic_year_id" name="academic_year_id" required>
                                    <option value="">Sélectionner une année</option>
                                    @foreach ($academicYears as $academicYear)
                                        <option value="{{ $academicYear->id }}" @selected(old('academic_year_id') == $academicYear->id)>
                                            {{ $academicYear->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="enrollment_date">Date d'inscription</label>
                                <input id="enrollment_date" name="enrollment_date" type="date" value="{{ old('enrollment_date') }}">
                            </div>
                            <div class="form-field">
                                <label for="previous_school">Établissement précédent</label>
                                <input id="previous_school" name="previous_school" type="text" value="{{ old('previous_school') }}">
                            </div>
                            <div class="form-field">
                                <label for="class_status">Statut de la classe</label>
                                <select id="class_status" name="class_status">
                                    <option value="active" @selected(old('class_status', 'active') === 'active')>Actif</option>
                                    <option value="transferred" @selected(old('class_status') === 'transferred')>Transféré</option>
                                    <option value="completed" @selected(old('class_status') === 'completed')>Terminé</option>
                                </select>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="student-modal__panel" data-form-panel="health" role="tabpanel">
                    <section class="form-section">
                        <h2>Informations médicales & urgence</h2>
                        <div class="form-grid">
                            <div class="form-field checkbox-field">
                                <label>
                                    <input type="checkbox" name="needs_special_care" value="1" @checked(old('needs_special_care'))>
                                    Besoin d'une prise en charge particulière
                                </label>
                            </div>
                            <div class="form-field">
                                <label for="medical_notes">Notes médicales</label>
                                <textarea id="medical_notes" name="medical_notes" rows="3">{{ old('medical_notes') }}</textarea>
                            </div>
                            <div class="form-field">
                                <label for="emergency_contact_name">Contact d'urgence</label>
                                <input id="emergency_contact_name" name="emergency_contact_name" type="text" value="{{ old('emergency_contact_name') }}">
                            </div>
                            <div class="form-field">
                                <label for="emergency_contact_phone">Téléphone d'urgence</label>
                                <input id="emergency_contact_phone" name="emergency_contact_phone" type="text" value="{{ old('emergency_contact_phone') }}">
                            </div>
                        </div>
                    </section>
                </div>

                <div class="student-modal__panel" data-form-panel="parent" role="tabpanel">
                    <section class="form-section">
                        <h2>Parent / Tuteur principal</h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="parent_first_name">Prénom</label>
                                <input id="parent_first_name" name="parent_first_name" type="text" value="{{ old('parent_first_name') }}">
                            </div>
                            <div class="form-field">
                                <label for="parent_last_name">Nom</label>
                                <input id="parent_last_name" name="parent_last_name" type="text" value="{{ old('parent_last_name') }}">
                            </div>
                            <div class="form-field">
                                <label for="parent_gender">Genre</label>
                                <select id="parent_gender" name="parent_gender">
                                    <option value="">Sélectionner</option>
                                    <option value="male" @selected(old('parent_gender') === 'male')>Masculin</option>
                                    <option value="female" @selected(old('parent_gender') === 'female')>Féminin</option>
                                    <option value="other" @selected(old('parent_gender') === 'other')>Autre</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="parent_relationship">Lien de parenté</label>
                                <input id="parent_relationship" name="parent_relationship" type="text" value="{{ old('parent_relationship') }}">
                            </div>
                            <div class="form-field">
                                <label for="parent_phone">Téléphone</label>
                                <input id="parent_phone" name="parent_phone" type="text" value="{{ old('parent_phone') }}">
                            </div>
                            <div class="form-field">
                                <label for="parent_email">Email</label>
                                <input id="parent_email" name="parent_email" type="email" value="{{ old('parent_email') }}">
                            </div>
                            <div class="form-field">
                                <label for="parent_address">Adresse</label>
                                <input id="parent_address" name="parent_address" type="text" value="{{ old('parent_address') }}">
                            </div>
                            <div class="form-field">
                                <label for="parent_occupation">Profession</label>
                                <input id="parent_occupation" name="parent_occupation" type="text" value="{{ old('parent_occupation') }}">
                            </div>
                            <div class="form-field">
                                <label for="parent_employer">Employeur</label>
                                <input id="parent_employer" name="parent_employer" type="text" value="{{ old('parent_employer') }}">
                            </div>
                            <div class="form-field">
                                <label for="parent_national_id">Identifiant national</label>
                                <input id="parent_national_id" name="parent_national_id" type="text" value="{{ old('parent_national_id') }}">
                            </div>
                            <div class="form-field checkbox-field">
                                <label>
                                    <input type="checkbox" name="parent_is_primary" value="1" @checked(old('parent_is_primary', true))>
                                    Parent principal
                                </label>
                            </div>
                            <div class="form-field checkbox-field">
                                <label>
                                    <input type="checkbox" name="parent_has_custody" value="1" @checked(old('parent_has_custody'))>
                                    Garde légale
                                </label>
                            </div>
                            <div class="form-field">
                                <label for="parent_notes">Notes</label>
                                <textarea id="parent_notes" name="parent_notes" rows="3">{{ old('parent_notes') }}</textarea>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="form-actions">
                    <button class="primary-button" type="submit">Enregistrer l'élève</button>
                    <button class="secondary-button" type="button" data-form-modal-close>Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
