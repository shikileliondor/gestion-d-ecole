<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\JournalAction;
use App\Support\AccountingRole;
use Illuminate\Http\Request;

abstract class AccountingController extends Controller
{
    protected function denyUnless(bool $allowed): void
    {
        abort_unless($allowed, 403, 'Action non autorisée pour votre profil.');
    }

    protected function canManageInvoices(Request $request): bool
    {
        return AccountingRole::canManageInvoices($request->user());
    }

    protected function canRegisterPayments(Request $request): bool
    {
        return AccountingRole::canRegisterPayments($request->user());
    }

    protected function canCancel(Request $request): bool
    {
        return AccountingRole::canCancel($request->user());
    }

    protected function canReadReports(Request $request): bool
    {
        return AccountingRole::canReadReports($request->user());
    }

    protected function logAction(Request $request, string $action, string $table, int $recordId, ?array $before = null, ?array $after = null): void
    {
        JournalAction::create([
            'user_id' => $request->user()->id,
            'action' => $action,
            'table_cible' => $table,
            'enregistrement_id' => $recordId,
            'anciennes_valeurs' => $before,
            'nouvelles_valeurs' => $after,
            'ip_adresse' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
