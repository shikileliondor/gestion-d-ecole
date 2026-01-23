<x-page-shell
    title="Comptabilité · Recettes · Catégories"
    subtitle="Configurer les catégories de recettes (paramétrable)."
>
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Catégories existantes</h3>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach (['Scolarité', 'Transport', 'Cantine', 'Activités', 'Autres'] as $category)
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">{{ $category }}</span>
                @endforeach
            </div>
        </div>
        <form class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Ajouter une catégorie</h3>
            <label class="mt-4 block text-sm text-gray-600">
                Nom de la catégorie
                <input type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
            </label>
            <button type="submit" class="mt-4 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Créer</button>
        </form>
    </div>
</x-page-shell>
