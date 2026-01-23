<x-page-shell
    title="Comptabilité · Reçus · Numérotation automatique"
    subtitle="Définir le format des numéros de reçus (ex : REC-2024-0001)."
>
    <form class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <label class="text-sm text-gray-600">
            Préfixe
            <input type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" value="REC-" />
        </label>
        <label class="mt-4 block text-sm text-gray-600">
            Prochain numéro
            <input type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" value="2024-0001" />
        </label>
        <button type="submit" class="mt-4 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Mettre à jour</button>
    </form>
</x-page-shell>
