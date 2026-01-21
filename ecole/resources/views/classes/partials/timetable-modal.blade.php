@php
    $timetableRooms = isset($classes)
        ? $classes->pluck('room')->filter()->unique()->values()
        : collect();
    $timetableStaff = isset($staff)
        ? $staff->map(fn ($member) => [
            'id' => $member->id,
            'name' => trim($member->last_name.' '.$member->first_name),
            'position' => $member->position ?? null,
        ])->values()
        : collect();
    $timetableSubjects = isset($subjects)
        ? $subjects->map(fn ($subject) => [
            'id' => $subject->id,
            'name' => $subject->name,
        ])->values()
        : collect();
@endphp

<div
    class="modal"
    data-modal="timetable"
    data-timetable-staff='@json($timetableStaff)'
    data-timetable-subjects='@json($timetableSubjects)'
    aria-hidden="true"
>
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
                <span class="toolbar-label">Planning</span>
                <div class="toolbar-chip">Standard par classe</div>
            </div>
            <div class="toolbar-group">
                <span class="toolbar-label">Mode</span>
                <div class="toolbar-chip">Créneaux libres</div>
            </div>
            <div class="toolbar-group">
                <span class="toolbar-label">Synchronisation</span>
                <div class="toolbar-chip">Sauvegarde locale</div>
            </div>
            <button class="secondary-button" type="button">Exporter PDF</button>
        </div>

        <div class="timetable-layout" data-timetable-layout>
            <div class="timetable-config-panel">
                <div class="config-card">
                    <div class="config-card__header">
                        <h3>Jours de cours</h3>
                        <p class="helper-text">Activez uniquement les jours utilisés par la classe.</p>
                    </div>
                    <div class="day-options" data-day-options></div>
                </div>

                <div class="config-card">
                    <div class="config-card__header">
                        <h3>Planifier un créneau</h3>
                        <p class="helper-text">Définissez l'horaire, la matière, l'enseignant et la salle.</p>
                    </div>
                    <form class="slot-form" data-slot-form>
                        <div class="form-grid">
                            <label>
                                Jour
                                <select name="day" data-day-select required></select>
                            </label>
                            <label>
                                Début
                                <input name="start" type="time" required>
                            </label>
                            <label>
                                Fin
                                <input name="end" type="time" required>
                            </label>
                            <label>
                                Type
                                <select name="type" data-slot-type>
                                    <option value="course">Cours</option>
                                    <option value="pause">Pause</option>
                                </select>
                            </label>
                            <label>
                                Matière
                                <select name="subject" data-subject-select></select>
                            </label>
                            <label>
                                Enseignant
                                <select name="teacher" data-teacher-select></select>
                            </label>
                            <label>
                                Salle
                                <input name="room" type="text" list="timetable-rooms" placeholder="Salle A1">
                            </label>
                        </div>
                        <div class="form-actions">
                            <button class="ghost-button" type="button" data-reset-slot>Effacer</button>
                            <button class="primary-button" type="submit" data-submit-slot>Ajouter le créneau</button>
                        </div>
                        <input type="hidden" name="slot_id" data-slot-id>
                    </form>
                </div>
            </div>

            <div class="timetable-board">
                <div class="timetable-board__header">
                    <div>
                        <h3>Planning standard</h3>
                        <p class="helper-text">Cliquez sur un créneau pour le modifier ou le supprimer.</p>
                    </div>
                    <div class="board-actions">
                        <div class="timetable-view-toggle" role="tablist" aria-label="Changer la vue">
                            <button class="ghost-button" type="button" data-timetable-view="list">Vue liste</button>
                            <button class="ghost-button" type="button" data-timetable-view="grid">Vue grille</button>
                        </div>
                        <button class="secondary-button" type="button" data-clear-schedule>Tout effacer</button>
                    </div>
                </div>
                <div class="timetable-view" data-timetable-view-container="list">
                    <div class="timetable-days" data-timetable-days></div>
                    <div class="timetable-empty" data-timetable-empty hidden>
                        <strong>Aucun créneau pour le moment.</strong>
                        <p>Ajoutez un cours ou une pause pour commencer.</p>
                    </div>
                </div>
                <div class="timetable-view" data-timetable-view-container="grid" hidden>
                    <div class="timetable-grid" data-timetable-grid></div>
                    <div class="timetable-grid-empty" data-timetable-grid-empty hidden>
                        <strong>Aucun créneau pour le moment.</strong>
                        <p>Ajoutez un cours ou une pause pour commencer.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="timetable-footer">
            <div class="legend" data-timetable-legend></div>
            <div class="timetable-footer__actions">
                <span class="timetable-save-status" data-timetable-save-status></span>
                <button class="primary-button" type="button" data-save-timetable>Enregistrer le planning</button>
            </div>
        </div>
    </div>
</div>

<datalist id="timetable-rooms">
    @foreach ($timetableRooms as $room)
        <option value="{{ $room }}"></option>
    @endforeach
</datalist>
