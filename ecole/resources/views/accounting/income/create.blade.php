<x-page-shell
    title="Comptabilité · Recettes · Ajouter un paiement"
    subtitle="Enregistrer un paiement direct pour un élève ou une facture."
>
    <form class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="grid gap-4 md:grid-cols-2">
            <label class="text-sm text-gray-600">
                Élève
                <input type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" placeholder="Rechercher un élève" />
            </label>
            <label class="text-sm text-gray-600">
                Période
                <select class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2">
                    <option>Mensuel</option>
                    <option>Trimestriel</option>
                    <option>Annuel</option>
                </select>
            </label>
            <label class="text-sm text-gray-600">
                Montant
                <input type="number" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" placeholder="0" />
            </label>
            <label class="text-sm text-gray-600">
                Mode de paiement
                <select class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2">
                    <option>Espèces</option>
                    <option>Mobile Money</option>
                    <option>Virement</option>
                </select>
            </label>
        </div>
        <div class="mt-6 flex flex-wrap gap-3">
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
            <button type="button" class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-600">Annuler</button>
        </div>
    </form>
</x-page-shell>
