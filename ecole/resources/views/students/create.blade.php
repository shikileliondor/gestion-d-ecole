<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/students/modal.css') }}">

    <div class="students-page">
        <div class="students-header">
            <div>
                <h1>Ajouter un élève</h1>
                <p>Utilisez la fenêtre modale pour compléter la fiche.</p>
            </div>
            <a class="secondary-button" href="{{ route('students.index') }}">Retour à la liste</a>
        </div>
    </div>

    @include('students.partials.student-form-modal', [
        'classes' => $classes,
        'academicYears' => $academicYears,
        'activeAcademicYear' => $activeAcademicYear,
        'levels' => $levels,
        'isOpen' => true,
    ])

    <script src="{{ asset('js/students/form-modal.js') }}" defer></script>
</x-app-layout>
