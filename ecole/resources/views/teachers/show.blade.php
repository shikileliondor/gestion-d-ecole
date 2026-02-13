<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/students/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/students/modal.css') }}">

    <div class="student-form-page">
        <div class="student-form-header">
            <div>
                <h1>Fiche enseignant</h1>
                <p>{{ $enseignant->nom }} {{ $enseignant->prenoms }}</p>
            </div>
            <div class="form-actions">
                <a class="secondary-button" href="{{ route('teachers.index') }}">Retour</a>
                <a class="primary-button" href="{{ route('teachers.edit', $enseignant) }}">Modifier</a>
            </div>
        </div>

        @if (session('status'))
            <div class="form-alert">
                <p>{{ session('status') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="form-alert">
                <h2>Veuillez corriger les erreurs</h2>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-section">
            <h2>Identité</h2>
            <div class="form-grid">
                <div class="form-field">
                    <label>Code enseignant</label>
                    <p>{{ $enseignant->code_enseignant }}</p>
                </div>
                <div class="form-field">
                    <label>Nom</label>
                    <p>{{ $enseignant->nom }}</p>
                </div>
                <div class="form-field">
                    <label>Prénoms</label>
                    <p>{{ $enseignant->prenoms }}</p>
                </div>
                <div class="form-field">
                    <label>Sexe</label>
                    <p>{{ $enseignant->sexe ?? '—' }}</p>
                </div>
                <div class="form-field">
                    <label>Photo</label>
                    <p>
                        @if ($enseignant->photo_url)
                            <a href="{{ $enseignant->photo_url }}" target="_blank" rel="noreferrer">Voir la photo</a>
                        @else
                            —
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Contacts</h2>
            <div class="form-grid">
                <div class="form-field">
                    <label>Téléphone principal</label>
                    <p>{{ $enseignant->telephone_1 }}</p>
                </div>
                <div class="form-field">
                    <label>Téléphone secondaire</label>
                    <p>{{ $enseignant->telephone_2 ?? '—' }}</p>
                </div>
                <div class="form-field">
                    <label>Email</label>
                    <p>{{ $enseignant->email ?? '—' }}</p>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Profil pédagogique</h2>
            <div class="form-grid">
                <div class="form-field">
                    <label>Spécialité</label>
                    <p>{{ $enseignant->specialite }}</p>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>RH léger</h2>
            <div class="form-grid">
                <div class="form-field">
                    <label>Type enseignant</label>
                    <p>{{ $enseignant->type_enseignant }}</p>
                </div>
                <div class="form-field">
                    <label>Début service</label>
                    <p>{{ $enseignant->date_debut_service?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div class="form-field">
                    <label>Fin service</label>
                    <p>{{ $enseignant->date_fin_service?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div class="form-field">
                    <label>Statut</label>
                    <p>{{ $enseignant->statut }}</p>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>Documents</h2>
            <form class="student-form" method="POST" action="{{ route('teachers.documents.store', $enseignant) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    <div class="form-field">
                        <label for="type_document">Type document *</label>
                        <select id="type_document" name="type_document" required>
                            <option value="">Sélectionner</option>
                            @foreach ($documentTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field">
                        <label for="libelle">Libellé *</label>
                        <input type="text" id="libelle" name="libelle" required>
                    </div>
                    <div class="form-field">
                        <label for="description">Description</label>
                        <input type="text" id="description" name="description">
                    </div>
                    <div class="form-field">
                        <label for="fichier">Fichier *</label>
                        <input type="file" id="fichier" name="fichier" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="primary-button" type="submit">Uploader</button>
                </div>
            </form>

            <div class="students-table-wrapper" style="margin-top: 16px;">
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Libellé</th>
                            <th>Description</th>
                            <th>Fichier</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enseignant->documents as $document)
                            <tr>
                                <td>{{ $document->type_document }}</td>
                                <td>{{ $document->libelle }}</td>
                                <td>{{ $document->description ?? '—' }}</td>
                                <td>
                                    <a href="{{ Storage::url($document->file_path) }}" target="_blank" rel="noreferrer">
                                        Télécharger
                                    </a>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('teachers.documents.destroy', [$enseignant, $document]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="secondary-button" type="submit">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Aucun document uploadé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <form method="POST" action="{{ route('teachers.destroy', $enseignant) }}">
            @csrf
            @method('DELETE')
            <button class="secondary-button" type="submit">Supprimer la fiche</button>
        </form>
    </div>
</x-app-layout>
