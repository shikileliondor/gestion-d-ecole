<x-page-shell
    title="Comptabilité · Dépenses · Liste"
    subtitle="Suivi des dépenses et décaissements enregistrés."
>
    <div class="flex flex-wrap gap-3">
        <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Ajouter une dépense</button>
        <button class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-600">Exporter PDF</button>
        <button class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-600">Exporter Excel</button>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-4 text-sm font-semibold text-gray-700">Dépenses récentes</div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Libellé</th>
                        <th class="px-5 py-3">Catégorie</th>
                        <th class="px-5 py-3">Montant</th>
                        <th class="px-5 py-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">Maintenance bâtiment</td>
                        <td class="px-5 py-3">Entretien</td>
                        <td class="px-5 py-3">35 000</td>
                        <td class="px-5 py-3">12/09/2024</td>
                    </tr>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">Fournitures pédagogiques</td>
                        <td class="px-5 py-3">Matériel</td>
                        <td class="px-5 py-3">18 000</td>
                        <td class="px-5 py-3">05/09/2024</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-page-shell>
