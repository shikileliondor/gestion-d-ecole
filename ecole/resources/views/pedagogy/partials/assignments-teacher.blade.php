@if ($teacherAssignments->isEmpty())
    <div class="text-sm text-slate-500">Aucune affectation pour le moment.</div>
@else
    @php
        $classesById = $classes->keyBy('id');
        $subjectsById = $subjects->keyBy('id');
    @endphp
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-xs uppercase text-slate-400">
                    <th class="px-4 py-3">Classe</th>
                    <th class="px-4 py-3">Matière</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($teacherAssignments as $assignment)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-3 text-sm font-semibold text-slate-800">
                            {{ $classesById->get($assignment->classe_id)?->name ?? 'Classe' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-600">
                            {{ $subjectsById->get($assignment->matiere_id)?->name ?? 'Matière' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
