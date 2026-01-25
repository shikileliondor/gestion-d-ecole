@php
    $subjectsById = $subjects->keyBy('id');
@endphp

@if ($selectedClassId === 0)
    <div class="empty-state">
        <h3>Sélectionnez une classe</h3>
        <p>Choisissez une classe pour gérer ses affectations.</p>
    </div>
@elseif ($programmeRows->isEmpty())
    <div class="empty-state">
        <h3>Programme vide</h3>
        <p>Ajoutez des matières au programme avant d'affecter des enseignants.</p>
    </div>
@else
    @if ($missingAssignments->isNotEmpty())
        <div class="mb-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-700">
            {{ $missingAssignments->count() }} matière(s) sans enseignant.
        </div>
    @endif
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-xs uppercase text-slate-400">
                    <th class="px-4 py-3">Matière</th>
                    <th class="px-4 py-3">Enseignant actuel</th>
                    <th class="px-4 py-3">Nouvel enseignant</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($programmeRows as $programme)
                    @php
                        $subject = $subjectsById->get($programme->matiere_id);
                        $assignment = $assignments->get($programme->matiere_id)?->first();
                    @endphp
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-3 font-semibold text-slate-800">{{ $subject?->name ?? 'Matière' }}</td>
                        <td class="px-4 py-3">
                            @if ($assignment)
                                @php
                                    $teacher = $teachers->firstWhere('id', $assignment->enseignant_id);
                                @endphp
                                <div class="text-sm font-semibold text-slate-700">{{ $teacher?->name ?? 'Enseignant' }}</div>
                                <div class="text-xs text-slate-400">{{ $teacherLoads[$assignment->enseignant_id] ?? 0 }} affectations</div>
                            @else
                                <span class="text-xs text-rose-600">Non affecté</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('pedagogy.assignments.store') }}" data-async-form data-async-target="[data-assignments-list]">
                                @csrf
                                <input type="hidden" name="academic_year_id" value="{{ $selectedAcademicYearId }}">
                                <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                                <input type="hidden" name="subject_id" value="{{ $programme->matiere_id }}">
                                <select name="teacher_id" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                                    <option value="">-- Aucun --</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" @selected($assignment?->enseignant_id === $teacher->id)>
                                            {{ $teacher->name }} ({{ $teacherLoads[$teacher->id] ?? 0 }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="mt-2 rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">Mettre à jour</button>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-xs text-slate-400">{{ $assignment ? 'Assigné' : 'À affecter' }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
