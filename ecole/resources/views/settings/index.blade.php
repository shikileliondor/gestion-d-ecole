<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Paramètres</h2>
                <p class="text-sm text-gray-500">Pilotez les référentiels et paramètres globaux depuis une seule page.</p>
            </div>
        </div>
    </x-slot>

    @php
        $users = [
            ['name' => 'Adama Koné', 'email' => 'adama.kone@lycee.ci', 'role' => 'ADMIN', 'status' => 'Actif'],
            ['name' => 'Mariam Traoré', 'email' => 'mariam.traore@lycee.ci', 'role' => 'SCOLARITÉ', 'status' => 'Actif'],
            ['name' => 'Koffi Yao', 'email' => 'koffi.yao@lycee.ci', 'role' => 'COMPTABLE', 'status' => 'Désactivé'],
        ];
        $levelUpdateRoute = route('settings.levels.update', ['level' => '__LEVEL__']);
        $serieUpdateRoute = route('settings.series.update', ['serie' => '__SERIE__']);
        $subjectUpdateRoute = route('settings.subjects.update', ['subject' => '__SUBJECT__']);
        $coefficientsStoreRoute = route('settings.coefficients.store');
        $coefficientsDefaultsRoute = route('settings.coefficients.defaults');
        $coefficientsCopyRoute = route('settings.coefficients.copy');
    @endphp

    <div class="py-8" x-data="{ tab: localStorage.getItem('settingsTab') ?? 'annee', editLevel: null, editSerie: null, editSubject: null }">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 border-b border-gray-200 pb-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Page Paramètres</h3>
                        <p class="text-sm text-gray-500">Une seule page avec des onglets pour tout piloter rapidement.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button
                            type="button"
                            class="rounded-full px-4 py-2 text-xs font-semibold transition"
                            :class="tab === 'annee' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            @click="tab = 'annee'; localStorage.setItem('settingsTab', tab)"
                        >
                            Année scolaire
                        </button>
                        <button
                            type="button"
                            class="rounded-full px-4 py-2 text-xs font-semibold transition"
                            :class="tab === 'referentiels' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            @click="tab = 'referentiels'; localStorage.setItem('settingsTab', tab)"
                        >
                            Référentiels
                        </button>
                        <button
                            type="button"
                            class="rounded-full px-4 py-2 text-xs font-semibold transition"
                            :class="tab === 'scolarite' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            @click="tab = 'scolarite'; localStorage.setItem('settingsTab', tab)"
                        >
                            Scolarité
                        </button>
                        <button
                            type="button"
                            class="rounded-full px-4 py-2 text-xs font-semibold transition"
                            :class="tab === 'documents' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            @click="tab = 'documents'; localStorage.setItem('settingsTab', tab)"
                        >
                            Documents officiels
                        </button>
                        <button
                            type="button"
                            class="rounded-full px-4 py-2 text-xs font-semibold transition"
                            :class="tab === 'users' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            @click="tab = 'users'; localStorage.setItem('settingsTab', tab)"
                        >
                            Utilisateurs & rôles
                        </button>
                    </div>
                </div>

                <div class="mt-6 space-y-10">
                    <div x-show="tab === 'annee'" x-transition>
                        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <h4 class="text-base font-semibold text-gray-800">Année scolaire</h4>
                                <p class="text-sm text-gray-500">Créer, activer et clôturer une année unique.</p>
                            </div>
                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
                                @click="$refs.addAcademicYear.showModal()"
                            >
                                Ajouter une année
                            </button>
                        </div>

                        <div class="mt-6 grid gap-4">
                            @forelse ($academicYears as $year)
                                <div class="flex flex-col gap-4 rounded-xl border border-gray-200 bg-gray-50 p-4 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h5 class="text-base font-semibold text-gray-900">{{ $year->name }}</h5>
                                            <span class="rounded-full px-2 py-1 text-xs font-semibold
                                                {{ $year->status === 'active' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                                {{ $year->status === 'closed' ? 'bg-amber-100 text-amber-700' : '' }}
                                                {{ $year->status === 'archived' ? 'bg-slate-200 text-slate-600' : '' }}
                                                {{ $year->status === 'planned' ? 'bg-blue-100 text-blue-700' : '' }}
                                            ">
                                                {{ ucfirst($year->status) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500">{{ $year->start_date }} → {{ $year->end_date }}</p>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <button
                                            type="button"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-white"
                                            @click="$refs.editAcademicYear.showModal()"
                                        >
                                            Modifier
                                        </button>
                                        @if ($year->status !== 'active')
                                            <form method="post" action="{{ route('settings.academic-years.status', $year) }}" onsubmit="return confirm('Activer cette année scolaire ? Une seule peut être active.');">
                                                @csrf
                                                <input type="hidden" name="status" value="active" />
                                                <button class="rounded-lg border border-emerald-200 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50" type="submit">
                                                    Activer
                                                </button>
                                            </form>
                                        @endif
                                        @if ($year->status !== 'closed')
                                            <form method="post" action="{{ route('settings.academic-years.status', $year) }}" onsubmit="return confirm('Clôturer cette année scolaire ?');">
                                                @csrf
                                                <input type="hidden" name="status" value="closed" />
                                                <button class="rounded-lg border border-amber-200 px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-50" type="submit">
                                                    Clôturer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Aucune année scolaire pour le moment.</p>
                            @endforelse
                        </div>

                        <div class="mt-8 grid gap-6 lg:grid-cols-2">
                            <form method="post" action="{{ route('settings.periods.store') }}" class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                @csrf
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="text-base font-semibold text-gray-800">Périodes scolaires</h4>
                                        <p class="text-xs text-gray-500">Générer les trimestres ou les semestres actifs.</p>
                                    </div>
                                </div>
                                <div class="mt-4 space-y-4 text-sm text-gray-700">
                                    <label class="block text-xs font-semibold text-gray-500">Type de période</label>
                                    <select name="period_type" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                        <option value="TRIMESTRE" @selected(old('period_type', $activePeriodType) === 'TRIMESTRE')>Trimestres (1, 2, 3)</option>
                                        <option value="SEMESTRE" @selected(old('period_type', $activePeriodType) === 'SEMESTRE')>Semestres (1, 2)</option>
                                    </select>
                                </div>
                                <button class="mt-4 inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">
                                    Enregistrer les périodes
                                </button>
                            </form>

                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="text-base font-semibold text-gray-800">Périodes configurées</h4>
                                        <p class="text-xs text-gray-500">Statut des périodes actuelles.</p>
                                    </div>
                                </div>
                                <div class="mt-4 space-y-2 text-sm">
                                    @forelse ($periods as $period)
                                        <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2">
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $period->libelle }}</p>
                                                <p class="text-xs text-gray-500">{{ ucfirst(strtolower($period->type)) }}</p>
                                            </div>
                                            <span class="text-xs font-semibold {{ $period->actif ? 'text-emerald-600' : 'text-gray-400' }}">
                                                {{ $period->actif ? 'Actif' : 'Désactivé' }}
                                            </span>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">Aucune période configurée.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="tab === 'referentiels'" x-transition>
                        <div class="grid gap-6 lg:grid-cols-3">
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="text-base font-semibold text-gray-800">Niveaux</h4>
                                        <p class="text-xs text-gray-500">Structure 6e à Tle.</p>
                                    </div>
                                    <button
                                        type="button"
                                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white"
                                        @click="$refs.addLevel.showModal()"
                                    >
                                        Ajouter
                                    </button>
                                </div>
                                <div class="mt-4 space-y-2">
                                    @forelse ($levels as $level)
                                        <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2 text-sm">
                                            <span class="font-medium text-gray-800">{{ $level->code }}</span>
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    class="text-xs font-semibold text-blue-600"
                                                    @click="editLevel = { id: {{ $level->id }}, code: @js($level->code) }; $refs.editLevel.showModal()"
                                                >
                                                    Modifier
                                                </button>
                                                <form method="post" action="{{ route('settings.levels.status', $level) }}" onsubmit="return confirm('Désactiver ce niveau ?');">
                                                    @csrf
                                                    <input type="hidden" name="active" value="0" />
                                                    <button
                                                        type="submit"
                                                        class="text-xs font-semibold text-gray-500"
                                                    >
                                                        Désactiver
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-gray-500">Aucun niveau enregistré.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="text-base font-semibold text-gray-800">Séries</h4>
                                        <p class="text-xs text-gray-500">A, C, D, G...</p>
                                    </div>
                                    <button
                                        type="button"
                                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white"
                                        @click="$refs.addSerie.showModal()"
                                    >
                                        Ajouter
                                    </button>
                                </div>
                                <div class="mt-4 space-y-2">
                                    @forelse ($series as $serie)
                                        <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2 text-sm">
                                            <span class="font-medium text-gray-800">Série {{ $serie->code }}</span>
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    class="text-xs font-semibold text-blue-600"
                                                    @click="editSerie = { id: {{ $serie->id }}, code: @js($serie->code), label: @js($serie->libelle) }; $refs.editSerie.showModal()"
                                                >
                                                    Modifier
                                                </button>
                                                <form method="post" action="{{ route('settings.series.status', $serie) }}" onsubmit="return confirm('Désactiver cette série ?');">
                                                    @csrf
                                                    <input type="hidden" name="active" value="0" />
                                                    <button
                                                        type="submit"
                                                        class="text-xs font-semibold text-gray-500"
                                                    >
                                                        Désactiver
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-gray-500">Aucune série enregistrée.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="text-base font-semibold text-gray-800">Matières</h4>
                                        <p class="text-xs text-gray-500">Catalogue pédagogique.</p>
                                    </div>
                                    <button
                                        type="button"
                                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white"
                                        @click="$refs.addSubject.showModal()"
                                    >
                                        Ajouter
                                    </button>
                                </div>
                                <div class="mt-4 space-y-2">
                                    @forelse ($subjects as $subject)
                                        <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2 text-sm">
                                            <span class="font-medium text-gray-800">{{ $subject->nom }}</span>
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    class="text-xs font-semibold text-blue-600"
                                                    @click="editSubject = { id: {{ $subject->id }}, name: @js($subject->nom), code: @js($subject->code) }; $refs.editSubject.showModal()"
                                                >
                                                    Modifier
                                                </button>
                                                <form method="post" action="{{ route('settings.subjects.status', $subject) }}" onsubmit="return confirm('Désactiver cette matière ?');">
                                                    @csrf
                                                    <input type="hidden" name="active" value="0" />
                                                    <button
                                                        type="submit"
                                                        class="text-xs font-semibold text-gray-500"
                                                    >
                                                        Désactiver
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-gray-500">Aucune matière enregistrée.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4"
                            x-data="officialCoefficientsManager({
                                levels: @js($levels->map(fn ($level) => ['id' => $level->id, 'code' => $level->code])),
                                series: @js($series->map(fn ($serie) => ['id' => $serie->id, 'code' => $serie->code, 'label' => $serie->libelle])),
                                subjects: @js($coefficientSubjects->map(fn ($subject) => ['id' => $subject->id, 'name' => $subject->nom, 'active' => (bool) $subject->actif])),
                                coefficients: @js($officialCoefficients->map(fn ($coef) => [
                                    'id' => $coef->id,
                                    'annee_scolaire_id' => $coef->annee_scolaire_id,
                                    'niveau_id' => $coef->niveau_id,
                                    'serie_id' => $coef->serie_id,
                                    'matiere_id' => $coef->matiere_id,
                                    'coefficient' => $coef->coefficient,
                                    'obligatoire' => $coef->obligatoire,
                                    'actif' => $coef->actif,
                                ])),
                                academicYearId: @js($coefficientAcademicYearId),
                                usesSeries: @js($series->isNotEmpty()),
                                storeUrl: @js($coefficientsStoreRoute),
                                defaultsUrl: @js($coefficientsDefaultsRoute),
                                copyUrl: @js($coefficientsCopyRoute),
                            })"
                            x-init="init()"
                        >
                            <div class="flex flex-col gap-4 border-b border-gray-200 pb-4 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <h4 class="text-base font-semibold text-gray-800">Coefficients officiels</h4>
                                    <p class="text-xs text-gray-500">Définir les coefficients par niveau, matière et série.</p>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        type="button"
                                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-blue-300"
                                        :disabled="!canEdit || isSaving"
                                        @click="save()"
                                    >
                                        <span x-text="isSaving ? 'Enregistrement...' : 'Enregistrer'"></span>
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-lg border border-blue-200 px-3 py-1.5 text-xs font-semibold text-blue-600 hover:bg-blue-50 disabled:cursor-not-allowed disabled:text-blue-300"
                                        :disabled="!canEdit || isApplying"
                                        @click="applyDefaults()"
                                    >
                                        <span x-text="isApplying ? 'Application...' : 'Appliquer coefficients par défaut'"></span>
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-100 disabled:cursor-not-allowed disabled:text-gray-300"
                                        :disabled="!canEdit"
                                        @click="resetForm()"
                                    >
                                        Réinitialiser
                                    </button>
                                </div>
                            </div>

                            <div class="mt-4 space-y-4">
                                <div class="grid gap-4 lg:grid-cols-3">
                                    <div>
                                        <label class="text-xs font-semibold text-gray-500">Niveau</label>
                                        <select
                                            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                            x-model.number="selectedLevelId"
                                            @change="refreshRows()"
                                        >
                                            <template x-for="level in levels" :key="level.id">
                                                <option :value="level.id" x-text="level.code"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div x-show="usesSeries">
                                        <label class="text-xs font-semibold text-gray-500">Série</label>
                                        <select
                                            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                            x-model.number="selectedSerieId"
                                            @change="refreshRows()"
                                        >
                                            <option :value="null">Toutes les séries</option>
                                            <template x-for="serie in series" :key="serie.id">
                                                <option :value="serie.id" x-text="serie.code"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-700" x-show="missingCount > 0">
                                        <strong x-text="missingCount"></strong> matières sans coefficient pour ce niveau.
                                    </div>
                                </div>

                                <div class="rounded-lg border border-gray-200 bg-white">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                                            <thead class="bg-gray-50">
                                                <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    <th class="px-4 py-3">Matière</th>
                                                    <th class="px-4 py-3">Coefficient</th>
                                                    <th class="px-4 py-3">Statut</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <template x-for="row in rows" :key="row.matiere_id">
                                                    <tr>
                                                        <td class="px-4 py-3">
                                                            <div class="font-medium text-gray-900" x-text="row.name"></div>
                                                            <div class="text-xs text-gray-500" x-show="!row.subjectActive">Matière désactivée</div>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <input
                                                                type="number"
                                                                min="1"
                                                                step="0.01"
                                                                class="w-32 rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                                                x-model.number="row.coefficient"
                                                                @input="row.isDefined = Boolean(row.coefficient); updateMissingCount()"
                                                                :disabled="!row.subjectActive"
                                                            />
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <span
                                                                class="rounded-full px-2 py-1 text-xs font-semibold"
                                                                :class="!row.subjectActive ? 'bg-gray-200 text-gray-600' : (row.isDefined ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700')"
                                                                x-text="!row.subjectActive ? 'Désactivée' : (row.isDefined ? 'Défini' : 'Manquant')"
                                                            ></span>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="grid gap-4 lg:grid-cols-3">
                                    <div class="lg:col-span-2 rounded-lg border border-gray-200 bg-white px-4 py-3">
                                        <div class="flex flex-col gap-3 md:flex-row md:items-end">
                                            <div class="flex-1">
                                                <label class="text-xs font-semibold text-gray-500">Copier depuis un autre niveau</label>
                                                <div class="mt-1 grid gap-3 md:grid-cols-2">
                                                    <select class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" x-model.number="copyFromLevelId">
                                                        <option value="">Choisir un niveau</option>
                                                        <template x-for="level in levels" :key="`copy-${level.id}`">
                                                            <option :value="level.id" x-text="level.code"></option>
                                                        </template>
                                                    </select>
                                                    <select
                                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                                        x-model.number="copyFromSerieId"
                                                        x-show="usesSeries"
                                                    >
                                                        <option :value="null">Toutes les séries</option>
                                                        <template x-for="serie in series" :key="`copy-serie-${serie.id}`">
                                                            <option :value="serie.id" x-text="serie.code"></option>
                                                        </template>
                                                    </select>
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                class="rounded-lg border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:text-gray-300"
                                                :disabled="!canEdit || !copyFromLevelId || isCopying"
                                                @click="copyFromLevel()"
                                            >
                                                <span x-text="isCopying ? 'Copie...' : 'Copier'"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 text-xs text-gray-500">
                                        Les coefficients sont uniques par niveau + matière (et série si activée). Toute mise à jour conserve l'historique.
                                    </div>
                                </div>

                                <div
                                    class="rounded-lg px-4 py-3 text-sm"
                                    x-show="statusMessage"
                                    :class="statusType === 'success' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'"
                                    x-text="statusMessage"
                                ></div>
                            </div>
                        </div>
                    </div>

                    <div x-show="tab === 'scolarite'" x-transition>
                        <div class="grid gap-6">
                            <div class="grid gap-6 lg:grid-cols-3">
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="text-base font-semibold text-gray-800">Types de frais</h4>
                                            <p class="text-xs text-gray-500">Catégories officielles.</p>
                                        </div>
                                        <button
                                            type="button"
                                            class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white"
                                            @click="$refs.addFeeType.showModal()"
                                        >
                                            Ajouter
                                        </button>
                                    </div>
                                    <div class="mt-4 space-y-2">
                                        @forelse ($feeTypes as $feeType)
                                            <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2 text-sm">
                                                <span class="font-medium text-gray-800">{{ $feeType->libelle }}</span>
                                                <div class="flex items-center gap-2">
                                                    <button
                                                        type="button"
                                                        class="text-xs font-semibold text-blue-600"
                                                        @click="$refs.editFeeType.showModal()"
                                                    >
                                                        Modifier
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="text-xs font-semibold text-gray-500"
                                                        onclick="return confirm('Désactiver ce type de frais ?');"
                                                    >
                                                        Désactiver
                                                    </button>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-xs text-gray-500">Aucun type de frais enregistré.</p>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="text-base font-semibold text-gray-800">Modes de paiement</h4>
                                            <p class="text-xs text-gray-500">Autoriser les modes.</p>
                                        </div>
                                    </div>
                                    <div class="mt-4 space-y-3 text-sm text-gray-700">
                                        @forelse ($paymentModes as $mode)
                                            <label class="flex items-center gap-2 rounded-lg bg-white px-3 py-2">
                                                <input type="checkbox" class="rounded border-gray-300 text-blue-600" @checked($mode->actif) />
                                                <span>{{ $mode->libelle }}</span>
                                            </label>
                                        @empty
                                            <p class="rounded-lg bg-white px-3 py-2 text-xs text-gray-500">Aucun mode de paiement enregistré.</p>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <div>
                                        <h4 class="text-base font-semibold text-gray-800">Politique impayés</h4>
                                        <p class="text-xs text-gray-500">Gestion des impayés.</p>
                                    </div>
                                    <div class="mt-4 space-y-3 text-sm text-gray-700">
                                        <label class="flex items-center gap-2 rounded-lg bg-white px-3 py-2">
                                            <input type="radio" name="impayes" class="text-blue-600" value="BLOCK" @checked($schoolSettings?->politique_impayes === 'BLOCK') />
                                            <span>Bloquer les services</span>
                                        </label>
                                        <label class="flex items-center gap-2 rounded-lg bg-white px-3 py-2">
                                            <input type="radio" name="impayes" class="text-blue-600" value="ALLOW" @checked($schoolSettings?->politique_impayes === 'ALLOW') />
                                            <span>Autoriser malgré impayés</span>
                                        </label>
                                        <label class="flex items-center gap-2 rounded-lg bg-white px-3 py-2">
                                            <input type="radio" name="impayes" class="text-blue-600" value="ALLOW_WITH_APPROVAL" @checked($schoolSettings?->politique_impayes === 'ALLOW_WITH_APPROVAL') />
                                            <span>Autoriser avec validation Direction</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                <div class="flex flex-col gap-4 border-b border-gray-200 pb-4 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <h4 class="text-base font-semibold text-gray-800">Grille des frais</h4>
                                        <p class="text-xs text-gray-500">Par année et niveau.</p>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <form method="get" class="flex flex-wrap items-center gap-2">
                                            <label class="text-xs text-gray-500">Année</label>
                                            <select name="academic_year_id" class="rounded-lg border border-gray-300 px-3 py-2 text-xs" onchange="this.form.submit()">
                                                @foreach ($academicYears as $year)
                                                    <option value="{{ $year->id }}" @selected($selectedAcademicYear?->id === $year->id)>
                                                        {{ $year->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                        <div class="flex items-center gap-2">
                                            <label class="text-xs text-gray-500">Niveau</label>
                                            <select class="rounded-lg border border-gray-300 px-3 py-2 text-xs">
                                                <option value="">Tous</option>
                                                @foreach ($levelOptions as $level)
                                                    <option value="{{ $level }}">{{ $level }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button
                                            type="button"
                                            class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white"
                                            @click="$refs.addFee.showModal()"
                                        >
                                            Ajouter un frais
                                        </button>
                                    </div>
                                </div>

                                @if (! $selectedAcademicYear)
                                    <p class="mt-4 text-sm text-gray-500">Ajoutez une année scolaire pour configurer les frais.</p>
                                @else
                                    <div class="mt-4 overflow-hidden rounded-xl border border-gray-200 bg-white">
                                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                                            <thead class="bg-gray-50">
                                                <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    <th class="px-4 py-3">Niveau</th>
                                                    <th class="px-4 py-3">Type</th>
                                                    <th class="px-4 py-3">Montant</th>
                                                    <th class="px-4 py-3">Périodicité</th>
                                                    <th class="px-4 py-3">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                @forelse ($fees as $fee)
                                                    <tr>
                                                        <td class="px-4 py-3 text-gray-700">{{ $fee->level ?? '—' }}</td>
                                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $fee->name }}</td>
                                                        <td class="px-4 py-3 text-gray-700">{{ number_format($fee->amount, 2, ',', ' ') }}</td>
                                                        <td class="px-4 py-3 text-gray-700">{{ $fee->billing_cycle ?? '—' }}</td>
                                                        <td class="px-4 py-3">
                                                            <button
                                                                type="button"
                                                                class="text-xs font-semibold text-blue-600"
                                                                @click="$refs.editFee.showModal()"
                                                            >
                                                                Modifier
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">Aucun frais pour cette année.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            <div class="grid gap-6 lg:grid-cols-2">
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="text-base font-semibold text-gray-800">Règles remises</h4>
                                            <p class="text-xs text-gray-500">Paramètres globaux.</p>
                                        </div>
                                        <button
                                            type="button"
                                            class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white"
                                            @click="$refs.editDiscountRules.showModal()"
                                        >
                                            Configurer
                                        </button>
                                    </div>
                                    <div class="mt-4 space-y-2 text-sm text-gray-700">
                                        <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2">
                                            <span>Remises activées</span>
                                            <span class="text-xs font-semibold {{ $schoolSettings?->remises_actives ? 'text-emerald-600' : 'text-gray-600' }}">
                                                {{ $schoolSettings?->remises_actives ? 'Oui' : 'Non' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2">
                                            <span>Plafond par remise</span>
                                            <span class="text-xs font-semibold text-gray-600">
                                                {{ $schoolSettings?->plafond_remise ? $schoolSettings->plafond_remise . '%' : 'Non défini' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2">
                                            <span>Validation</span>
                                            <span class="text-xs font-semibold text-gray-600">{{ $schoolSettings?->validation_remise ?? 'Non définie' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="text-base font-semibold text-gray-800">Résumé global</h4>
                                            <p class="text-xs text-gray-500">Appliqué à toute l'école.</p>
                                        </div>
                                    </div>
                                    <div class="mt-4 space-y-2 text-sm text-gray-700">
                                        <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2">
                                            <span>Année active</span>
                                            <span class="text-xs font-semibold text-gray-600">{{ $selectedAcademicYear?->name ?? 'Non définie' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2">
                                            <span>Frais configurés</span>
                                            <span class="text-xs font-semibold text-gray-600">{{ $fees->count() }}</span>
                                        </div>
                                        <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2">
                                            <span>Modes de paiement actifs</span>
                                            <span class="text-xs font-semibold text-gray-600">{{ $paymentModes->where('actif', true)->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="tab === 'documents'" x-transition>
                        <div class="flex flex-col gap-6">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <h4 class="text-base font-semibold text-gray-800">Documents officiels</h4>
                                    <p class="text-sm text-gray-500">Logo, cachet, signatures, numérotation.</p>
                                </div>
                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
                                    @click="$refs.editDocuments.showModal()"
                                >
                                    Modifier
                                </button>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                @foreach ($documents as $document)
                                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                        <div class="text-xs font-semibold uppercase text-gray-500">{{ $document['label'] }}</div>
                                        <div class="mt-2 text-sm font-medium text-gray-800">{{ $document['value'] }}</div>
                                        @if ($document['url'])
                                            <div class="mt-3 space-y-2">
                                                @if ($document['is_image'])
                                                    <img src="{{ $document['url'] }}" alt="{{ $document['label'] }}" class="h-24 w-24 rounded-lg border border-gray-200 object-cover" />
                                                @endif
                                                <a href="{{ $document['url'] }}" class="text-xs font-semibold text-blue-600" target="_blank" rel="noopener">
                                                    Voir le document
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div x-show="tab === 'users'" x-transition>
                        <div class="flex flex-col gap-6">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <h4 class="text-base font-semibold text-gray-800">Utilisateurs & rôles</h4>
                                    <p class="text-sm text-gray-500">Attribuer les accès et activer/désactiver.</p>
                                </div>
                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
                                    @click="$refs.addUser.showModal()"
                                >
                                    Ajouter un utilisateur
                                </button>
                            </div>

                            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                            <th class="px-4 py-3">Utilisateur</th>
                                            <th class="px-4 py-3">Rôle</th>
                                            <th class="px-4 py-3">Statut</th>
                                            <th class="px-4 py-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach ($users as $user)
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <div class="font-medium text-gray-900">{{ $user['name'] }}</div>
                                                    <div class="text-xs text-gray-500">{{ $user['email'] }}</div>
                                                </td>
                                                <td class="px-4 py-3 text-gray-700">{{ $user['role'] }}</td>
                                                <td class="px-4 py-3">
                                                    <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $user['status'] === 'Actif' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-600' }}">
                                                        {{ $user['status'] }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="flex flex-wrap gap-2">
                                                        <button
                                                            type="button"
                                                            class="text-xs font-semibold text-blue-600"
                                                            @click="$refs.editUser.showModal()"
                                                        >
                                                            Modifier
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="text-xs font-semibold text-gray-500"
                                                            onclick="return confirm('Changer le statut de cet utilisateur ?');"
                                                        >
                                                            {{ $user['status'] === 'Actif' ? 'Désactiver' : 'Activer' }}
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <dialog x-ref="addAcademicYear" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="post" action="{{ route('settings.academic-years.store') }}" class="space-y-4 p-6">
                @csrf
                <h3 class="text-lg font-semibold text-gray-800">Ajouter une année scolaire</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" name="name" placeholder="2024-2025" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required />
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Date de début</label>
                        <input type="date" name="start_date" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" name="end_date" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required />
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm" @click="$refs.addAcademicYear.close()">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="editAcademicYear" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="dialog" class="space-y-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Modifier l'année scolaire</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Date de début</label>
                        <input type="date" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="addLevel" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="post" action="{{ route('settings.levels.store') }}" class="space-y-4 p-6">
                @csrf
                <h3 class="text-lg font-semibold text-gray-800">Ajouter un niveau</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" name="code" placeholder="6e" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm" @click="$refs.addLevel.close()">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="editLevel" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="post" class="space-y-4 p-6" x-bind:action="editLevel ? '{{ $levelUpdateRoute }}'.replace('__LEVEL__', editLevel.id) : ''">
                @csrf
                @method('put')
                <h3 class="text-lg font-semibold text-gray-800">Modifier le niveau</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" name="code" x-model="editLevel.code" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm" @click="$refs.editLevel.close()">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="addSerie" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="post" action="{{ route('settings.series.store') }}" class="space-y-4 p-6">
                @csrf
                <h3 class="text-lg font-semibold text-gray-800">Ajouter une série</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" name="code" placeholder="A" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Nom complet</label>
                    <input type="text" name="label" placeholder="Série A" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm" @click="$refs.addSerie.close()">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="editSerie" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="post" class="space-y-4 p-6" x-bind:action="editSerie ? '{{ $serieUpdateRoute }}'.replace('__SERIE__', editSerie.id) : ''">
                @csrf
                @method('put')
                <h3 class="text-lg font-semibold text-gray-800">Modifier la série</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" name="code" x-model="editSerie.code" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Nom complet</label>
                    <input type="text" name="label" x-model="editSerie.label" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm" @click="$refs.editSerie.close()">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="addSubject" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="post" action="{{ route('settings.subjects.store') }}" class="space-y-4 p-6">
                @csrf
                <h3 class="text-lg font-semibold text-gray-800">Ajouter une matière</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" name="name" placeholder="Mathématiques" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Code matière</label>
                    <input type="text" name="code" placeholder="MATH" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm" @click="$refs.addSubject.close()">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="editSubject" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="post" class="space-y-4 p-6" x-bind:action="editSubject ? '{{ $subjectUpdateRoute }}'.replace('__SUBJECT__', editSubject.id) : ''">
                @csrf
                @method('put')
                <h3 class="text-lg font-semibold text-gray-800">Modifier la matière</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" name="name" x-model="editSubject.name" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Code matière</label>
                    <input type="text" name="code" x-model="editSubject.code" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm" @click="$refs.editSubject.close()">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="addFeeType" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="dialog" class="space-y-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Ajouter un type de frais</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" placeholder="Scolarité" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="editFeeType" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="dialog" class="space-y-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Modifier le type de frais</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="addFee" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="post" action="{{ route('settings.fees.store') }}" class="space-y-4 p-6">
                @csrf
                <h3 class="text-lg font-semibold text-gray-800">Ajouter un frais</h3>
                <input type="hidden" name="academic_year_id" value="{{ $selectedAcademicYear?->id }}" />
                <div>
                    <label class="text-sm font-medium text-gray-700">Niveau</label>
                    <input list="levels" name="level" placeholder="6e" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required />
                    <datalist id="levels">
                                                @foreach ($levelOptions as $level)
                                                    <option value="{{ $level }}"></option>
                                                @endforeach
                                            </datalist>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Type de frais</label>
                    <input type="text" name="name" placeholder="Inscription" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required />
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Montant</label>
                        <input type="number" step="0.01" name="amount" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Périodicité</label>
                        <input type="text" name="billing_cycle" placeholder="Mensuel" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="payment_terms" rows="2" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="Modalités"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm" @click="$refs.addFee.close()">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="editFee" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="dialog" class="space-y-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Modifier un frais</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Niveau</label>
                    <input type="text" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Type de frais</label>
                    <input type="text" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Montant</label>
                    <input type="number" step="0.01" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Périodicité</label>
                    <input type="text" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="editDiscountRules" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="dialog" class="space-y-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Règles de remise</h3>
                <div class="flex items-center gap-2">
                    <input type="checkbox" class="rounded border-gray-300 text-blue-600" @checked($schoolSettings?->remises_actives) />
                    <span class="text-sm text-gray-700">Activer les remises</span>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Plafond (%)</label>
                    <input type="number" value="{{ $schoolSettings?->plafond_remise }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Qui peut accorder</label>
                    <select class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        <option @selected($schoolSettings?->validation_remise === 'Direction uniquement')>Direction uniquement</option>
                        <option @selected($schoolSettings?->validation_remise === 'Direction & Comptable')>Direction & Comptable</option>
                        <option @selected($schoolSettings?->validation_remise === 'Tout responsable')>Tout responsable</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="editDocuments" class="w-full max-w-xl rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="post" action="{{ route('settings.documents.update') }}" class="space-y-4 p-6" enctype="multipart/form-data">
                @csrf
                <h3 class="text-lg font-semibold text-gray-800">Documents officiels</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Logo établissement</label>
                    <input type="file" name="logo" accept="image/*" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Cachet & signatures</label>
                    <input type="file" name="cachet" accept="image/*" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Signature direction</label>
                    <input type="file" name="signature" accept="image/*" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Numéro facture</label>
                        <input type="text" name="facture_prefix" value="{{ $schoolSettings?->facture_prefix }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Numéro reçu</label>
                        <input type="text" name="recu_prefix" value="{{ $schoolSettings?->recu_prefix }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Matricule</label>
                        <input type="text" name="matricule_prefix" value="{{ $schoolSettings?->matricule_prefix }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm" @click="$refs.editDocuments.close()">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="addUser" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="dialog" class="space-y-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Ajouter un utilisateur</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Nom complet</label>
                    <input type="text" placeholder="Nom & prénom" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input type="email" placeholder="email@lycee.ci" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Rôle</label>
                    <select class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        <option>ADMIN</option>
                        <option>SCOLARITÉ</option>
                        <option>PÉDAGOGIE</option>
                        <option>COMPTABLE</option>
                        <option>DIRECTION</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="editUser" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="dialog" class="space-y-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Modifier l'utilisateur</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Nom complet</label>
                    <input type="text" value="Adama Koné" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Rôle</label>
                    <select class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        <option selected>ADMIN</option>
                        <option>SCOLARITÉ</option>
                        <option>PÉDAGOGIE</option>
                        <option>COMPTABLE</option>
                        <option>DIRECTION</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <script>
            function officialCoefficientsManager(config) {
                return {
                    levels: config.levels ?? [],
                    series: config.series ?? [],
                    subjects: config.subjects ?? [],
                    coefficients: config.coefficients ?? [],
                    academicYearId: config.academicYearId,
                    usesSeries: config.usesSeries ?? false,
                    storeUrl: config.storeUrl,
                    defaultsUrl: config.defaultsUrl,
                    copyUrl: config.copyUrl,
                    selectedLevelId: config.levels?.[0]?.id ?? null,
                    selectedSerieId: config.usesSeries ? (config.series?.[0]?.id ?? null) : null,
                    copyFromLevelId: null,
                    copyFromSerieId: null,
                    rows: [],
                    initialSnapshot: [],
                    missingCount: 0,
                    statusMessage: '',
                    statusType: 'success',
                    isSaving: false,
                    isApplying: false,
                    isCopying: false,
                    get canEdit() {
                        return Boolean(this.academicYearId && this.selectedLevelId);
                    },
                    init() {
                        this.refreshRows();
                    },
                    refreshRows() {
                        const filtered = this.coefficients.filter((coef) => {
                            const matchesLevel = coef.niveau_id === this.selectedLevelId;
                            const matchesSerie = this.usesSeries
                                ? ((coef.serie_id ?? null) === (this.selectedSerieId ?? null))
                                : (coef.serie_id ?? null) === null;

                            return matchesLevel && matchesSerie;
                        });

                        const coefficientMap = new Map();

                        filtered.forEach((coef) => {
                            coefficientMap.set(coef.matiere_id, coef);
                        });

                        this.rows = this.subjects.map((subject) => {
                            const existing = coefficientMap.get(subject.id);
                            const isDefined = Boolean(existing && existing.actif);

                            return {
                                matiere_id: subject.id,
                                name: subject.name,
                                subjectActive: subject.active,
                                coefficient: isDefined ? Number(existing.coefficient) : null,
                                isDefined,
                            };
                        });

                        this.initialSnapshot = this.rows.map((row) => ({ ...row }));
                        this.updateMissingCount();
                    },
                    updateMissingCount() {
                        this.missingCount = this.rows.filter((row) => row.subjectActive && !row.coefficient).length;
                    },
                    resolveErrorMessage(data, fallback) {
                        if (data?.errors) {
                            const firstField = Object.keys(data.errors)[0];
                            if (firstField && data.errors[firstField]?.length) {
                                return data.errors[firstField][0];
                            }
                        }

                        return data?.message ?? fallback;
                    },
                    resetForm() {
                        this.rows = this.initialSnapshot.map((row) => ({ ...row }));
                        this.updateMissingCount();
                    },
                    async save() {
                        if (!this.canEdit) {
                            return;
                        }

                        this.statusMessage = '';
                        this.statusType = 'success';
                        this.isSaving = true;

                        const payload = {
                            academic_year_id: this.academicYearId,
                            niveau_id: this.selectedLevelId,
                            serie_id: this.usesSeries ? this.selectedSerieId : null,
                            coefficients: this.rows.map((row) => ({
                                matiere_id: row.matiere_id,
                                coefficient: row.coefficient ?? null,
                            })),
                        };

                        try {
                            const response = await fetch(this.storeUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify(payload),
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                this.statusType = 'error';
                                this.statusMessage = this.resolveErrorMessage(data, 'Impossible d\'enregistrer les coefficients.');
                                return;
                            }

                            this.coefficients = data.coefficients ?? [];
                            this.statusMessage = data.message ?? 'Les coefficients ont été enregistrés.';
                            this.refreshRows();
                        } catch (error) {
                            this.statusType = 'error';
                            this.statusMessage = 'Erreur réseau lors de la sauvegarde.';
                        } finally {
                            this.isSaving = false;
                        }
                    },
                    async applyDefaults() {
                        if (!this.canEdit) {
                            return;
                        }

                        this.statusMessage = '';
                        this.statusType = 'success';
                        this.isApplying = true;

                        try {
                            const response = await fetch(this.defaultsUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    academic_year_id: this.academicYearId,
                                    niveau_id: this.selectedLevelId,
                                    serie_id: this.usesSeries ? this.selectedSerieId : null,
                                }),
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                this.statusType = 'error';
                                this.statusMessage = this.resolveErrorMessage(data, 'Impossible d\'appliquer les coefficients par défaut.');
                                return;
                            }

                            this.coefficients = data.coefficients ?? [];
                            this.statusMessage = data.message ?? 'Les coefficients par défaut ont été appliqués.';
                            this.refreshRows();
                        } catch (error) {
                            this.statusType = 'error';
                            this.statusMessage = 'Erreur réseau lors de l\'application des coefficients.';
                        } finally {
                            this.isApplying = false;
                        }
                    },
                    async copyFromLevel() {
                        if (!this.canEdit || !this.copyFromLevelId) {
                            return;
                        }

                        this.statusMessage = '';
                        this.statusType = 'success';
                        this.isCopying = true;

                        try {
                            const response = await fetch(this.copyUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    academic_year_id: this.academicYearId,
                                    source_niveau_id: this.copyFromLevelId,
                                    source_serie_id: this.usesSeries ? this.copyFromSerieId : null,
                                    target_niveau_id: this.selectedLevelId,
                                    target_serie_id: this.usesSeries ? this.selectedSerieId : null,
                                }),
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                this.statusType = 'error';
                                this.statusMessage = this.resolveErrorMessage(data, 'Impossible de copier les coefficients.');
                                return;
                            }

                            this.coefficients = data.coefficients ?? [];
                            this.statusMessage = data.message ?? 'Les coefficients ont été copiés.';
                            this.refreshRows();
                        } catch (error) {
                            this.statusType = 'error';
                            this.statusMessage = 'Erreur réseau lors de la copie.';
                        } finally {
                            this.isCopying = false;
                        }
                    },
                };
            }
        </script>
    </div>
</x-app-layout>
