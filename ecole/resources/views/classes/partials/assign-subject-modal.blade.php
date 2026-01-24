@php
    $assignSubjectErrors = $errors->getBag('assignSubjectForm');
@endphp

<div
    class="modal"
    data-modal="assign-subject"
    data-open-on-load="{{ $isOpen ? 'true' : 'false' }}"
    aria-hidden="true"
    role="dialog"
>
    <div class="modal__overlay" data-modal-close></div>
    <div class="modal__content">
        <div class="modal__header">
            <div>
                <h2>Affecter une matière</h2>
                <p>Classe sélectionnée : <strong data-class-label data-class-fallback="{{ old('class_label_subject') }}">—</strong></p>
            </div>
            <button type="button" class="icon-button" data-modal-close aria-label="Fermer">
                ✕
            </button>
        </div>

        <form
            class="modal__form"
            method="POST"
            action=""
            data-action-target
            data-action-fallback="{{ old('action_target_subject') }}"
            data-async-form
            data-async-action="assign-subject"
        >
            @csrf
            <input type="hidden" name="action_target_subject" value="{{ old('action_target_subject') }}" data-action-input>
            <input type="hidden" name="class_label_subject" value="{{ old('class_label_subject') }}" data-class-input>
            <div class="form-grid">
                <div class="form-field form-field--full">
                    <label for="subject_id">Matière</label>
                    <select id="subject_id" name="subject_id" required data-assign-subject-select>
                        <option value="">Sélectionner</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>
                                {{ $subject->name }}
                                @if ($subject->level)
                                    ({{ $subject->level }}@if ($subject->series) • Série {{ $subject->series }}@endif)
                                @elseif ($subject->series)
                                    (Série {{ $subject->series }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @if ($assignSubjectErrors->has('subject_id'))
                        <span class="error-text">{{ $assignSubjectErrors->first('subject_id') }}</span>
                    @endif
                </div>
                <div class="form-field">
                    <label for="teacher_ids">Enseignants (multi-sélection)</label>
                    <select id="teacher_ids" name="teacher_ids[]" multiple size="5">
                        @foreach ($staff as $member)
                            <option value="{{ $member->id }}" @selected(collect(old('teacher_ids', []))->contains($member->id))>
                                {{ $member->last_name }} {{ $member->first_name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="helper-text">Maintenez Ctrl/⌘ pour sélectionner plusieurs enseignants.</p>
                    @if ($assignSubjectErrors->has('teacher_ids'))
                        <span class="error-text">{{ $assignSubjectErrors->first('teacher_ids') }}</span>
                    @endif
                </div>
                <div class="form-field">
                    <label for="coefficient">Coefficient</label>
                    <input id="coefficient" name="coefficient" type="number" min="1" value="{{ old('coefficient', 1) }}">
                </div>
                <div class="form-field">
                    <label for="color">Couleur matière</label>
                    <input id="color" name="color" type="color" value="{{ old('color', '#1d4ed8') }}">
                    @if ($assignSubjectErrors->has('color'))
                        <span class="error-text">{{ $assignSubjectErrors->first('color') }}</span>
                    @endif
                </div>
                <div class="form-field form-field--full">
                    <label class="checkbox">
                        <input type="checkbox" name="is_optional" value="1" @checked(old('is_optional'))>
                        <span>Matière optionnelle</span>
                    </label>
                </div>
            </div>

            <div class="modal__actions">
                <button type="button" class="ghost-button" data-modal-close>Annuler</button>
                <button type="submit" class="primary-button">Affecter la matière</button>
            </div>
        </form>

        <div class="modal__divider"></div>
        <div class="subject-summary">
            <h3>Matières déjà affectées</h3>
            <p class="helper-text">Vérifiez les enseignants associés et les couleurs utilisées pour l'emploi du temps.</p>
            <div class="subject-summary__list" data-subject-summary>
                <p class="helper-text">Sélectionnez une classe pour voir ses matières.</p>
            </div>
        </div>
    </div>
</div>
