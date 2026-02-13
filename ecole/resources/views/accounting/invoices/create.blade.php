<x-page-shell title="Comptabilité · Créer une facture" subtitle="Saisir les lignes de facturation sans ressaisie inutile.">
    <form method="POST" action="{{ route('accounting.invoices.store') }}" class="space-y-4 rounded-2xl border bg-white p-5">@csrf
        <div class="grid gap-3 md:grid-cols-3">
            <select name="inscription_id" class="rounded border px-3 py-2" required><option value="">Élève inscrit</option>@foreach($enrollments as $e)<option value="{{ $e->id }}">{{ $e->eleve?->nom }} {{ $e->eleve?->prenoms }} - {{ $e->classe?->nom }}</option>@endforeach</select>
            <select name="profil_facturable" class="rounded border px-3 py-2" required><option value="ELEVE">Élève</option><option value="PARENT">Parent</option><option value="TIERS">Tiers</option></select>
            <select name="type_facture" class="rounded border px-3 py-2" required><option>SCOLARITE</option><option>INSCRIPTION</option><option>CANTINE</option><option>TRANSPORT</option><option>UNIFORME</option><option>AUTRE</option></select>
        </div>
        <input type="date" name="date_emission" class="rounded border px-3 py-2" value="{{ now()->toDateString() }}" required>
        <div class="space-y-3">
            @for($i=0;$i<3;$i++)
            <div class="grid gap-2 md:grid-cols-5">
                <input name="lignes[{{ $i }}][libelle]" class="rounded border px-2 py-2" placeholder="Libellé" {{ $i===0?'required':'' }}>
                <select name="lignes[{{ $i }}][type_frais_id]" class="rounded border px-2 py-2"><option value="">Type</option>@foreach($feeTypes as $t)<option value="{{ $t->id }}">{{ $t->libelle }}</option>@endforeach</select>
                <input type="number" min="1" step="1" name="lignes[{{ $i }}][quantite]" value="1" class="rounded border px-2 py-2" {{ $i===0?'required':'' }}>
                <input type="number" min="0" step="0.01" name="lignes[{{ $i }}][prix_unitaire]" class="rounded border px-2 py-2" placeholder="Prix" {{ $i===0?'required':'' }}>
                <input type="number" min="0" step="0.01" name="lignes[{{ $i }}][remise]" value="0" class="rounded border px-2 py-2" placeholder="Remise">
            </div>
            @endfor
        </div>
        <button class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Valider la facture</button>
    </form>
</x-page-shell>
