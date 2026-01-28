<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/students/form.css') }}">

    <div class="student-form-page">
        <div class="student-form-header">
            <div>
                <h1>Nouvel enseignant</h1>
                <p>Créer une fiche enseignant simple et propre.</p>
            </div>
            <a class="secondary-button" href="{{ route('teachers.index') }}">Retour à la liste</a>
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

        <form class="student-form" method="POST" action="{{ route('teachers.store') }}" enctype="multipart/form-data">
            @csrf

            @include('teachers.partials.form-fields')

            <div class="form-actions">
                <button class="primary-button" type="submit">Enregistrer</button>
                <a class="secondary-button" href="{{ route('teachers.index') }}">Annuler</a>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/teachers/form.js') }}" defer></script>
</x-app-layout>
