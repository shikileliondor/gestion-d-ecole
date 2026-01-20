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
                data-default-position="{{ $defaultPosition }}"
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
                    $fullName = trim($staff->last_name . ' ' . $staff->first_name);
                    $contact = collect([$staff->phone, $staff->email])->filter()->implode(' · ');
                    $tags = $isTeacherList
                        ? $staff->assignments->pluck('subject.name')->filter()->unique()
                        : collect([$staff->position ?: "Personnel"]);
                    $initials = collect(explode(' ', $fullName))
                        ->filter()
                        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                        ->take(2)
                        ->implode('');
                @endphp
                <article class="staff-card" data-staff-card>
                    <div class="staff-card__header">
                        <div class="staff-avatar">{{ $initials ?: '—' }}</div>
                        <span class="status-pill {{ $staff->status === 'active' ? 'status-active' : 'status-inactive' }}">
                            {{ $staff->status === 'active' ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>

                    <div class="staff-card__body">
                        <h3 data-staff-name>{{ $fullName }}</h3>
                        <p class="staff-role" data-staff-position>{{ $staff->position ?? '—' }}</p>
                        <p class="staff-identifier">
                            <span>{{ $identifierLabel }}</span>
                            <span data-staff-id>{{ $staff->staff_number }}</span>
                        </p>
                        <p class="staff-contact">{{ $contact ?: '—' }}</p>
                        <div class="staff-tags">
                            @forelse ($tags as $tag)
                                <span class="tag">{{ $tag }}</span>
                            @empty
                                <span class="tag">Aucune matière</span>
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
                        @if ($staff->contract_id)
                            <a class="secondary-button" href="{{ route('staff.contracts.download', $staff->contract_id) }}">
                                Contrat
                            </a>
                        @else
                            <span class="muted">Contrat indisponible</span>
                        @endif
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
        'subjects' => $subjects,
        'isOpen' => $errors->any() || request()->get('open') === 'create',
        'formEyebrow' => $formEyebrow,
        'formTitle' => $formTitle,
    ])

    <script src="{{ asset('js/staff/modal.js') }}" defer></script>
    <script src="{{ asset('js/staff/form-modal.js') }}" defer></script>
    <script src="{{ asset('js/staff/table.js') }}" defer></script>
</x-app-layout>
