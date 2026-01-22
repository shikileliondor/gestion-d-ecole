<div
    class="modal"
    data-modal="timetable-preview"
    data-timetable-preview="true"
    aria-hidden="true"
>
    <div class="modal__overlay" data-modal-close></div>
    <div class="modal__content modal__content--wide timetable-preview" role="dialog" aria-modal="true">
        <div class="modal__header">
            <div>
                <h2>Emploi du temps - <span data-class-label data-class-fallback="Classe"></span></h2>
                <p>Vue synthétique des cours par créneau et par jour.</p>
            </div>
            <button class="icon-button" type="button" data-modal-close aria-label="Fermer">
                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <div class="timetable-preview__toolbar">
            <div class="toolbar-group">
                <span class="toolbar-label">Période</span>
                <div class="toolbar-chip">Semaine en cours</div>
            </div>
            <div class="toolbar-group">
                <span class="toolbar-label">Affichage</span>
                <div class="toolbar-chip">Vue grille</div>
            </div>
            <button class="secondary-button" type="button">Exporter PDF</button>
        </div>

        <div class="timetable-preview__grid" data-timetable-preview-grid></div>
        <div class="timetable-preview__empty" data-timetable-preview-empty hidden>
            <strong>Aucun créneau planifié.</strong>
            <p>Ajoutez des cours pour afficher l'emploi du temps.</p>
        </div>

        <div class="timetable-preview__footer">
            <div class="legend" data-timetable-preview-legend></div>
        </div>
    </div>
</div>
