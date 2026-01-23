<x-page-shell
    title="Comptabilité · Facturation · Factures par classe"
    subtitle="Synthèse des factures générées par classe et période."
>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">
            <select class="w-full max-w-xs rounded-lg border border-gray-200 px-3 py-2">
                <option>Choisir une classe</option>
                <option>6e A</option>
                <option>6e B</option>
            </select>
            <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Afficher</button>
        </div>
        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Classe</th>
                        <th class="px-5 py-3">Facturé</th>
                        <th class="px-5 py-3">Payé</th>
                        <th class="px-5 py-3">Reste</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">6e A</td>
                        <td class="px-5 py-3">600 000</td>
                        <td class="px-5 py-3">520 000</td>
                        <td class="px-5 py-3">80 000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-page-shell>
