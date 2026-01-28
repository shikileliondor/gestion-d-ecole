<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/staff/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff/cards.css') }}">

    <div class="staff-page">
        <div class="staff-header">
            <div>
                <h1>{{ $title }}</h1>
                <p>{{ $subtitle }}</p>
            </div>
            <button
                class="primary-button"
                type="button"
                data-form-modal-open
                data-form-title="{{ $formTitle }}"
                data-form-eyebrow="{{ $formEyebrow }}"
            >
                + {{ $ctaLabel }}
            </button>
        </div>

        <div class="staff-toolbar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Rechercher par nom, fonction ou ID..." data-staff-search>
            </div>
        </div>

        <div class="staff-grid">
            @foreach ($staffMembers as $staff)
                @php
                    $fullName = trim($staff->nom . ' ' . $staff->prenoms);
                    $contact = collect([$staff->telephone_1, $staff->telephone_2, $staff->email])
                        ->filter()
                        ->implode(' · ');
                    $categoryLabels = [
                        'ADMINISTRATION' => 'Administration',
                        'SURVEILLANCE' => 'Surveillance',
                        'INTENDANCE' => 'Intendance',
                        'COMPTABILITE' => 'Comptabilité',
                        'TECHNIQUE' => 'Technique',
                        'SERVICE' => 'Service',
                    ];
                    $contractLabels = [
                        'CDI' => 'CDI',
                        'CDD' => 'CDD',
                        'VACATAIRE' => 'Vacataire',
                        'STAGE' => 'Stage',
                    ];
                    $tags = collect([
                        $categoryLabels[$staff->categorie_personnel] ?? $staff->categorie_personnel,
                        $contractLabels[$staff->type_contrat] ?? $staff->type_contrat,
                    ])->filter();
                    $initials = collect(explode(' ', $fullName))
                        ->filter()
                        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                        ->take(2)
                        ->implode('');
                    $photoUrl = $staff->photo_path ? Storage::url($staff->photo_path) : null;
                    $statusClass = match ($staff->statut) {
                        'ACTIF' => 'status-active',
                        'SUSPENDU' => 'status-suspended',
                        'PARTI' => 'status-departed',
                        default => 'status-inactive',
                    };
                    $statusLabel = match ($staff->statut) {
                        'ACTIF' => 'Actif',
                        'SUSPENDU' => 'Suspendu',
                        'PARTI' => 'Parti',
                        default => 'Inconnu',
                    };
                @endphp
                <article class="staff-card" data-staff-card>
                    <div class="staff-card__header">
                        <div class="staff-avatar">
                            @if ($photoUrl)
                                <img src="{{ $photoUrl }}" alt="Photo de {{ $fullName }}">
                            @else
                                {{ $initials ?: '—' }}
                            @endif
                        </div>
                        <span class="status-pill {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="staff-card__body">
                        <h3 data-staff-name>{{ $fullName }}</h3>
                        <p class="staff-role" data-staff-position>{{ $staff->poste ?? '—' }}</p>
                        <p class="staff-identifier">
                            <span>{{ $identifierLabel }}</span>
                            <span data-staff-id>{{ $staff->code_personnel }}</span>
                        </p>
                        <p class="staff-contact">{{ $contact ?: '—' }}</p>
                        <div class="staff-tags">
                            @forelse ($tags as $tag)
                                <span class="tag">{{ $tag }}</span>
                            @empty
                                <span class="tag">Aucune catégorie</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="staff-card__actions">
                        <button
                            class="primary-button"
                            type="button"
                            data-staff-modal-open
                            data-staff-id="{{ $staff->id }}"
                            data-staff-name="{{ $fullName }}"
                            data-staff-url="{{ route('staff.show', $staff) }}"
                        >
                            Fiche
                        </button>
                    </div>
                </article>
            @endforeach
        </div>
    </div>

    @include('staff.partials.staff-modal', [
        'identifierLabel' => $identifierLabel,
        'profileTitle' => $profileTitle,
    ])
    @include('staff.partials.staff-form-modal', [
        'isOpen' => $errors->any() || request()->get('open') === 'create',
        'formEyebrow' => $formEyebrow,
        'formTitle' => $formTitle,
    ])

    <script src="{{ asset('js/staff/modal.js') }}" defer></script>
    <script src="{{ asset('js/staff/form-modal.js') }}" defer></script>
    <script src="{{ asset('js/staff/table.js') }}" defer></script>
</x-app-layout>
