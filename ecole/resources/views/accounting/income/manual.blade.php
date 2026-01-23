<x-page-shell
    title="Comptabilité · Recettes · Écritures diverses"
    subtitle="Saisir des recettes manuelles (subventions, dons, activités)."
>
    <div class="grid gap-6 lg:grid-cols-2">
        <form class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="grid gap-4">
                <label class="text-sm text-gray-600">
                    Libellé
                    <input type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" placeholder="Subvention municipale" />
                </label>
                <label class="text-sm text-gray-600">
                    Catégorie
                    <input type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" placeholder="Autres recettes" />
                </label>
                <label class="text-sm text-gray-600">
                    Montant
                    <input type="number" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
                </label>
                <label class="text-sm text-gray-600">
                    Commentaire
                    <textarea class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" rows="3"></textarea>
                </label>
            </div>
            <button type="submit" class="mt-4 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Ajouter l'écriture</button>
        </form>
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Historique des écritures</h3>
            <ul class="mt-4 space-y-3 text-sm text-gray-600">
                <li class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2">
                    <span>Dons des parents</span>
                    <span class="font-semibold text-emerald-600">+120 000</span>
                </li>
                <li class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2">
                    <span>Location de salle</span>
                    <span class="font-semibold text-emerald-600">+45 000</span>
                </li>
            </ul>
        </div>
    </div>
</x-page-shell>
