@php
    // Préparer les données des matières assignées
    $subjectAssignmentsData = [];
    foreach ($class->subjectAssignments as $assignment) {
        $teachers = [];
        foreach ($assignment->teachers as $teacher) {
            $teachers[] = trim($teacher->last_name . ' ' . $teacher->first_name);
        }

        if (empty($teachers) && $assignment->teacher) {
            $teachers = [trim($assignment->teacher->last_name . ' ' . $assignment->teacher->first_name)];
        }

        $subjectAssignmentsData[] = [
            'name' => $assignment->subject?->name,
            'level' => $assignment->subject?->level,
            'series' => $assignment->subject?->series,
            'coefficient' => $assignment->coefficient,
            'color' => $assignment->color,
            'teachers' => array_values($teachers),
        ];
    }

    // Préparer les données pour l'emploi du temps
    $timetableData = [];
    foreach ($class->subjectAssignments as $assignment) {
        $timetableData[] = [
            'id' => $assignment->subject?->id,
            'name' => $assignment->subject?->name,
            'color' => $assignment->color,
        ];
    }
@endphp

<div class="class-card" data-class-card-id="{{ $class->id }}" data-class-name="{{ $class->name }}">
    <div class="class-card__header">
        <div>
            <h3>{{ $class->name }}</h3>
            <p>
                {{ $class->level ?? 'Niveau non défini' }}
                @if ($class->series)
                    • Série {{ $class->series }}
                @endif
                @if ($class->section)
                    • Groupe {{ $class->section }}
                @endif
            </p>
        </div>
        <span class="badge">{{ $class->academicYear->name ?? 'Année inconnue' }}</span>
    </div>

    <div class="class-card__badges">
        <span class="badge {{ $class->programme_complete ? 'badge--success' : 'badge--warning' }}">
            {{ $class->programme_complete ? 'Programme complet' : 'Programme à compléter' }}
        </span>
        <span class="badge {{ $class->assignments_complete ? 'badge--success' : 'badge--warning' }}">
            {{ $class->assignments_complete ? 'Affectations OK' : 'Affectations manquantes' }}
        </span>
        <span class="badge badge--warning" data-edt-status data-class-id="{{ $class->id }}">EDT à planifier</span>
    </div>

    <div class="class-card__stats">
        <div class="stat">
            <span>Effectif</span>
            <strong>
                {{ $class->manual_headcount ?? $class->student_assignments_count }}
                <small>élèves</small>
            </strong>
        </div>
        <div class="stat">
            <span>Matières</span>
            <strong>{{ $class->subject_assignments_count }} <small>matières</small></strong>
        </div>
        <div class="stat">
            <span>Affectations</span>
            <strong>{{ $class->teacher_assignments_count }} <small>enseignants</small></strong>
        </div>
    </div>

    <form
        class="headcount-form"
        method="POST"
        action="{{ route('classes.headcount.update', $class) }}"
        data-async-form
        data-async-action="headcount-update"
    >
        @csrf
        @method('PATCH')
        <label for="headcount-{{ $class->id }}">Effectif manuel</label>
        <div class="inline-input">
            <input
                id="headcount-{{ $class->id }}"
                name="manual_headcount"
                type="number"
                min="0"
                value="{{ old('manual_headcount', $class->manual_headcount) }}"
                placeholder="0"
            >
            <button type="submit" class="ghost-button">Mettre à jour</button>
        </div>
    </form>

    <div class="class-card__actions">
        <button
            class="pill-button"
            type="button"
            data-modal-open="assign-student"
            data-action="{{ route('classes.students.assign', $class) }}"
            data-class-name="{{ $class->name }}"
        >
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3Zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5C23 14.17 18.33 13 16 13Z"/>
            </svg>
            Ajouter un élève
        </button>
        <button
            class="pill-button"
            type="button"
            data-modal-open="assign-subject"
            data-action="{{ route('classes.subjects.assign', $class) }}"
            data-class-name="{{ $class->name }}"
            data-class-subjects='@json($subjectAssignmentsData)'
        >
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M19 2H9a2 2 0 0 0-2 2v15a1 1 0 0 0 1.45.9L12 18.12l3.55 1.78A1 1 0 0 0 17 19V4a2 2 0 0 0-2-2Zm0 15.5-2.55-1.28a1 1 0 0 0-.9 0L13 17.5V4h6v13.5Z"/>
            </svg>
            Ajouter une matière
        </button>
        <button
            class="pill-button"
            type="button"
            data-modal-open="timetable"
            data-class-name="{{ $class->name }}"
            data-class-id="{{ $class->id }}"
            data-class-subjects='@json($timetableData)'
        >
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Zm0 6v2h2V8H7Zm4 0v2h2V8h-2Zm4 0v2h2V8h-2ZM7 12v2h2v-2H7Zm4 0v2h2v-2h-2Zm4 0v2h2v-2h-2Z"/>
            </svg>
            Emploi du temps
        </button>
        <button
            class="pill-button"
            type="button"
            data-modal-open="timetable-preview"
            data-class-name="{{ $class->name }}"
            data-class-id="{{ $class->id }}"
            data-class-subjects='@json($timetableData)'
        >
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M4 4h16a2 2 0 0 1 2 2v3H2V6a2 2 0 0 1 2-2Zm-2 7h20v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-7Zm5 2v2h4v-2H7Z"/>
            </svg>
            Voir le planning
        </button>
    </div>
</div>
