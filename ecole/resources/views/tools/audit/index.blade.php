<x-page-shell title="Outils · Journal & Audit" subtitle="Connexions (succès/échec) et actions sensibles non modifiables.">
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="GET" class="grid gap-4 md:grid-cols-3 xl:grid-cols-6">
            <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="rounded-xl border-slate-300">
            <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="rounded-xl border-slate-300">
            <select name="type" class="rounded-xl border-slate-300">
                <option value="">Tous types</option>
                <option value="connexions" @selected(($filters['type'] ?? '') === 'connexions')>Connexions</option>
                <option value="actions" @selected(($filters['type'] ?? '') === 'actions')>Actions sensibles</option>
            </select>
            <select name="user_id" class="rounded-xl border-slate-300">
                <option value="">Tous utilisateurs</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected((string)($filters['user_id'] ?? '') === (string)$user->id)>{{ $user->name }}</option>
                @endforeach
            </select>
            <input type="text" name="module" value="{{ $filters['module'] ?? '' }}" placeholder="Module" class="rounded-xl border-slate-300">
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nom, action, référence" class="rounded-xl border-slate-300">
            <button class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Filtrer</button>
        </form>
    </section>

    @if($logs instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-slate-900">Actions sensibles</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="text-left text-slate-500"><th>Date</th><th>Utilisateur</th><th>Action</th><th>Module</th><th>Référence</th><th>Résultat</th></tr></thead>
                    <tbody>
                    @foreach($logs as $log)
                        <tr class="border-t border-slate-100 text-slate-700">
                            <td class="py-2">{{ $log->created_at?->format('d/m/Y H:i') }}</td>
                            <td>{{ $log->user?->name ?? 'Inconnu' }}</td>
                            <td><a class="text-blue-600" href="{{ route('tools.audit.actions.show', $log) }}">{{ $log->action }}</a></td>
                            <td>{{ $log->table_cible }}</td>
                            <td>{{ $log->enregistrement_id }}</td>
                            <td>{{ $log->nouvelles_valeurs['resultat'] ?? 'SUCCES' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $logs->links() }}</div>
        </section>
    @endif

    @if($connectionLogs instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-slate-900">Connexions</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="text-left text-slate-500"><th>Date</th><th>Email tenté</th><th>Utilisateur</th><th>Statut</th><th>Origine</th><th>IP</th><th>Détail</th></tr></thead>
                    <tbody>
                    @foreach($connectionLogs as $log)
                        <tr class="border-t border-slate-100 text-slate-700">
                            <td class="py-2">{{ $log->date_connexion?->format('d/m/Y H:i') }}</td>
                            <td>{{ $log->email_tente ?? '—' }}</td>
                            <td>{{ $log->user?->name ?? 'Inconnu' }}</td>
                            <td>{{ $log->statut }}</td>
                            <td>{{ $log->origine }}</td>
                            <td>{{ $log->ip_adresse }}</td>
                            <td><a href="{{ route('tools.audit.connections.show', $log) }}" class="text-blue-600">Voir</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $connectionLogs->links() }}</div>
        </section>
    @endif
</x-page-shell>
