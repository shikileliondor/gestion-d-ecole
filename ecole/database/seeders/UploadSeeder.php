<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Database\Seeder;

class UploadSeeder extends Seeder
{
    public function run(): void
    {
        $document = Document::first();
        $user = User::where('email', 'admin@example.com')->first();

        Upload::firstOrCreate(
            ['document_id' => $document?->id, 'uploaded_at' => now()],
            ['uploaded_by' => $user?->id, 'ip_address' => '127.0.0.1', 'user_agent' => 'Seeder']
        );
    }
}
