<x-page-shell
    title="Comptabilité · Rapports · Par classe"
    subtitle="Analyse par classe avec exports PDF/Excel." 
>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">
            <select class="rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-600">
                <option>Classe : 6e A</option>
                <option>Classe : 6e B</option>
            </select>
            <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Générer</button>
            <button class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-600">Exporter PDF</button>
            <button class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-600">Exporter Excel</button>
        </div>
        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Indicateur</th>
                        <th class="px-5 py-3">Valeur</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">Total facturé</td>
                        <td class="px-5 py-3">600 000</td>
                    </tr>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">Total payé</td>
                        <td class="px-5 py-3">520 000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-page-shell>
