<x-page-shell
    title="Élèves · Réinscriptions"
    subtitle="Renouveler une inscription sur une nouvelle année scolaire."
>
    <form method="get" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="grid gap-4 md:grid-cols-2">
            <label class="text-sm text-gray-600">
                Élève
                <select name="student_id" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2">
                    <option value="">Sélectionner un élève</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}" @selected($selectedStudent && $selectedStudent->id === $student->id)>
                            {{ $student->nom }} {{ $student->prenoms }}
                        </option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm text-gray-600">
                Année scolaire (automatique)
                <input
                    type="text"
                    class="mt-1 w-full rounded-lg border border-gray-200 bg-slate-50 px-3 py-2 text-slate-500"
                    value="{{ $activeAcademicYear?->libelle ?? 'Aucune année active' }}"
                    readonly
                />
            </label>
        </div>
        <button type="submit" class="mt-4 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Afficher le résumé</button>
    </form>

    @if ($studentSummary)
        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Résumé de l'élève</h3>
            <dl class="mt-4 grid gap-4 md:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase text-slate-400">Élève</dt>
                    <dd class="text-sm text-slate-700">{{ $selectedStudent->nom }} {{ $selectedStudent->prenoms }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase text-slate-400">Dernière classe</dt>
                    <dd class="text-sm text-slate-700">{{ $studentSummary['current_class']?->nom ?? 'Non renseignée' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase text-slate-400">Moyenne</dt>
                    <dd class="text-sm text-slate-700">
                        {{ $studentSummary['average'] !== null ? number_format($studentSummary['average'], 2) . ' / 20' : 'Aucune note' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase text-slate-400">Statut</dt>
                    <dd class="text-sm text-slate-700">{{ $studentSummary['status'] }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase text-slate-400">Classe recommandée</dt>
                    <dd class="text-sm text-slate-700">
                        {{ $studentSummary['recommended_class']?->nom ?? 'Non définie' }}
                    </dd>
                </div>
            </dl>

            <form method="post" action="{{ route('students.re-enrollments.store') }}" class="mt-6">
                @csrf
                <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}" />
                <input type="hidden" name="class_id" value="{{ $studentSummary['recommended_class']?->id }}" />
                @if ($studentSummary['recommended_class'])
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Valider la réinscription</button>
                @else
                    <p class="text-sm text-amber-600">Aucune classe recommandée n'est disponible pour l'année active.</p>
                @endif
            </form>
        </div>
    @endif
</x-page-shell>
