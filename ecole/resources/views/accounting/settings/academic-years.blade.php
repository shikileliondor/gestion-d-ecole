<x-page-shell
    title="Comptabilité · Paramètres · Années scolaires"
    subtitle="Configuration des années scolaires et trimestres." 
>
    <div class="grid gap-6 lg:grid-cols-2">
        <form class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Nouvelle année scolaire</h3>
            <div class="mt-4 grid gap-4">
                <input type="text" class="w-full rounded-lg border border-gray-200 px-3 py-2" placeholder="2024-2025" />
                <textarea class="w-full rounded-lg border border-gray-200 px-3 py-2" rows="3" placeholder="Définir les trimestres"></textarea>
            </div>
            <button class="mt-4 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Ajouter</button>
        </form>
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Années configurées</h3>
            <ul class="mt-4 space-y-3 text-sm text-gray-600">
                <li class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2">
                    <span>2024-2025</span>
                    <span class="text-xs text-emerald-600">Active</span>
                </li>
                <li class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2">
                    <span>2023-2024</span>
                    <span class="text-xs text-gray-400">Archivée</span>
                </li>
            </ul>
        </div>
    </div>
</x-page-shell>
