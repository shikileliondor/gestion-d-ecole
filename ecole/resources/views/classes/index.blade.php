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
        $statusMessage = session('status');
        $allErrors = collect([$classFormErrors, $subjectFormErrors, $assignStudentErrors, $assignSubjectErrors, $headcountErrors])
            ->flatMap(fn ($bag) => $bag->all());
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

        <div class="alert success" data-feedback-success @if (! $statusMessage) hidden @endif>
            {{ $statusMessage }}
        </div>

        <div class="alert error" data-feedback-error @if ($allErrors->isEmpty()) hidden @endif>
            @if ($allErrors->isNotEmpty())
                <strong>Des erreurs doivent être corrigées :</strong>
                <ul>
                    @foreach ($allErrors as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="classes-grid" data-classes-grid>
            @forelse ($classes as $class)
                @include('classes.partials.class-card', ['class' => $class])
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
    @include('classes.partials.timetable-modal')
    @include('classes.partials.timetable-preview-modal')

    <datalist id="series-options">
        @include('classes.partials.series-options', ['seriesOptions' => $seriesOptions ?? []])
    </datalist>

    <script src="{{ asset('js/classes/modals.js') }}" defer></script>
    <script src="{{ asset('js/classes/async-actions.js') }}" defer></script>
</x-app-layout>
