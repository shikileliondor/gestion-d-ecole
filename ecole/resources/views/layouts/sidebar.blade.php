@php
    $links = [
        [
            'label' => 'Tableau de bord',
            'route' => 'dashboard',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h6.5v6.5h-6.5zM13.75 3.75h6.5v6.5h-6.5zM3.75 13.75h6.5v6.5h-6.5zM13.75 13.75h6.5v6.5h-6.5z" />',
            'active' => request()->routeIs('dashboard'),
        ],
        [
            'label' => 'Élèves',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.25 19.5V17.625A3.375 3.375 0 0013.875 14.25H10.125A3.375 3.375 0 006.75 17.625V19.5M15 7.875A3.375 3.375 0 118.25 7.875 3.375 3.375 0 0115 7.875z" />',
            'children' => [
                [
                    'label' => 'Liste des élèves',
                    'route' => 'students.index',
                    'active' => request()->routeIs('students.index', 'students.show'),
                ],
                [
                    'label' => 'Inscriptions',
                    'route' => 'students.enrollments',
                    'active' => request()->routeIs('students.enrollments'),
                ],
                [
                    'label' => 'Réinscriptions',
                    'route' => 'students.re-enrollments',
                    'active' => request()->routeIs('students.re-enrollments'),
                ],
            ],
        ],
        [
            'label' => 'Personnel',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 8.25a3 3 0 11-6 0 3 3 0 016 0zM19.5 19.5a6 6 0 00-15 0" />',
            'children' => [
                [
                    'label' => 'Professeurs',
                    'route' => 'teachers.index',
                    'active' => request()->routeIs('teachers.*'),
                ],
                [
                    'label' => "Personnel de l'école",
                    'route' => 'staff.index',
                    'active' => request()->routeIs('staff.*'),
                ],
            ],
        ],
        [
            'label' => 'Classes & Matières',
            'route' => 'classes.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 5.25h7.5m-7.5 0v13.5m0-13.5L15 4.5m-3 0v13.5m0-13.5h7.5m-7.5 0v13.5" />',
            'active' => request()->routeIs('classes.*'),
        ],
        [
            'label' => 'Notes & Bulletins',
            'url' => '#',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5l7.5 4.125L12 12.75 4.5 8.625 12 4.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 8.625V15.75L12 19.5l7.5-3.75V8.625" />',
        ],
        [
            'label' => 'Comptabilité',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75h18M4.5 10.5h15m-15 6h6" />',
            'children' => [
                [
                    'label' => 'Tableau de bord',
                    'route' => 'accounting.dashboard',
                    'active' => request()->routeIs('accounting.dashboard'),
                ],
                [
                    'label' => 'Recettes',
                    'children' => [
                        [
                            'label' => 'Liste des paiements',
                            'route' => 'accounting.income.index',
                            'active' => request()->routeIs('accounting.income.index'),
                        ],
                        [
                            'label' => 'Ajouter un paiement',
                            'route' => 'accounting.income.create',
                            'active' => request()->routeIs('accounting.income.create'),
                        ],
                        [
                            'label' => 'Écritures diverses',
                            'route' => 'accounting.income.manual',
                            'active' => request()->routeIs('accounting.income.manual'),
                        ],
                        [
                            'label' => 'Catégories recettes',
                            'route' => 'accounting.income.categories',
                            'active' => request()->routeIs('accounting.income.categories'),
                        ],
                    ],
                ],
                [
                    'label' => 'Dépenses',
                    'children' => [
                        [
                            'label' => 'Liste des dépenses',
                            'route' => 'accounting.expenses.index',
                            'active' => request()->routeIs('accounting.expenses.index'),
                        ],
                        [
                            'label' => 'Ajouter dépense',
                            'route' => 'accounting.expenses.create',
                            'active' => request()->routeIs('accounting.expenses.create'),
                        ],
                        [
                            'label' => 'Catégories dépenses',
                            'route' => 'accounting.expenses.categories',
                            'active' => request()->routeIs('accounting.expenses.categories'),
                        ],
                    ],
                ],
                [
                    'label' => 'Facturation',
                    'children' => [
                        [
                            'label' => 'Générer factures',
                            'route' => 'accounting.billing.generate',
                            'active' => request()->routeIs('accounting.billing.generate'),
                        ],
                        [
                            'label' => 'Liste des factures',
                            'route' => 'accounting.invoices.index',
                            'active' => request()->routeIs('accounting.invoices.index'),
                        ],
                        [
                            'label' => 'Factures par élève',
                            'route' => 'accounting.invoices.student',
                            'active' => request()->routeIs('accounting.invoices.student'),
                        ],
                        [
                            'label' => 'Factures par classe',
                            'route' => 'accounting.invoices.class',
                            'active' => request()->routeIs('accounting.invoices.class'),
                        ],
                        [
                            'label' => 'Factures impayées',
                            'route' => 'accounting.invoices.unpaid',
                            'active' => request()->routeIs('accounting.invoices.unpaid'),
                        ],
                    ],
                ],
                [
                    'label' => 'Reçus',
                    'children' => [
                        [
                            'label' => 'Liste des reçus',
                            'route' => 'accounting.receipts.list',
                            'active' => request()->routeIs('accounting.receipts.list'),
                        ],
                        [
                            'label' => 'Télécharger PDF',
                            'route' => 'accounting.receipts.download',
                            'active' => request()->routeIs('accounting.receipts.download'),
                        ],
                        [
                            'label' => 'Numérotation automatique',
                            'route' => 'accounting.receipts.numbering',
                            'active' => request()->routeIs('accounting.receipts.numbering'),
                        ],
                    ],
                ],
                [
                    'label' => 'Impayés',
                    'children' => [
                        [
                            'label' => 'Impayés par classe',
                            'route' => 'accounting.overdue.class',
                            'active' => request()->routeIs('accounting.overdue.class'),
                        ],
                        [
                            'label' => 'Impayés par élève',
                            'route' => 'accounting.overdue.student',
                            'active' => request()->routeIs('accounting.overdue.student'),
                        ],
                        [
                            'label' => 'Historique des relances',
                            'route' => 'accounting.overdue.history',
                            'active' => request()->routeIs('accounting.overdue.history'),
                        ],
                    ],
                ],
                [
                    'label' => 'Rapports',
                    'children' => [
                        [
                            'label' => 'Rapport annuel',
                            'route' => 'accounting.reports.annual',
                            'active' => request()->routeIs('accounting.reports.annual'),
                        ],
                        [
                            'label' => 'Rapport mensuel',
                            'route' => 'accounting.reports.monthly',
                            'active' => request()->routeIs('accounting.reports.monthly'),
                        ],
                        [
                            'label' => 'Rapport par classe',
                            'route' => 'accounting.reports.class',
                            'active' => request()->routeIs('accounting.reports.class'),
                        ],
                        [
                            'label' => 'Rapport par catégorie',
                            'route' => 'accounting.reports.category',
                            'active' => request()->routeIs('accounting.reports.category'),
                        ],
                    ],
                ],
                [
                    'label' => 'Paramètres Comptabilité',
                    'children' => [
                        [
                            'label' => 'Années scolaires',
                            'route' => 'accounting.settings.academic-years',
                            'active' => request()->routeIs('accounting.settings.academic-years'),
                        ],
                        [
                            'label' => 'Catégories recettes',
                            'route' => 'accounting.settings.revenue-categories',
                            'active' => request()->routeIs('accounting.settings.revenue-categories'),
                        ],
                        [
                            'label' => 'Catégories dépenses',
                            'route' => 'accounting.settings.expense-categories',
                            'active' => request()->routeIs('accounting.settings.expense-categories'),
                        ],
                        [
                            'label' => 'Modes de paiement',
                            'route' => 'accounting.settings.payment-modes',
                            'active' => request()->routeIs('accounting.settings.payment-modes'),
                        ],
                        [
                            'label' => 'Champs personnalisés',
                            'route' => 'accounting.settings.custom-fields',
                            'active' => request()->routeIs('accounting.settings.custom-fields'),
                        ],
                        [
                            'label' => 'Modèle reçu / facture',
                            'route' => 'accounting.settings.templates',
                            'active' => request()->routeIs('accounting.settings.templates'),
                        ],
                    ],
                ],
            ],
        ],
        [
            'label' => 'Documents',
            'url' => '#',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.5h7.5L18 8.25v11.25a1.5 1.5 0 01-1.5 1.5h-9a1.5 1.5 0 01-1.5-1.5V4.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 4.5v3.75H18" />',
        ],
        [
            'label' => 'Patrimoine',
            'url' => '#',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5L12 3l7.5 4.5v9L12 21l-7.5-4.5v-9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18" />',
        ],
        [
            'label' => 'Statistiques',
            'url' => '#',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5V9m5.25 10.5V4.5M15 19.5v-7.5M19.5 19.5v-3" />',
        ],
        [
            'label' => 'Messagerie',
            'url' => '#',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 9.75h9m-9 3h5.25M6 18.75h6l4.5 3v-3h1.5A3.75 3.75 0 0021.75 15V7.5A3.75 3.75 0 0018 3.75H6A3.75 3.75 0 002.25 7.5V15A3.75 3.75 0 006 18.75z" />',
        ],
        [
            'label' => 'Paramètres',
            'route' => 'settings.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h3m-7.5 0h1.5m9 0h1.5m-12 6h3m6 0h3m-12 6h1.5m9 0h1.5m-7.5 0h3" />',
            'active' => request()->routeIs('settings.*'),
        ],
    ];
