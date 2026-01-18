<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Download;
use App\Models\User;
use Illuminate\Database\Seeder;

class DownloadSeeder extends Seeder
{
    public function run(): void
    {
        $document = Document::first();
        $user = User::where('email', 'admin@example.com')->first();

        Download::firstOrCreate(
            ['document_id' => $document?->id, 'downloaded_at' => now()],
            ['downloaded_by' => $user?->id, 'ip_address' => '127.0.0.1', 'user_agent' => 'Seeder']
        );
    }
}
