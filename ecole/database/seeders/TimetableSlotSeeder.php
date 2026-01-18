<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\Subject;
use App\Models\Timetable;
use App\Models\TimetableSlot;
use Illuminate\Database\Seeder;

class TimetableSlotSeeder extends Seeder
{
    public function run(): void
    {
        $timetable = Timetable::first();
        $subject = Subject::first();
        $staff = Staff::first();

        TimetableSlot::firstOrCreate(
            ['timetable_id' => $timetable?->id, 'day_of_week' => 1, 'start_time' => '08:00:00'],
            [
                'end_time' => '09:00:00',
                'subject_id' => $subject?->id,
                'staff_id' => $staff?->id,
                'room' => 'Salle 1',
            ]
        );
    }
}
