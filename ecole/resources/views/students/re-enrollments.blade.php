<x-page-shell
    title="Élèves · Réinscriptions"
    subtitle="Renouveler une inscription sur une nouvelle année scolaire."
>
    <form class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="grid gap-4 md:grid-cols-2">
            <label class="text-sm text-gray-600">
                Élève
                <input type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" placeholder="Nom et prénom" />
            </label>
            <label class="text-sm text-gray-600">
                Nouvelle année scolaire
                <select class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2">
                    <option>2024-2025</option>
                    <option>2023-2024</option>
                </select>
            </label>
            <label class="text-sm text-gray-600">
                Nouvelle classe
                <input type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" placeholder="Classe" />
            </label>
            <label class="text-sm text-gray-600">
                Date de réinscription
                <input type="date" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
            </label>
        </div>
        <button type="submit" class="mt-4 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Valider</button>
    </form>
</x-page-shell>
