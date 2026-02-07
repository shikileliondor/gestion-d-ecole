<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/students/modal.css') }}">

    <div class="students-page">
        <div class="students-header">
            <div>
                <h1>Élèves</h1>
                <p>Inscriptions et liste des élèves réunies sur un seul écran.</p>
            </div>
            <button class="primary-button" type="button" data-form-modal-open>+ Nouvelle inscription</button>
        </div>

        <div class="students-toolbar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Rechercher par nom ou matricule...">
            </div>
            <button class="filter-button" type="button">
                Toutes les classes
                <span class="chevron">▾</span>
            </button>
        </div>

        <div class="students-table-wrapper">
            <table class="students-table">
                <thead>
                    <tr>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Classe</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $student->admission_number ?? '—' }}</td>
                            <td>{{ $student->last_name }} {{ $student->first_name }}</td>
                            <td>{{ $student->class_name ?? '—' }}</td>
                            <td>
                                <span class="status-pill {{ $student->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                    {{ $student->status ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <button
                                    class="icon-button"
                                    type="button"
                                    data-student-id="{{ $student->id }}"
                                    data-student-name="{{ $student->last_name }} {{ $student->first_name }}"
                                    data-student-url="{{ route('students.show', $student) }}"
                                    aria-label="Voir la fiche de {{ $student->last_name }} {{ $student->first_name }}"
                                >
                                    <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                                        <path
                                            d="M12 5c5.05 0 9.27 3.11 11 7-1.73 3.89-5.95 7-11 7S2.73 15.89 1 12c1.73-3.89 5.95-7 11-7Zm0 2C7.82 7 4.31 9.24 2.75 12 4.31 14.76 7.82 17 12 17s7.69-2.24 9.25-5C19.69 9.24 16.18 7 12 7Zm0 2.5A2.5 2.5 0 1 1 9.5 12 2.5 2.5 0 0 1 12 9.5Zm0 1.8A.7.7 0 1 0 12.7 12 .7.7 0 0 0 12 11.3Z"
                                        />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('students.partials.student-modal')
    @include('students.partials.student-form-modal', [
        'classes' => $classes,
        'academicYears' => $academicYears,
        'activeAcademicYear' => $activeAcademicYear,
        'levels' => $levels,
        'isOpen' => $errors->any() || request()->get('open') === 'create',
    ])

    <script src="{{ asset('js/students/modal.js') }}" defer></script>
    <script src="{{ asset('js/students/form-modal.js') }}" defer></script>
</x-app-layout>
