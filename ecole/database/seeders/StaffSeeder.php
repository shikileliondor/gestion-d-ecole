<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();

        Staff::firstOrCreate(
            ['code_personnel' => 'PER001'],
            [
                'school_id' => $school?->id,
                'nom' => 'Traore',
                'prenoms' => 'Jean',
                'sexe' => 'M',
                'email' => 'jean.traore@example.com',
                'telephone_1' => '0102030405',
                'categorie_personnel' => 'ADMINISTRATION',
                'poste' => 'Secrétaire',
                'type_contrat' => 'CDI',
                'date_debut_service' => '2022-09-01',
                'statut' => 'ACTIF',
            ]
        );
    }
}
