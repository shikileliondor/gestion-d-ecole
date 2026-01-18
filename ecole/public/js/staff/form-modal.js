document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('staff-form-modal');
    const openButton = document.querySelector('[data-form-modal-open]');
    const closeButtons = modal?.querySelectorAll('[data-form-modal-close]') || [];
    const tabButtons = modal?.querySelectorAll('[data-form-tab]') || [];
    const panels = modal?.querySelectorAll('[data-form-panel]') || [];
    const positionInput = modal?.querySelector('#position');
    const subjectSelect = modal?.querySelector('#subjects');

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

    const toggleSubjects = () => {
        if (!subjectSelect) {
            return;
        }
        const isTeacher = positionInput?.value?.toLowerCase().includes('enseignant');
        subjectSelect.disabled = !isTeacher;
        subjectSelect.closest('.form-field')?.classList.toggle('is-disabled', !isTeacher);
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

    positionInput?.addEventListener('input', toggleSubjects);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    toggleSubjects();

    if (modal.dataset.openOnLoad === 'true') {
        openModal();
    }
});
