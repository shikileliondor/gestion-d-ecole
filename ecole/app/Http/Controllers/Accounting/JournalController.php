<?php

namespace App\Http\Controllers\Accounting;

use App\Models\JournalAction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JournalController extends AccountingController
{
    public function index(Request $request): View
    {
        $this->denyUnless($this->canReadReports($request));

        $filters = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'action' => ['nullable', 'string'],
            'user' => ['nullable', 'integer'],
            'reference' => ['nullable', 'string'],
        ]);

        $entries = JournalAction::query()
            ->with('user:id,name')
            ->when($filters['from'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['to'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->when($filters['action'] ?? null, fn ($q, $v) => $q->where('action', $v))
            ->when($filters['user'] ?? null, fn ($q, $v) => $q->where('user_id', $v))
            ->when($filters['reference'] ?? null, fn ($q, $v) => $q->where('enregistrement_id', $v))
            ->latest('created_at')
            ->paginate(30)
            ->withQueryString();

        return view('accounting.journal.index', ['entries' => $entries]);
    }
}
