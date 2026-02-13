<x-page-shell title="Nouveau versement" subtitle="Encaissement total/partiel avec reçu automatique et traçabilité.">
    <form method="POST" action="{{ route('accounting.payments.store-payment') }}" class="space-y-4 rounded-2xl border bg-white p-5">@csrf
        <select name="facture_id" class="js-select2 w-full rounded border px-3 py-2" data-placeholder="Facture concernée" required>
            <option value=""></option>
            @foreach($invoices as $invoice)
                @php
                    $discounts = (float) $invoice->remises->whereNull('annule_le')->sum('montant_applique');
                    $paid = (float) $invoice->paiements->whereNull('annule_le')->sum('montant_paye');
                    $remaining = max((float) $invoice->montant_total - $discounts - $paid, 0);
                @endphp
                <option value="{{ $invoice->id }}">{{ $invoice->numero_facture }} - {{ $invoice->inscription?->eleve?->nom }} {{ $invoice->inscription?->eleve?->prenoms }} (Reste: {{ number_format($remaining,0,',',' ') }})</option>
            @endforeach
        </select>

        <div class="grid gap-3 md:grid-cols-2">
            <input type="number" name="montant_paye" min="1" step="0.01" required class="rounded border px-3 py-2" placeholder="Montant versé">
            <input type="date" name="date_paiement" value="{{ now()->toDateString() }}" required class="rounded border px-3 py-2">
        </div>

        <div class="grid gap-3 md:grid-cols-2">
            <select name="mode_paiement_libre" class="js-select2 rounded border px-3 py-2" data-placeholder="Mode de paiement" required>
                <option value=""></option><option>ESPECES</option><option>MOBILE_MONEY</option><option>CHEQUE</option><option>VIREMENT</option><option>AUTRE</option>
            </select>
            <input name="reference" class="rounded border px-3 py-2" placeholder="Référence (optionnel)">
        </div>

        <textarea name="commentaire" class="w-full rounded border px-3 py-2" rows="3" placeholder="Commentaire (optionnel)"></textarea>
        <button class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer le versement</button>
    </form>

    <script>
        $(function () {
            $('.js-select2').select2({ width: '100%', allowClear: true, placeholder: function(){ return $(this).data('placeholder') || 'Sélectionner'; } });
        });
    </script>
</x-page-shell>
