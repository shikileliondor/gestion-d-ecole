<x-page-shell
    title="Outils · Dossiers élèves"
    subtitle="Consulter l'historique complet des dossiers élèves."
>
    <div class="space-y-6">
        @forelse ($dossiers as $dossier)
            @php
                $eleve = $dossier->eleve;
                $history = $journalEntries->get($dossier->id, collect());
            @endphp

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">
                            {{ $eleve?->prenoms }} {{ $eleve?->nom }}
                        </h3>
                        <p class="text-sm text-slate-500">
                            Matricule : {{ $eleve?->matricule ?? '—' }}
                        </p>
                    </div>
                    <div class="text-sm text-slate-600">
                        <div>Statut : <span class="font-semibold text-slate-900">{{ $dossier->statut }}</span></div>
                        <div>Ouvert le : {{ optional($dossier->date_ouverture)->format('d/m/Y') ?? '—' }}</div>
                        <div>Dernière réouverture : {{ optional($dossier->date_derniere_reouverture)->format('d/m/Y') ?? '—' }}</div>
                    </div>
                    <div class="rounded-xl bg-slate-50 px-4 py-2 text-sm text-slate-600">
                        <span class="font-semibold text-slate-900">{{ $dossier->inscriptions->count() }}</span> inscription(s)
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Journal détaillé</h4>
                    @if ($history->isEmpty())
                        <p class="mt-2 text-sm text-slate-500">Aucune action enregistrée pour ce dossier.</p>
                    @else
                        <div class="mt-3 space-y-3">
                            @foreach ($history as $entry)
                                @php
                                    $details = $entry->nouvelles_valeurs ?? [];
                                @endphp
                                <div class="rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <span class="font-semibold text-slate-900">{{ $entry->action }}</span>
                                        <span class="text-xs text-slate-500">{{ $entry->created_at?->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="mt-2 grid gap-1 text-xs text-slate-500">
                                        <span>Inscription : {{ $details['inscription_id'] ?? '—' }}</span>
                                        <span>Année scolaire : {{ $details['annee_scolaire_id'] ?? '—' }}</span>
                                        <span>Classe : {{ $details['classe_id'] ?? '—' }}</span>
                                        <span>Date d'inscription : {{ $details['date_inscription'] ?? '—' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-10 text-center text-sm text-slate-500">
                Aucun dossier élève n'a été créé pour le moment.
            </div>
        @endforelse
    </div>
</x-page-shell>
