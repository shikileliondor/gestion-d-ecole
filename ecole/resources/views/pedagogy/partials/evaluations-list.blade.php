@php
    $classesById = $classes->keyBy('id');
    $subjectsById = $subjects->keyBy('id');
    $periodsById = $periods->keyBy('id');
@endphp

@if ($evaluations->isEmpty())
    <div class="empty-state">
        <h3>Aucune évaluation</h3>
        <p>Créez une évaluation pour démarrer la saisie des notes.</p>
    </div>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-xs uppercase text-slate-400">
                    <th class="px-4 py-3">Évaluation</th>
                    <th class="px-4 py-3">Classe</th>
                    <th class="px-4 py-3">Matière</th>
                    <th class="px-4 py-3">Période</th>
                    <th class="px-4 py-3">Barème</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($evaluations as $evaluation)
                    @php
                        $class = $classesById->get($evaluation->classe_id);
                        $subject = $subjectsById->get($evaluation->matiere_id);
                        $period = $periodsById->get($evaluation->periode_id);
                    @endphp
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-3">
                            <div class="text-sm font-semibold text-slate-800">{{ $evaluation->titre ?: 'Évaluation' }}</div>
                            <div class="text-xs text-slate-500">{{ $evaluation->date_evaluation?->format('d/m/Y') }}</div>
                            <div class="text-xs text-slate-400">{{ $evaluation->type }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $class?->name ?? 'Classe' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $subject?->name ?? 'Matière' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $period?->libelle ?? 'Période' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">/ {{ $evaluation->note_sur }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('pedagogy.evaluations.status', $evaluation) }}" data-async-form data-async-target="[data-evaluations-list]">
                                @csrf
                                <select name="status" class="rounded-lg border border-slate-200 px-2 py-1 text-xs" @disabled($evaluation->statut === 'CLOTUREE')>
                                    <option value="BROUILLON" @selected($evaluation->statut === 'BROUILLON')>Brouillon</option>
                                    <option value="PUBLIEE" @selected($evaluation->statut === 'PUBLIEE')>Publiée</option>
                                    <option value="CLOTUREE" @selected($evaluation->statut === 'CLOTUREE')>Clôturée</option>
                                </select>
                                <button type="submit" class="mt-2 rounded-lg border border-slate-200 px-2 py-1 text-xs font-semibold text-slate-600">Maj</button>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <button
                                    type="button"
                                    class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600"
                                    data-modal-open="evaluation-edit"
                                    data-evaluation-id="{{ $evaluation->id }}"
                                    data-evaluation-period="{{ $evaluation->periode_id }}"
                                    data-evaluation-type="{{ $evaluation->type }}"
                                    data-evaluation-title="{{ $evaluation->titre }}"
                                    data-evaluation-date="{{ $evaluation->date_evaluation?->format('Y-m-d') }}"
                                    data-evaluation-scale="{{ $evaluation->note_sur }}"
                                    @if ($evaluation->statut === 'CLOTUREE') disabled @endif
                                >
                                    Modifier
                                </button>
                                <form method="POST" action="{{ route('pedagogy.evaluations.destroy', $evaluation) }}" data-async-form data-async-target="[data-evaluations-list]">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-rose-600" @disabled($evaluation->statut === 'CLOTUREE')>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
