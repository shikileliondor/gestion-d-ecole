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
            <h2 id="student-form-modal-title">Inscrire un élève</h2>
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

            <form class="student-form" method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
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
                                <label for="matricule_national">Matricule national</label>
                                <input id="matricule_national" name="matricule_national" type="text" value="{{ old('matricule_national') }}">
                                <p class="form-helper">Optionnel, à renseigner plus tard si nécessaire.</p>
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
                                <label for="photo">Photo</label>
                                <input id="photo" name="photo" type="file" accept="image/*">
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
                                <label for="level_id">Niveau *</label>
                                <select id="level_id" name="level_id" required>
                                    <option value="">Sélectionner un niveau</option>
                                    @foreach ($levels as $level)
                                        <option value="{{ $level->id }}" @selected(old('level_id') == $level->id)>
                                            {{ $level->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="class_id">Classe *</label>
                                <select id="class_id" name="class_id" required>
                                    <option value="">Sélectionner une classe</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" data-level-id="{{ $class->niveau_id }}" @selected(old('class_id') == $class->id)>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-field">
                                <label>Année scolaire (globale)</label>
                                @if (! empty($activeAcademicYear))
                                    <p class="form-static">{{ $activeAcademicYear->name ?? $activeAcademicYear->libelle }}</p>
                                @else
                                    <p class="error-text">Aucune année scolaire active n'est configurée. Définissez-la dans Paramètres.</p>
                                @endif
                            </div>
                            <div class="form-field">
                                <label for="enrollment_date">Date d'inscription</label>
                                <input id="enrollment_date" name="enrollment_date" type="date" value="{{ old('enrollment_date') }}">
                            </div>
                            <div class="form-field">
                                <label for="previous_school">Établissement d'origine</label>
                                <input id="previous_school" name="previous_school" type="text" value="{{ old('previous_school') }}">
                            </div>
                            <div class="form-field">
                                <label for="arrival_date">Date d'arrivée</label>
                                <input id="arrival_date" name="arrival_date" type="date" value="{{ old('arrival_date') }}">
                            </div>
                            <div class="form-field">
                                <label for="previous_class">Classe précédente</label>
                                <input id="previous_class" name="previous_class" type="text" value="{{ old('previous_class') }}">
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

                <div class="student-modal__panel" data-form-panel="parent" role="tabpanel">
                    <section class="form-section">
                        <h2>Parents et correspondant</h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="father_name">Nom et prénoms du père</label>
                                <input id="father_name" name="father_name" type="text" value="{{ old('father_name') }}">
                            </div>
                            <div class="form-field">
                                <label for="father_occupation">Profession du père</label>
                                <input id="father_occupation" name="father_occupation" type="text" value="{{ old('father_occupation') }}">
                            </div>
                            <div class="form-field">
                                <label for="father_address">Domicile du père</label>
                                <input id="father_address" name="father_address" type="text" value="{{ old('father_address') }}">
                            </div>
                            <div class="form-field">
                                <label for="father_phone">Téléphone du père</label>
                                <input id="father_phone" name="father_phone" type="text" value="{{ old('father_phone') }}">
                            </div>
                            <div class="form-field">
                                <label for="mother_name">Nom et prénoms de la mère</label>
                                <input id="mother_name" name="mother_name" type="text" value="{{ old('mother_name') }}">
                            </div>
                            <div class="form-field">
                                <label for="mother_occupation">Profession de la mère</label>
                                <input id="mother_occupation" name="mother_occupation" type="text" value="{{ old('mother_occupation') }}">
                            </div>
                            <div class="form-field">
                                <label for="mother_address">Domicile de la mère</label>
                                <input id="mother_address" name="mother_address" type="text" value="{{ old('mother_address') }}">
                            </div>
                            <div class="form-field">
                                <label for="mother_phone">Téléphone de la mère</label>
                                <input id="mother_phone" name="mother_phone" type="text" value="{{ old('mother_phone') }}">
                            </div>
                            <div class="form-field">
                                <label for="guardian_name">Nom et prénoms du correspondant</label>
                                <input id="guardian_name" name="guardian_name" type="text" value="{{ old('guardian_name') }}">
                            </div>
                            <div class="form-field">
                                <label for="guardian_relationship">Lien avec l'élève</label>
                                <input id="guardian_relationship" name="guardian_relationship" type="text" value="{{ old('guardian_relationship') }}">
                            </div>
                            <div class="form-field">
                                <label for="guardian_occupation">Profession du correspondant</label>
                                <input id="guardian_occupation" name="guardian_occupation" type="text" value="{{ old('guardian_occupation') }}">
                            </div>
                            <div class="form-field">
                                <label for="guardian_address">Domicile du correspondant</label>
                                <input id="guardian_address" name="guardian_address" type="text" value="{{ old('guardian_address') }}">
                            </div>
                            <div class="form-field">
                                <label for="guardian_phone">Téléphone du correspondant</label>
                                <input id="guardian_phone" name="guardian_phone" type="text" value="{{ old('guardian_phone') }}">
                            </div>
                            <div class="form-field">
                                <p class="form-helper">Renseignez au moins le nom et le téléphone si un contact est ajouté.</p>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="form-actions">
                    <button class="primary-button" type="submit">Valider l'inscription</button>
                    <button class="secondary-button" type="button" data-form-modal-close>Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
