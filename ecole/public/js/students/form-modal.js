document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('student-form-modal');
    const openButton = document.querySelector('[data-form-modal-open]');
    const closeButtons = modal?.querySelectorAll('[data-form-modal-close]') || [];
    const tabButtons = modal?.querySelectorAll('[data-form-tab]') || [];
    const panels = modal?.querySelectorAll('[data-form-panel]') || [];
    const enrollmentInput = modal?.querySelector('#enrollment_date');
    const firstNameInput = modal?.querySelector('#first_name');
    const lastNameInput = modal?.querySelector('#last_name');
    const admissionPreview = modal?.querySelector('[data-admission-preview]');

    if (!modal) {
        return;
    }

    const resetTabs = () => {
        tabButtons.forEach((button) => {
            button.classList.remove('is-active');
            button.setAttribute('aria-selected', 'false');
        });
        panels.forEach((panel) => panel.classList.remove('is-active'));
    };

    const activateTab = (tabName) => {
        resetTabs();
        const activeButton = modal.querySelector(`[data-form-tab="${tabName}"]`);
        const activePanel = modal.querySelector(`[data-form-panel="${tabName}"]`);
        if (activeButton) {
            activeButton.classList.add('is-active');
            activeButton.setAttribute('aria-selected', 'true');
        }
        if (activePanel) {
            activePanel.classList.add('is-active');
        }
    };

    const openModal = () => {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        activateTab('identity');
    };

    const closeModal = () => {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    };

    const sanitizeLetters = (value) => (value || '').replace(/[^A-Za-z]/g, '');

    const updateAdmissionPreview = () => {
        if (!admissionPreview) {
            return;
        }
        const yearValue = enrollmentInput?.value
            ? new Date(enrollmentInput.value).getFullYear()
            : new Date().getFullYear();
        const lettersSource = `${sanitizeLetters(lastNameInput?.value)}${sanitizeLetters(firstNameInput?.value)}`;
        let letters = lettersSource.slice(0, 3).toUpperCase();
        if (letters.length < 3) {
            letters = letters.padEnd(3, 'X');
        }
        admissionPreview.textContent = `${yearValue}-${letters}`;
    };

    if (openButton) {
        openButton.addEventListener('click', openModal);
    }

    closeButtons.forEach((button) => {
        button.addEventListener('click', closeModal);
    });

    tabButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const tabName = button.getAttribute('data-form-tab');
            if (tabName) {
                activateTab(tabName);
            }
        });
    });

    [enrollmentInput, firstNameInput, lastNameInput].forEach((input) => {
        input?.addEventListener('input', updateAdmissionPreview);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    updateAdmissionPreview();

    if (modal.dataset.openOnLoad === 'true') {
        openModal();
    }
});
