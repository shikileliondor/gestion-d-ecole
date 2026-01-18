<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Student;
use App\Models\StudentDocument;
use Illuminate\Database\Seeder;

class StudentDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $student = Student::first();
        $document = Document::first();

        StudentDocument::firstOrCreate(
            ['student_id' => $student?->id, 'document_id' => $document?->id],
            ['is_required' => true, 'status' => 'received']
        );
    }
}
