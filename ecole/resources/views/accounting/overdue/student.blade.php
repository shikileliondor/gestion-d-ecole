<x-page-shell
    title="Comptabilité · Impayés · Par élève"
    subtitle="Suivi des impayés par élève et historique de paiement."
>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap gap-3">
            <input type="text" class="w-full max-w-sm rounded-lg border border-gray-200 px-3 py-2" placeholder="Rechercher un élève" />
            <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Filtrer</button>
        </div>
        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Élève</th>
                        <th class="px-5 py-3">Montant dû</th>
                        <th class="px-5 py-3">Dernier paiement</th>
                        <th class="px-5 py-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">Paul Yao</td>
                        <td class="px-5 py-3">50 000</td>
                        <td class="px-5 py-3">—</td>
                        <td class="px-5 py-3 text-rose-600">Impayé</td>
                    </tr>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">Awa Diabaté</td>
                        <td class="px-5 py-3">30 000</td>
                        <td class="px-5 py-3">12/10/2024</td>
                        <td class="px-5 py-3 text-amber-600">Partiel</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-page-shell>
