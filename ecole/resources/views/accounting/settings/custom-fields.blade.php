<x-page-shell
    title="Comptabilité · Paramètres · Champs personnalisés"
    subtitle="Ajouter des champs personnalisés aux factures ou reçus." 
>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap gap-3">
            <input type="text" class="w-full max-w-sm rounded-lg border border-gray-200 px-3 py-2" placeholder="Nom du champ" />
            <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Ajouter</button>
        </div>
        <ul class="mt-6 space-y-2 text-sm text-gray-600">
            <li class="rounded-lg bg-gray-50 px-3 py-2">Référence interne</li>
            <li class="rounded-lg bg-gray-50 px-3 py-2">Observation</li>
        </ul>
    </div>
</x-page-shell>
