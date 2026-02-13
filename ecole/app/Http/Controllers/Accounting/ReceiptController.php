<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Recu;

class ReceiptController extends Controller
{
    public function index()
    {
        $receipts = Recu::query()
            ->with(['paiement.inscription.eleve'])
            ->orderByDesc('date_emission')
            ->orderByDesc('id')
            ->paginate(20);

        return view('accounting.receipts.list', [
            'receipts' => $receipts,
        ]);
    }
}
