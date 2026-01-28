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

        <form class="filters-bar" method="GET" data-async-form data-async-action="classes-filter" data-no-reset="true">
            <div class="filter-group">
                <label for="filter-year">Année</label>
                <select id="filter-year" name="academic_year_id">
                    <option value="">Toutes</option>
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}" @selected(($selectedAcademicYearId ?? null) == $year->id)>{{ $year->libelle }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label for="filter-level">Niveau</label>
                <select id="filter-level" name="level_id">
                    <option value="">Tous</option>
                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}" @selected(($selectedLevelId ?? null) == $level->id)>{{ $level->code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label for="filter-serie">Série</label>
                <select id="filter-serie" name="serie_id">
                    <option value="">Toutes</option>
                    @foreach ($series as $serie)
                        <option value="{{ $serie->id }}" @selected(($selectedSerieId ?? null) == $serie->id)>{{ $serie->code }}</option>
                    @endforeach
                </select>
            </div>
            <button class="secondary-button" type="submit">Filtrer</button>
        </form>

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

        <div data-classes-grid-wrapper>
            @include('classes.partials.class-grid', ['classes' => $classes])
        </div>
    </div>

    @include('classes.partials.class-form-modal', [
        'academicYears' => $academicYears,
        'levels' => $levels,
        'series' => $series,
        'isOpen' => $classFormErrors->any(),
        'activeAcademicYear' => $activeAcademicYear ?? null,
        'lyceeLevelCodes' => $lyceeLevelCodes ?? [],
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
