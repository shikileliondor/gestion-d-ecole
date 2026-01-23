<x-page-shell
    title="Comptabilité · Reçus · Télécharger PDF"
    subtitle="Télécharger en lot les reçus d'une période ou d'une classe."
>
    <form class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="grid gap-4 md:grid-cols-2">
            <label class="text-sm text-gray-600">
                Période
                <select class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2">
                    <option>Septembre 2024</option>
                    <option>T1 2024-2025</option>
                </select>
            </label>
            <label class="text-sm text-gray-600">
                Classe
                <select class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2">
                    <option>6e A</option>
                    <option>6e B</option>
                </select>
            </label>
        </div>
        <button type="submit" class="mt-4 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Télécharger</button>
    </form>
</x-page-shell>
