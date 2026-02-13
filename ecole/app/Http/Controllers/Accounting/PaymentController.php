<?php

namespace App\Http\Controllers\Accounting;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Facture;
use App\Models\FraisInscription;
use App\Models\Paiement;
use App\Models\Periode;
use App\Models\Remise;
use App\Models\Recu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends AccountingController
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'annee_scolaire_id' => ['nullable', 'integer', 'exists:annees_scolaires,id'],
            'periode_id' => ['nullable', 'integer', 'exists:periodes,id'],
            'classe_id' => ['nullable', 'integer', 'exists:classes,id'],
            'inscription_id' => ['nullable', 'array'],
            'inscription_id.*' => ['integer', 'exists:inscriptions,id'],
            'operation_type' => ['nullable', 'string', 'in:VERSEMENT,REMISE'],
            'payment_mode' => ['nullable', 'string', 'in:ESPECES,MOBILE_MONEY,CHEQUE,VIREMENT,AUTRE'],
            'status' => ['nullable', 'string', 'in:PAYEE,PARTIELLE,EMISE,EN_RETARD'],
        ]);

        $invoices = Facture::query()
            ->with(['inscription.eleve', 'inscription.classe.niveau', 'paiements', 'remises'])
            ->when($filters['annee_scolaire_id'] ?? null, fn ($q, $v) => $q->whereHas('inscription', fn ($sq) => $sq->where('annee_scolaire_id', $v)))
            ->when($filters['classe_id'] ?? null, fn ($q, $v) => $q->whereHas('inscription', fn ($sq) => $sq->where('classe_id', $v)))
            ->when($filters['inscription_id'] ?? null, fn ($q, $v) => $q->whereIn('inscription_id', $v))
            ->when($filters['periode_id'] ?? null, function ($q, $v) {
                $q->whereHas('remises', fn ($rq) => $rq->where('periode_id', $v))
                    ->orWhereHas('paiements', fn ($pq) => $pq->whereMonth('date_paiement', Periode::find($v)?->date_debut?->month ?? now()->month));
            })
            ->when($filters['payment_mode'] ?? null, fn ($q, $v) => $q->whereHas('paiements', fn ($sq) => $sq->where('mode_paiement_libre', $v)->whereNull('annule_le')))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $rows = $invoices->through(function (Facture $invoice) {
            $invoiceAmount = (float) $invoice->montant_total;
            $discountTotal = (float) $invoice->remises->whereNull('annule_le')->sum('montant_applique');
            $netToPay = max($invoiceAmount - $discountTotal, 0);
            $paidTotal = (float) $invoice->paiements->whereNull('annule_le')->sum('montant_paye');
            $remaining = max($netToPay - $paidTotal, 0);

            $status = $remaining <= 0 ? 'PAYEE' : ($paidTotal > 0 ? 'PARTIELLE' : 'EMISE');
            if ($status !== 'PAYEE' && optional($invoice->date_emission)->lt(now()->subDays(30))) {
                $status = 'EN_RETARD';
            }

            return [
                'invoice' => $invoice,
                'invoice_amount' => $invoiceAmount,
                'discount_total' => $discountTotal,
                'paid_total' => $paidTotal,
                'remaining' => $remaining,
                'status' => $status,
            ];
        })->filter(function (array $row) use ($filters) {
            if (($filters['status'] ?? null) && $row['status'] !== $filters['status']) {
                return false;
            }

            if (($filters['operation_type'] ?? null) === 'VERSEMENT' && $row['paid_total'] <= 0) {
                return false;
            }

            if (($filters['operation_type'] ?? null) === 'REMISE' && $row['discount_total'] <= 0) {
                return false;
            }

            return true;
        });

        return view('accounting.payments.index', [
            'rows' => $rows,
            'invoices' => $invoices,
            'filters' => $filters,
            'academicYears' => AnneeScolaire::query()->orderByDesc('id')->get(),
            'periods' => Periode::query()->orderBy('ordre')->get(),
            'classes' => Classe::query()->orderBy('nom')->get(),
            'inscriptions' => \App\Models\Inscription::query()->with('eleve')->latest('id')->limit(200)->get(),
            'paymentModes' => collect(['ESPECES', 'MOBILE_MONEY', 'CHEQUE', 'VIREMENT', 'AUTRE']),
            'canRegisterPayments' => $this->canRegisterPayments($request),
            'canManageDiscounts' => $this->canManageInvoices($request),
        ]);
    }

    public function createPayment(Request $request): View
    {
        $this->denyUnless($this->canRegisterPayments($request));

        return view('accounting.payments.create-payment', [
            'invoices' => Facture::query()->with('inscription.eleve', 'remises', 'paiements')->whereIn('statut', ['EMISE', 'PARTIELLE', 'PAYEE'])->latest('id')->limit(150)->get(),
        ]);
    }

    public function storePayment(Request $request): RedirectResponse
    {
        $this->denyUnless($this->canRegisterPayments($request));

        $data = $request->validate([
            'facture_id' => ['required', 'exists:factures,id'],
            'montant_paye' => ['required', 'numeric', 'gt:0'],
            'date_paiement' => ['required', 'date'],
            'mode_paiement_libre' => ['required', 'in:ESPECES,MOBILE_MONEY,CHEQUE,VIREMENT,AUTRE'],
            'reference' => ['nullable', 'string', 'max:80'],
            'commentaire' => ['nullable', 'string', 'max:255'],
        ]);

        $feeId = FraisInscription::query()->value('id');
        abort_if(! $feeId, 422, "Aucun frais d'inscription de référence disponible.");

        DB::transaction(function () use ($data, $request, $feeId) {
            $invoice = Facture::query()->with('remises')->lockForUpdate()->findOrFail($data['facture_id']);
            $remaining = $this->remainingAmount($invoice);
            abort_if((float) $data['montant_paye'] > $remaining, 422, 'Montant supérieur au reste à payer net.');

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

            $invoice->update(['statut' => $this->remainingAmount($invoice->fresh('paiements', 'remises')) <= 0 ? 'PAYEE' : 'PARTIELLE']);

            $this->logAction($request, 'VERSEMENT_AJOUTE', 'paiements', $payment->id, null, [
                'facture_id' => $invoice->id,
                'montant' => $payment->montant_paye,
                'commentaire' => $data['commentaire'] ?? null,
            ]);

            $this->logAction($request, 'RECU_GENERE_AUTO', 'recus', $receipt->id, null, ['paiement_id' => $payment->id]);
        });

        return redirect()->route('accounting.payments.index')->with('status', 'Versement enregistré et reçu généré.');
    }

    public function cancelPayment(Request $request, Paiement $payment): RedirectResponse
    {
        $this->denyUnless($request->user()?->role === 'ADMIN');

        $data = $request->validate(['justification' => ['required', 'string', 'min:5']]);

        DB::transaction(function () use ($request, $payment, $data) {
            $payment->update([
                'motif_annulation' => $data['justification'],
                'annule_le' => now(),
                'annule_par' => $request->user()->id,
            ]);

            Recu::query()->where('paiement_id', $payment->id)->whereNull('annule_le')->update([
                'motif_annulation' => $data['justification'],
                'annule_le' => now(),
                'annule_par' => $request->user()->id,
            ]);

            if ($payment->facture) {
                $payment->facture->update(['statut' => $this->remainingAmount($payment->facture->fresh('paiements', 'remises')) <= 0 ? 'PAYEE' : 'PARTIELLE']);
            }

            $this->logAction($request, 'VERSEMENT_ANNULE', 'paiements', $payment->id, null, ['justification' => $data['justification']]);
        });

        return back()->with('status', 'Versement annulé et solde recalculé.');
    }

    public function createDiscount(Request $request): View
    {
        $this->denyUnless($this->canManageInvoices($request));

        return view('accounting.payments.create-discount', [
            'invoices' => Facture::query()->with('inscription.eleve')->whereIn('statut', ['EMISE', 'PARTIELLE'])->latest('id')->limit(150)->get(),
            'periods' => Periode::query()->orderBy('ordre')->get(),
        ]);
    }

    public function storeDiscount(Request $request): RedirectResponse
    {
        $this->denyUnless($this->canManageInvoices($request));

        $data = $request->validate([
            'facture_id' => ['required', 'exists:factures,id'],
            'periode_id' => ['nullable', 'exists:periodes,id'],
            'type_calcul' => ['required', 'in:MONTANT,POURCENTAGE'],
            'valeur' => ['required', 'numeric', 'gt:0'],
            'type_remise' => ['required', 'in:BOURSE,REDUCTION,GESTE'],
            'motif' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:255'],
            'validation_admin' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($data, $request) {
            $invoice = Facture::query()->with(['paiements', 'remises'])->lockForUpdate()->findOrFail($data['facture_id']);
            $baseDue = max((float) $invoice->montant_total - (float) $invoice->remises->whereNull('annule_le')->sum('montant_applique'), 0);
            $discountAmount = $data['type_calcul'] === 'POURCENTAGE'
                ? round($baseDue * ((float) $data['valeur'] / 100), 2)
                : (float) $data['valeur'];

            abort_if($discountAmount > $baseDue, 422, 'La remise dépasse le montant dû.');

            $discount = Remise::create([
                'inscription_id' => $invoice->inscription_id,
                'facture_id' => $invoice->id,
                'periode_id' => $data['periode_id'] ?? null,
                'type_remise' => $data['type_remise'],
                'type_calcul' => $data['type_calcul'],
                'valeur' => $data['valeur'],
                'montant_applique' => $discountAmount,
                'montant' => $discountAmount,
                'motif' => $data['motif'],
                'description' => $data['description'] ?? null,
                'accordee_par' => $request->user()->id,
                'validee_par' => ($data['validation_admin'] ?? false) ? null : $request->user()->id,
                'validee_le' => ($data['validation_admin'] ?? false) ? null : now(),
            ]);

            $invoice->update(['statut' => $this->remainingAmount($invoice->fresh('paiements', 'remises')) <= 0 ? 'PAYEE' : 'PARTIELLE']);

            $this->logAction($request, 'REMISE_AJOUTEE', 'remises', $discount->id, null, [
                'facture_id' => $invoice->id,
                'montant' => $discountAmount,
                'type' => $data['type_remise'],
            ]);
        });

        return redirect()->route('accounting.payments.index')->with('status', 'Remise enregistrée et appliquée au net à payer.');
    }

    public function cancelDiscount(Request $request, Remise $discount): RedirectResponse
    {
        $this->denyUnless($request->user()?->role === 'ADMIN');

        $data = $request->validate(['justification' => ['required', 'string', 'min:5']]);

        DB::transaction(function () use ($request, $discount, $data) {
            $discount->update([
                'motif_annulation' => $data['justification'],
                'annule_le' => now(),
                'annule_par' => $request->user()->id,
            ]);

            if ($discount->facture) {
                $discount->facture->update(['statut' => $this->remainingAmount($discount->facture->fresh('paiements', 'remises')) <= 0 ? 'PAYEE' : 'PARTIELLE']);
            }

            $this->logAction($request, 'REMISE_ANNULEE', 'remises', $discount->id, null, ['justification' => $data['justification']]);
        });

        return back()->with('status', 'Remise annulée et solde recalculé.');
    }

    private function remainingAmount(Facture $invoice): float
    {
        $invoiceTotal = (float) $invoice->montant_total;
        $discountTotal = (float) $invoice->remises()->whereNull('annule_le')->sum('montant_applique');
        $paidTotal = (float) $invoice->paiements()->whereNull('annule_le')->sum('montant_paye');

        return max($invoiceTotal - $discountTotal - $paidTotal, 0);
    }
}