@endphp

<aside class="{{ $classes ?? '' }} bg-slate-900 text-slate-100">
    <div class="flex h-full flex-col border-r border-slate-800 px-6 py-8">
        <a href="{{ route('dashboard') }}" class="space-y-1 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-3 shadow-lg">
            <div class="text-lg font-semibold text-white">Schermo ERP</div>
            <div class="text-xs text-blue-100/80">Gestion Scolaire</div>
        </a>

        <nav class="mt-10 flex-1 space-y-2">
            @foreach ($links as $link)
                @php
                    $hasChildren = isset($link['children']);
                    $isActive = $link['active'] ?? false;
                    $href = isset($link['route']) ? route($link['route']) : ($link['url'] ?? '#');
                    $childActive = $hasChildren
                        ? collect($link['children'])->contains(function ($child) {
                            if ($child['active'] ?? false) {
                                return true;
                            }
                            if (isset($child['children'])) {
                                return collect($child['children'])->contains(fn ($grandchild) => $grandchild['active'] ?? false);
                            }
                            return false;
                        })
                        : false;
                @endphp
                @if ($hasChildren)
                    <details class="group space-y-1" @if ($childActive) open @endif>
                        <summary class="{{ $childActive ? 'text-white' : 'text-slate-300' }} flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl px-3 py-2 text-xs font-semibold uppercase tracking-wide transition hover:bg-white/10 [&::-webkit-details-marker]:hidden">
                            <span class="flex items-center gap-3">
                                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                    {!! $link['icon'] !!}
                                </svg>
                                <span>{{ $link['label'] }}</span>
                            </span>
                            <svg class="h-4 w-4 text-slate-400 transition group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="space-y-2 pl-8">
                            @foreach ($link['children'] as $child)
                                @php
                                    $childHref = isset($child['route']) ? route($child['route']) : ($child['url'] ?? '#');
                                    $childIsActive = $child['active'] ?? false;
                                @endphp
                                @if (isset($child['children']))
                                    @php
                                        $grandActive = collect($child['children'])->contains(fn ($grandchild) => $grandchild['active'] ?? false);
                                    @endphp
                                    <details class="group space-y-1" @if ($grandActive) open @endif>
                                        <summary class="flex cursor-pointer list-none items-center justify-between gap-2 px-3 pt-3 text-[11px] font-semibold uppercase tracking-wide transition hover:text-white {{ $grandActive ? 'text-white' : 'text-slate-400' }} [&::-webkit-details-marker]:hidden">
                                            <span>{{ $child['label'] }}</span>
                                            <svg class="h-3.5 w-3.5 text-slate-500 transition group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </summary>
                                        <div class="space-y-1 pl-4">
                                            @foreach ($child['children'] as $grandchild)
                                                @php
                                                    $grandHref = isset($grandchild['route']) ? route($grandchild['route']) : ($grandchild['url'] ?? '#');
                                                    $grandIsActive = $grandchild['active'] ?? false;
                                                @endphp
                                                <a
                                                    href="{{ $grandHref }}"
                                                    class="{{ $grandIsActive ? 'bg-blue-600/20 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition"
                                                    @if ($grandIsActive) aria-current="page" @endif
                                                >
                                                    <span>{{ $grandchild['label'] }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </details>
                                @else
                                    <a
                                        href="{{ $childHref }}"
                                        class="{{ $childIsActive ? 'bg-blue-600/20 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition"
                                        @if ($childIsActive) aria-current="page" @endif
                                    >
                                        <span>{{ $child['label'] }}</span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </details>
                @else
                    <a
                        href="{{ $href }}"
                        class="{{ $isActive ? 'bg-blue-600/20 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition"
                        @if ($isActive) aria-current="page" @endif
                    >
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                            {!! $link['icon'] !!}
                        </svg>
                        <span>{{ $link['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </nav>
    </div>
</aside>
