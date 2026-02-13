<?php

namespace App\Http\Controllers\Accounting;

use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Recu;
use App\Models\FraisInscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReceiptController extends AccountingController
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'mode' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
        ]);

        $receipts = Recu::query()
            ->with(['paiement.facture.inscription.eleve'])
            ->when($filters['from'] ?? null, fn ($q, $v) => $q->whereDate('date_emission', '>=', $v))
            ->when($filters['to'] ?? null, fn ($q, $v) => $q->whereDate('date_emission', '<=', $v))
            ->when($filters['mode'] ?? null, fn ($q, $v) => $q->whereHas('paiement', fn ($sq) => $sq->where('mode_paiement_libre', $v)))
            ->when($filters['search'] ?? null, fn ($q, $v) => $q
                ->where('numero_recu', 'like', "%{$v}%")
                ->orWhereHas('paiement.facture', fn ($sq) => $sq->where('numero_facture', 'like', "%{$v}%")))
            ->latest('date_emission')
            ->paginate(20)
            ->withQueryString();

        return view('accounting.receipts.list', [
            'receipts' => $receipts,
            'canRegisterPayments' => $this->canRegisterPayments($request),
            'canCancel' => $this->canCancel($request),
        ]);
    }

    public function create(Request $request): View
    {
        $this->denyUnless($this->canRegisterPayments($request));

        return view('accounting.receipts.create', [
            'invoices' => Facture::query()->whereIn('statut', ['EMISE', 'PARTIELLE'])->with('inscription.eleve')->latest('id')->limit(100)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->denyUnless($this->canRegisterPayments($request));

        $data = $request->validate([
            'facture_id' => ['required', 'exists:factures,id'],
            'montant_paye' => ['required', 'numeric', 'gt:0'],
            'date_paiement' => ['required', 'date'],
            'mode_paiement_libre' => ['required', 'in:ESPECES,MOBILE_MONEY,CHEQUE,VIREMENT,AUTRE'],
            'reference' => ['nullable', 'string', 'max:80'],
        ]);

        $feeId = FraisInscription::query()->value('id');
        abort_if(! $feeId, 422, "Aucun frais d'inscription de référence disponible.");

        DB::transaction(function () use ($data, $request, $feeId) {
            $invoice = Facture::query()->with('remises')->lockForUpdate()->findOrFail($data['facture_id']);
            $paid = (float) $invoice->paiements()->whereNull('annule_le')->sum('montant_paye');
            $discount = (float) $invoice->remises()->whereNull('annule_le')->sum('montant_applique');
            $remaining = (float) $invoice->montant_total - $discount - $paid;
            abort_if($data['montant_paye'] > $remaining, 422, 'Montant supérieur au reste à payer');

            $payment = Paiement::create([
                'inscription_id' => $invoice->inscription_id,
                'frais_inscription_id' => $feeId,
                'facture_id' => $invoice->id,
                'montant_paye' => $data['montant_paye'],
                'date_paiement' => $data['date_paiement'],
                'mode_paiement' => 'CASH',
                'mode_paiement_libre' => $data['mode_paiement_libre'],
                'reference' => $data['reference'] ?? null,
            ]);

            $receipt = Recu::create([
                'numero_recu' => 'REC-'.now()->format('YmdHis').'-'.random_int(10, 99),
                'paiement_id' => $payment->id,
                'date_emission' => $data['date_paiement'],
                'montant' => $data['montant_paye'],
            ]);

            $newPaid = (float) $invoice->paiements()->whereNull('annule_le')->sum('montant_paye');
            $newDiscount = (float) $invoice->remises()->whereNull('annule_le')->sum('montant_applique');
            $invoice->update(['statut' => $newPaid >= ((float) $invoice->montant_total - $newDiscount) ? 'PAYEE' : 'PARTIELLE']);

            $this->logAction($request, 'PAIEMENT_RECU', 'recus', $receipt->id, null, ['facture_id' => $invoice->id, 'montant' => $receipt->montant]);
        });

        return redirect()->route('accounting.receipts.list')->with('status', 'Paiement enregistré et reçu généré.');
    }

    public function cancel(Request $request, Recu $receipt): RedirectResponse
    {
        $this->denyUnless($this->canCancel($request));

        $data = $request->validate(['justification' => ['required', 'string', 'min:5']]);

        DB::transaction(function () use ($receipt, $request, $data) {
            $receipt->update([
                'motif_annulation' => $data['justification'],
                'annule_le' => now(),
                'annule_par' => $request->user()->id,
            ]);

            $payment = $receipt->paiement;
            $payment->update([
                'motif_annulation' => $data['justification'],
                'annule_le' => now(),
                'annule_par' => $request->user()->id,
            ]);

            if ($payment->facture) {
                $activePayments = $payment->facture->paiements()->whereNull('annule_le')->sum('montant_paye');
                $payment->facture->update(['statut' => $activePayments <= 0 ? 'EMISE' : 'PARTIELLE']);
            }

            $this->logAction($request, 'RECU_ANNULE', 'recus', $receipt->id, null, ['justification' => $data['justification']]);
        });

        return back()->with('status', 'Reçu annulé.');
    }
}
