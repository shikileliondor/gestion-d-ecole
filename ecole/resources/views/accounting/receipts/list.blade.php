<x-page-shell
    title="Comptabilité · Reçus · Liste"
    subtitle="Accès aux reçus émis pour les paiements enregistrés."
>
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-4 text-sm font-semibold text-gray-700">Reçus disponibles</div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Reçu</th>
                        <th class="px-5 py-3">Élève</th>
                        <th class="px-5 py-3">Date</th>
                        <th class="px-5 py-3">Montant</th>
                        <th class="px-5 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($receipts as $receipt)
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-3">{{ $receipt->numero_recu }}</td>
                            <td class="px-5 py-3">{{ trim(($receipt->paiement?->inscription?->eleve?->nom ?? '') . ' ' . ($receipt->paiement?->inscription?->eleve?->prenoms ?? '')) ?: '—' }}</td>
                            <td class="px-5 py-3">{{ $receipt->date_emission?->format('d/m/Y') ?? '—' }}</td>
                            <td class="px-5 py-3">{{ number_format((float) $receipt->montant, 0, ',', ' ') }}</td>
                            <td class="px-5 py-3">
                                <a href="{{ route('accounting.receipts.download') }}" class="rounded-lg border border-gray-200 px-3 py-1 text-xs text-gray-600">Télécharger PDF</a>
                            </td>
                        </tr>
                    @empty
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-4 text-gray-500" colspan="5">Aucun reçu disponible.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($receipts->hasPages())
            <div class="border-t border-gray-100 px-5 py-3">
                {{ $receipts->links() }}
            </div>
        @endif
    </div>
</x-page-shell>
