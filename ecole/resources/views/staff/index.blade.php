<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/staff/modal.css') }}">

    <div class="staff-page">
        <div class="staff-header">
            <div>
                <h1>Gestion du personnel</h1>
                <p>Suivi des contrats et affectations pédagogiques</p>
            </div>
            <button class="primary-button" type="button" data-form-modal-open>+ Ajouter un membre</button>
        </div>

        <div class="staff-toolbar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Rechercher par nom, fonction ou ID..." data-staff-search>
            </div>
        </div>

        <div class="staff-table-wrapper">
            <table class="staff-table">
                <thead>
                    <tr>
                        <th>ID employé</th>
                        <th>Nom complet</th>
                        <th>Fonction</th>
                        <th>Contact</th>
                        <th>Type de contrat</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($staffMembers as $staff)
                        <tr data-staff-row>
                            <td data-staff-id>{{ $staff->staff_number }}</td>
                            <td data-staff-name>{{ $staff->last_name }} {{ $staff->first_name }}</td>
                            <td data-staff-position>{{ $staff->position ?? '—' }}</td>
                            <td>
                                <div class="contact-stack">
                                    <span>{{ $staff->phone ?? '—' }}</span>
                                    <span>{{ $staff->email ?? '—' }}</span>
                                </div>
                            </td>
                            <td>{{ $staff->contract_type ? strtoupper($staff->contract_type) : '—' }}</td>
                            <td>
                                <span class="status-pill {{ $staff->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                    {{ $staff->status === 'active' ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button
                                        class="icon-button"
                                        type="button"
                                        data-staff-id="{{ $staff->id }}"
                                        data-staff-name="{{ $staff->last_name }} {{ $staff->first_name }}"
                                        data-staff-url="{{ route('staff.show', $staff) }}"
                                        aria-label="Voir la fiche de {{ $staff->last_name }} {{ $staff->first_name }}"
                                    >
                                        <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                                            <path
                                                d="M12 5c5.05 0 9.27 3.11 11 7-1.73 3.89-5.95 7-11 7S2.73 15.89 1 12c1.73-3.89 5.95-7 11-7Zm0 2C7.82 7 4.31 9.24 2.75 12 4.31 14.76 7.82 17 12 17s7.69-2.24 9.25-5C19.69 9.24 16.18 7 12 7Zm0 2.5A2.5 2.5 0 1 1 9.5 12 2.5 2.5 0 0 1 12 9.5Zm0 1.8A.7.7 0 1 0 12.7 12 .7.7 0 0 0 12 11.3Z"
                                            />
                                        </svg>
                                    </button>
                                    @if ($staff->contract_id)
                                        <a class="link-button" href="{{ route('staff.contracts.download', $staff->contract_id) }}">
                                            Télécharger contrat
                                        </a>
                                    @else
                                        <span class="muted">Contrat indisponible</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('staff.partials.staff-modal')
    @include('staff.partials.staff-form-modal', [
        'subjects' => $subjects,
        'isOpen' => $errors->any() || request()->get('open') === 'create',
    ])

    <script src="{{ asset('js/staff/modal.js') }}" defer></script>
    <script src="{{ asset('js/staff/form-modal.js') }}" defer></script>
    <script src="{{ asset('js/staff/table.js') }}" defer></script>
</x-app-layout>
