<x-page-shell
    title="Élèves · Inscriptions"
    subtitle="Enregistrer une nouvelle inscription scolaire."
>
    <form method="post" action="{{ route('students.enrollments.store') }}" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        <div class="grid gap-4 md:grid-cols-2">
            <label class="text-sm text-gray-600">
                Nom
                <input name="last_name" type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" placeholder="Nom de famille" required />
            </label>
            <label class="text-sm text-gray-600">
                Prénoms
                <input name="first_name" type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" placeholder="Prénom(s)" required />
            </label>
            <label class="text-sm text-gray-600">
                Deuxième prénom (optionnel)
                <input name="middle_name" type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" placeholder="Autres prénoms" />
            </label>
            <label class="text-sm text-gray-600">
                Sexe
                <select name="gender" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2">
                    <option value="">Sélectionner</option>
                    <option value="male">Masculin</option>
                    <option value="female">Féminin</option>
                    <option value="other">Autre</option>
                </select>
            </label>
            <label class="text-sm text-gray-600">
                Date de naissance
                <input name="date_of_birth" type="date" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
            </label>
            <label class="text-sm text-gray-600">
                Lieu de naissance
                <input name="place_of_birth" type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" placeholder="Ville de naissance" />
            </label>
            <label class="text-sm text-gray-600">
                Nationalité
                <input name="nationality" type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" placeholder="Ivoirienne, etc." />
            </label>
            <label class="text-sm text-gray-600">
                Année scolaire (automatique)
                <input
                    type="text"
                    class="mt-1 w-full rounded-lg border border-gray-200 bg-slate-50 px-3 py-2 text-slate-500"
                    value="{{ $activeAcademicYear?->libelle ?? 'Aucune année active' }}"
                    readonly
                />
            </label>
            <label class="text-sm text-gray-600">
                Classe
                <select name="class_id" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" required>
                    <option value="">Sélectionner une classe</option>
                    @foreach ($classes as $classe)
                        <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm text-gray-600">
                Date d'inscription
                <input name="enrollment_date" type="date" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
            </label>
        </div>
        <button type="submit" class="mt-4 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
    </form>
</x-page-shell>
