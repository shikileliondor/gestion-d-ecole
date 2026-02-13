<x-page-shell title="Nouvelle remise" subtitle="Appliquer une réduction traçable sur une facture.">
    <form method="POST" action="{{ route('accounting.payments.store-discount') }}" class="space-y-4 rounded-2xl border bg-white p-5">@csrf
        <select name="facture_id" class="js-select2 w-full rounded border px-3 py-2" data-placeholder="Bénéficiaire / Facture" required>
            <option value=""></option>
            @foreach($invoices as $invoice)
                <option value="{{ $invoice->id }}">{{ $invoice->numero_facture }} - {{ $invoice->inscription?->eleve?->nom }} {{ $invoice->inscription?->eleve?->prenoms }}</option>
            @endforeach
        </select>

        <div class="grid gap-3 md:grid-cols-2">
            <select name="periode_id" class="js-select2 rounded border px-3 py-2" data-placeholder="Période (optionnel)">
                <option value=""></option>
                @foreach($periods as $period)
                    <option value="{{ $period->id }}">{{ $period->libelle }}</option>
                @endforeach
            </select>
            <select name="type_remise" class="js-select2 rounded border px-3 py-2" data-placeholder="Type de remise" required>
                <option value=""></option><option value="BOURSE">Bourse</option><option value="REDUCTION">Réduction sociale / promotion / exonération</option><option value="GESTE">Geste commercial</option>
            </select>
        </div>

        <div class="grid gap-3 md:grid-cols-2">
            <select name="type_calcul" class="js-select2 rounded border px-3 py-2" data-placeholder="Calcul" required><option value="MONTANT">Montant fixe</option><option value="POURCENTAGE">Pourcentage</option></select>
            <input type="number" name="valeur" min="0.01" step="0.01" required class="rounded border px-3 py-2" placeholder="Valeur (montant ou %)">
        </div>

        <input name="motif" class="w-full rounded border px-3 py-2" required placeholder="Motif détaillé">
        <textarea name="description" class="w-full rounded border px-3 py-2" rows="3" placeholder="Justification/description"></textarea>
        <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="validation_admin" value="1"> Exiger une validation admin avant application finale</label>

        <button class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Appliquer la remise</button>
    </form>

    <script>
        $(function () {
            $('.js-select2').select2({ width: '100%', allowClear: true, placeholder: function(){ return $(this).data('placeholder') || 'Sélectionner'; } });
        });
    </script>
</x-page-shell>
