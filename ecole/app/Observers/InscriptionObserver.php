<?php

namespace App\Observers;

use App\Models\DossierEleve;
use App\Models\Inscription;
use App\Models\JournalAction;
use Illuminate\Support\Facades\Auth;

class InscriptionObserver
{
    public function created(Inscription $inscription): void
    {
        $dossier = DossierEleve::firstOrCreate(
            ['eleve_id' => $inscription->eleve_id],
            [
                'statut' => 'OUVERT',
                'date_ouverture' => $inscription->date_inscription ?? now()->toDateString(),
            ]
        );

        if (! $dossier->wasRecentlyCreated) {
            $dossier->update([
                'statut' => 'OUVERT',
                'date_derniere_reouverture' => $inscription->date_inscription ?? now()->toDateString(),
            ]);
        }

        if (! Auth::check()) {
            return;
        }

        $action = $dossier->wasRecentlyCreated ? 'CREATION_DOSSIER' : 'REOUVERTURE_DOSSIER';

        JournalAction::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'table_cible' => 'dossiers_eleves',
            'enregistrement_id' => $dossier->id,
            'anciennes_valeurs' => null,
            'nouvelles_valeurs' => [
                'inscription_id' => $inscription->id,
                'annee_scolaire_id' => $inscription->annee_scolaire_id,
                'classe_id' => $inscription->classe_id,
                'date_inscription' => optional($inscription->date_inscription)->toDateString(),
            ],
            'ip_adresse' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}
