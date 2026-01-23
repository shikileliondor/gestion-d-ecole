<x-page-shell
    title="Comptabilité · Paramètres · Modes de paiement"
    subtitle="Configurer les modes de paiement autorisés." 
>
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Modes actifs</h3>
            <ul class="mt-4 space-y-2 text-sm text-gray-600">
                <li class="rounded-lg bg-gray-50 px-3 py-2">Espèces</li>
                <li class="rounded-lg bg-gray-50 px-3 py-2">Mobile Money</li>
                <li class="rounded-lg bg-gray-50 px-3 py-2">Virement bancaire</li>
            </ul>
        </div>
        <form class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Ajouter un mode</h3>
            <input type="text" class="mt-4 w-full rounded-lg border border-gray-200 px-3 py-2" placeholder="Nouveau mode" />
            <button class="mt-4 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Ajouter</button>
        </form>
    </div>
</x-page-shell>
