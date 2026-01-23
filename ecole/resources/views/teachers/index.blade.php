<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/students/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/students/form.css') }}">

    <div class="students-page">
        <div class="students-header">
            <div>
                <h1>Fiches enseignants</h1>
                <p>Gestion des enseignants et volet RH léger</p>
            </div>
            <a class="primary-button" href="{{ route('teachers.create') }}">+ Ajouter un enseignant</a>
        </div>

        @if (session('status'))
            <div class="form-alert">
                <p>{{ session('status') }}</p>
            </div>
        @endif

        <div class="students-table-wrapper">
            <table class="students-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Nom & Prénoms</th>
                        <th>Spécialité</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($enseignants as $enseignant)
                        <tr>
                            <td>{{ $enseignant->code_enseignant }}</td>
                            <td>{{ $enseignant->nom }} {{ $enseignant->prenoms }}</td>
                            <td>{{ $enseignant->specialite }}</td>
                            <td>{{ $enseignant->type_enseignant }}</td>
                            <td>{{ $enseignant->statut }}</td>
                            <td class="table-actions">
                                <a class="secondary-button" href="{{ route('teachers.show', $enseignant) }}">Voir</a>
                                <a class="secondary-button" href="{{ route('teachers.edit', $enseignant) }}">Modifier</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Aucun enseignant enregistré.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
