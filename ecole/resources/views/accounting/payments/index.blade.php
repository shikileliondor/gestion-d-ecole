<x-page-shell title="Comptabilité · Paiements" subtitle="Suivi global des versements et remises par élève, classe et période.">
    @if (session('status'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif

    <form method="GET" class="mb-4 grid gap-3 rounded-2xl border bg-white p-4 md:grid-cols-4">
        <select name="annee_scolaire_id" class="js-select2 w-full rounded border px-3 py-2" data-placeholder="Année scolaire">
            <option value=""></option>
            @foreach($academicYears as $year)
                <option value="{{ $year->id }}" @selected(($filters['annee_scolaire_id'] ?? null) == $year->id)>{{ $year->libelle }}</option>
            @endforeach
        </select>
        <select name="periode_id" class="js-select2 w-full rounded border px-3 py-2" data-placeholder="Période">
            <option value=""></option>
            @foreach($periods as $period)
                <option value="{{ $period->id }}" @selected(($filters['periode_id'] ?? null) == $period->id)>{{ $period->libelle }}</option>
            @endforeach
        </select>
        <select name="classe_id" class="js-select2 w-full rounded border px-3 py-2" data-placeholder="Classe / niveau">
            <option value=""></option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected(($filters['classe_id'] ?? null) == $class->id)>{{ $class->nom }}</option>
            @endforeach
        </select>
        <select name="inscription_id[]" multiple class="js-select2 w-full rounded border px-3 py-2" data-placeholder="Élève / parent">
            @foreach($inscriptions as $enrollment)
                <option value="{{ $enrollment->id }}" @selected(collect($filters['inscription_id'] ?? [])->contains($enrollment->id))>{{ $enrollment->eleve?->nom }} {{ $enrollment->eleve?->prenoms }}</option>
            @endforeach
        </select>
        <select name="operation_type" class="js-select2 w-full rounded border px-3 py-2" data-placeholder="Type d'opération">
            <option value=""></option>
            <option value="VERSEMENT" @selected(($filters['operation_type'] ?? null) === 'VERSEMENT')>Versement</option>
            <option value="REMISE" @selected(($filters['operation_type'] ?? null) === 'REMISE')>Remise</option>
        </select>
        <select name="payment_mode" class="js-select2 w-full rounded border px-3 py-2" data-placeholder="Mode de paiement">
            <option value=""></option>
            @foreach($paymentModes as $mode)
                <option value="{{ $mode }}" @selected(($filters['payment_mode'] ?? null) === $mode)>{{ $mode }}</option>
            @endforeach
        </select>
        <select name="status" class="js-select2 w-full rounded border px-3 py-2" data-placeholder="Statut global">
            <option value=""></option>
            @foreach(['PAYEE' => 'Payé', 'PARTIELLE' => 'Partiel', 'EMISE' => 'Impayé', 'EN_RETARD' => 'En retard'] as $value => $label)
                <option value="{{ $value }}" @selected(($filters['status'] ?? null) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <div class="flex items-center gap-2">
            <button class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Filtrer</button>
            <a href="{{ route('accounting.payments.index') }}" class="rounded border px-4 py-2 text-sm">Effacer</a>
        </div>
    </form>

    <div class="mb-4 flex flex-wrap gap-2">
        @if($canRegisterPayments)
            <a href="{{ route('accounting.payments.create-payment') }}" class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer un versement</a>
        @endif
        @if($canManageDiscounts)
            <a href="{{ route('accounting.payments.create-discount') }}" class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Appliquer une remise</a>
        @endif
    </div>

    <div class="rounded-2xl border bg-white shadow-sm overflow-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Élève / Parent</th><th class="px-4 py-3">Classe</th><th class="px-4 py-3">Facturé</th><th class="px-4 py-3">Remises</th><th class="px-4 py-3">Versé</th><th class="px-4 py-3">Reste</th><th class="px-4 py-3">Statut</th><th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $row['invoice']->inscription?->eleve?->nom }} {{ $row['invoice']->inscription?->eleve?->prenoms }}</td>
                        <td class="px-4 py-3">{{ $row['invoice']->inscription?->classe?->nom ?? '—' }}</td>
                        <td class="px-4 py-3">{{ number_format($row['invoice_amount'], 0, ',', ' ') }}</td>
                        <td class="px-4 py-3">{{ number_format($row['discount_total'], 0, ',', ' ') }}</td>
                        <td class="px-4 py-3">{{ number_format($row['paid_total'], 0, ',', ' ') }}</td>
                        <td class="px-4 py-3">{{ number_format($row['remaining'], 0, ',', ' ') }}</td>
                        <td class="px-4 py-3">{{ str_replace('_', ' ', $row['status']) }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('accounting.invoices.show', $row['invoice']) }}" class="text-blue-600 hover:underline">Voir détail historique</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-5 text-center text-gray-500">Aucune donnée de paiement.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $invoices->links() }}</div>
    </div>

    <script>
        $(function () {
            $('.js-select2').select2({
                width: '100%',
                allowClear: true,
                placeholder: function () { return $(this).data('placeholder') || 'Sélectionner'; }
            });
        });
    </script>
</x-page-shell>
