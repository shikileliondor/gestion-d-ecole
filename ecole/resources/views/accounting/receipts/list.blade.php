<x-page-shell
    title="Comptabilité · Reçus · Liste"
    subtitle="Accès aux reçus émis pour les paiements enregistrés."
>
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-4 text-sm font-semibold text-gray-700">Reçus disponibles</div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Reçu</th>
                        <th class="px-5 py-3">Élève</th>
                        <th class="px-5 py-3">Montant</th>
                        <th class="px-5 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">REC-2024-001</td>
                        <td class="px-5 py-3">Alice Kouamé</td>
                        <td class="px-5 py-3">50 000</td>
                        <td class="px-5 py-3">
                            <button class="rounded-lg border border-gray-200 px-3 py-1 text-xs text-gray-600">Télécharger PDF</button>
                        </td>
                    </tr>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">REC-2024-002</td>
                        <td class="px-5 py-3">Paul Yao</td>
                        <td class="px-5 py-3">20 000</td>
                        <td class="px-5 py-3">
                            <button class="rounded-lg border border-gray-200 px-3 py-1 text-xs text-gray-600">Télécharger PDF</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-page-shell>
