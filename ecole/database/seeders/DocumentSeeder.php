<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        $admin = User::where('email', 'admin@example.com')->first();

        Document::firstOrCreate(
            ['school_id' => $school?->id, 'name' => 'Reglement interieur'],
            [
                'category' => 'administratif',
                'description' => 'Reglement scolaire',
                'file_path' => 'documents/reglement.pdf',
                'mime_type' => 'application/pdf',
                'size' => 102400,
                'uploaded_by' => $admin?->id,
            ]
        );
    }
}
