@php
    $subjectsById = $subjects->keyBy('id');
@endphp

@if ($programmeRows->isEmpty())
    <div class="empty-state">
        <h3>Aucune matière dans le programme</h3>
        <p>Ajoutez une matière pour construire le programme de la classe.</p>
    </div>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-xs uppercase text-slate-400">
                    <th class="px-4 py-3">Matière</th>
                    <th class="px-4 py-3">Enseignant(s)</th>
                    <th class="px-4 py-3">Coefficient officiel</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($programmeRows as $programme)
                    @php
                        $subject = $subjectsById->get($programme->matiere_id);
                        $coefficient = $coefficients->get($programme->matiere_id)?->coefficient;
                        $teachers = $teachersBySubject->get($programme->matiere_id) ?? collect();
                    @endphp
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-3 text-sm font-semibold text-slate-800">
                            {{ $subject?->name ?? 'Matière' }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($teachers->isNotEmpty())
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($teachers as $teacher)
                                        <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">{{ $teacher }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-amber-600">Aucun enseignant</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($coefficient)
                                <span class="text-sm font-semibold text-slate-800">{{ $coefficient }}</span>
                                <span class="text-xs text-slate-400">officiel (paramètres)</span>
                            @else
                                <span class="text-xs text-rose-600">Coef manquant</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('pedagogy.programme.destroy', $programme) }}" data-async-form data-async-target="[data-programme-list]">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">Retirer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
