<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\StaffDocument;
use Illuminate\Database\Seeder;

class StaffDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $staff = Staff::first();

        if (! $staff) {
            return;
        }

        StaffDocument::firstOrCreate(
            ['staff_id' => $staff->id, 'libelle' => 'Carte nationale d’identité'],
            [
                'type_document' => 'CNI',
                'description' => 'Copie de la CNI.',
                'fichier_url' => 'documents/staff/cni-staff.pdf',
                'mime_type' => 'application/pdf',
                'taille' => 204800,
            ]
        );
    }
}
