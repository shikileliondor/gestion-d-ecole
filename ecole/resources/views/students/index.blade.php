<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/students/modal.css') }}">

    <div class="students-page">
        <div class="students-header">
            <div>
                <h1>Gestion des élèves</h1>
                <p>Liste complète et fiches détaillées</p>
            </div>
            <button class="primary-button" type="button">+ Ajouter un élève</button>
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
                        <th>Moyenne</th>
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
                                @if ($student->average_score)
                                    {{ number_format($student->average_score, 1) }}/20
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <button
                                    class="link-button"
                                    type="button"
                                    data-student-id="{{ $student->id }}"
                                    data-student-name="{{ $student->last_name }} {{ $student->first_name }}"
                                >
                                    Voir fiche
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('students.partials.student-modal')

    <script src="{{ asset('js/students/modal.js') }}" defer></script>
</x-app-layout>
