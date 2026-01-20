<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerm;
use App\Models\AcademicYear;
use App\Models\Fee;
use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        $schoolId = School::query()->value('id');
        $academicYears = collect();
        $selectedAcademicYear = null;
        $terms = collect();
        $fees = collect();
        $levels = collect();

        if ($schoolId) {
            $academicYears = AcademicYear::query()
                ->where('school_id', $schoolId)
                ->orderByDesc('start_date')
                ->get();

            $selectedAcademicYear = $academicYears
                ->firstWhere('id', $request->integer('academic_year_id'))
                ?? $academicYears->firstWhere('status', 'active')
                ?? $academicYears->first();

            if ($selectedAcademicYear) {
                $terms = AcademicTerm::query()
                    ->where('academic_year_id', $selectedAcademicYear->id)
                    ->orderBy('sequence')
                    ->get();

                $fees = Fee::query()
                    ->where('academic_year_id', $selectedAcademicYear->id)
                    ->orderBy('level')
                    ->orderBy('name')
                    ->get();
            }

            $levels = SchoolClass::query()
                ->where('school_id', $schoolId)
                ->whereNotNull('level')
                ->distinct()
                ->orderBy('level')
                ->pluck('level');
        }

        return view('settings.index', [
            'schoolId' => $schoolId,
            'academicYears' => $academicYears,
            'selectedAcademicYear' => $selectedAcademicYear,
            'terms' => $terms,
            'fees' => $fees,
            'levels' => $levels,
        ]);
    }

    public function storeAcademicYear(Request $request): RedirectResponse
    {
        $schoolId = School::query()->value('id');

        if (! $schoolId) {
            return back()->withErrors(['school_id' => "Aucune école n'est configurée."])->withInput();
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:academic_years,name,NULL,id,school_id,'.$schoolId],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        AcademicYear::query()->create([
            'school_id' => $schoolId,
            'name' => $data['name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'is_current' => false,
            'status' => 'planned',
        ]);

        return back()->with('status', "L'année scolaire a été ajoutée.");
    }

    public function updateAcademicYearStatus(Request $request, AcademicYear $academicYear): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:planned,active,closed,archived'],
        ]);

        if ($data['status'] === 'active') {
            $anotherActive = AcademicYear::query()
                ->where('school_id', $academicYear->school_id)
                ->where('status', 'active')
                ->where('id', '!=', $academicYear->id)
                ->exists();

            if ($anotherActive) {
                return back()->withErrors([
                    'status' => "Une autre année est déjà active. Veuillez la clôturer ou l'archiver avant d'activer celle-ci.",
                ]);
            }
        }

        $academicYear->update([
            'status' => $data['status'],
            'is_current' => $data['status'] === 'active',
        ]);

        return back()->with('status', "Le statut de l'année scolaire a été mis à jour.");
    }

    public function storeTerms(Request $request, AcademicYear $academicYear): RedirectResponse
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
                $startDate = $term['start_date'] ?? null;
                $endDate = $term['end_date'] ?? null;

                if ($startDate && $endDate && $endDate < $startDate) {
                    $validator->errors()->add("terms.$index.end_date", 'La date de fin doit être postérieure à la date de début.');
                }

                if ($startDate && ($startDate < $academicYear->start_date || $startDate > $academicYear->end_date)) {
                    $validator->errors()->add("terms.$index.start_date", "La date doit se situer dans l'année scolaire.");
                }

                if ($endDate && ($endDate < $academicYear->start_date || $endDate > $academicYear->end_date)) {
                    $validator->errors()->add("terms.$index.end_date", "La date doit se situer dans l'année scolaire.");
                }
            }
        });

        $data = $validator->validate();

        foreach ($data['terms'] as $term) {
            AcademicTerm::query()->updateOrCreate(
                [
                    'academic_year_id' => $academicYear->id,
                    'sequence' => $term['sequence'],
                ],
                [
                    'school_id' => $academicYear->school_id,
                    'name' => $term['name'],
                    'start_date' => $term['start_date'],
                    'end_date' => $term['end_date'],
                    'status' => 'active',
                ]
            );
        }

        return back()->with('status', 'Les trimestres ont été enregistrés.');
    }

    public function storeFee(Request $request): RedirectResponse
    {
        $schoolId = School::query()->value('id');

        if (! $schoolId) {
            return back()->withErrors(['school_id' => "Aucune école n'est configurée."])->withInput();
        }

        $data = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'level' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'billing_cycle' => ['nullable', 'string', 'max:50'],
            'payment_terms' => ['nullable', 'string'],
        ]);

        Fee::query()->create([
            'school_id' => $schoolId,
            'academic_year_id' => $data['academic_year_id'],
            'level' => $data['level'],
            'name' => $data['name'],
            'description' => null,
            'amount' => $data['amount'],
            'due_date' => null,
            'fee_type' => null,
            'billing_cycle' => $data['billing_cycle'] ?? null,
            'payment_terms' => $data['payment_terms'] ?? null,
            'is_mandatory' => true,
            'status' => 'active',
        ]);

        return back()->with('status', 'Le frais a été ajouté au niveau sélectionné.');
    }
}
