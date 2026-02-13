<?php

namespace App\Http\Controllers\Accounting;

use App\Models\Facture;
use App\Models\Inscription;
use App\Models\TypeFrais;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvoiceController extends AccountingController
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'search' => ['nullable', 'string'],
        ]);

        $invoices = Facture::query()
            ->with(['inscription.eleve', 'inscription.classe', 'lignes', 'paiements'])
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('statut', $v))
            ->when($filters['type'] ?? null, fn ($q, $v) => $q->where('type_facture', $v))
            ->when($filters['from'] ?? null, fn ($q, $v) => $q->whereDate('date_emission', '>=', $v))
            ->when($filters['to'] ?? null, fn ($q, $v) => $q->whereDate('date_emission', '<=', $v))
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where('numero_facture', 'like', "%{$search}%")
                    ->orWhereHas('inscription.eleve', fn ($sq) => $sq
                        ->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenoms', 'like', "%{$search}%"));
            })
            ->latest('date_emission')
            ->paginate(20)
            ->withQueryString();

        return view('accounting.invoices.index', [
            'invoices' => $invoices,
            'statuses' => ['EMISE', 'PARTIELLE', 'PAYEE', 'ANNULEE'],
            'types' => ['SCOLARITE', 'INSCRIPTION', 'CANTINE', 'TRANSPORT', 'UNIFORME', 'AUTRE'],
            'canManageInvoices' => $this->canManageInvoices($request),
            'canCancel' => $this->canCancel($request),
        ]);
    }

    public function create(Request $request): View
    {
        $this->denyUnless($this->canManageInvoices($request));

        return view('accounting.invoices.create', [
            'enrollments' => Inscription::query()->with('eleve', 'classe')->latest('id')->limit(150)->get(),
            'feeTypes' => TypeFrais::query()->where('actif', true)->orderBy('libelle')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->denyUnless($this->canManageInvoices($request));

        $data = $request->validate([
            'inscription_id' => ['required', 'exists:inscriptions,id'],
            'profil_facturable' => ['required', 'in:ELEVE,PARENT,TIERS'],
            'type_facture' => ['required', 'in:SCOLARITE,INSCRIPTION,CANTINE,TRANSPORT,UNIFORME,AUTRE'],
            'date_emission' => ['required', 'date'],
            'commentaire' => ['nullable', 'string'],
            'lignes' => ['required', 'array', 'min:1'],
            'lignes.*.type_frais_id' => ['nullable', 'exists:types_frais,id'],
            'lignes.*.libelle' => ['required', 'string', 'max:120'],
            'lignes.*.quantite' => ['required', 'numeric', 'min:1'],
            'lignes.*.prix_unitaire' => ['required', 'numeric', 'min:0'],
            'lignes.*.remise' => ['nullable', 'numeric', 'min:0'],
        ]);

        $invoice = DB::transaction(function () use ($data, $request) {
            $invoice = Facture::create([
                'numero_facture' => 'FAC-'.now()->format('YmdHis').'-'.random_int(10, 99),
                'inscription_id' => $data['inscription_id'],
                'profil_facturable' => $data['profil_facturable'],
                'type_facture' => $data['type_facture'],
                'date_emission' => $data['date_emission'],
                'statut' => 'EMISE',
                'commentaire' => $data['commentaire'] ?? null,
                'montant_total' => 0,
            ]);

            $total = 0;
            foreach ($data['lignes'] as $line) {
                $subtotal = ($line['quantite'] * $line['prix_unitaire']) - ($line['remise'] ?? 0);
                $total += max($subtotal, 0);

                $invoice->lignes()->create([
                    'type_frais_id' => $line['type_frais_id'] ?? null,
                    'libelle' => $line['libelle'],
                    'quantite' => $line['quantite'],
                    'prix_unitaire' => $line['prix_unitaire'],
                    'remise' => $line['remise'] ?? 0,
                    'montant' => max($subtotal, 0),
                ]);
            }

            $invoice->update(['montant_total' => $total]);
            $this->logAction($request, 'FACTURE_CREEE', 'factures', $invoice->id, null, $invoice->toArray());

            return $invoice;
        });

        return redirect()->route('accounting.invoices.show', $invoice)->with('status', 'Facture créée.');
    }

    public function show(Request $request, Facture $invoice): View
    {
        $invoice->load(['inscription.eleve', 'inscription.classe', 'lignes', 'paiements']);

        return view('accounting.invoices.show', ['invoice' => $invoice, 'canManageInvoices' => $this->canManageInvoices($request), 'canCancel' => $this->canCancel($request)]);
    }

    public function validateInvoice(Request $request, Facture $invoice): RedirectResponse
    {
        $this->denyUnless($this->canManageInvoices($request));
        abort_if($invoice->statut === 'ANNULEE', 422, 'Facture annulée');
        $invoice->update(['date_validation' => now(), 'statut' => 'EMISE']);
        $this->logAction($request, 'FACTURE_VALIDEE', 'factures', $invoice->id, null, ['date_validation' => now()->toDateString()]);

        return back()->with('status', 'Facture validée.');
    }

    public function cancel(Request $request, Facture $invoice): RedirectResponse
    {
        $this->denyUnless($this->canCancel($request));

        $data = $request->validate(['justification' => ['required', 'string', 'min:5']]);
        $invoice->update(['statut' => 'ANNULEE', 'motif_annulation' => $data['justification']]);
        $this->logAction($request, 'FACTURE_ANNULEE', 'factures', $invoice->id, ['statut' => $invoice->getOriginal('statut')], ['statut' => 'ANNULEE', 'justification' => $data['justification']]);

        return back()->with('status', 'Facture annulée.');
    }
}
