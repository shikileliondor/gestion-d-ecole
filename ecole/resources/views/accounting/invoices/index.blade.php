<x-page-shell
    title="Comptabilité · Facturation · Liste des factures"
    subtitle="Vue globale des factures générées, statuts et montants."
>
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-4 text-sm font-semibold text-gray-700">Factures récentes</div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Facture</th>
                        <th class="px-5 py-3">Élève</th>
                        <th class="px-5 py-3">Classe</th>
                        <th class="px-5 py-3">Date d'émission</th>
                        <th class="px-5 py-3">Montant</th>
                        <th class="px-5 py-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-3">{{ $invoice->numero_facture }}</td>
                            <td class="px-5 py-3">{{ trim(($invoice->inscription?->eleve?->nom ?? '') . ' ' . ($invoice->inscription?->eleve?->prenoms ?? '')) ?: '—' }}</td>
                            <td class="px-5 py-3">{{ $invoice->inscription?->classe?->nom ?? '—' }}</td>
                            <td class="px-5 py-3">{{ $invoice->date_emission?->format('d/m/Y') ?? '—' }}</td>
                            <td class="px-5 py-3">{{ number_format((float) $invoice->montant_total, 0, ',', ' ') }}</td>
                            <td class="px-5 py-3 {{ $statusClasses[$invoice->statut] ?? 'text-slate-600' }}">
                                {{ $statusLabels[$invoice->statut] ?? $invoice->statut }}
                            </td>
                        </tr>
                    @empty
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-4 text-gray-500" colspan="6">Aucune facture disponible.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($invoices->hasPages())
            <div class="border-t border-gray-100 px-5 py-3">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</x-page-shell>
