<x-page-shell title="Comptabilité · Rapports financiers" subtitle="Recettes, impayés, répartition et top débiteurs.">
    <div class="grid gap-4 md:grid-cols-4">@foreach(['Recettes'=>'recettes','Total impayés'=>'impayes','Factures partiellement payées'=>'factures_partielles','Factures impayées'=>'factures_impayees'] as $label=>$key)<div class="rounded-xl border bg-white p-4"><p class="text-xs text-gray-500">{{ $label }}</p><p class="text-xl font-semibold">{{ number_format($metrics[$key] ?? 0,0,',',' ') }}</p></div>@endforeach</div>
    <div class="grid gap-5 lg:grid-cols-2">
        <div class="rounded-2xl border bg-white p-4"><h3 class="font-semibold">Recettes par type</h3><ul class="mt-3 text-sm">@forelse($byType as $type=>$amount)<li class="flex justify-between border-b py-1"><span>{{ $type }}</span><span>{{ number_format($amount,0,',',' ') }}</span></li>@empty <li class="text-gray-500">Aucune donnée</li>@endforelse</ul></div>
        <div class="rounded-2xl border bg-white p-4"><h3 class="font-semibold">Modes de paiement</h3><ul class="mt-3 text-sm">@forelse($paymentModes as $mode=>$amount)<li class="flex justify-between border-b py-1"><span>{{ $mode ?: 'NON RENSEIGNÉ' }}</span><span>{{ number_format($amount,0,',',' ') }}</span></li>@empty <li class="text-gray-500">Aucune donnée</li>@endforelse</ul></div>
    </div>
</x-page-shell>
