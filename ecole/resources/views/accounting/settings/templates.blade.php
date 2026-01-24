<x-page-shell
    title="Comptabilité · Paramètres · Modèles reçus / factures"
    subtitle="Personnaliser logo, signature et cachet sur les documents." 
>
    <form class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="grid gap-4 md:grid-cols-2">
            <label class="text-sm text-gray-600">
                Logo
                <input type="file" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
            </label>
            <label class="text-sm text-gray-600">
                Signature
                <input type="file" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
            </label>
            <label class="text-sm text-gray-600">
                Cachet
                <input type="file" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
            </label>
            <label class="text-sm text-gray-600">
                Préfixe facture
                <input type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" value="{{ $schoolSettings?->facture_prefix }}" />
            </label>
        </div>
        <button type="submit" class="mt-4 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
    </form>
</x-page-shell>
