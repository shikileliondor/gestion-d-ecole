@php
    $assignStudentErrors = $errors->getBag('assignStudentForm');
@endphp

<div
    class="modal"
    data-modal="assign-student"
    data-open-on-load="{{ $isOpen ? 'true' : 'false' }}"
    aria-hidden="true"
    role="dialog"
>
    <div class="modal__overlay" data-modal-close></div>
    <div class="modal__content">
        <div class="modal__header">
            <div>
                <h2>Affecter un élève</h2>
                <p>Classe sélectionnée : <strong data-class-label data-class-fallback="{{ old('class_label_student') }}">—</strong></p>
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
            data-action-fallback="{{ old('action_target_student') }}"
            data-async-form
            data-async-action="assign-student"
        >
            @csrf
            <input type="hidden" name="action_target_student" value="{{ old('action_target_student') }}" data-action-input>
            <input type="hidden" name="class_label_student" value="{{ old('class_label_student') }}" data-class-input>
            <div class="form-grid">
                <div class="form-field form-field--full">
                    <label for="student_id">Élève</label>
                    <select id="student_id" name="student_id" required>
                        <option value="">Sélectionner</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                {{ $student->last_name }} {{ $student->first_name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($assignStudentErrors->has('student_id'))
                        <span class="error-text">{{ $assignStudentErrors->first('student_id') }}</span>
                    @endif
                </div>
                <div class="form-field">
                    <label for="start_date">Date de début</label>
                    <input id="start_date" name="start_date" type="date" value="{{ old('start_date') }}">
                </div>
                <div class="form-field">
                    <label for="assignment_status">Statut</label>
                    <select id="assignment_status" name="status">
                        <option value="active" @selected(old('status', 'active') === 'active')>Actif</option>
                        <option value="transferred" @selected(old('status') === 'transferred')>Transféré</option>
                        <option value="completed" @selected(old('status') === 'completed')>Terminé</option>
                    </select>
                </div>
            </div>

            <div class="modal__actions">
                <button type="button" class="ghost-button" data-modal-close>Annuler</button>
                <button type="submit" class="primary-button">Affecter l'élève</button>
            </div>
        </form>
    </div>
</div>
