<x-page-shell
    title="Comptabilité · Facturation · Factures par élève"
    subtitle="Historique des factures et paiements par élève."
>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">
            <input type="text" class="w-full max-w-sm rounded-lg border border-gray-200 px-3 py-2" placeholder="Rechercher un élève" />
            <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Filtrer</button>
        </div>
        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Élève</th>
                        <th class="px-5 py-3">Factures</th>
                        <th class="px-5 py-3">Payé</th>
                        <th class="px-5 py-3">Reste</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">Alice Kouamé</td>
                        <td class="px-5 py-3">3</td>
                        <td class="px-5 py-3">150 000</td>
                        <td class="px-5 py-3">0</td>
                    </tr>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">Paul Yao</td>
                        <td class="px-5 py-3">3</td>
                        <td class="px-5 py-3">100 000</td>
                        <td class="px-5 py-3">50 000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-page-shell>
