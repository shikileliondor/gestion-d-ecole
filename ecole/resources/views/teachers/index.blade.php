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

        <div class="staff-toolbar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Rechercher par nom, spécialité ou code..." data-teacher-search>
            </div>
        </div>

        <div class="staff-grid">
            @forelse ($enseignants as $enseignant)
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
                    </div>
                </article>
            @empty
                <p class="muted">Aucun enseignant enregistré.</p>
            @endforelse
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
