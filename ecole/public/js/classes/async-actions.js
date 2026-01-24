document.addEventListener('DOMContentLoaded', () => {
    const classesGrid = document.querySelector('[data-classes-grid]');
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

    const handlers = {
        'class-create': (payload) => handleClassCreated(payload.card_html),
        'headcount-update': handleHeadcountUpdated,
        'assign-student': handleStudentAssigned,
        'assign-subject': handleSubjectAssigned,
        'subject-create': handleSubjectCreated,
        'series-update': handleSeriesUpdated,
    };

    const updateForAction = (action, payload) => {
        const handler = handlers[action];
        if (handler) {
            handler(payload);
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
        const method = form.method || 'POST';

        try {
            const response = await fetch(form.action, {
                method: method.toUpperCase(),
                body: serializeForm(form),
                ...fetchOptions,
            });

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

            if (modal && !payload?.keep_open) {
                closeModal(modal);
            }
            form.reset();
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
});
