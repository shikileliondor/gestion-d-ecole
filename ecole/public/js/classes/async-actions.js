document.addEventListener('DOMContentLoaded', () => {
    let classesGrid = document.querySelector('[data-classes-grid]');
    const successAlert = document.querySelector('[data-feedback-success]');
    const errorAlert = document.querySelector('[data-feedback-error]');
    const modals = document.querySelectorAll('[data-modal]');

    if (!classesGrid) {
        return;
    }

    const fetchOptions = {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        credentials: 'same-origin',
    };

    const clearAlert = (alertEl) => {
        if (!alertEl) {
            return;
        }
        alertEl.textContent = '';
        alertEl.hidden = true;
    };

    const showAlert = (alertEl, message, useHtml = false) => {
        if (!alertEl) {
            return;
        }
        if (useHtml) {
            alertEl.innerHTML = message;
        } else {
            alertEl.textContent = message;
        }
        alertEl.hidden = false;
    };

    const resetAlerts = () => {
        clearAlert(successAlert);
        clearAlert(errorAlert);
    };

    const closeModal = (modal) => {
        if (!modal) {
            return;
        }
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    };

    const getModalErrorBox = (modal) => {
        if (!modal) {
            return null;
        }
        let errorBox = modal.querySelector('[data-modal-errors]');
        if (!errorBox) {
            errorBox = document.createElement('div');
            errorBox.className = 'alert error';
            errorBox.hidden = true;
            errorBox.setAttribute('data-modal-errors', 'true');
            const header = modal.querySelector('.modal__header');
            if (header && header.parentElement) {
                header.parentElement.insertBefore(errorBox, header.nextSibling);
            } else {
                modal.prepend(errorBox);
            }
        }
        return errorBox;
    };

    const setSubmitting = (form, isSubmitting) => {
        if (!form) {
            return;
        }
        form.dataset.submitting = isSubmitting ? 'true' : 'false';
        const buttons = form.querySelectorAll('button[type="submit"]');
        buttons.forEach((button) => {
            button.disabled = isSubmitting;
            if (!button.dataset.defaultLabel) {
                button.dataset.defaultLabel = button.textContent.trim();
            }
            button.textContent = isSubmitting ? 'En cours…' : button.dataset.defaultLabel;
        });
    };

    const serializeForm = (form) => {
        const formData = new FormData(form);
        return formData;
    };

    const buildGetUrl = (action, formData) => {
        const url = new URL(action, window.location.origin);
        formData.forEach((value, key) => {
            if (value === null || value === undefined || value === '') {
                return;
            }
            url.searchParams.append(key, String(value));
        });
        return url;
    };

    const parseJson = async (response) => {
        try {
            return await response.json();
        } catch (error) {
            return null;
        }
    };

    const renderHtmlToElement = (html) => {
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html.trim();
        return wrapper.firstElementChild;
    };

    const applyClassCardUpdate = (html, classId) => {
        const newCard = renderHtmlToElement(html);
        if (!newCard) {
            return;
        }
        const existingCard = classesGrid.querySelector(`[data-class-card-id="${classId}"]`);
        if (existingCard) {
            existingCard.replaceWith(newCard);
        } else {
            classesGrid.prepend(newCard);
        }
    };

    const handleClassCreated = (html) => {
        const newCard = renderHtmlToElement(html);
        if (!newCard) {
            return;
        }
        classesGrid.prepend(newCard);
    };

    const handleSubjectAssigned = (payload) => {
        if (payload.card_html && payload.class_id) {
            applyClassCardUpdate(payload.card_html, payload.class_id);
        }
        if (payload.subject_summary_html) {
            const modal = document.querySelector('[data-modal="assign-subject"]');
            const summary = modal?.querySelector('[data-subject-summary]');
            if (summary) {
                summary.innerHTML = payload.subject_summary_html;
            }
        }
    };

    const handleStudentAssigned = (payload) => {
        if (payload.card_html && payload.class_id) {
            applyClassCardUpdate(payload.card_html, payload.class_id);
        }
    };

    const handleHeadcountUpdated = (payload) => {
        if (payload.card_html && payload.class_id) {
            applyClassCardUpdate(payload.card_html, payload.class_id);
        }
    };

    const handleSubjectCreated = (payload) => {
        if (payload.card_html && payload.class_id) {
            applyClassCardUpdate(payload.card_html, payload.class_id);
        }
        if (payload.subject_option_html) {
            const select = document.querySelector('[data-assign-subject-select]');
            if (select) {
                select.insertAdjacentHTML('beforeend', payload.subject_option_html);
            }
        }
    };

    const handleSeriesUpdated = (payload) => {
        if (payload.series_options_html) {
            const datalist = document.querySelector('#series-options');
            if (datalist) {
                datalist.innerHTML = payload.series_options_html;
            }
        }
    };

    const updateTimetableBadges = () => {
        const badges = document.querySelectorAll('[data-edt-status]');
        badges.forEach((badge) => {
            const classId = badge.dataset.classId;
            if (!classId) {
                return;
            }
            const raw = window.localStorage?.getItem(`timetable:${classId}`);
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
        });
    };

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

    const calculateHours = (slots) => {
        if (!Array.isArray(slots)) {
            return 0;
        }
        return slots.reduce((total, slot) => {
            if (slot?.type === 'pause') {
                return total;
            }
            const start = toMinutes(slot?.start);
            const end = toMinutes(slot?.end);
            if (start === null || end === null || end <= start) {
                return total;
            }
            return total + (end - start) / 60;
        }, 0);
    };

    const formatHours = (hours) => {
        if (!hours || hours <= 0) {
            return '0';
        }
        const rounded = Math.round(hours * 10) / 10;
        return Number.isInteger(rounded) ? String(rounded) : rounded.toFixed(1);
    };

    const updateTimetableHours = () => {
        const hourNodes = document.querySelectorAll('[data-class-hours]');
        hourNodes.forEach((node) => {
            const classId = node.dataset.classId;
            if (!classId) {
                return;
            }
            const raw = window.localStorage?.getItem(`timetable:${classId}`);
            if (!raw) {
                node.textContent = '0';
                return;
            }
            try {
                const parsed = JSON.parse(raw);
                const hours = calculateHours(parsed?.slots || []);
                node.textContent = formatHours(hours);
            } catch (error) {
                node.textContent = '0';
            }
        });
    };

    const handleClassFilter = (payload) => {
        if (payload.grid_html) {
            const wrapper = document.querySelector('[data-classes-grid-wrapper]');
            if (wrapper) {
                wrapper.innerHTML = payload.grid_html;
                classesGrid = wrapper.querySelector('[data-classes-grid]');
            }
        }
    };

    const handlers = {
        'class-create': (payload) => handleClassCreated(payload.card_html),
        'headcount-update': handleHeadcountUpdated,
        'assign-student': handleStudentAssigned,
        'assign-subject': handleSubjectAssigned,
        'subject-create': handleSubjectCreated,
        'series-update': handleSeriesUpdated,
        'classes-filter': handleClassFilter,
    };

    const updateForAction = (action, payload) => {
        const handler = handlers[action];
        if (handler) {
            handler(payload);
            updateTimetableBadges();
            updateTimetableHours();
        }
    };

    const refreshClassesGrid = async () => {
        if (document.hidden) {
            return;
        }
        if (document.querySelector('[data-modal].is-open')) {
            return;
        }
        try {
            const response = await fetch(window.location.href, fetchOptions);
            const payload = await parseJson(response);
            if (!response.ok || !payload) {
                return;
            }
            handleClassFilter(payload);
            updateTimetableBadges();
            updateTimetableHours();
        } catch (error) {
            // Ignorer les erreurs réseau pour le rafraîchissement automatique.
        }
    };

    const extractErrorMessages = (payload) => {
        if (!payload) {
            return ['Une erreur est survenue.'];
        }
        if (payload.message) {
            return [payload.message];
        }
        if (payload.errors) {
            return Object.values(payload.errors).flat();
        }
        return ['Une erreur est survenue.'];
    };

    const submitAsyncForm = async (form) => {
        if (form.dataset.submitting === 'true') {
            return;
        }

        resetAlerts();
        setSubmitting(form, true);

        const modal = form.closest('[data-modal]');
        const modalErrors = getModalErrorBox(modal);
        clearAlert(modalErrors);

        const action = form.dataset.asyncAction || '';
        const method = (form.method || 'POST').toUpperCase();
        const formData = serializeForm(form);
        const isGetRequest = method === 'GET';
        const requestUrl = isGetRequest ? buildGetUrl(form.action, formData).toString() : form.action;
        const requestInit = {
            method,
            ...fetchOptions,
        };

        if (!isGetRequest) {
            requestInit.body = formData;
        }

        try {
            const response = await fetch(requestUrl, requestInit);

            const payload = await parseJson(response);

            if (!response.ok) {
                const messages = extractErrorMessages(payload);
                const errorHtml = [
                    '<strong>Des erreurs doivent être corrigées :</strong>',
                    '<ul>',
                    ...messages.map((message) => `<li>${message}</li>`),
                    '</ul>',
                ].join('');
                showAlert(modalErrors || errorAlert, errorHtml, true);
                setSubmitting(form, false);
                return;
            }

            if (payload?.message) {
                showAlert(successAlert, payload.message);
            }
            if (payload) {
                updateForAction(action, payload);
            }

            if (action === 'classes-filter') {
                window.history.replaceState({}, '', requestUrl);
            } else {
                await refreshClassesGrid();
            }

            if (modal && !payload?.keep_open) {
                closeModal(modal);
            }
            if (!form.hasAttribute('data-no-reset')) {
                form.reset();
            }
        } catch (error) {
            showAlert(errorAlert, 'Une erreur est survenue. Veuillez réessayer.');
        } finally {
            setSubmitting(form, false);
        }
    };

    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) {
            return;
        }
        if (!form.hasAttribute('data-async-form')) {
            return;
        }
        event.preventDefault();
        submitAsyncForm(form);
    });

    modals.forEach((modal) => {
        const openOnLoad = modal.dataset.openOnLoad === 'true';
        if (!openOnLoad) {
            return;
        }
        const errorBox = getModalErrorBox(modal);
        if (errorBox && !errorBox.textContent.trim()) {
            errorBox.hidden = true;
        }
    });

    updateTimetableBadges();
    updateTimetableHours();

    const AUTO_REFRESH_MS = 60000;
    window.setInterval(refreshClassesGrid, AUTO_REFRESH_MS);
});
