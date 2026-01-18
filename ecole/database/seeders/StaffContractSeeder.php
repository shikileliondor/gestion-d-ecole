<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\School;
use App\Models\Staff;
use App\Models\StaffContract;
use App\Models\User;
use Illuminate\Database\Seeder;

class StaffContractSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        $admin = User::where('email', 'admin@example.com')->first();
        $staff = Staff::where('staff_number', 'EMP001')->first();

        if (! $school || ! $staff) {
            return;
        }

        $document = Document::firstOrCreate(
            ['school_id' => $school->id, 'name' => 'Contrat EMP001'],
            [
                'category' => 'contrat_personnel',
                'description' => 'Contrat de travail',
                'file_path' => 'documents/staff-contracts/contrat-emp001.pdf',
                'mime_type' => 'application/pdf',
                'size' => 204800,
                'uploaded_by' => $admin?->id,
                'is_public' => false,
                'status' => 'active',
            ]
        );

        StaffContract::firstOrCreate(
            ['staff_id' => $staff->id, 'document_id' => $document->id],
            [
                'contract_type' => 'cdi',
                'start_date' => '2022-09-01',
                'status' => 'active',
            ]
        );
    }
}
