<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Frais;
use App\Models\Matiere;
use App\Models\ModePaiement;
use App\Models\Niveau;
use App\Models\ParametreEcole;
use App\Models\Periode;
use App\Models\Serie;
use App\Models\TypeFrais;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        $academicYears = AnneeScolaire::query()
            ->orderByDesc('date_debut')
            ->get()
            ->each(fn (AnneeScolaire $annee) => $this->hydrateAcademicYear($annee));

        $selectedAcademicYear = $academicYears
            ->firstWhere('id', $request->integer('academic_year_id'))
            ?? $academicYears->first();

        $terms = collect();
        $fees = collect();

        $levels = Niveau::query()
            ->where('actif', true)
            ->orderBy('ordre')
            ->get();

        $levelOptions = $levels->pluck('code');

        $series = Serie::query()
            ->where('actif', true)
            ->orderBy('code')
            ->get();

        $subjects = Matiere::query()
            ->where('actif', true)
            ->orderBy('nom')
            ->get();

        $feeTypes = TypeFrais::query()
            ->where('actif', true)
            ->orderBy('libelle')
            ->get();

        $paymentModes = ModePaiement::query()
            ->orderBy('libelle')
            ->get();

        $schoolSettings = ParametreEcole::query()->first();
        $periods = Periode::query()
            ->orderBy('ordre')
            ->get();
        $activePeriodType = $periods->firstWhere('actif', true)?->type;

        if ($selectedAcademicYear) {
            $fees = $this->buildFees($selectedAcademicYear->id);
        }

        return view('settings.index', [
            'schoolId' => null,
            'academicYears' => $academicYears,
            'selectedAcademicYear' => $selectedAcademicYear,
            'terms' => $terms,
            'fees' => $fees,
            'levels' => $levels,
            'levelOptions' => $levelOptions,
            'series' => $series,
            'subjects' => $subjects,
            'feeTypes' => $feeTypes,
            'paymentModes' => $paymentModes,
            'schoolSettings' => $schoolSettings,
            'documents' => $this->buildDocuments($schoolSettings),
            'periods' => $periods,
            'activePeriodType' => $activePeriodType,
        ]);
    }

    public function storeAcademicYear(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:annees_scolaires,libelle'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        AnneeScolaire::query()->create([
            'libelle' => $data['name'],
            'date_debut' => $data['start_date'],
            'date_fin' => $data['end_date'],
            'statut' => $this->defaultAcademicYearStatus(),
        ]);

        return back()->with('status', "L'année scolaire a été ajoutée.");
    }

    public function updateAcademicYearStatus(Request $request, AnneeScolaire $academicYear): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:planned,active,closed,archived'],
        ]);

        if ($data['status'] === 'active') {
            $anotherActive = AnneeScolaire::query()
                ->where('statut', 'ACTIVE')
                ->where('id', '!=', $academicYear->id)
                ->exists();

            if ($anotherActive) {
                return back()->withErrors([
                    'status' => "Une autre année est déjà active. Veuillez la clôturer ou l'archiver avant d'activer celle-ci.",
                ]);
            }
        }

        $academicYear->update([
            'statut' => $this->mapAcademicYearStatus($data['status']),
        ]);

        return back()->with('status', "Le statut de l'année scolaire a été mis à jour.");
    }

    public function storeTerms(Request $request, AnneeScolaire $academicYear): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'terms' => ['required', 'array', 'size:3'],
            'terms.*.sequence' => ['required', 'integer', 'between:1,3'],
            'terms.*.name' => ['required', 'string', 'max:50'],
            'terms.*.start_date' => ['required', 'date'],
            'terms.*.end_date' => ['required', 'date'],
        ]);

        $validator->after(function ($validator) use ($academicYear, $request) {
            $terms = $request->input('terms', []);

            foreach ($terms as $index => $term) {
                $startDate = data_get($term, 'start_date');
                $endDate = data_get($term, 'end_date');

                if ($startDate && $endDate && $endDate < $startDate) {
                    $validator->errors()->add("terms.$index.end_date", 'La date de fin doit être postérieure à la date de début.');
                }

                if ($startDate && ($startDate < $academicYear->date_debut || $startDate > $academicYear->date_fin)) {
                    $validator->errors()->add("terms.$index.start_date", "La date doit se situer dans l'année scolaire.");
                }

                if ($endDate && ($endDate < $academicYear->date_debut || $endDate > $academicYear->date_fin)) {
                    $validator->errors()->add("terms.$index.end_date", "La date doit se situer dans l'année scolaire.");
                }
            }
        });

        $validator->validate();

        return back()->with('status', 'Les trimestres ont été enregistrés.');
    }

    public function storePeriods(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'period_type' => ['required', 'in:TRIMESTRE,SEMESTRE'],
        ]);

        $type = $data['period_type'];
        $templates = $type === 'TRIMESTRE'
            ? [
                ['ordre' => 1, 'libelle' => 'Trimestre 1'],
                ['ordre' => 2, 'libelle' => 'Trimestre 2'],
                ['ordre' => 3, 'libelle' => 'Trimestre 3'],
            ]
            : [
                ['ordre' => 1, 'libelle' => 'Semestre 1'],
                ['ordre' => 2, 'libelle' => 'Semestre 2'],
            ];

        $activeIds = [];

        foreach ($templates as $template) {
            $period = Periode::query()->updateOrCreate(
                ['type' => $type, 'ordre' => $template['ordre']],
                ['libelle' => $template['libelle'], 'actif' => true],
            );

            $activeIds[] = $period->id;
        }

        Periode::query()
            ->where('type', $type)
            ->whereNotIn('id', $activeIds)
            ->update(['actif' => false]);

        Periode::query()
            ->where('type', '!=', $type)
            ->update(['actif' => false]);

        return back()->with('status', 'Les périodes ont été enregistrées.');
    }

    public function storeFee(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'academic_year_id' => ['required', 'exists:annees_scolaires,id'],
            'level' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'billing_cycle' => ['nullable', 'string', 'max:50'],
            'payment_terms' => ['nullable', 'string'],
        ]);

        $niveau = Niveau::query()->firstOrCreate(
            ['code' => $data['level']],
            [
                'ordre' => (int) Niveau::query()->max('ordre') + 1,
                'actif' => true,
            ]
        );

        $typeFrais = TypeFrais::query()->firstOrCreate(
            ['libelle' => $data['name']],
            ['obligatoire' => true, 'actif' => true]
        );

        Frais::query()->updateOrCreate(
            [
                'annee_scolaire_id' => $data['academic_year_id'],
                'niveau_id' => $niveau->id,
                'type_frais_id' => $typeFrais->id,
            ],
            [
                'periodicite' => $this->mapBillingCycle($data['billing_cycle'] ?? null),
                'montant' => $data['amount'],
                'actif' => true,
            ]
        );

        return back()->with('status', 'Le frais a été ajouté au niveau sélectionné.');
    }

    public function storeLevel(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:niveaux,code'],
        ]);

        $maxOrder = (int) Niveau::query()->max('ordre');

        Niveau::query()->create([
            'code' => $data['code'],
            'ordre' => $maxOrder + 1,
            'actif' => true,
        ]);

        return back()->with('status', 'Le niveau a été ajouté.');
    }

    public function updateLevel(Request $request, Niveau $level): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('niveaux', 'code')->ignore($level->id)],
        ]);

        $level->update([
            'code' => $data['code'],
        ]);

        return back()->with('status', 'Le niveau a été mis à jour.');
    }

    public function updateLevelStatus(Request $request, Niveau $level): RedirectResponse
    {
        $data = $request->validate([
            'active' => ['required', 'boolean'],
        ]);

        $level->update([
            'actif' => (bool) $data['active'],
        ]);

        return back()->with('status', 'Le niveau a été mis à jour.');
    }

    public function storeSerie(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:10', 'unique:series,code'],
            'label' => ['nullable', 'string', 'max:50'],
        ]);

        Serie::query()->create([
            'code' => $data['code'],
            'libelle' => $data['label'] ?: $data['code'],
            'actif' => true,
        ]);

        return back()->with('status', 'La série a été ajoutée.');
    }

    public function updateSerie(Request $request, Serie $serie): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:10', Rule::unique('series', 'code')->ignore($serie->id)],
            'label' => ['nullable', 'string', 'max:50'],
        ]);

        $serie->update([
            'code' => $data['code'],
            'libelle' => $data['label'] ?: $data['code'],
        ]);

        return back()->with('status', 'La série a été mise à jour.');
    }

    public function updateSerieStatus(Request $request, Serie $serie): RedirectResponse
    {
        $data = $request->validate([
            'active' => ['required', 'boolean'],
        ]);

        $serie->update([
            'actif' => (bool) $data['active'],
        ]);

        return back()->with('status', 'La série a été mise à jour.');
    }

    public function storeSubject(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:matieres,nom'],
            'code' => ['nullable', 'string', 'max:20'],
        ]);

        Matiere::query()->create([
            'nom' => $data['name'],
            'code' => $data['code'],
            'actif' => true,
        ]);

        return back()->with('status', 'La matière a été ajoutée.');
    }

    public function updateSubject(Request $request, Matiere $subject): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('matieres', 'nom')->ignore($subject->id)],
            'code' => ['nullable', 'string', 'max:20'],
        ]);

        $subject->update([
            'nom' => $data['name'],
            'code' => $data['code'],
        ]);

        return back()->with('status', 'La matière a été mise à jour.');
    }

    public function updateSubjectStatus(Request $request, Matiere $subject): RedirectResponse
    {
        $data = $request->validate([
            'active' => ['required', 'boolean'],
        ]);

        $subject->update([
            'actif' => (bool) $data['active'],
        ]);

        return back()->with('status', 'La matière a été mise à jour.');
    }

    public function updateDocuments(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'logo' => ['nullable', 'image', 'max:2048'],
            'cachet' => ['nullable', 'image', 'max:2048'],
            'signature' => ['nullable', 'image', 'max:2048'],
            'facture_prefix' => ['nullable', 'string', 'max:20'],
            'recu_prefix' => ['nullable', 'string', 'max:20'],
            'matricule_prefix' => ['nullable', 'string', 'max:20'],
        ]);

        $settings = ParametreEcole::query()->firstOrCreate([]);

        if ($request->hasFile('logo')) {
            $settings->logo_path = $request->file('logo')->store('documents', 'public');
        }

        if ($request->hasFile('cachet')) {
            $settings->cachet_path = $request->file('cachet')->store('documents', 'public');
        }

        if ($request->hasFile('signature')) {
            $settings->signature_path = $request->file('signature')->store('documents', 'public');
        }

        $settings->facture_prefix = $data['facture_prefix'] ?? $settings->facture_prefix;
        $settings->recu_prefix = $data['recu_prefix'] ?? $settings->recu_prefix;
        $settings->matricule_prefix = $data['matricule_prefix'] ?? $settings->matricule_prefix;

        $settings->save();

        return back()->with('status', 'Les documents officiels ont été mis à jour.');
    }

    private function hydrateAcademicYear(AnneeScolaire $annee): void
    {
        $annee->setAttribute('name', $annee->libelle);
        $annee->setAttribute('start_date', $annee->date_debut);
        $annee->setAttribute('end_date', $annee->date_fin);
        $annee->setAttribute('status', $this->formatAcademicYearStatus($annee->statut));
    }

    private function buildFees(int $academicYearId): Collection
    {
        $levels = Niveau::query()->pluck('code', 'id');
        $types = TypeFrais::query()->pluck('libelle', 'id');

        return Frais::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->orderBy('niveau_id')
            ->get()
            ->map(function (Frais $frais) use ($levels, $types) {
                return (object) [
                    'level' => $levels[$frais->niveau_id] ?? null,
                    'name' => $types[$frais->type_frais_id] ?? '—',
                    'amount' => $frais->montant,
                    'billing_cycle' => $frais->periodicite,
                    'payment_terms' => null,
                ];
            });
    }

    private function mapAcademicYearStatus(string $status): string
    {
        return match ($status) {
            'active' => 'ACTIVE',
            'closed' => 'CLOTUREE',
            'archived' => 'ARCHIVEE',
            default => 'CLOTUREE',
        };
    }

    private function formatAcademicYearStatus(?string $status): string
    {
        return match ($status) {
            'ACTIVE' => 'active',
            'CLOTUREE', 'CLOSED' => 'closed',
            'ARCHIVEE', 'ARCHIVED' => 'archived',
            'PLANNED' => 'planned',
            default => 'planned',
        };
    }

    private function defaultAcademicYearStatus(): string
    {
        $hasActive = AnneeScolaire::query()->where('statut', 'ACTIVE')->exists();

        return $hasActive ? 'CLOTUREE' : 'ACTIVE';
    }

    private function mapBillingCycle(?string $billingCycle): string
    {
        $value = strtolower(trim((string) $billingCycle));

        return match (true) {
            str_contains($value, 'mens') => 'MENSUEL',
            str_contains($value, 'trim') => 'TRIMESTRIEL',
            str_contains($value, 'annu') => 'ANNUEL',
            default => 'UNIQUE',
        };
    }

    private function buildDocuments(?ParametreEcole $settings): Collection
    {
        $items = collect([
            [
                'label' => 'Logo établissement',
                'value' => $settings?->logo_path,
            ],
            [
                'label' => 'Cachet établissement',
                'value' => $settings?->cachet_path,
            ],
            [
                'label' => 'Signature direction',
                'value' => $settings?->signature_path,
            ],
            [
                'label' => 'Numérotation facture',
                'value' => $settings?->facture_prefix,
            ],
            [
                'label' => 'Numérotation reçu',
                'value' => $settings?->recu_prefix,
            ],
            [
                'label' => 'Numérotation matricule',
                'value' => $settings?->matricule_prefix,
            ],
        ]);

        return $items->map(function (array $item) {
            $value = $item['value'];
            $isFile = is_string($value) && str_contains($value, '/');
            $url = $isFile ? Storage::disk('public')->url($value) : null;
            $display = $value ? basename((string) $value) : 'Non renseigné';
            $extension = $value ? strtolower(pathinfo((string) $value, PATHINFO_EXTENSION)) : '';
            $isImage = in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'webp'], true);

            return [
                'label' => $item['label'],
                'value' => $display,
                'url' => $url,
                'is_image' => $isImage,
            ];
        });
    }
}
