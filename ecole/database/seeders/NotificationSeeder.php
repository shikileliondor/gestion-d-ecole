<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'teacher@example.com')->first();

        Notification::firstOrCreate(
            ['user_id' => $user?->id, 'title' => 'Nouvelle evaluation'],
            ['body' => 'Une evaluation a ete ajoutee.', 'type' => 'assessment', 'is_read' => false]
        );
    }
}
