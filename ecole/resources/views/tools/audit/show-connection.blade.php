<x-page-shell title="Détail connexion" subtitle="Traçabilité de la session de connexion">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-700 space-y-2">
        <div><strong>Date :</strong> {{ $connection->date_connexion?->format('d/m/Y H:i:s') }}</div>
        <div><strong>Email tenté :</strong> {{ $connection->email_tente ?? '—' }}</div>
        <div><strong>Utilisateur :</strong> {{ $connection->user?->name ?? 'Inconnu' }}</div>
        <div><strong>Statut :</strong> {{ $connection->statut }}</div>
        <div><strong>Origine :</strong> {{ $connection->origine }}</div>
        <div><strong>Session ID :</strong> {{ $connection->session_id ?? '—' }}</div>
        <div><strong>IP :</strong> {{ $connection->ip_adresse ?? '—' }}</div>
        <div><strong>Navigateur :</strong> {{ $connection->user_agent ?? '—' }}</div>
    </div>
</x-page-shell>
