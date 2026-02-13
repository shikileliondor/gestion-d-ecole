<x-page-shell title="Comptabilité · Journal" subtitle="Journal d'audit non modifiable des opérations financières.">
    <div class="rounded-2xl border bg-white shadow-sm">
        <table class="w-full text-sm"><thead class="bg-gray-50 text-left text-xs uppercase text-gray-500"><tr><th class="px-4 py-3">Date/heure</th><th class="px-4 py-3">Opération</th><th class="px-4 py-3">Table</th><th class="px-4 py-3">Référence</th><th class="px-4 py-3">Utilisateur</th><th class="px-4 py-3">Commentaire</th></tr></thead><tbody>
            @forelse($entries as $entry)
            <tr class="border-t"><td class="px-4 py-3">{{ $entry->created_at?->format('d/m/Y H:i') }}</td><td class="px-4 py-3">{{ $entry->action }}</td><td class="px-4 py-3">{{ $entry->table_cible }}</td><td class="px-4 py-3">#{{ $entry->enregistrement_id }}</td><td class="px-4 py-3">{{ $entry->user?->name }}</td><td class="px-4 py-3">{{ data_get($entry->nouvelles_valeurs,'justification','—') }}</td></tr>
            @empty <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucune opération financière enregistrée.</td></tr>@endforelse
        </tbody></table>
        <div class="p-4">{{ $entries->links() }}</div>
    </div>
</x-page-shell>
