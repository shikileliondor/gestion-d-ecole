document.addEventListener('DOMContentLoaded', () => {
    const modals = document.querySelectorAll('[data-modal]');
    const openButtons = document.querySelectorAll('[data-modal-open]');
    const closeButtons = document.querySelectorAll('[data-modal-close]');

    const closeModal = (modal) => {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    };

    const timetableDefaults = {
        days: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'],
        slots: [],
    };

    const weekDays = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    const toMinutes = (time) => {
        if (!time) {
            return null;
        }
        const [hours, minutes] = time.split(':').map(Number);
        if (Number.isNaN(hours) || Number.isNaN(minutes)) {
            return null;
        }
        return hours * 60 + minutes;
    };

    const collectTimeSlots = (slots) => {
        const timeSlots = [];
        const seen = new Set();

        slots.forEach((slot) => {
            if (!slot.start || !slot.end) {
                return;
            }
            const key = `${slot.start}-${slot.end}`;
            if (!seen.has(key)) {
                seen.add(key);
                timeSlots.push({ start: slot.start, end: slot.end });
            }
        });

        timeSlots.sort((a, b) => {
            const startDiff = (toMinutes(a.start) ?? 0) - (toMinutes(b.start) ?? 0);
            if (startDiff !== 0) {
                return startDiff;
            }
            return (toMinutes(a.end) ?? 0) - (toMinutes(b.end) ?? 0);
        });

        return timeSlots;
    };

    const getStorageKey = (classId) => `timetable:${classId || 'default'}`;

    const loadTimetableState = (classId) => {
        if (!classId) {
            return { ...timetableDefaults, slots: [...timetableDefaults.slots] };
        }
        const raw = window.localStorage?.getItem(getStorageKey(classId));
        if (!raw) {
            return { ...timetableDefaults, slots: [...timetableDefaults.slots] };
        }
        try {
            const parsed = JSON.parse(raw);
            return {
                days: Array.isArray(parsed.days) && parsed.days.length ? parsed.days : [...timetableDefaults.days],
                slots: Array.isArray(parsed.slots) ? parsed.slots : [],
            };
        } catch (error) {
            return { ...timetableDefaults, slots: [...timetableDefaults.slots] };
        }
    };

    const saveTimetableState = (classId, state) => {
        if (!classId || !window.localStorage) {
            return;
        }
        window.localStorage.setItem(getStorageKey(classId), JSON.stringify(state));
    };

    const updateTimetableBadge = (classId) => {
        if (!classId) {
            return;
        }
        const badge = document.querySelector(`[data-edt-status][data-class-id="${classId}"]`);
        if (!badge) {
            return;
        }
        const raw = window.localStorage?.getItem(getStorageKey(classId));
        if (!raw) {
            badge.textContent = 'EDT à planifier';
            badge.classList.remove('badge--success');
            badge.classList.add('badge--warning');
            return;
        }
        try {
            const parsed = JSON.parse(raw);
            const hasSlots = Array.isArray(parsed.slots) && parsed.slots.length > 0;
            badge.textContent = hasSlots ? 'EDT en place' : 'EDT à planifier';
            badge.classList.toggle('badge--success', hasSlots);
            badge.classList.toggle('badge--warning', !hasSlots);
        } catch (error) {
            badge.textContent = 'EDT à planifier';
            badge.classList.remove('badge--success');
            badge.classList.add('badge--warning');
        }
    };

    const openModal = (modal, trigger) => {
        const actionTarget = modal.querySelector('[data-action-target]');
        const classLabel = modal.querySelector('[data-class-label]');
        const actionInput = modal.querySelector('[data-action-input]');
        const classLabelInput = modal.querySelector('[data-class-input]');
        const subjectSummary = modal.querySelector('[data-subject-summary]');
        const timetableLayout = modal.querySelector('[data-timetable-layout]');
        const timetablePreviewGrid = modal.querySelector('[data-timetable-preview-grid]');

        if (actionTarget) {
            const action = trigger?.dataset?.action || actionTarget.dataset.actionFallback;
            if (action) {
                actionTarget.setAttribute('action', action);
            }
        }

        if (classLabel) {
            const label = trigger?.dataset?.className || classLabel.dataset.classFallback;
            if (label) {
                classLabel.textContent = label;
            }
        }

        if (actionInput && trigger?.dataset?.action) {
            actionInput.value = trigger.dataset.action;
        }

        if (classLabelInput && trigger?.dataset?.className) {
            classLabelInput.value = trigger.dataset.className;
        }

        if (subjectSummary) {
            const subjects = trigger?.dataset?.classSubjects
                ? JSON.parse(trigger.dataset.classSubjects)
                : [];
            subjectSummary.innerHTML = '';

            if (!subjects.length) {
                const empty = document.createElement('p');
                empty.className = 'helper-text';
                empty.textContent = 'Aucune matière affectée pour le moment.';
                subjectSummary.appendChild(empty);
            } else {
                subjects.forEach((subject) => {
                    const item = document.createElement('div');
                    item.className = 'subject-summary__item';

                    const header = document.createElement('div');
                    header.className = 'subject-summary__header';

                    const name = document.createElement('div');
                    name.className = 'subject-summary__name';
                    name.textContent = subject.name || 'Matière';

                    const meta = document.createElement('div');
                    meta.className = 'subject-summary__meta';
                    const levelParts = [];
                    if (subject.level) {
                        levelParts.push(subject.level);
                    }
                    if (subject.series) {
                        levelParts.push(`Série ${subject.series}`);
                    }
                    if (subject.coefficient) {
                        levelParts.push(`Coef. ${subject.coefficient}`);
                    }
                    meta.textContent = levelParts.length ? levelParts.join(' • ') : 'Tous niveaux';

                    header.appendChild(name);
                    header.appendChild(meta);

                    const teachers = document.createElement('div');
                    teachers.className = 'subject-summary__teachers';
                    if (subject.teachers && subject.teachers.length) {
                        subject.teachers.forEach((teacherName) => {
                            const badge = document.createElement('span');
                            badge.className = 'teacher-badge';
                            badge.textContent = teacherName;
                            teachers.appendChild(badge);
                        });
                    } else {
                        const emptyBadge = document.createElement('span');
                        emptyBadge.className = 'teacher-badge';
                        emptyBadge.textContent = 'Enseignant à définir';
                        teachers.appendChild(emptyBadge);
                    }

                    const color = document.createElement('div');
                    color.className = 'color-chip';
                    const swatch = document.createElement('span');
                    swatch.className = 'color-swatch';
                    swatch.style.backgroundColor = subject.color || '#e2e8f0';
                    const colorLabel = document.createElement('span');
                    colorLabel.textContent = subject.color ? `Couleur ${subject.color}` : 'Couleur automatique';
                    color.appendChild(swatch);
                    color.appendChild(colorLabel);

                    item.appendChild(header);
                    item.appendChild(teachers);
                    item.appendChild(color);
                    subjectSummary.appendChild(item);
                });
            }
        }

        if (timetableLayout) {
            const classId = trigger?.dataset?.classId || trigger?.dataset?.className || '';
            modal.dataset.activeClassId = classId;
            const classSubjects = trigger?.dataset?.classSubjects
                ? JSON.parse(trigger.dataset.classSubjects)
                : [];
            const staffOptions = modal.dataset.timetableStaff
                ? JSON.parse(modal.dataset.timetableStaff)
                : [];
            const subjectOptions = modal.dataset.timetableSubjects
                ? JSON.parse(modal.dataset.timetableSubjects)
                : [];

            const timetableState = loadTimetableState(classId);
            modal.timetableState = timetableState;

            const dayOptionsContainer = modal.querySelector('[data-day-options]');
            const daySelect = modal.querySelector('[data-day-select]');
            const slotForm = modal.querySelector('[data-slot-form]');
            const slotType = modal.querySelector('[data-slot-type]');
            const subjectSelect = modal.querySelector('[data-subject-select]');
            const teacherSelect = modal.querySelector('[data-teacher-select]');
            const submitButton = modal.querySelector('[data-submit-slot]');
            const resetButton = modal.querySelector('[data-reset-slot]');
            const slotIdInput = modal.querySelector('[data-slot-id]');
            const timetableDays = modal.querySelector('[data-timetable-days]');
            const timetableEmpty = modal.querySelector('[data-timetable-empty]');
            const timetableGrid = modal.querySelector('[data-timetable-grid]');
            const timetableGridEmpty = modal.querySelector('[data-timetable-grid-empty]');
            const legend = modal.querySelector('[data-timetable-legend]');
            const clearButton = modal.querySelector('[data-clear-schedule]');
            const viewButtons = Array.from(modal.querySelectorAll('[data-timetable-view]'));
            const viewContainers = Array.from(modal.querySelectorAll('[data-timetable-view-container]'));
            const saveButton = modal.querySelector('[data-save-timetable]');
            const saveStatus = modal.querySelector('[data-timetable-save-status]');

            const subjectsForClass = classSubjects.length ? classSubjects : subjectOptions;
            modal.timetableSubjects = subjectsForClass;
            modal.timetableStaff = staffOptions;
            const getState = () => modal.timetableState;
            const getClassId = () => modal.dataset.activeClassId || '';
            const getSubjects = () => modal.timetableSubjects || [];
            const getStaff = () => modal.timetableStaff || [];
            let saveStatusTimeout = null;

            const resetForm = () => {
                slotForm.reset();
                slotIdInput.value = '';
                submitButton.textContent = 'Ajouter le créneau';
                slotForm.dataset.mode = 'create';
                toggleSubjectFields();
            };

            const toggleSubjectFields = () => {
                const isPause = slotType.value === 'pause';
                subjectSelect.disabled = isPause;
                teacherSelect.disabled = isPause;
                slotForm.elements.room.disabled = isPause;
                if (isPause) {
                    subjectSelect.value = '';
                    teacherSelect.value = '';
                    slotForm.elements.room.value = '';
                }
            };

            const renderDayOptions = () => {
                const state = getState();
                dayOptionsContainer.innerHTML = '';
                daySelect.innerHTML = '';
                const activeDays = state.days.length ? state.days : [...weekDays];
                weekDays.forEach((day) => {
                    const wrapper = document.createElement('label');
                    wrapper.className = 'day-option';

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.value = day;
                    checkbox.checked = state.days.includes(day);
                    checkbox.addEventListener('change', () => {
                        const updatedState = getState();
                        if (checkbox.checked) {
                            updatedState.days.push(day);
                        } else {
                            updatedState.days = updatedState.days.filter((item) => item !== day);
                        }
                        renderSchedule();
                        saveTimetableState(getClassId(), updatedState);
                    });

                    const label = document.createElement('span');
                    label.textContent = day;

                    wrapper.appendChild(checkbox);
                    wrapper.appendChild(label);
                    dayOptionsContainer.appendChild(wrapper);

                    if (activeDays.includes(day)) {
                        const option = document.createElement('option');
                        option.value = day;
                        option.textContent = day;
                        daySelect.appendChild(option);
                    }
                });

                if (daySelect.options.length && !daySelect.value) {
                    daySelect.value = daySelect.options[0].value;
                }
            };

            const renderLegend = () => {
                legend.innerHTML = '';
                const subjects = getSubjects();
                if (!subjects.length) {
                    const empty = document.createElement('span');
                    empty.className = 'helper-text';
                    empty.textContent = 'Aucune matière assignée à cette classe.';
                    legend.appendChild(empty);
                    return;
                }
                subjects.forEach((subject) => {
                    const item = document.createElement('span');
                    item.className = 'legend-item';
                    if (subject.color) {
                        item.style.setProperty('--legend-color', subject.color);
                    }
                    item.textContent = subject.name || 'Matière';
                    legend.appendChild(item);
                });
            };

            const renderGrid = () => {
                if (!timetableGrid) {
                    return;
                }
                const state = getState();
                const days = state.days.length ? state.days : [...weekDays];
                const timeSlots = collectTimeSlots(state.slots);

                timetableGrid.innerHTML = '';
                timetableGrid.style.setProperty('--day-columns', days.length);

                if (!state.slots.length) {
                    if (timetableGridEmpty) {
                        timetableGridEmpty.hidden = false;
                    }
                    return;
                }

                if (timetableGridEmpty) {
                    timetableGridEmpty.hidden = true;
                }

                const headerRow = document.createElement('div');
                headerRow.className = 'timetable-grid__row';

                const timeHeader = document.createElement('div');
                timeHeader.className = 'timetable-grid__cell timetable-grid__cell--header';
                timeHeader.textContent = 'Horaires';
                headerRow.appendChild(timeHeader);

                days.forEach((day) => {
                    const cell = document.createElement('div');
                    cell.className = 'timetable-grid__cell timetable-grid__cell--header';
                    cell.textContent = day;
                    headerRow.appendChild(cell);
                });

                timetableGrid.appendChild(headerRow);

                timeSlots.forEach((timeSlot) => {
                    const row = document.createElement('div');
                    row.className = 'timetable-grid__row';

                    const timeCell = document.createElement('div');
                    timeCell.className = 'timetable-grid__cell timetable-grid__cell--time';
                    timeCell.textContent = `${timeSlot.start} - ${timeSlot.end}`;
                    row.appendChild(timeCell);

                    days.forEach((day) => {
                        const cell = document.createElement('div');
                        cell.className = 'timetable-grid__cell';
                        const slot = state.slots.find(
                            (item) => item.day === day && item.start === timeSlot.start && item.end === timeSlot.end
                        );

                        if (!slot) {
                            cell.classList.add('timetable-grid__cell--empty');
                            cell.textContent = 'Libre';
                        } else {
                            const card = document.createElement('div');
                            card.className = `slot-card ${slot.type === 'pause' ? 'slot-card--pause' : ''}`;
                            if (slot.color) {
                                card.style.setProperty('--slot-color', slot.color);
                            }

                            const title = document.createElement('div');
                            title.className = 'slot-card__title';
                            title.textContent = slot.type === 'pause' ? 'Pause' : slot.subjectName || 'Matière';

                            const meta = document.createElement('div');
                            meta.className = 'slot-card__meta';
                            if (slot.type === 'pause') {
                                meta.textContent = 'Créneau de pause';
                            } else {
                                const metaParts = [];
                                if (slot.teacherName) {
                                    metaParts.push(slot.teacherName);
                                }
                                if (slot.room) {
                                    metaParts.push(slot.room);
                                }
                                meta.textContent = metaParts.join(' • ') || 'Enseignant et salle à préciser';
                            }

                            card.appendChild(title);
                            card.appendChild(meta);
                            cell.appendChild(card);
                        }

                        row.appendChild(cell);
                    });

                    timetableGrid.appendChild(row);
                });
            };

            const setView = (view) => {
                viewContainers.forEach((container) => {
                    const isActive = container.dataset.timetableViewContainer === view;
                    container.hidden = !isActive;
                });
                viewButtons.forEach((button) => {
                    const isActive = button.dataset.timetableView === view;
                    button.classList.toggle('is-active', isActive);
                });
                modal.dataset.timetableView = view;
            };

            const renderSchedule = () => {
                const state = getState();
                timetableDays.innerHTML = '';
                const days = state.days.length ? state.days : [...weekDays];
                timetableDays.style.setProperty('--day-columns', days.length);

                days.forEach((day) => {
                    const column = document.createElement('div');
                    column.className = 'timetable-day';
                    column.dataset.day = day;

                    const header = document.createElement('div');
                    header.className = 'timetable-day__header';
                    header.textContent = day;

                    const list = document.createElement('div');
                    list.className = 'timetable-day__slots';

                    const slots = state.slots
                        .filter((slot) => slot.day === day)
                        .sort((a, b) => (a.start || '').localeCompare(b.start || ''));

                    if (!slots.length) {
                        const empty = document.createElement('div');
                        empty.className = 'timetable-day__empty';
                        empty.textContent = 'Aucun créneau';
                        list.appendChild(empty);
                    } else {
                        slots.forEach((slot) => {
                            const card = document.createElement('div');
                            card.className = `slot-card ${slot.type === 'pause' ? 'slot-card--pause' : ''}`;
                            card.dataset.slotId = slot.id;

                            const time = document.createElement('div');
                            time.className = 'slot-card__time';
                            time.textContent = slot.start && slot.end ? `${slot.start} - ${slot.end}` : 'Horaire à définir';

                            const title = document.createElement('div');
                            title.className = 'slot-card__title';
                            title.textContent = slot.type === 'pause' ? 'Pause' : slot.subjectName || 'Matière';
                            if (slot.color) {
                                card.style.setProperty('--slot-color', slot.color);
                            }

                            const meta = document.createElement('div');
                            meta.className = 'slot-card__meta';
                            if (slot.type === 'pause') {
                                meta.textContent = 'Créneau de pause';
                            } else {
                                const metaParts = [];
                                if (slot.teacherName) {
                                    metaParts.push(slot.teacherName);
                                }
                                if (slot.room) {
                                    metaParts.push(slot.room);
                                }
                                meta.textContent = metaParts.join(' • ') || 'Enseignant et salle à préciser';
                            }

                            const actions = document.createElement('div');
                            actions.className = 'slot-card__actions';

                            const editButton = document.createElement('button');
                            editButton.type = 'button';
                            editButton.className = 'ghost-button';
                            editButton.textContent = 'Modifier';
                            editButton.addEventListener('click', () => {
                                slotForm.elements.day.value = slot.day;
                                slotForm.elements.start.value = slot.start || '';
                                slotForm.elements.end.value = slot.end || '';
                                slotForm.elements.type.value = slot.type || 'course';
                                slotForm.elements.subject.value = slot.subjectId || '';
                                slotForm.elements.teacher.value = slot.teacherId || '';
                                slotForm.elements.room.value = slot.room || '';
                                slotIdInput.value = slot.id;
                                submitButton.textContent = 'Mettre à jour';
                                slotForm.dataset.mode = 'edit';
                                toggleSubjectFields();
                            });

                            const deleteButton = document.createElement('button');
                            deleteButton.type = 'button';
                            deleteButton.className = 'ghost-button ghost-button--danger';
                            deleteButton.textContent = 'Supprimer';
                            deleteButton.addEventListener('click', () => {
                                const updatedState = getState();
                                updatedState.slots = updatedState.slots.filter((item) => item.id !== slot.id);
                                renderSchedule();
                                saveTimetableState(getClassId(), updatedState);
                            });

                            actions.appendChild(editButton);
                            actions.appendChild(deleteButton);

                            card.appendChild(time);
                            card.appendChild(title);
                            card.appendChild(meta);
                            card.appendChild(actions);
                            list.appendChild(card);
                        });
                    }

                    column.appendChild(header);
                    column.appendChild(list);
                    timetableDays.appendChild(column);
                });

                if (state.slots.length) {
                    timetableEmpty.hidden = true;
                } else {
                    timetableEmpty.hidden = false;
                }

                renderDayOptions();
                renderGrid();
            };

            const setSelectOptions = () => {
                subjectSelect.innerHTML = '';
                teacherSelect.innerHTML = '';
                const subjects = getSubjects();
                const staff = getStaff();

                const subjectPlaceholder = document.createElement('option');
                subjectPlaceholder.value = '';
                subjectPlaceholder.textContent = subjects.length
                    ? 'Sélectionner une matière'
                    : 'Aucune matière';
                subjectSelect.appendChild(subjectPlaceholder);
                subjects.forEach((subject) => {
                    const option = document.createElement('option');
                    option.value = subject.id || subject.name;
                    option.textContent = subject.name || 'Matière';
                    subjectSelect.appendChild(option);
                });

                const teacherPlaceholder = document.createElement('option');
                teacherPlaceholder.value = '';
                teacherPlaceholder.textContent = staff.length
                    ? 'Sélectionner un enseignant'
                    : 'Aucun enseignant';
                teacherSelect.appendChild(teacherPlaceholder);
                staff.forEach((member) => {
                    const option = document.createElement('option');
                    option.value = member.id || member.name;
                    option.textContent = member.name;
                    teacherSelect.appendChild(option);
                });
            };

            const buildSlotPayload = () => {
                const subjectValue = slotForm.elements.subject.value;
                const teacherValue = slotForm.elements.teacher.value;
                const subjectData = getSubjects().find(
                    (subject) => String(subject.id || subject.name) === String(subjectValue)
                );
                const teacherData = getStaff().find(
                    (member) => String(member.id || member.name) === String(teacherValue)
                );
                return {
                    id: slotIdInput.value || `slot-${Date.now()}-${Math.random().toString(16).slice(2)}`,
                    day: slotForm.elements.day.value,
                    start: slotForm.elements.start.value,
                    end: slotForm.elements.end.value,
                    type: slotForm.elements.type.value,
                    subjectId: subjectValue || null,
                    subjectName: subjectData?.name || '',
                    teacherId: teacherValue || null,
                    teacherName: teacherData?.name || '',
                    room: slotForm.elements.room.value,
                    color: subjectData?.color || '',
                };
            };

            if (!modal.dataset.timetableInitialized) {
                slotType.addEventListener('change', toggleSubjectFields);
                slotForm.addEventListener('submit', (event) => {
                    event.preventDefault();
                    const payload = buildSlotPayload();
                    const updatedState = getState();
                    if (slotForm.dataset.mode === 'edit') {
                        updatedState.slots = updatedState.slots.map((slot) =>
                            slot.id === payload.id ? payload : slot
                        );
                    } else {
                        updatedState.slots.push(payload);
                    }
                    saveTimetableState(getClassId(), updatedState);
                    updateTimetableBadge(getClassId());
                    renderSchedule();
                    resetForm();
                });
                resetButton.addEventListener('click', resetForm);
                clearButton.addEventListener('click', () => {
                    const updatedState = getState();
                    updatedState.slots = [];
                    saveTimetableState(getClassId(), updatedState);
                    updateTimetableBadge(getClassId());
                    renderSchedule();
                    resetForm();
                });
                viewButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        setView(button.dataset.timetableView);
                    });
                });
                if (saveButton) {
                    saveButton.addEventListener('click', () => {
                        if (!saveStatus) {
                            saveTimetableState(getClassId(), getState());
                            updateTimetableBadge(getClassId());
                            return;
                        }
                        if (saveStatusTimeout) {
                            window.clearTimeout(saveStatusTimeout);
                        }
                        const classId = getClassId();
                        if (!classId) {
                            saveStatus.textContent = 'Sélectionnez une classe pour enregistrer.';
                            saveStatus.classList.add('is-error');
                            saveStatusTimeout = window.setTimeout(() => {
                                saveStatus.textContent = '';
                                saveStatus.classList.remove('is-error');
                            }, 3000);
                            return;
                        }
                        saveTimetableState(classId, getState());
                        updateTimetableBadge(classId);
                        saveStatus.textContent = 'Planning enregistré.';
                        saveStatus.classList.remove('is-error');
                        saveStatusTimeout = window.setTimeout(() => {
                            saveStatus.textContent = '';
                        }, 3000);
                    });
                }
                modal.dataset.timetableInitialized = 'true';
            }

            modal.timetableHandlers = {
                renderSchedule,
                resetForm,
                renderLegend,
                setSelectOptions,
            };

            setSelectOptions();
            renderLegend();
            renderSchedule();
            resetForm();
            setView(modal.dataset.timetableView || 'list');
        }

        if (timetablePreviewGrid) {
            const classId = trigger?.dataset?.classId || trigger?.dataset?.className || '';
            modal.dataset.activeClassId = classId;
            const classSubjects = trigger?.dataset?.classSubjects
                ? JSON.parse(trigger.dataset.classSubjects)
                : [];
            const timetableState = loadTimetableState(classId);
            const previewEmpty = modal.querySelector('[data-timetable-preview-empty]');
            const previewLegend = modal.querySelector('[data-timetable-preview-legend]');
            const days = timetableState.days.length ? timetableState.days : [...weekDays];
            const timeSlots = collectTimeSlots(timetableState.slots);

            timetablePreviewGrid.innerHTML = '';
            timetablePreviewGrid.style.setProperty('--day-columns', days.length);

            if (!timetableState.slots.length) {
                if (previewEmpty) {
                    previewEmpty.hidden = false;
                }
            } else if (previewEmpty) {
                previewEmpty.hidden = true;
            }

            if (timetableState.slots.length) {
                const headerRow = document.createElement('div');
                headerRow.className = 'timetable-preview__row';

                const timeHeader = document.createElement('div');
                timeHeader.className = 'timetable-preview__cell timetable-preview__cell--header';
                timeHeader.textContent = 'Horaires';
                headerRow.appendChild(timeHeader);

                days.forEach((day) => {
                    const cell = document.createElement('div');
                    cell.className = 'timetable-preview__cell timetable-preview__cell--header';
                    cell.textContent = day;
                    headerRow.appendChild(cell);
                });

                timetablePreviewGrid.appendChild(headerRow);

                timeSlots.forEach((timeSlot) => {
                    const row = document.createElement('div');
                    row.className = 'timetable-preview__row';

                    const timeCell = document.createElement('div');
                    timeCell.className = 'timetable-preview__cell timetable-preview__cell--time';
                    timeCell.textContent = `${timeSlot.start} - ${timeSlot.end}`;
                    row.appendChild(timeCell);

                    days.forEach((day) => {
                        const cell = document.createElement('div');
                        cell.className = 'timetable-preview__cell';

                        const slot = timetableState.slots.find(
                            (item) => item.day === day && item.start === timeSlot.start && item.end === timeSlot.end
                        );

                        if (!slot) {
                            cell.classList.add('timetable-preview__cell--empty');
                            cell.textContent = 'Libre';
                        } else {
                            const card = document.createElement('div');
                            card.className = 'timetable-preview__card';
                            if (slot.color) {
                                card.style.setProperty('--slot-color', slot.color);
                            }

                            const title = document.createElement('div');
                            title.className = 'timetable-preview__title';
                            title.textContent = slot.type === 'pause' ? 'Pause' : slot.subjectName || 'Matière';

                            const meta = document.createElement('div');
                            meta.className = 'timetable-preview__meta';
                            if (slot.type === 'pause') {
                                meta.textContent = 'Créneau de pause';
                            } else {
                                const metaParts = [];
                                if (slot.teacherName) {
                                    metaParts.push(slot.teacherName);
                                }
                                if (slot.room) {
                                    metaParts.push(slot.room);
                                }
                                meta.textContent = metaParts.join(' • ') || 'Enseignant et salle à préciser';
                            }

                            card.appendChild(title);
                            card.appendChild(meta);
                            cell.appendChild(card);
                        }

                        row.appendChild(cell);
                    });

                    timetablePreviewGrid.appendChild(row);
                });
            }

            if (previewLegend) {
                previewLegend.innerHTML = '';
                if (!classSubjects.length) {
                    const empty = document.createElement('span');
                    empty.className = 'helper-text';
                    empty.textContent = 'Aucune matière assignée à cette classe.';
                    previewLegend.appendChild(empty);
                } else {
                    classSubjects.forEach((subject) => {
                        const item = document.createElement('span');
                        item.className = 'legend-item';
                        if (subject.color) {
                            item.style.setProperty('--legend-color', subject.color);
                        }
                        item.textContent = subject.name || 'Matière';
                        previewLegend.appendChild(item);
                    });
                }
            }
        }

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const modalName = button.getAttribute('data-modal-open');
            const modal = document.querySelector(`[data-modal="${modalName}"]`);
            if (modal) {
                openModal(modal, button);
            }
        });
    });

    closeButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const modal = button.closest('[data-modal]');
            if (modal) {
                closeModal(modal);
            }
        });
    });

    modals.forEach((modal) => {
        if (modal.dataset.openOnLoad === 'true') {
            openModal(modal);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') {
            return;
        }

        modals.forEach((modal) => {
            if (modal.classList.contains('is-open')) {
                closeModal(modal);
            }
        });
    });
});
