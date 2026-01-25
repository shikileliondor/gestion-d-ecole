document.addEventListener('DOMContentLoaded', () => {
    const successAlert = document.querySelector('[data-feedback-success]');
    const errorAlert = document.querySelector('[data-feedback-error]');

    const showAlert = (element, message, useHtml = false) => {
        if (!element) {
            return;
        }
        element.hidden = false;
        if (useHtml) {
            element.innerHTML = message;
        } else {
            element.textContent = message;
        }
    };

    const clearAlert = (element) => {
        if (!element) {
            return;
        }
        element.textContent = '';
        element.hidden = true;
    };

    const setSubmitting = (form, submitting) => {
        form.dataset.submitting = submitting ? 'true' : 'false';
        const buttons = form.querySelectorAll('button[type="submit"]');
        buttons.forEach((button) => {
            button.disabled = submitting;
            if (!button.dataset.defaultLabel) {
                button.dataset.defaultLabel = button.textContent.trim();
            }
            button.textContent = submitting ? 'En cours…' : button.dataset.defaultLabel;
        });
    };

    const updateListTarget = (form, payload) => {
        if (payload?.list_html) {
            const targetSelector = form.dataset.asyncTarget;
            const target = targetSelector ? document.querySelector(targetSelector) : null;
            if (target) {
                target.innerHTML = payload.list_html;
            }
        }

        if (payload?.row_html && payload?.subject_id) {
            const row = document.querySelector(`[data-subject-row-id="${payload.subject_id}"]`);
            if (row) {
                const wrapper = document.createElement('tbody');
                wrapper.innerHTML = payload.row_html.trim();
                const newRow = wrapper.firstElementChild;
                if (newRow) {
                    row.replaceWith(newRow);
                }
            }
        }

        if (typeof payload?.locked === 'boolean' && form.hasAttribute('data-lock-toggle')) {
            const button = form.querySelector('[data-lock-button]');
            if (button) {
                button.textContent = payload.locked ? 'Déverrouiller la période' : 'Verrouiller la période';
            }
        }
    };

    const closeModal = (form) => {
        const modal = form.closest('[data-modal]');
        if (!modal) {
            return;
        }
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    };

    document.addEventListener('submit', async (event) => {
        const form = event.target;
        if (!form.matches('[data-async-form]')) {
            return;
        }
        event.preventDefault();
        clearAlert(successAlert);
        clearAlert(errorAlert);
        setSubmitting(form, true);

        const formData = new FormData(form);
        const fetchOptions = {
            method: form.method.toUpperCase(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
            body: formData,
        };

        try {
            const response = await fetch(form.action, fetchOptions);
            const payload = await response.json().catch(() => null);

            if (!response.ok) {
                const errors = payload?.errors ? Object.values(payload.errors).flat() : [];
                const message = payload?.message || (errors.length ? errors.join('<br>') : 'Une erreur est survenue.');
                showAlert(errorAlert, message, true);
                setSubmitting(form, false);
                return;
            }

            updateListTarget(form, payload);
            showAlert(successAlert, payload?.message || 'Opération réussie.');
            setSubmitting(form, false);
            closeModal(form);
        } catch (error) {
            showAlert(errorAlert, 'Une erreur est survenue.');
            setSubmitting(form, false);
        }
    });
});
