<x-page-shell
    title="Comptabilité · Tableau de bord"
    subtitle="Résumé global des finances, évolution mensuelle et alertes par classe."
>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach (['Recettes', 'Dépenses', 'Solde', 'Impayés'] as $metric)
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-sm text-gray-500">{{ $metric }}</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">—</p>
                <p class="mt-1 text-xs text-gray-400">Mis à jour automatiquement</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Graphiques mensuels</h3>
                <span class="text-xs text-gray-400">Mensuel / Trimestriel / Annuel</span>
            </div>
            <div class="mt-4 flex h-64 items-center justify-center rounded-xl border border-dashed border-gray-200 text-sm text-gray-400">
                Zone graphique (recettes, dépenses, impayés)
            </div>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Alertes impayés</h3>
            <ul class="mt-4 space-y-3 text-sm text-gray-600">
                <li class="flex items-center justify-between rounded-lg bg-amber-50 px-3 py-2">
                    <span>Classe 3e A</span>
                    <span class="font-semibold text-amber-700">8 impayés</span>
                </li>
                <li class="flex items-center justify-between rounded-lg bg-amber-50 px-3 py-2">
                    <span>Classe 6e B</span>
                    <span class="font-semibold text-amber-700">5 impayés</span>
                </li>
                <li class="flex items-center justify-between rounded-lg bg-amber-50 px-3 py-2">
                    <span>Classe Terminale C</span>
                    <span class="font-semibold text-amber-700">4 impayés</span>
                </li>
            </ul>
        </div>
    </div>
</x-page-shell>
