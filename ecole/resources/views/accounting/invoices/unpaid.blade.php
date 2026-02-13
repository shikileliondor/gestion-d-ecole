<x-page-shell
    title="Comptabilité · Facturation · Factures impayées"
    subtitle="Suivi des factures en statut impayé ou partiel."
>
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-4 text-sm font-semibold text-gray-700">Factures en souffrance</div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Élève</th>
                        <th class="px-5 py-3">Montant dû</th>
                        <th class="px-5 py-3">Payé</th>
                        <th class="px-5 py-3">Reste</th>
                        <th class="px-5 py-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($unpaidInvoices as $invoice)
                        @php
                            $paid = min((float) $invoice->montant_total, (float) $invoice->total_paye_inscription);
                            $remaining = max(0, (float) $invoice->montant_total - $paid);
                        @endphp
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-3">{{ trim(($invoice->inscription?->eleve?->nom ?? '') . ' ' . ($invoice->inscription?->eleve?->prenoms ?? '')) ?: '—' }}</td>
                            <td class="px-5 py-3">{{ number_format((float) $invoice->montant_total, 0, ',', ' ') }}</td>
                            <td class="px-5 py-3">{{ number_format($paid, 0, ',', ' ') }}</td>
                            <td class="px-5 py-3">{{ number_format($remaining, 0, ',', ' ') }}</td>
                            <td class="px-5 py-3 {{ $statusClasses[$invoice->statut] ?? 'text-slate-600' }}">
                                {{ $statusLabels[$invoice->statut] ?? $invoice->statut }}
                            </td>
                        </tr>
                    @empty
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-4 text-gray-500" colspan="5">Aucune facture impayée ou en paiement partiel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($unpaidInvoices->hasPages())
            <div class="border-t border-gray-100 px-5 py-3">
                {{ $unpaidInvoices->links() }}
            </div>
        @endif
    </div>
</x-page-shell>
