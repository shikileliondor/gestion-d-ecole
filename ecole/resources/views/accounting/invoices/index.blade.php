<x-page-shell title="Comptabilité · Factures" subtitle="Facturation scolaire: création, suivi des paiements et statuts.">
    <div class="flex items-center justify-between gap-3">
        <form class="flex flex-wrap gap-2" method="GET">
            <input name="search" value="{{ request('search') }}" placeholder="N° facture / élève" class="rounded border px-3 py-2 text-sm" />
            <select name="status" class="rounded border px-3 py-2 text-sm"><option value="">Statut</option>@foreach($statuses as $status)<option value="{{ $status }}" @selected(request('status')===$status)>{{ $status }}</option>@endforeach</select>
            <select name="type" class="rounded border px-3 py-2 text-sm"><option value="">Type</option>@foreach($types as $type)<option value="{{ $type }}" @selected(request('type')===$type)>{{ $type }}</option>@endforeach</select>
            <button class="rounded bg-slate-800 px-3 py-2 text-xs font-semibold text-white">Filtrer</button>
        </form>
        @if($canManageInvoices)
            <a href="{{ route('accounting.invoices.create') }}" class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Créer une facture</a>
        @endif
    </div>
    <div class="rounded-2xl border bg-white shadow-sm">
        <table class="w-full text-sm"><thead class="bg-gray-50 text-left text-xs uppercase text-gray-500"><tr><th class="px-4 py-3">Facture</th><th class="px-4 py-3">Élève</th><th class="px-4 py-3">Type</th><th class="px-4 py-3">Total</th><th class="px-4 py-3">Payé</th><th class="px-4 py-3">Reste</th><th class="px-4 py-3">Statut</th><th class="px-4 py-3"></th></tr></thead><tbody>
            @forelse($invoices as $invoice)
                @php($paid = (float)$invoice->paiements->whereNull('annule_le')->sum('montant_paye'))
                <tr class="border-t"><td class="px-4 py-3">{{ $invoice->numero_facture }}</td><td class="px-4 py-3">{{ trim(($invoice->inscription?->eleve?->nom ?? '').' '.($invoice->inscription?->eleve?->prenoms ?? '')) }}</td><td class="px-4 py-3">{{ $invoice->type_facture }}</td><td class="px-4 py-3">{{ number_format($invoice->montant_total,0,',',' ') }}</td><td class="px-4 py-3">{{ number_format($paid,0,',',' ') }}</td><td class="px-4 py-3">{{ number_format(max($invoice->montant_total-$paid,0),0,',',' ') }}</td><td class="px-4 py-3"><span class="rounded px-2 py-1 text-xs {{ $invoice->statut==='PAYEE'?'bg-emerald-100 text-emerald-700':($invoice->statut==='PARTIELLE'?'bg-amber-100 text-amber-700':($invoice->statut==='ANNULEE'?'bg-rose-100 text-rose-700':'bg-slate-100 text-slate-700')) }}">{{ $invoice->statut }}</span></td><td class="px-4 py-3"><a class="text-blue-600" href="{{ route('accounting.invoices.show',$invoice) }}">Détail</a></td></tr>
            @empty <tr><td colspan="8" class="px-4 py-6 text-center text-gray-500">Aucune facture. Commencez par créer une facture.</td></tr>@endforelse
        </tbody></table>
        <div class="p-4">{{ $invoices->links() }}</div>
    </div>
</x-page-shell>
