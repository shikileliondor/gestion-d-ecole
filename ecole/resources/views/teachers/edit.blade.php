<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/students/form.css') }}">

    <div class="student-form-page">
        <div class="student-form-header">
            <div>
                <h1>Modifier l'enseignant</h1>
                <p>Mettez à jour les informations essentielles.</p>
            </div>
            <a class="secondary-button" href="{{ route('teachers.show', $enseignant) }}">Retour à la fiche</a>
        </div>

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

        <form class="student-form" method="POST" action="{{ route('teachers.update', $enseignant) }}">
            @csrf
            @method('PUT')

            @include('teachers.partials.form-fields', ['enseignant' => $enseignant])

            <div class="form-actions">
                <button class="primary-button" type="submit">Mettre à jour</button>
                <a class="secondary-button" href="{{ route('teachers.show', $enseignant) }}">Annuler</a>
            </div>
        </form>
    </div>
</x-app-layout>
