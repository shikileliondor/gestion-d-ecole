<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Paiement;
use Illuminate\Database\Eloquent\Builder;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = $this->baseInvoicesQuery()
            ->orderByDesc('date_emission')
            ->orderByDesc('id')
            ->paginate(20);

        return view('accounting.invoices.index', [
            'invoices' => $invoices,
            'statusLabels' => $this->statusLabels(),
            'statusClasses' => $this->statusClasses(),
        ]);
    }

    public function unpaid()
    {
        $unpaidInvoices = $this->baseInvoicesQuery()
            ->whereIn('factures.statut', ['EMISE', 'PARTIELLE'])
            ->orderBy('date_emission')
            ->orderBy('id')
            ->paginate(20);

        return view('accounting.invoices.unpaid', [
            'unpaidInvoices' => $unpaidInvoices,
            'statusLabels' => $this->statusLabels(),
            'statusClasses' => $this->statusClasses(),
        ]);
    }

    private function baseInvoicesQuery(): Builder
    {
        $paymentsByInscription = Paiement::query()
            ->selectRaw('inscription_id, SUM(montant_paye) as total_paye')
            ->groupBy('inscription_id');

        return Facture::query()
            ->with(['inscription.eleve', 'inscription.classe'])
            ->leftJoinSub($paymentsByInscription, 'paiements_totaux', function ($join) {
                $join->on('paiements_totaux.inscription_id', '=', 'factures.inscription_id');
            })
            ->select('factures.*')
            ->selectRaw('COALESCE(paiements_totaux.total_paye, 0) as total_paye_inscription');
    }

    private function statusLabels(): array
    {
        return [
            'EMISE' => 'Émise',
            'PARTIELLE' => 'Paiement partiel',
            'PAYEE' => 'Soldée',
            'ANNULEE' => 'Annulée',
        ];
    }

    private function statusClasses(): array
    {
        return [
            'EMISE' => 'text-slate-600',
            'PARTIELLE' => 'text-amber-600',
            'PAYEE' => 'text-emerald-600',
            'ANNULEE' => 'text-rose-600',
        ];
    }
}
