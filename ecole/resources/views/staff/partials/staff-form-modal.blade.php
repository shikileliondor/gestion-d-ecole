<div
    class="staff-form-modal"
    id="staff-form-modal"
    aria-hidden="true"
    role="dialog"
    aria-labelledby="staff-form-title"
    data-open-on-load="{{ $isOpen ? 'true' : 'false' }}"
>
    <div class="staff-form-modal__overlay" data-form-modal-close></div>
    <div class="staff-form-modal__content">
        <header class="staff-form-modal__header">
            <div>
                <p class="eyebrow" data-form-eyebrow>{{ $formEyebrow ?? 'Nouveau personnel' }}</p>
                <h2 id="staff-form-title" data-form-title>{{ $formTitle ?? 'Ajouter un membre du personnel' }}</h2>
            </div>
            <button class="icon-button" type="button" data-form-modal-close aria-label="Fermer">
                <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                    <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
        </header>

        <form class="staff-form" method="POST" action="{{ route('staff.store') }}" enctype="multipart/form-data">
            @csrf

            @if ($errors->any())
                <div class="form-alert">
                    <h3>Veuillez corriger les erreurs</h3>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="staff-form__panel is-active" data-form-panel="identity" role="tabpanel">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="code_personnel">Code personnel *</label>
                        <input type="text" id="code_personnel" name="code_personnel" value="{{ old('code_personnel') }}" required>
                    </div>
                    <div class="form-field">
                        <label for="nom">Nom *</label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required>
                    </div>
                    <div class="form-field">
                        <label for="prenoms">Prénoms *</label>
                        <input type="text" id="prenoms" name="prenoms" value="{{ old('prenoms') }}" required>
                    </div>
                    <div class="form-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button class="secondary-button" type="button" data-form-modal-close>Annuler</button>
                <button class="primary-button" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
