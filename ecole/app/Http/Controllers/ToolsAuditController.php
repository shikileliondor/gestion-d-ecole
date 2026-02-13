<?php

namespace App\Http\Controllers;

use App\Models\JournalAction;
use App\Models\JournalConnexion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ToolsAuditController extends Controller
{
    public function index(Request $request): View
    {
        $query = JournalAction::query()->with('user');
        $connectionQuery = JournalConnexion::query()->with('user');

        $startDate = $request->date('start_date');
        $endDate = $request->date('end_date');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
            $connectionQuery->whereDate('date_connexion', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
            $connectionQuery->whereDate('date_connexion', '<=', $endDate);
        }

        if ($userId = $request->integer('user_id')) {
            $query->where('user_id', $userId);
            $connectionQuery->where('user_id', $userId);
        }

        if ($module = $request->string('module')->toString()) {
            $query->where('table_cible', 'like', "%{$module}%");
        }

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($builder) use ($search) {
                $builder->where('action', 'like', "%{$search}%")
                    ->orWhere('table_cible', 'like', "%{$search}%")
                    ->orWhere('enregistrement_id', 'like', "%{$search}%");
            });
        }

        if ($request->string('type')->toString() === 'connexions') {
            $logs = collect();
            $connectionLogs = $connectionQuery->latest('date_connexion')->paginate(20)->withQueryString();
        } elseif ($request->string('type')->toString() === 'actions') {
            $logs = $query->latest('created_at')->paginate(20)->withQueryString();
            $connectionLogs = collect();
        } else {
            $logs = $query->latest('created_at')->paginate(20)->withQueryString();
            $connectionLogs = $connectionQuery->latest('date_connexion')->paginate(20, ['*'], 'conn_page')->withQueryString();
        }

        return view('tools.audit.index', [
            'logs' => $logs,
            'connectionLogs' => $connectionLogs,
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
            'filters' => $request->only(['type', 'user_id', 'module', 'search', 'start_date', 'end_date']),
        ]);
    }

    public function showAction(JournalAction $action): View
    {
        $action->load('user');

        return view('tools.audit.show-action', compact('action'));
    }

    public function showConnection(JournalConnexion $connection): View
    {
        $connection->load('user');

        return view('tools.audit.show-connection', compact('connection'));
    }
}
