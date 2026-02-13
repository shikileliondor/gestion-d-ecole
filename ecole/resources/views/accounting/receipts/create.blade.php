<x-page-shell title="Comptabilité · Enregistrer un paiement" subtitle="Associer un encaissement à une facture et générer un reçu unique.">
    <form method="POST" action="{{ route('accounting.receipts.store') }}" class="space-y-4 rounded-2xl border bg-white p-5">@csrf
        <select name="facture_id" class="w-full rounded border px-3 py-2" required><option value="">Facture concernée</option>@foreach($invoices as $invoice)<option value="{{ $invoice->id }}">{{ $invoice->numero_facture }} - {{ $invoice->inscription?->eleve?->nom }} {{ $invoice->inscription?->eleve?->prenoms }} ({{ number_format($invoice->montant_total,0,',',' ') }})</option>@endforeach</select>
        <div class="grid gap-3 md:grid-cols-2"><input type="number" name="montant_paye" min="1" step="0.01" required class="rounded border px-3 py-2" placeholder="Montant payé"><input type="date" name="date_paiement" value="{{ now()->toDateString() }}" required class="rounded border px-3 py-2"></div>
        <div class="grid gap-3 md:grid-cols-2"><select name="mode_paiement_libre" class="rounded border px-3 py-2" required><option>ESPECES</option><option>MOBILE_MONEY</option><option>CHEQUE</option><option>VIREMENT</option><option>AUTRE</option></select><input name="reference" class="rounded border px-3 py-2" placeholder="Référence (facultative)"></div>
        <button class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Générer le reçu</button>
    </form>
</x-page-shell>
