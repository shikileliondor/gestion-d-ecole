<?php

namespace App\Http\Controllers;

use App\Models\DossierEleve;
use App\Models\JournalAction;
use Illuminate\View\View;

class DossierEleveController extends Controller
{
    public function index(): View
    {
        $dossiers = DossierEleve::query()
            ->with(['eleve', 'inscriptions'])
            ->latest('updated_at')
            ->get();

        $journalEntries = JournalAction::query()
            ->where('table_cible', 'dossiers_eleves')
            ->whereIn('enregistrement_id', $dossiers->pluck('id'))
            ->latest('created_at')
            ->get()
            ->groupBy('enregistrement_id');

        return view('tools.dossiers-eleves', [
            'dossiers' => $dossiers,
            'journalEntries' => $journalEntries,
        ]);
    }
}
