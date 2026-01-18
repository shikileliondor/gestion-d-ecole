<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $sender = User::where('email', 'admin@example.com')->first();
        $recipient = User::where('email', 'teacher@example.com')->first();

        Message::firstOrCreate(
            ['sender_id' => $sender?->id, 'recipient_id' => $recipient?->id, 'subject' => 'Bienvenue'],
            ['body' => 'Bienvenue sur la plateforme.', 'channel' => 'in_app', 'status' => 'sent', 'sent_at' => now()]
        );
    }
}
