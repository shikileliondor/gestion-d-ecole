@php
    $isActiveLink = function ($patterns) {
        if (empty($patterns)) {
            return false;
        }

        return request()->routeIs(...(array) $patterns);
    };

    $menu = [
        [
            'id' => 'dashboard',
            'label' => 'Tableau de bord',
            'route' => 'dashboard',
            'active' => 'dashboard',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h6.5v6.5h-6.5zM13.75 3.75h6.5v6.5h-6.5zM3.75 13.75h6.5v6.5h-6.5zM13.75 13.75h6.5v6.5h-6.5z" />',
        ],
        [
            'id' => 'scolarite',
            'label' => 'Scolarité',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75h18M4.5 10.5h15m-15 6h6" />',
            'children' => [
                [
                    'label' => 'Élèves',
                    'route' => 'students.index',
                    'active' => 'students.index',
                ],
                [
                    'label' => 'Inscriptions',
                    'url' => route('students.index', ['open' => 'create']),
                    'active' => 'students.index',
                ],
                [
                    'label' => 'Réinscriptions',
                    'route' => 'students.re-enrollments',
                    'active' => 'students.re-enrollments',
                ],
                [
                    'label' => 'Paiements',
                    'route' => 'accounting.income.index',
                    'active' => 'accounting.income.index',
                ],
            ],
        ],
        [
            'id' => 'pedagogie',
            'label' => 'Pédagogie',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5l7.5 4.125L12 12.75 4.5 8.625 12 4.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 8.625V15.75L12 19.5l7.5-3.75V8.625" />',
            'children' => [
                [
                    'label' => 'Enseignement',
                    'children' => [
                        [
                            'label' => 'Matières',
                            'route' => 'pedagogy.subjects.index',
                            'active' => 'pedagogy.subjects.index',
                        ],
                        [
                            'label' => 'Classes',
                            'route' => 'classes.index',
                            'active' => 'classes.index',
                        ],
                    ],
                ],
                [
                    'label' => 'Notes & bulletins',
                    'children' => [
                        [
                            'label' => 'Tableau de bord',
                            'route' => 'pedagogy.dashboard.index',
                            'active' => 'pedagogy.dashboard.index',
                        ],
                        [
                            'label' => 'Évaluations',
                            'route' => 'pedagogy.evaluations.index',
                            'active' => 'pedagogy.evaluations.index',
                        ],
                        [
                            'label' => 'Saisie',
                            'route' => 'pedagogy.grades.index',
                            'active' => 'pedagogy.grades.index',
                        ],
                        [
                            'label' => 'Résultats',
                            'route' => 'pedagogy.results-dashboard.index',
                            'active' => 'pedagogy.results-dashboard.index',
                        ],
                        [
                            'label' => 'Documents',
                            'route' => 'pedagogy.report-cards.index',
                            'active' => 'pedagogy.report-cards.index',
                        ],
                    ],
                ],
            ],
        ],
        [
            'id' => 'rh',
            'label' => 'RH',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 8.25a3 3 0 11-6 0 3 3 0 016 0zM19.5 19.5a6 6 0 00-15 0" />',
            'children' => [
                [
                    'label' => 'Enseignants',
                    'route' => 'teachers.index',
                    'active' => 'teachers.index',
                ],
                [
                    'label' => 'Personnel administratif',
                    'route' => 'staff.index',
                    'active' => 'staff.index',
                ],
                [
                    'label' => 'Documents RH & Urgences',
                    'route' => 'rh.documents.index',
                    'active' => 'rh.documents.index',
                ],
            ],
        ],
        [
            'id' => 'comptabilite',
            'label' => 'Comptabilité',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75h18M4.5 10.5h15m-15 6h6" />',
            'children' => [
                [
                    'label' => 'Factures',
                    'route' => 'accounting.invoices.index',
                    'active' => 'accounting.invoices.index',
                ],
                [
                    'label' => 'Reçus',
                    'route' => 'accounting.receipts.list',
                    'active' => 'accounting.receipts.list',
                ],
                [
                    'label' => 'Journal',
                    'url' => '#',
                    'todo' => true,
                ],
                [
                    'label' => 'Rapports financiers',
                    'url' => '#',
                    'todo' => true,
                ],
            ],
        ],
        [
            'id' => 'outils',
            'label' => 'Outils',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5L12 3l7.5 4.5v9L12 21l-7.5-4.5v-9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18" />',
            'children' => [
                [
                    'label' => 'Dossiers élèves',
                    'route' => 'tools.student-files.index',
                    'active' => 'tools.student-files.index',
                ],
                [
                    'label' => 'Messagerie',
                    'url' => '#',
                    'todo' => true,
                ],
                [
                    'label' => 'Journal & Audit',
                    'url' => '#',
                    'todo' => true,
                ],
                [
                    'label' => 'Exports',
                    'children' => [
                        [
                            'label' => 'PDF',
                            'url' => '#',
                            'todo' => true,
                        ],
                        [
                            'label' => 'Excel',
                            'url' => '#',
                            'todo' => true,
                        ],
                    ],
                ],
            ],
        ],
        [
            'id' => 'parametres',
            'label' => 'Paramètres',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h3m-7.5 0h1.5m9 0h1.5m-12 6h3m6 0h3m-12 6h1.5m9 0h1.5m-7.5 0h3" />',
            'route' => 'settings.index',
            'active' => 'settings.index',
        ],
    ];

    $sectionStates = collect($menu)
        ->filter(fn ($section) => isset($section['children']))
        ->mapWithKeys(function ($section) use ($isActiveLink) {
            $hasActiveChild = collect($section['children'])->contains(function ($child) use ($isActiveLink) {
                if (isset($child['children'])) {
                    return collect($child['children'])->contains(fn ($grandchild) => $isActiveLink($grandchild['active'] ?? null));
                }

                return $isActiveLink($child['active'] ?? null);
            });

            return [$section['id'] => $hasActiveChild];
        });
