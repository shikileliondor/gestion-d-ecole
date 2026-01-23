<x-page-shell
    title="Comptabilité · Dépenses · Ajouter"
    subtitle="Ajouter une dépense avec catégorie, date et pièce justificative."
>
    <form class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="grid gap-4 md:grid-cols-2">
            <label class="text-sm text-gray-600">
                Libellé
                <input type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
            </label>
            <label class="text-sm text-gray-600">
                Catégorie
                <input type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
            </label>
            <label class="text-sm text-gray-600">
                Montant
                <input type="number" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
            </label>
            <label class="text-sm text-gray-600">
                Date
                <input type="date" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
            </label>
            <label class="text-sm text-gray-600 md:col-span-2">
                Justificatif
                <input type="file" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
            </label>
        </div>
        <div class="mt-6 flex flex-wrap gap-3">
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
            <button type="button" class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-600">Annuler</button>
        </div>
    </form>
</x-page-shell>
