<x-page-shell
    title="Comptabilité · Facturation · Factures impayées"
    subtitle="Suivi des factures en statut impayé ou partiel."
>
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-4 text-sm font-semibold text-gray-700">Factures en souffrance</div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Élève</th>
                        <th class="px-5 py-3">Montant dû</th>
                        <th class="px-5 py-3">Payé</th>
                        <th class="px-5 py-3">Reste</th>
                        <th class="px-5 py-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">Paul Yao</td>
                        <td class="px-5 py-3">50 000</td>
                        <td class="px-5 py-3">0</td>
                        <td class="px-5 py-3">50 000</td>
                        <td class="px-5 py-3 text-rose-600">Impayé</td>
                    </tr>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">Awa Diabaté</td>
                        <td class="px-5 py-3">50 000</td>
                        <td class="px-5 py-3">20 000</td>
                        <td class="px-5 py-3">30 000</td>
                        <td class="px-5 py-3 text-amber-600">Partiel</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-page-shell>
