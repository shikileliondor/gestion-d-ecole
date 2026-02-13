<?php

namespace App\Http\Controllers\Accounting;

use App\Models\Facture;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends AccountingController
{
    public function index(Request $request): View
    {
        $this->denyUnless($this->canReadReports($request));

        $filters = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'type' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'mode' => ['nullable', 'string'],
        ]);

        $invoiceQuery = Facture::query()
            ->when($filters['from'] ?? null, fn ($q, $v) => $q->whereDate('date_emission', '>=', $v))
            ->when($filters['to'] ?? null, fn ($q, $v) => $q->whereDate('date_emission', '<=', $v))
            ->when($filters['type'] ?? null, fn ($q, $v) => $q->where('type_facture', $v))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('statut', $v));

        $paymentQuery = Paiement::query()->whereNull('annule_le')
            ->when($filters['from'] ?? null, fn ($q, $v) => $q->whereDate('date_paiement', '>=', $v))
            ->when($filters['to'] ?? null, fn ($q, $v) => $q->whereDate('date_paiement', '<=', $v))
            ->when($filters['mode'] ?? null, fn ($q, $v) => $q->where('mode_paiement_libre', $v));

        $metrics = [
            'recettes' => (float) $paymentQuery->sum('montant_paye'),
            'impayes' => (float) (clone $invoiceQuery)->whereIn('statut', ['EMISE', 'PARTIELLE'])->sum('montant_total'),
            'factures_partielles' => (clone $invoiceQuery)->where('statut', 'PARTIELLE')->count(),
            'factures_impayees' => (clone $invoiceQuery)->where('statut', 'EMISE')->count(),
        ];

        $byType = (clone $invoiceQuery)
            ->selectRaw('type_facture, SUM(montant_total) total')
            ->groupBy('type_facture')
            ->pluck('total', 'type_facture');

        $paymentModes = (clone $paymentQuery)
            ->selectRaw('mode_paiement_libre, SUM(montant_paye) total')
            ->groupBy('mode_paiement_libre')
            ->pluck('total', 'mode_paiement_libre');

        $topDebtors = Facture::query()
            ->with('inscription.eleve')
            ->whereIn('statut', ['EMISE', 'PARTIELLE'])
            ->orderByDesc('montant_total')
            ->limit(10)
            ->get();

        return view('accounting.reports.index', compact('metrics', 'byType', 'paymentModes', 'topDebtors'));
    }
}
