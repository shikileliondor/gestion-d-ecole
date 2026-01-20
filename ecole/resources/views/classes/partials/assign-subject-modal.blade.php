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
        >
            @csrf
            <input type="hidden" name="action_target_subject" value="{{ old('action_target_subject') }}" data-action-input>
            <input type="hidden" name="class_label_subject" value="{{ old('class_label_subject') }}" data-class-input>
            <div class="form-grid">
                <div class="form-field form-field--full">
                    <label for="subject_id">Matière</label>
                    <select id="subject_id" name="subject_id" required>
                        <option value="">Sélectionner</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>
                                {{ $subject->name }}@if ($subject->level) ({{ $subject->level }})@endif
                            </option>
                        @endforeach
                    </select>
                    @if ($assignSubjectErrors->has('subject_id'))
                        <span class="error-text">{{ $assignSubjectErrors->first('subject_id') }}</span>
                    @endif
                </div>
                <div class="form-field">
                    <label for="teacher_id">Enseignant</label>
                    <select id="teacher_id" name="teacher_id">
                        <option value="">Aucun</option>
                        @foreach ($staff as $member)
                            <option value="{{ $member->id }}" @selected(old('teacher_id') == $member->id)>
                                {{ $member->last_name }} {{ $member->first_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="coefficient">Coefficient</label>
                    <input id="coefficient" name="coefficient" type="number" min="1" value="{{ old('coefficient', 1) }}">
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
    </div>
</div>
