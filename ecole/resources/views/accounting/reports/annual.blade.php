<x-page-shell
    title="Comptabilité · Rapports · Annuel"
    subtitle="Synthèse annuelle basée sur les trimestres." 
>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">
            <select class="rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-600">
                <option>Année scolaire 2024-2025</option>
                <option>Année scolaire 2023-2024</option>
            </select>
            <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Générer</button>
            <button class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-600">Exporter PDF</button>
            <button class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-600">Exporter Excel</button>
        </div>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (['Recettes', 'Dépenses', 'Solde', 'Impayés'] as $metric)
                <div class="rounded-xl bg-gray-50 p-4">
                    <p class="text-xs text-gray-500">{{ $metric }}</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900">—</p>
                </div>
            @endforeach
        </div>
    </div>
</x-page-shell>
