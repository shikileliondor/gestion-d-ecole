<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Frais;
use App\Models\Niveau;
use App\Models\TypeFrais;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        $levels = collect();

        if ($selectedAcademicYear) {
            $fees = $this->buildFees($selectedAcademicYear->id);
            $levels = Niveau::query()
                ->orderBy('ordre')
                ->pluck('code');
        }

        return view('settings.index', [
            'schoolId' => null,
            'academicYears' => $academicYears,
            'selectedAcademicYear' => $selectedAcademicYear,
            'terms' => $terms,
            'fees' => $fees,
            'levels' => $levels,
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
            'statut' => 'PLANNED',
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
            'closed' => 'CLOSED',
            'archived' => 'ARCHIVED',
            default => 'PLANNED',
        };
    }

    private function formatAcademicYearStatus(?string $status): string
    {
        return match ($status) {
            'ACTIVE' => 'active',
            'CLOSED' => 'closed',
            'ARCHIVED' => 'archived',
            default => 'planned',
        };
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
}
