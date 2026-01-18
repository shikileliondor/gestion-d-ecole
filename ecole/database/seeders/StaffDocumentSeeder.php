<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Staff;
use App\Models\StaffDocument;
use Illuminate\Database\Seeder;

class StaffDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $staff = Staff::first();
        $document = Document::first();

        StaffDocument::firstOrCreate(
            ['staff_id' => $staff?->id, 'document_id' => $document?->id],
            ['is_required' => true, 'status' => 'verified']
        );
    }
}
