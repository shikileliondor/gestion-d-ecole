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
            UserSeeder::class,
            SchoolSeeder::class,
            AcademicYearSeeder::class,
            SettingSeeder::class,
            RoleSeeder::class,
            RoleUserSeeder::class,
            DocumentSeeder::class,
            StudentSeeder::class,
            ParentProfileSeeder::class,
            StudentParentSeeder::class,
            StudentDocumentSeeder::class,
            StaffSeeder::class,
            StaffDocumentSeeder::class,
            StaffContractSeeder::class,
            StaffAssignmentSeeder::class,
            SchoolClassSeeder::class,
            SubjectSeeder::class,
            ClassSubjectSeeder::class,
            TimetableSeeder::class,
            TimetableSlotSeeder::class,
            StudentClassSeeder::class,
            AssessmentSeeder::class,
            GradeSeeder::class,
            BulletinSeeder::class,
            AppreciationSeeder::class,
            RankingSeeder::class,
            FeeSeeder::class,
            FeeClassSeeder::class,
            PaymentSeeder::class,
            PaymentHistorySeeder::class,
            ReceiptSeeder::class,
            UploadSeeder::class,
            DownloadSeeder::class,
            MessageSeeder::class,
            NotificationSeeder::class,
            SmsLogSeeder::class,
            WhatsappLogSeeder::class,
            AssetCategorySeeder::class,
            AssetSeeder::class,
            AssetMovementSeeder::class,
            ReportSeeder::class,
            ActivityLogSeeder::class,
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
        ]);
    }
}
