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
                <button class="tab-button" type="button" data-form-tab="contract" role="tab" aria-selected="false">
                    Contrat
                </button>
                <button class="tab-button" type="button" data-form-tab="assignments" role="tab" aria-selected="false">
                    Affectations
                </button>
                <button class="tab-button" type="button" data-form-tab="teacher" role="tab" aria-selected="false">
                    Profil enseignant
                </button>
            </div>

            <div class="staff-form__panel is-active" data-form-panel="identity" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="full_name">Nom complet *</label>
                        <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                    </div>
                    <div class="form-field">
                        <label for="position">Fonction *</label>
                        <input type="text" id="position" name="position" value="{{ old('position') }}" required>
                    </div>
                    <div class="form-field">
                        <label for="email">Email professionnel</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}">
                    </div>
                    <div class="form-field">
                        <label for="phone">Téléphone</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}">
                    </div>
                </div>
            </div>

            <div class="staff-form__panel" data-form-panel="contract" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="contract_type">Type de contrat *</label>
                        <select id="contract_type" name="contract_type" required>
                            <option value="">Sélectionner</option>
                            <option value="CDI" @selected(old('contract_type') === 'CDI')>CDI</option>
                            <option value="CDD" @selected(old('contract_type') === 'CDD')>CDD</option>
                            <option value="Vacation" @selected(old('contract_type') === 'Vacation')>Vacation</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label for="hire_date">Date d'embauche *</label>
                        <input type="date" id="hire_date" name="hire_date" value="{{ old('hire_date') }}" required>
                    </div>
                    <div class="form-field">
                        <label for="contract_file">Upload du contrat (PDF, 5MB) *</label>
                        <input type="file" id="contract_file" name="contract_file" accept="application/pdf" required>
                    </div>
                </div>
            </div>

            <div class="staff-form__panel" data-form-panel="assignments" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="subjects">Matières enseignées (si enseignant)</label>
                        <select id="subjects" name="subjects[]" multiple>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected(collect(old('subjects'))->contains($subject->id))>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="staff-form__panel" data-form-panel="teacher" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="teacher_code">Code enseignant *</label>
                        <input type="text" id="teacher_code" name="teacher_code" value="{{ old('teacher_code') }}">
                    </div>
                    <div class="form-field">
                        <label for="grade">Grade / Rang</label>
                        <input type="text" id="grade" name="grade" value="{{ old('grade') }}">
                    </div>
                    <div class="form-field">
                        <label for="speciality">Spécialité</label>
                        <input type="text" id="speciality" name="speciality" value="{{ old('speciality') }}">
                    </div>
                    <div class="form-field">
                        <label for="qualification">Qualification</label>
                        <input type="text" id="qualification" name="qualification" value="{{ old('qualification') }}">
                    </div>
                    <div class="form-field">
                        <label for="teaching_load_hours">Charge horaire (heures/semaine)</label>
                        <input type="number" id="teaching_load_hours" name="teaching_load_hours" min="0" value="{{ old('teaching_load_hours') }}">
                    </div>
                    <div class="form-field">
                        <label for="pedagogical_responsibility">Responsabilité pédagogique</label>
                        <input type="text" id="pedagogical_responsibility" name="pedagogical_responsibility" value="{{ old('pedagogical_responsibility') }}">
                    </div>
                    <div class="form-field">
                        <label for="start_teaching_date">Date début enseignement</label>
                        <input type="date" id="start_teaching_date" name="start_teaching_date" value="{{ old('start_teaching_date') }}">
                    </div>
                    <div class="form-field">
                        <label for="teaching_experience_years">Années d'expérience</label>
                        <input type="number" id="teaching_experience_years" name="teaching_experience_years" min="0" value="{{ old('teaching_experience_years') }}">
                    </div>
                    <div class="form-field">
                        <label for="teacher_evaluation">Évaluation</label>
                        <input type="text" id="teacher_evaluation" name="teacher_evaluation" value="{{ old('teacher_evaluation') }}">
                    </div>
                    <div class="form-field">
                        <label for="research_interests">Intérêts de recherche</label>
                        <textarea id="research_interests" name="research_interests" rows="3">{{ old('research_interests') }}</textarea>
                    </div>
                    <div class="form-field">
                        <label for="professional_development">Développement professionnel</label>
                        <textarea id="professional_development" name="professional_development" rows="3">{{ old('professional_development') }}</textarea>
                    </div>
                    <div class="form-field">
                        <label for="notes">Notes / Observations</label>
                        <textarea id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    </div>
                    <div class="form-field">
                        <label for="teacher_documents">Documents pédagogiques (PDF)</label>
                        <input type="file" id="teacher_documents" name="teacher_documents[]" accept="application/pdf" multiple>
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
