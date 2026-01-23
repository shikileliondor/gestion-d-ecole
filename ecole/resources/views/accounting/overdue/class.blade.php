<x-page-shell
    title="Comptabilité · Impayés · Par classe"
    subtitle="Visualiser les élèves qui n'ont pas payé par classe et par période."
>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="grid gap-4 md:grid-cols-3">
            <select class="rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-600">
                <option>Année scolaire 2024-2025</option>
            </select>
            <select class="rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-600">
                <option>Classe : 6e A</option>
                <option>Classe : 6e B</option>
            </select>
            <select class="rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-600">
                <option>Période : Mensuel</option>
                <option>Période : Trimestriel</option>
                <option>Période : Annuel</option>
            </select>
        </div>
        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Élève</th>
                        <th class="px-5 py-3">Montant dû</th>
                        <th class="px-5 py-3">Montant payé</th>
                        <th class="px-5 py-3">Reste</th>
                        <th class="px-5 py-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">Alice</td>
                        <td class="px-5 py-3">50 000</td>
                        <td class="px-5 py-3">20 000</td>
                        <td class="px-5 py-3">30 000</td>
                        <td class="px-5 py-3 text-amber-600">Partiel</td>
                    </tr>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">Paul</td>
                        <td class="px-5 py-3">50 000</td>
                        <td class="px-5 py-3">0</td>
                        <td class="px-5 py-3">50 000</td>
                        <td class="px-5 py-3 text-rose-600">Impayé</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex flex-wrap gap-3">
            <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer un paiement</button>
            <button class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-600">Imprimer la liste</button>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-semibold text-gray-900">Résumé de la classe</h3>
        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl bg-gray-50 p-4">
                <p class="text-xs text-gray-500">Total facturé</p>
                <p class="mt-1 text-lg font-semibold text-gray-900">600 000</p>
            </div>
            <div class="rounded-xl bg-gray-50 p-4">
                <p class="text-xs text-gray-500">Total payé</p>
                <p class="mt-1 text-lg font-semibold text-gray-900">520 000</p>
            </div>
            <div class="rounded-xl bg-gray-50 p-4">
                <p class="text-xs text-gray-500">Reste total</p>
                <p class="mt-1 text-lg font-semibold text-gray-900">80 000</p>
            </div>
            <div class="rounded-xl bg-gray-50 p-4">
                <p class="text-xs text-gray-500">% d'élèves impayés</p>
                <p class="mt-1 text-lg font-semibold text-gray-900">25%</p>
            </div>
        </div>
    </div>
</x-page-shell>
