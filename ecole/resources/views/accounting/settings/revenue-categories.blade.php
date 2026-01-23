<x-page-shell
    title="Comptabilité · Paramètres · Catégories recettes"
    subtitle="Personnaliser les catégories de recettes et leurs libellés." 
>
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Catégories</h3>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach (['Scolarité', 'Cantine', 'Transport', 'Activités'] as $category)
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">{{ $category }}</span>
                @endforeach
            </div>
        </div>
        <form class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Ajouter</h3>
            <input type="text" class="mt-4 w-full rounded-lg border border-gray-200 px-3 py-2" placeholder="Nouvelle catégorie" />
            <button class="mt-4 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Ajouter</button>
        </form>
    </div>
</x-page-shell>
