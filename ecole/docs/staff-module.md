# Module Gestion du personnel (ERP école)

## Modèle de données (normalisé)

### Tables principales
- **staff** : identité et statut du personnel (profil).【F:database/migrations/2024_03_01_000014_create_staff_table.php†L9-L32】
- **staff_contracts** : contrats liés au personnel, avec document associé et type de contrat (CDI, CDD, Vacation).【F:database/migrations/2024_03_01_000043_create_staff_contracts_table.php†L9-L25】
- **documents** : métadonnées des fichiers (contrats PDF stockés comme fichiers).【F:database/migrations/2024_03_01_000008_create_documents_table.php†L9-L29】
- **staff_assignments** : affectations pédagogiques sur une période, reliées aux matières et classes (historisation).【F:database/migrations/2024_03_01_000044_create_staff_assignments_table.php†L9-L23】

### Relations
- `staff` 1—N `staff_contracts` (un personnel peut avoir plusieurs contrats dans le temps).【F:database/migrations/2024_03_01_000043_create_staff_contracts_table.php†L9-L24】
- `staff_contracts` N—1 `documents` (le contrat est un document attaché, pas un texte libre).【F:database/migrations/2024_03_01_000043_create_staff_contracts_table.php†L11-L18】
- `staff` 1—N `staff_assignments` (un enseignant peut avoir plusieurs affectations dans le temps).【F:database/migrations/2024_03_01_000044_create_staff_assignments_table.php†L9-L22】
- `staff_assignments` N—1 `subjects` et `classes` (matière obligatoire, classe optionnelle).【F:database/migrations/2024_03_01_000044_create_staff_assignments_table.php†L12-L18】

## Flux fonctionnels (CRUD)

### Liste du personnel
- **Read** : affichage de la liste avec recherche par ID, nom, fonction + accès à la fiche et au contrat.【F:resources/views/staff/index.blade.php†L18-L93】【F:public/js/staff/table.js†L1-L24】

### Ajout d’un membre du personnel (modal)
- **Create** : création du personnel + contrat + affectations en transaction (validation stricte).【F:app/Http/Controllers/StaffController.php†L44-L135】

### Fiche personnel (modal)
- **Read** : consultation des informations et des affectations via l’API JSON dédiée.【F:app/Http/Controllers/StaffController.php†L137-L186】【F:public/js/staff/modal.js†L1-L172】

### Contrat
- **Download** : téléchargement du fichier PDF attaché au contrat (document associé).【F:app/Http/Controllers/StaffController.php†L188-L205】

## Comportement UI attendu

### Écran “Gestion du personnel”
- Tableau avec colonnes obligatoires : ID employé, nom complet, fonction, contact, type de contrat, statut, actions (fiche + téléchargement contrat).【F:resources/views/staff/index.blade.php†L35-L93】
- Barre de recherche par nom, fonction ou ID avec filtrage instantané côté UI.【F:resources/views/staff/index.blade.php†L18-L26】【F:public/js/staff/table.js†L1-L24】

### Modal “Ajouter un membre”
- Champs obligatoires : Nom complet, Fonction, Type de contrat, Date d’embauche, Upload contrat PDF (max 5MB).【F:resources/views/staff/partials/staff-form-modal.blade.php†L44-L110】【F:app/Http/Controllers/StaffController.php†L44-L64】
- Champ conditionnel : Matières enseignées (obligatoire si fonction = “Enseignant”).【F:resources/views/staff/partials/staff-form-modal.blade.php†L112-L122】【F:public/js/staff/form-modal.js†L45-L53】【F:app/Http/Controllers/StaffController.php†L58-L62】

### Modal “Fiche personnel”
- Onglet informations (identité, contrat, statut) + onglet affectations (historique).【F:resources/views/staff/partials/staff-modal.blade.php†L1-L56】【F:public/js/staff/modal.js†L88-L166】

## Règles métier clés
- **Désactivation sans suppression** : statut du personnel géré par champ `status` (pas de suppression physique).【F:database/migrations/2024_03_01_000014_create_staff_table.php†L27-L31】
- **Contrat = document** : le contrat est toujours un fichier référencé via `documents` (pas de texte libre).【F:database/migrations/2024_03_01_000043_create_staff_contracts_table.php†L11-L18】【F:database/migrations/2024_03_01_000008_create_documents_table.php†L13-L22】
- **Affectations historisées** : chaque affectation a des dates de début/fin et un statut (plusieurs périodes possibles).【F:database/migrations/2024_03_01_000044_create_staff_assignments_table.php†L13-L23】
