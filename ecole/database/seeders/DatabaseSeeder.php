<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AnneeScolaireSeeder::class,
            NiveauSeeder::class,
            SerieSeeder::class,
            MatiereSeeder::class,
            ClasseSeeder::class,
            EleveSeeder::class,
            EleveContactSeeder::class,
            EleveTuteurSeeder::class,
            EleveUrgenceSeeder::class,
            InscriptionSeeder::class,
            ProgrammeMatiereSeeder::class,
            EnseignantSeeder::class,
            EnseignantUrgenceSeeder::class,
            EnseignantDocumentSeeder::class,
            AffectationEnseignantSeeder::class,
            EvaluationSeeder::class,
            NoteSeeder::class,
            TypeFraisSeeder::class,
            FraisSeeder::class,
            FraisInscriptionSeeder::class,
            FactureSeeder::class,
            FactureLigneSeeder::class,
            PaiementSeeder::class,
            RecuSeeder::class,
            RemiseSeeder::class,
            EcheancierSeeder::class,
            DocumentFinancierSeeder::class,
            JournalActionSeeder::class,
            JournalConnexionSeeder::class,
            ExportSeeder::class,
            ModePaiementSeeder::class,
            ParametreEcoleSeeder::class,
        ]);
    }
}
