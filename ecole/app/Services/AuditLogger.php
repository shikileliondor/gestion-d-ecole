<?php

namespace App\Services;

use App\Models\JournalAction;
use Illuminate\Http\Request;

class AuditLogger
{
    public function log(
        ?int $userId,
        string $action,
        string $module,
        ?int $reference,
        string $result = 'SUCCES',
        ?string $comment = null,
        ?Request $request = null,
        array $extra = []
    ): JournalAction {
        return JournalAction::query()->create([
            'user_id' => $userId,
            'action' => $action,
            'table_cible' => $module,
            'enregistrement_id' => $reference ?? 0,
            'nouvelles_valeurs' => array_merge([
                'resultat' => $result,
                'commentaire' => $comment,
            ], $extra),
            'ip_adresse' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