@endphp

<aside class="{{ $classes ?? '' }} border-r border-slate-800/70 bg-slate-950/95 text-slate-100 backdrop-blur">
    <div class="flex h-full flex-col px-6 py-8">
        <a href="{{ route('dashboard') }}" class="space-y-1 rounded-3xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-3 shadow-lg shadow-blue-500/20">
            <div class="text-lg font-semibold text-white">Schermo ERP</div>
            <div class="text-xs text-blue-100/80">Gestion Scolaire</div>
        </a>

        <nav class="mt-8 flex-1 space-y-3" x-data="{ openSections: {{ $sectionStates->toJson() }} }">
            @foreach ($menu as $section)
                @php
                    $hasChildren = isset($section['children']);
                    $sectionActive = $isActiveLink($section['active'] ?? null);
                    $sectionHref = isset($section['route']) ? route($section['route']) : ($section['url'] ?? '#');
                    $sectionId = 'section-' . $section['id'];
                @endphp

                @if ($hasChildren)
                    <div class="space-y-1">
                        <button
                            type="button"
                            class="{{ $sectionStates[$section['id']] ? 'text-white' : 'text-slate-300' }} flex w-full items-center justify-between gap-3 rounded-2xl px-3 py-2 text-xs font-semibold uppercase tracking-wide transition hover:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-2"
                            @click="openSections['{{ $section['id'] }}'] = !openSections['{{ $section['id'] }}']"
                            :aria-expanded="openSections['{{ $section['id'] }}'].toString()"
                            aria-controls="{{ $sectionId }}"
                        >
                            <span class="flex items-center gap-3">
                                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                    {!! $section['icon'] !!}
                                </svg>
                                <span>{{ $section['label'] }}</span>
                            </span>
                            <svg class="h-4 w-4 text-slate-400 transition" :class="{ 'rotate-180': openSections['{{ $section['id'] }}'] }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="{{ $sectionId }}" x-show="openSections['{{ $section['id'] }}']" x-transition class="space-y-2 pl-7">
                            @foreach ($section['children'] as $childIndex => $child)
                                @php
                                    $childHasChildren = isset($child['children']);
                                    $childActive = $isActiveLink($child['active'] ?? null);
                                    $childHref = isset($child['route']) ? route($child['route']) : ($child['url'] ?? '#');
                                @endphp

                                @if ($childHasChildren)
                                    @php
                                        $groupId = $section['id'] . '-group-' . $childIndex;
                                        $groupActive = collect($child['children'])->contains(fn ($grandchild) => $isActiveLink($grandchild['active'] ?? null));
                                    @endphp
                                    <div class="space-y-1" x-data="{ open: {{ $groupActive ? 'true' : 'false' }} }">
                                        <button
                                            type="button"
                                            class="flex w-full items-center justify-between gap-2 px-3 pt-3 text-[11px] font-semibold uppercase tracking-wide transition hover:text-white {{ $groupActive ? 'text-white' : 'text-slate-400' }} focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-2"
                                            @click="open = !open"
                                            :aria-expanded="open.toString()"
                                            aria-controls="{{ $groupId }}"
                                        >
                                            <span>{{ $child['label'] }}</span>
                                            <svg class="h-3.5 w-3.5 text-slate-500 transition" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div id="{{ $groupId }}" x-show="open" x-transition class="space-y-1 pl-4">
                                            @foreach ($child['children'] as $grandchild)
                                                @php
                                                    $grandHref = isset($grandchild['route']) ? route($grandchild['route']) : ($grandchild['url'] ?? '#');
                                                    $grandActive = $isActiveLink($grandchild['active'] ?? null);
                                                @endphp
                                                <a
                                                    href="{{ $grandHref }}"
                                                    class="{{ $grandActive ? 'bg-blue-600/20 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-medium transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-2"
                                                    @if ($grandActive) aria-current="page" @endif
                                                >
                                                    <span>{{ $grandchild['label'] }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <a
                                        href="{{ $childHref }}"
                                        class="{{ $childActive ? 'bg-blue-600/20 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-medium transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-2"
                                        @if ($childActive) aria-current="page" @endif
                                    >
                                        <span>{{ $child['label'] }}</span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    <a
                        href="{{ $sectionHref }}"
                        class="{{ $sectionActive ? 'bg-blue-600/20 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-medium transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-2"
                        @if ($sectionActive) aria-current="page" @endif
                    >
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                            {!! $section['icon'] !!}
                        </svg>
                        <span>{{ $section['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </nav>
    </div>
</aside>

{{-- TODO routes: impayes.index, remises.index, echeanciers.index, notes.saisie, bulletins.index, releves.index, rh.documents, compta.journal, compta.rapports, docs.index, messagerie.index, audit.index, exports.pdf, exports.excel, annees.index, niveaux.index, series.index, types-frais.index, frais.index, settings.paiement, settings.impayes, settings.remises, users.index --}}
