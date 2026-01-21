<div class="modal" data-modal="timetable" aria-hidden="true">
    <div class="modal__overlay" data-modal-close></div>
    <div class="modal__content modal__content--wide" role="dialog" aria-modal="true">
        <div class="modal__header">
            <div>
                <h2>Emploi du temps - <span data-class-label data-class-fallback="Classe"></span></h2>
                <p>Vue hebdomadaire des cours, salles et enseignants</p>
            </div>
            <button class="icon-button" type="button" data-modal-close aria-label="Fermer">
                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <div class="timetable-toolbar">
            <div class="toolbar-group">
                <span class="toolbar-label">Semaine</span>
                <div class="toolbar-chip">7 - 11 oct. 2024</div>
            </div>
            <div class="toolbar-group">
                <span class="toolbar-label">Période</span>
                <div class="toolbar-chip">Semestre 1</div>
            </div>
            <div class="toolbar-group">
                <span class="toolbar-label">Salle</span>
                <div class="toolbar-chip">Bâtiment A</div>
            </div>
            <button class="secondary-button" type="button">Exporter PDF</button>
        </div>

        <div class="timetable-scroll" role="region" aria-label="Emploi du temps">
            <table class="timetable" data-timetable>
                <thead>
                    <tr>
                        <th>Horaires</th>
                        <th>Lundi</th>
                        <th>Mardi</th>
                        <th>Mercredi</th>
                        <th>Jeudi</th>
                        <th>Vendredi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>08:00 - 09:00</th>
                        <td><div class="lesson-pill" style="--lesson-color: #e0f2fe">Mathématiques<br><span>Pr. Kouassi • Salle A1</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #fef3c7">Français<br><span>Pr. Yao • Salle B2</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #ede9fe">Anglais<br><span>Pr. Diallo • Salle C1</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #fce7f3">Histoire-Géo<br><span>Pr. Traoré • Salle A3</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #dcfce7">SVT<br><span>Pr. Mensah • Labo 2</span></div></td>
                    </tr>
                    <tr>
                        <th>09:00 - 10:00</th>
                        <td><div class="lesson-pill" style="--lesson-color: #fef3c7">Français<br><span>Pr. Yao • Salle B2</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #e0f2fe">Mathématiques<br><span>Pr. Kouassi • Salle A1</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #fce7f3">Physique<br><span>Pr. N'Guessan • Labo 1</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #ede9fe">Anglais<br><span>Pr. Diallo • Salle C1</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #e0e7ff">EPS<br><span>Pr. Koné • Gymnase</span></div></td>
                    </tr>
                    <tr>
                        <th>10:00 - 11:00</th>
                        <td><div class="lesson-pill" style="--lesson-color: #ede9fe">Anglais<br><span>Pr. Diallo • Salle C1</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #dcfce7">SVT<br><span>Pr. Mensah • Labo 2</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #e0f2fe">Mathématiques<br><span>Pr. Kouassi • Salle A1</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #fef3c7">Français<br><span>Pr. Yao • Salle B2</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #fce7f3">Physique<br><span>Pr. N'Guessan • Labo 1</span></div></td>
                    </tr>
                    <tr>
                        <th>11:00 - 12:00</th>
                        <td><div class="lesson-pill" style="--lesson-color: #dcfce7">SVT<br><span>Pr. Mensah • Labo 2</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #fce7f3">Physique<br><span>Pr. N'Guessan • Labo 1</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #fef3c7">Français<br><span>Pr. Yao • Salle B2</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #e0f2fe">Mathématiques<br><span>Pr. Kouassi • Salle A1</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #ede9fe">Anglais<br><span>Pr. Diallo • Salle C1</span></div></td>
                    </tr>
                    <tr>
                        <th>14:00 - 15:00</th>
                        <td><div class="lesson-pill" style="--lesson-color: #fce7f3">Histoire-Géo<br><span>Pr. Traoré • Salle A3</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #e0e7ff">EPS<br><span>Pr. Koné • Gymnase</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #dcfce7">SVT<br><span>Pr. Mensah • Labo 2</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #fef3c7">Français<br><span>Pr. Yao • Salle B2</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #e0f2fe">Mathématiques<br><span>Pr. Kouassi • Salle A1</span></div></td>
                    </tr>
                    <tr>
                        <th>15:00 - 16:00</th>
                        <td><div class="lesson-pill" style="--lesson-color: #dcfce7">SVT<br><span>Pr. Mensah • Labo 2</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #fce7f3">Physique<br><span>Pr. N'Guessan • Labo 1</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #fef3c7">Français<br><span>Pr. Yao • Salle B2</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #ede9fe">Anglais<br><span>Pr. Diallo • Salle C1</span></div></td>
                        <td><div class="lesson-pill" style="--lesson-color: #e0e7ff">EPS<br><span>Pr. Koné • Gymnase</span></div></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="timetable-footer">
            <div class="legend">
                <span class="legend-item" style="--legend-color: #e0f2fe">Mathématiques</span>
                <span class="legend-item" style="--legend-color: #fef3c7">Français</span>
                <span class="legend-item" style="--legend-color: #ede9fe">Anglais</span>
                <span class="legend-item" style="--legend-color: #fce7f3">Physique</span>
                <span class="legend-item" style="--legend-color: #dcfce7">SVT</span>
                <span class="legend-item" style="--legend-color: #e0e7ff">EPS</span>
            </div>
            <button class="primary-button" type="button">Planifier un cours</button>
        </div>
    </div>
</div>
