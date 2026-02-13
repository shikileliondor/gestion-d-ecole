<x-page-shell title="Détail log d'action" :subtitle="$action->action">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-700 space-y-2">
        <div><strong>Date :</strong> {{ $action->created_at?->format('d/m/Y H:i:s') }}</div>
        <div><strong>Utilisateur :</strong> {{ $action->user?->name ?? 'Inconnu' }}</div>
        <div><strong>Module :</strong> {{ $action->table_cible }}</div>
        <div><strong>Référence :</strong> {{ $action->enregistrement_id }}</div>
        <div><strong>Résultat :</strong> {{ $action->nouvelles_valeurs['resultat'] ?? 'SUCCES' }}</div>
        <div><strong>Commentaire :</strong> {{ $action->nouvelles_valeurs['commentaire'] ?? '—' }}</div>
        <div><strong>IP :</strong> {{ $action->ip_adresse ?? '—' }}</div>
        <div><strong>User-Agent :</strong> {{ $action->user_agent ?? '—' }}</div>
    </div>
</x-page-shell>
