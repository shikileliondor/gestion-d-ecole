<x-page-shell title="Comptabilité · Reçus" subtitle="Encaissements, annulations tracées et impression des reçus.">
    <div class="flex items-center justify-between">@if($canRegisterPayments)<a href="{{ route('accounting.receipts.create') }}" class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer un paiement</a>@endif</div>
    <div class="rounded-2xl border bg-white shadow-sm">
        <table class="w-full text-sm"><thead class="bg-gray-50 text-left text-xs uppercase text-gray-500"><tr><th class="px-4 py-3">Reçu</th><th class="px-4 py-3">Facture</th><th class="px-4 py-3">Élève</th><th class="px-4 py-3">Mode</th><th class="px-4 py-3">Montant</th><th class="px-4 py-3">Statut</th><th class="px-4 py-3"></th></tr></thead><tbody>
            @forelse($receipts as $receipt)
            <tr class="border-t"><td class="px-4 py-3">{{ $receipt->numero_recu }}</td><td class="px-4 py-3">{{ $receipt->paiement?->facture?->numero_facture ?? '—' }}</td><td class="px-4 py-3">{{ $receipt->paiement?->facture?->inscription?->eleve?->nom }} {{ $receipt->paiement?->facture?->inscription?->eleve?->prenoms }}</td><td class="px-4 py-3">{{ $receipt->paiement?->mode_paiement_libre }}</td><td class="px-4 py-3">{{ number_format($receipt->montant,0,',',' ') }}</td><td class="px-4 py-3">{{ $receipt->annule_le ? 'ANNULÉ' : 'VALIDE' }}</td><td class="px-4 py-3">@if($canCancel && !$receipt->annule_le)<form method="POST" action="{{ route('accounting.receipts.cancel',$receipt) }}" class="flex gap-1">@csrf<input name="justification" required placeholder="Justification" class="rounded border px-2 py-1 text-xs"><button class="rounded bg-rose-600 px-2 py-1 text-xs text-white">Annuler</button></form>@endif</td></tr>
            @empty <tr><td colspan="7" class="px-4 py-5 text-center text-gray-500">Aucun reçu disponible.</td></tr>@endforelse
        </tbody></table>
        <div class="p-4">{{ $receipts->links() }}</div>
    </div>
</x-page-shell>
