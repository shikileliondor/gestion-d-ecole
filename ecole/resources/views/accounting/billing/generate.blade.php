<x-page-shell
    title="Comptabilité · Facturation · Générer des factures"
    subtitle="Création automatique par période/classe/niveau ou manuelle élève par élève."
>
    <div class="grid gap-6 lg:grid-cols-2">
        <form class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Automatique</h3>
            <div class="mt-4 grid gap-4">
                <label class="text-sm text-gray-600">
                    Année scolaire
                    <select class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2">
                        <option>2024-2025</option>
                        <option>2023-2024</option>
                    </select>
                </label>
                <label class="text-sm text-gray-600">
                    Période
                    <select class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2">
                        <option>Mensuel</option>
                        <option>Trimestriel</option>
                        <option>Annuel (basé sur les trimestres)</option>
                    </select>
                </label>
                <label class="text-sm text-gray-600">
                    Classe(s)
                    <input type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" placeholder="Ex : 6e A, 6e B" />
                </label>
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Générer</button>
            </div>
        </form>
        <form class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Manuelle</h3>
            <div class="mt-4 grid gap-4">
                <label class="text-sm text-gray-600">
                    Élève
                    <input type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
                </label>
                <label class="text-sm text-gray-600">
                    Montant facturé
                    <input type="number" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
                </label>
                <label class="text-sm text-gray-600">
                    Frais variables
                    <textarea class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" rows="3" placeholder="Transport, cantine, activités, remise..."></textarea>
                </label>
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Créer la facture</button>
            </div>
        </form>
    </div>
</x-page-shell>
