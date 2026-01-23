<x-page-shell
    title="Comptabilité · Facturation · Liste des factures"
    subtitle="Vue globale des factures générées, statuts et montants."
>
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-4 text-sm font-semibold text-gray-700">Factures récentes</div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Facture</th>
                        <th class="px-5 py-3">Élève</th>
                        <th class="px-5 py-3">Période</th>
                        <th class="px-5 py-3">Montant</th>
                        <th class="px-5 py-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">FAC-2024-001</td>
                        <td class="px-5 py-3">Alice Kouamé</td>
                        <td class="px-5 py-3">T1 2024-2025</td>
                        <td class="px-5 py-3">50 000</td>
                        <td class="px-5 py-3 text-emerald-600">Soldé</td>
                    </tr>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">FAC-2024-002</td>
                        <td class="px-5 py-3">Paul Yao</td>
                        <td class="px-5 py-3">T1 2024-2025</td>
                        <td class="px-5 py-3">50 000</td>
                        <td class="px-5 py-3 text-amber-600">Partiel</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-page-shell>
