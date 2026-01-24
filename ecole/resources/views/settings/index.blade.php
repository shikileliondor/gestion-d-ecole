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
        $series = ['A', 'C', 'D', 'G'];
        $subjects = ['Mathématiques', 'Français', 'Anglais', 'SVT', 'Physique-Chimie', 'HG'];
        $users = [
            ['name' => 'Adama Koné', 'email' => 'adama.kone@lycee.ci', 'role' => 'ADMIN', 'status' => 'Actif'],
            ['name' => 'Mariam Traoré', 'email' => 'mariam.traore@lycee.ci', 'role' => 'SCOLARITÉ', 'status' => 'Actif'],
            ['name' => 'Koffi Yao', 'email' => 'koffi.yao@lycee.ci', 'role' => 'COMPTABLE', 'status' => 'Désactivé'],
        ];
        $feeTypes = ['Inscription', 'Scolarité', 'Tenue', 'Examen', 'Transport', 'Cantine', 'Autre'];
        $paymentModes = ['Espèces', 'Mobile Money', 'Virement', 'Chèque'];
        $documents = [
            ['label' => 'Logo & cachet', 'value' => 'Fichier logo-lycee.png'],
            ['label' => 'Signature direction', 'value' => 'Signature DGE.pdf'],
            ['label' => 'Numérotation facture', 'value' => 'FACT-{YYYY}-{####}'],
            ['label' => 'Numérotation reçu', 'value' => 'REC-{YYYY}-{####}'],
            ['label' => 'Numérotation matricule', 'value' => 'LYC-{YY}-{####}'],
        ];
    @endphp

    <div class="py-8" x-data="{ tab: 'annee' }">
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
                            @click="tab = 'annee'"
                        >
                            Année scolaire
                        </button>
                        <button
                            type="button"
                            class="rounded-full px-4 py-2 text-xs font-semibold transition"
                            :class="tab === 'referentiels' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            @click="tab = 'referentiels'"
                        >
                            Référentiels
                        </button>
                        <button
                            type="button"
                            class="rounded-full px-4 py-2 text-xs font-semibold transition"
                            :class="tab === 'scolarite' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            @click="tab = 'scolarite'"
                        >
                            Scolarité
                        </button>
                        <button
                            type="button"
                            class="rounded-full px-4 py-2 text-xs font-semibold transition"
                            :class="tab === 'documents' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            @click="tab = 'documents'"
                        >
                            Documents officiels
                        </button>
                        <button
                            type="button"
                            class="rounded-full px-4 py-2 text-xs font-semibold transition"
                            :class="tab === 'users' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            @click="tab = 'users'"
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
                                            <span class="font-medium text-gray-800">{{ $level }}</span>
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    class="text-xs font-semibold text-blue-600"
                                                    @click="$refs.editLevel.showModal()"
                                                >
                                                    Modifier
                                                </button>
                                                <button
                                                    type="button"
                                                    class="text-xs font-semibold text-gray-500"
                                                    onclick="return confirm('Désactiver ce niveau ?');"
                                                >
                                                    Désactiver
                                                </button>
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
                                    @foreach ($series as $serie)
                                        <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2 text-sm">
                                            <span class="font-medium text-gray-800">Série {{ $serie }}</span>
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    class="text-xs font-semibold text-blue-600"
                                                    @click="$refs.editSerie.showModal()"
                                                >
                                                    Modifier
                                                </button>
                                                <button
                                                    type="button"
                                                    class="text-xs font-semibold text-gray-500"
                                                    onclick="return confirm('Désactiver cette série ?');"
                                                >
                                                    Désactiver
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
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
                                    @foreach ($subjects as $subject)
                                        <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2 text-sm">
                                            <span class="font-medium text-gray-800">{{ $subject }}</span>
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    class="text-xs font-semibold text-blue-600"
                                                    @click="$refs.editSubject.showModal()"
                                                >
                                                    Modifier
                                                </button>
                                                <button
                                                    type="button"
                                                    class="text-xs font-semibold text-gray-500"
                                                    onclick="return confirm('Désactiver cette matière ?');"
                                                >
                                                    Désactiver
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
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
                                        @foreach ($feeTypes as $feeType)
                                            <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2 text-sm">
                                                <span class="font-medium text-gray-800">{{ $feeType }}</span>
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
                                        @endforeach
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
                                        @foreach ($paymentModes as $mode)
                                            <label class="flex items-center gap-2 rounded-lg bg-white px-3 py-2">
                                                <input type="checkbox" class="rounded border-gray-300 text-blue-600" checked />
                                                <span>{{ $mode }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <div>
                                        <h4 class="text-base font-semibold text-gray-800">Politique impayés</h4>
                                        <p class="text-xs text-gray-500">Gestion des impayés.</p>
                                    </div>
                                    <div class="mt-4 space-y-3 text-sm text-gray-700">
                                        <label class="flex items-center gap-2 rounded-lg bg-white px-3 py-2">
                                            <input type="radio" name="impayes" class="text-blue-600" checked />
                                            <span>Bloquer les services</span>
                                        </label>
                                        <label class="flex items-center gap-2 rounded-lg bg-white px-3 py-2">
                                            <input type="radio" name="impayes" class="text-blue-600" />
                                            <span>Autoriser malgré impayés</span>
                                        </label>
                                        <label class="flex items-center gap-2 rounded-lg bg-white px-3 py-2">
                                            <input type="radio" name="impayes" class="text-blue-600" />
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
                                                @foreach ($levels as $level)
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
                                            <span class="text-xs font-semibold text-emerald-600">Oui</span>
                                        </div>
                                        <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2">
                                            <span>Plafond par remise</span>
                                            <span class="text-xs font-semibold text-gray-600">20%</span>
                                        </div>
                                        <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2">
                                            <span>Validation</span>
                                            <span class="text-xs font-semibold text-gray-600">Direction uniquement</span>
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
                                            <span class="text-xs font-semibold text-gray-600">{{ count($paymentModes) }}</span>
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
                    <input type="text" value="2024-2025" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
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
            <form method="dialog" class="space-y-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Ajouter un niveau</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" placeholder="6e" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="editLevel" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="dialog" class="space-y-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Modifier le niveau</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" value="3e" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="addSerie" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="dialog" class="space-y-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Ajouter une série</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" placeholder="A" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="editSerie" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="dialog" class="space-y-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Modifier la série</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" value="D" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="addSubject" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="dialog" class="space-y-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Ajouter une matière</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" placeholder="Mathématiques" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="editSubject" class="w-full max-w-lg rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="dialog" class="space-y-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Modifier la matière</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Libellé</label>
                    <input type="text" value="SVT" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">Annuler</button>
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
                    <input type="text" value="Transport" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
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
                        @foreach ($levels as $level)
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
                    <input type="text" value="2nde" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Type de frais</label>
                    <input type="text" value="Scolarité" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Montant</label>
                        <input type="number" step="0.01" value="250000" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Périodicité</label>
                        <input type="text" value="Mensuel" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
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
                    <input type="checkbox" class="rounded border-gray-300 text-blue-600" checked />
                    <span class="text-sm text-gray-700">Activer les remises</span>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Plafond (%)</label>
                    <input type="number" value="20" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Qui peut accorder</label>
                    <select class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        <option>Direction uniquement</option>
                        <option>Direction & Comptable</option>
                        <option>Tout responsable</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">Annuler</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Enregistrer</button>
                </div>
            </form>
        </dialog>

        <dialog x-ref="editDocuments" class="w-full max-w-xl rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
            <form method="dialog" class="space-y-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Documents officiels</h3>
                <div>
                    <label class="text-sm font-medium text-gray-700">Logo établissement</label>
                    <input type="file" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Cachet & signatures</label>
                    <input type="file" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Numéro facture</label>
                        <input type="text" value="FACT-{YYYY}-{####}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Numéro reçu</label>
                        <input type="text" value="REC-{YYYY}-{####}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Matricule</label>
                        <input type="text" value="LYC-{YY}-{####}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">Annuler</button>
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
    </div>
</x-app-layout>
