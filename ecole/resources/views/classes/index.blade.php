<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/classes/index.css') }}">

    @php
        $classFormErrors = $errors->getBag('classForm');
        $subjectFormErrors = $errors->getBag('subjectForm');
        $assignStudentErrors = $errors->getBag('assignStudentForm');
        $assignSubjectErrors = $errors->getBag('assignSubjectForm');
        $headcountErrors = $errors->getBag('headcountForm');
    @endphp

    <div class="classes-page">
        <div class="classes-header">
            <div>
                <h1>Classes & Matières</h1>
                <p>Gestion des classes, matières et enseignants</p>
            </div>
            <div class="header-actions">
                <button class="secondary-button" type="button" data-modal-open="series">Gérer les séries</button>
                <button class="secondary-button" type="button" data-modal-open="subject">+ Ajouter une matière</button>
                <button class="primary-button" type="button" data-modal-open="class">+ Ajouter une classe</button>
            </div>
        </div>

        @if (session('status'))
            <div class="alert success">
                {{ session('status') }}
            </div>
        @endif

        @if ($classFormErrors->any() || $subjectFormErrors->any() || $assignStudentErrors->any() || $assignSubjectErrors->any() || $headcountErrors->any())
            <div class="alert error">
                <strong>Des erreurs doivent être corrigées :</strong>
                <ul>
                    @foreach ([$classFormErrors, $subjectFormErrors, $assignStudentErrors, $assignSubjectErrors, $headcountErrors] as $bag)
                        @foreach ($bag->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="classes-grid">
            @forelse ($classes as $class)
                <div class="class-card">
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

                    <div class="class-card__stats">
                        <div class="stat">
                            <span>Effectif déclaré</span>
                            <strong>{{ $class->manual_headcount ?? 'Non renseigné' }}</strong>
                        </div>
                        <div class="stat">
                            <span>Affectations</span>
                            <strong>{{ $class->student_assignments_count }}</strong>
                        </div>
                        <div class="stat">
                            <span>Matières</span>
                            <strong>{{ $class->subject_assignments_count }}</strong>
                        </div>
                    </div>

                    <form class="headcount-form" method="POST" action="{{ route('classes.headcount.update', $class) }}">
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
                            class="outline-button"
                            type="button"
                            data-modal-open="assign-student"
                            data-action="{{ route('classes.students.assign', $class) }}"
                            data-class-name="{{ $class->name }}"
                        >
                            Ajouter un élève
                        </button>
                        <button
                            class="outline-button"
                            type="button"
                            data-modal-open="assign-subject"
                            data-action="{{ route('classes.subjects.assign', $class) }}"
                            data-class-name="{{ $class->name }}"
                            data-class-subjects='@json($class->subjectAssignments->map(function ($assignment) {
                                $teachers = $assignment->teachers->map(fn ($teacher) => trim($teacher->last_name.' '.$teacher->first_name));

                                if ($teachers->isEmpty() && $assignment->teacher) {
                                    $teachers = collect([trim($assignment->teacher->last_name.' '.$assignment->teacher->first_name)]);
                                }

                                return [
                                    'name' => $assignment->subject?->name,
                                    'level' => $assignment->subject?->level,
                                    'series' => $assignment->subject?->series,
                                    'coefficient' => $assignment->coefficient,
                                    'color' => $assignment->color,
                                    'teachers' => $teachers->values(),
                                ];
                            }))'
                        >
                            Ajouter une matière
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <h3>Aucune classe enregistrée</h3>
                    <p>Commencez par créer votre première classe pour affecter élèves et matières.</p>
                </div>
            @endforelse
        </div>
    </div>

    @include('classes.partials.class-form-modal', [
        'academicYears' => $academicYears,
        'isOpen' => $classFormErrors->any(),
        'seriesOptions' => $seriesOptions ?? [],
    ])
    @include('classes.partials.subject-form-modal', [
        'isOpen' => $subjectFormErrors->any(),
        'seriesOptions' => $seriesOptions ?? [],
    ])
    @include('classes.partials.series-form-modal', [
        'isOpen' => false,
        'seriesOptions' => $seriesOptions ?? [],
    ])
    @include('classes.partials.assign-student-modal', [
        'students' => $students,
        'isOpen' => $assignStudentErrors->any(),
    ])
    @include('classes.partials.assign-subject-modal', [
        'subjects' => $subjects,
        'staff' => $staff,
        'isOpen' => $assignSubjectErrors->any(),
    ])

    <datalist id="series-options">
        @foreach ($seriesOptions ?? [] as $seriesOption)
            <option value="{{ $seriesOption }}"></option>
        @endforeach
    </datalist>

    <script src="{{ asset('js/classes/modals.js') }}" defer></script>
</x-app-layout>
