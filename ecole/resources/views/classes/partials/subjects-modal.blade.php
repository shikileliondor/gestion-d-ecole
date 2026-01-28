<div
    class="modal"
    data-modal="subjects"
    aria-hidden="true"
    role="dialog"
>
    <div class="modal__overlay" data-modal-close></div>
    <div class="modal__content modal__content--wide">
        <div class="modal__header">
            <div>
                <h2>Matières - <span data-class-label data-class-fallback="Classe"></span></h2>
                <p>Liste des matières, enseignants et coefficients.</p>
            </div>
            <button class="icon-button" type="button" data-modal-close aria-label="Fermer">
                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <div class="subjects-table__wrapper">
            <table class="subjects-table">
                <thead>
                    <tr>
                        <th>Matière</th>
                        <th>Enseignant</th>
                        <th>Coefficient</th>
                    </tr>
                </thead>
                <tbody data-subjects-table></tbody>
            </table>
            <p class="helper-text" data-subjects-empty hidden>Aucune matière affectée pour le moment.</p>
        </div>
    </div>
</div>
