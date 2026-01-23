<x-page-shell
    title="Comptabilité · Rapports · Mensuel"
    subtitle="Rapport mensuel des recettes, dépenses et impayés." 
>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">
            <input type="month" class="rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-600" />
            <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Générer</button>
            <button class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-600">Exporter PDF</button>
            <button class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-600">Exporter Excel</button>
        </div>
        <div class="mt-6 h-64 rounded-xl border border-dashed border-gray-200 text-sm text-gray-400 flex items-center justify-center">
            Graphique mensuel
        </div>
    </div>
</x-page-shell>
