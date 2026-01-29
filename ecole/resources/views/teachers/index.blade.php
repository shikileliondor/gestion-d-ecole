<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/staff/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff/cards.css') }}">

    <div class="staff-page">
        <div class="staff-header">
            <div>
                <h1>Fiches enseignants</h1>
                <p>Gestion des enseignants et volet RH léger</p>
            </div>
            <button
                class="primary-button"
                type="button"
                data-teacher-form-modal-open
                data-form-title="Ajouter un enseignant"
                data-form-eyebrow="Nouvel enseignant"
            >
                + Ajouter un enseignant
            </button>
        </div>

        @if (session('status'))
            <div class="form-alert">
                <p>{{ session('status') }}</p>
            </div>
        @endif

        @php
            $activeTeachers = $enseignants->filter(fn ($enseignant) => $enseignant->statut !== 'PARTI');
            $archivedTeachers = $enseignants->filter(fn ($enseignant) => $enseignant->statut === 'PARTI');
        @endphp

        <div class="staff-toolbar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Rechercher par nom, spécialité ou code..." data-teacher-search>
            </div>
            <div class="staff-tabs" role="tablist" aria-label="Filtrer les enseignants">
                <button class="staff-tab is-active" type="button" data-teacher-tab="active" aria-selected="true">
                    Actifs ({{ $activeTeachers->count() }})
                </button>
                <button class="staff-tab" type="button" data-teacher-tab="archived" aria-selected="false">
                    Archivés ({{ $archivedTeachers->count() }})
                </button>
            </div>
        </div>

        <div class="staff-panel is-active" data-teacher-panel="active">
            <div class="staff-grid">
                @forelse ($activeTeachers as $enseignant)
                    @php
                        $fullName = trim($enseignant->nom . ' ' . $enseignant->prenoms);
                        $statusClass = match ($enseignant->statut) {
                            'ACTIF' => 'status-active',
                            'SUSPENDU' => 'status-suspended',
                            'PARTI' => 'status-departed',
                            default => 'status-inactive',
                        };
                        $statusLabel = match ($enseignant->statut) {
                            'ACTIF' => 'Actif',
                            'SUSPENDU' => 'Suspendu',
                            'PARTI' => 'Archivé',
                            default => 'Inconnu',
                        };
                        $initials = collect(explode(' ', $fullName))
                            ->filter()
                            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                            ->take(2)
                            ->implode('');
                    @endphp
                    <article class="staff-card" data-teacher-card>
                        <div class="staff-card__header">
                            <div class="staff-avatar">
                                @if ($enseignant->photo_url)
                                    <img src="{{ $enseignant->photo_url }}" alt="Photo de {{ $fullName }}">
                                @else
                                    {{ $initials ?: '—' }}
                                @endif
                            </div>
                            <span class="status-pill {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <div class="staff-card__body">
                            <h3 data-teacher-name>{{ $fullName }}</h3>
                            <p class="staff-role">{{ $enseignant->specialite }}</p>
                            <p class="staff-identifier">
                                <span>Code enseignant</span>
                                <span>{{ $enseignant->code_enseignant }}</span>
                            </p>
                            <p class="staff-contact">{{ $enseignant->email ?? '—' }}</p>
                            <div class="staff-tags">
                                <span class="tag">{{ $enseignant->type_enseignant }}</span>
                            </div>
                        </div>

                        <div class="staff-card__actions">
                            <button
                                class="primary-button"
                                type="button"
                                data-teacher-modal-open
                                data-teacher-url="{{ route('teachers.show', $enseignant) }}"
                                data-teacher-name="{{ $fullName }}"
                                data-archive-url="{{ route('teachers.archive', $enseignant) }}"
                            >
                                Fiche
                            </button>
                            <form method="POST" action="{{ route('teachers.archive', $enseignant) }}">
                                @csrf
                                @method('PUT')
                                <button class="secondary-button" type="submit">Archiver</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="muted">Aucun enseignant actif.</p>
                @endforelse
            </div>
        </div>

        <div class="staff-panel" data-teacher-panel="archived">
            <div class="staff-grid">
                @forelse ($archivedTeachers as $enseignant)
                    @php
                        $fullName = trim($enseignant->nom . ' ' . $enseignant->prenoms);
                        $statusClass = match ($enseignant->statut) {
                            'ACTIF' => 'status-active',
                            'SUSPENDU' => 'status-suspended',
                            'PARTI' => 'status-departed',
                            default => 'status-inactive',
                        };
                        $statusLabel = match ($enseignant->statut) {
                            'ACTIF' => 'Actif',
                            'SUSPENDU' => 'Suspendu',
                            'PARTI' => 'Archivé',
                            default => 'Inconnu',
                        };
                        $initials = collect(explode(' ', $fullName))
                            ->filter()
                            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                            ->take(2)
                            ->implode('');
                    @endphp
                    <article class="staff-card" data-teacher-card>
                        <div class="staff-card__header">
                            <div class="staff-avatar">
                                @if ($enseignant->photo_url)
                                    <img src="{{ $enseignant->photo_url }}" alt="Photo de {{ $fullName }}">
                                @else
                                    {{ $initials ?: '—' }}
                                @endif
                            </div>
                            <span class="status-pill {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <div class="staff-card__body">
                            <h3 data-teacher-name>{{ $fullName }}</h3>
                            <p class="staff-role">{{ $enseignant->specialite }}</p>
                            <p class="staff-identifier">
                                <span>Code enseignant</span>
                                <span>{{ $enseignant->code_enseignant }}</span>
                            </p>
                            <p class="staff-contact">{{ $enseignant->email ?? '—' }}</p>
                            <div class="staff-tags">
                                <span class="tag">{{ $enseignant->type_enseignant }}</span>
                            </div>
                        </div>

                        <div class="staff-card__actions">
                            <button
                                class="primary-button"
                                type="button"
                                data-teacher-modal-open
                                data-teacher-url="{{ route('teachers.show', $enseignant) }}"
                                data-teacher-name="{{ $fullName }}"
                                data-archive-url="{{ route('teachers.archive', $enseignant) }}"
                            >
                                Fiche
                            </button>
                            <button class="secondary-button" type="button" disabled>Archivé</button>
                        </div>
                    </article>
                @empty
                    <p class="muted">Aucun enseignant archivé.</p>
                @endforelse
            </div>
        </div>
    </div>

    @include('teachers.partials.teacher-modal')
    @include('teachers.partials.teacher-form-modal', [
        'isOpen' => $errors->any() || request()->get('open') === 'create',
    ])

    <script src="{{ asset('js/teachers/modal.js') }}" defer></script>
    <script src="{{ asset('js/teachers/form-modal.js') }}" defer></script>
    <script src="{{ asset('js/teachers/form.js') }}" defer></script>
    <script src="{{ asset('js/teachers/table.js') }}" defer></script>
</x-app-layout>
