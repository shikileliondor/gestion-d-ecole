<?php

namespace Database\Seeders;

use App\Models\DocumentFinancier;
use App\Models\Facture;
use App\Models\Recu;
use Illuminate\Database\Seeder;

class DocumentFinancierSeeder extends Seeder
{
    public function run(): void
    {
        Facture::query()->each(function (Facture $facture) {
            DocumentFinancier::query()->create([
                'type_document' => 'FACTURE',
                'reference_id' => $facture->id,
                'file_path' => 'documents/factures/' . $facture->numero_facture . '.pdf',
                'original_name' => $facture->numero_facture . '.pdf',
                'mime_type' => 'application/pdf',
                'size_bytes' => 150000,
            ]);
        });

        Recu::query()->each(function (Recu $recu) {
            DocumentFinancier::query()->create([
                'type_document' => 'RECU',
                'reference_id' => $recu->id,
                'file_path' => 'documents/recus/' . $recu->numero_recu . '.pdf',
                'original_name' => $recu->numero_recu . '.pdf',
                'mime_type' => 'application/pdf',
                'size_bytes' => 90000,
            ]);
        });
    }
}
