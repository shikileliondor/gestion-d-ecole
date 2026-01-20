<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Paramètres</h2>
                <p class="text-sm text-gray-500">Année scolaire, trimestres et frais par niveau.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-10 px-4 sm:px-6 lg:px-8">
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
                        <h3 class="text-lg font-semibold text-gray-800">Année scolaire</h3>
                        <p class="text-sm text-gray-500">Créer, activer, clôturer ou archiver une année.</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-8 lg:grid-cols-5">
                    <form class="lg:col-span-2 space-y-4" method="post" action="{{ route('settings.academic-years.store') }}">
                        @csrf
                        <div>
                            <label class="text-sm font-medium text-gray-700">Nom</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="2024-2025"
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                required
                            />
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Date de début</label>
                                <input
                                    type="date"
                                    name="start_date"
                                    value="{{ old('start_date') }}"
                                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                    required
                                />
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Date de fin</label>
                                <input
                                    type="date"
                                    name="end_date"
                                    value="{{ old('end_date') }}"
                                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                    required
                                />
                            </div>
                        </div>
                        <button class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700" type="submit">
                            Ajouter l'année
                        </button>
                    </form>

                    <div class="lg:col-span-3 space-y-4">
                        @forelse ($academicYears as $year)
                            <div class="flex flex-col gap-4 rounded-xl border border-gray-200 bg-gray-50 p-4 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-base font-semibold text-gray-900">{{ $year->name }}</h4>
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
                                    @if ($year->status !== 'active')
                                        <form method="post" action="{{ route('settings.academic-years.status', $year) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="active" />
                                            <button class="rounded-lg border border-emerald-200 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50" type="submit">
                                                Activer
                                            </button>
                                        </form>
                                    @endif
                                    @if ($year->status !== 'closed')
                                        <form method="post" action="{{ route('settings.academic-years.status', $year) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="closed" />
                                            <button class="rounded-lg border border-amber-200 px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-50" type="submit">
                                                Clôturer
                                            </button>
                                        </form>
                                    @endif
                                    @if ($year->status !== 'archived')
                                        <form method="post" action="{{ route('settings.academic-years.status', $year) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="archived" />
                                            <button class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100" type="submit">
                                                Archiver
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
            </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-2 border-b border-gray-200 pb-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Trimestres</h3>
                        <p class="text-sm text-gray-500">Saisir manuellement les 3 trimestres pour une année.</p>
                    </div>
                    <form method="get" class="flex items-center gap-2">
                        <label class="text-sm text-gray-500">Année</label>
                        <select name="academic_year_id" class="rounded-lg border border-gray-300 px-3 py-2 text-sm" onchange="this.form.submit()">
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}" @selected($selectedAcademicYear?->id === $year->id)>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                @if (! $selectedAcademicYear)
                    <p class="mt-4 text-sm text-gray-500">Ajoutez une année scolaire pour configurer les trimestres.</p>
                @else
                    @php
                        $defaultNames = ['T1', 'T2', 'T3'];
                    @endphp
                    <form class="mt-6 space-y-4" method="post" action="{{ route('settings.academic-years.terms.store', $selectedAcademicYear) }}">
                        @csrf
                        <div class="grid gap-4 lg:grid-cols-3">
                            @for ($i = 0; $i < 3; $i++)
                                @php
                                    $term = $terms->firstWhere('sequence', $i + 1);
                                @endphp
                                <div class="rounded-xl border border-gray-200 p-4">
                                    <input type="hidden" name="terms[{{ $i }}][sequence]" value="{{ $i + 1 }}" />
                                    <div class="mb-3 text-sm font-semibold text-gray-700">Trimestre {{ $i + 1 }}</div>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="text-xs font-medium text-gray-500">Nom</label>
                                            <input
                                                type="text"
                                                name="terms[{{ $i }}][name]"
                                                value="{{ old('terms.'.$i.'.name', $term->name ?? $defaultNames[$i]) }}"
                                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500">Début</label>
                                            <input
                                                type="date"
                                                name="terms[{{ $i }}][start_date]"
                                                value="{{ old('terms.'.$i.'.start_date', $term->start_date ?? null) }}"
                                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500">Fin</label>
                                            <input
                                                type="date"
                                                name="terms[{{ $i }}][end_date]"
                                                value="{{ old('terms.'.$i.'.end_date', $term->end_date ?? null) }}"
                                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                        <button class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700" type="submit">
                            Enregistrer les trimestres
                        </button>
                    </form>
                @endif
            </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-2 border-b border-gray-200 pb-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Frais par niveau</h3>
                        <p class="text-sm text-gray-500">Définir les types de frais et leurs modalités par niveau.</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $selectedAcademicYear?->name ?? 'Aucune année sélectionnée' }}
                    </div>
                </div>

                @if (! $selectedAcademicYear)
                    <p class="mt-4 text-sm text-gray-500">Ajoutez une année scolaire pour configurer les frais.</p>
                @else
                    <form class="mt-6 grid gap-4 lg:grid-cols-5" method="post" action="{{ route('settings.fees.store') }}">
                        @csrf
                        <input type="hidden" name="academic_year_id" value="{{ $selectedAcademicYear->id }}" />
                        <div class="lg:col-span-1">
                            <label class="text-sm font-medium text-gray-700">Niveau</label>
                            <input
                                list="levels"
                                name="level"
                                value="{{ old('level') }}"
                                placeholder="Ex: 6e, CP, Terminale"
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                required
                            />
                            <datalist id="levels">
                                @foreach ($levels as $level)
                                    <option value="{{ $level }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="text-sm font-medium text-gray-700">Type de frais</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Inscription, mensualité, annexe..."
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                required
                            />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Montant</label>
                            <input
                                type="number"
                                step="0.01"
                                name="amount"
                                value="{{ old('amount') }}"
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                required
                            />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Périodicité</label>
                            <input
                                type="text"
                                name="billing_cycle"
                                value="{{ old('billing_cycle') }}"
                                placeholder="Unique, mensuel, trimestriel..."
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                            />
                        </div>
                        <div class="lg:col-span-5">
                            <label class="text-sm font-medium text-gray-700">Modalités / Notes</label>
                            <textarea
                                name="payment_terms"
                                rows="2"
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                placeholder="Informations complémentaires sur le règlement"
                            >{{ old('payment_terms') }}</textarea>
                        </div>
                        <div class="lg:col-span-5">
                            <button class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700" type="submit">
                                Ajouter le frais
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    <th class="px-4 py-3">Niveau</th>
                                    <th class="px-4 py-3">Type</th>
                                    <th class="px-4 py-3">Montant</th>
                                    <th class="px-4 py-3">Périodicité</th>
                                    <th class="px-4 py-3">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($fees as $fee)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-700">{{ $fee->level ?? '—' }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $fee->name }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ number_format($fee->amount, 2, ',', ' ') }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $fee->billing_cycle ?? '—' }}</td>
                                        <td class="px-4 py-3 text-gray-500">{{ $fee->payment_terms ?? '—' }}</td>
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
            </section>
        </div>
    </div>
</x-app-layout>
