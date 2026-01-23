document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('staff-form-modal');
    const openButtons = document.querySelectorAll('[data-form-modal-open]');
    const closeButtons = modal?.querySelectorAll('[data-form-modal-close]') || [];
    const tabButtons = modal?.querySelectorAll('[data-form-tab]') || [];
    const panels = modal?.querySelectorAll('[data-form-panel]') || [];
    const positionInput = modal?.querySelector('#position');
    const subjectSelect = modal?.querySelector('#subjects');
    const teacherPanel = modal?.querySelector('[data-form-panel="teacher"]');
    const teacherInputs = teacherPanel?.querySelectorAll('input, textarea, select') || [];
    const formTitle = modal?.querySelector('[data-form-title]');
    const formEyebrow = modal?.querySelector('[data-form-eyebrow]');

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

    const openModal = (button) => {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        activateTab('identity');
        if (button) {
            if (formTitle && button.dataset.formTitle) {
                formTitle.textContent = button.dataset.formTitle;
            }
            if (formEyebrow && button.dataset.formEyebrow) {
                formEyebrow.textContent = button.dataset.formEyebrow;
            }
            if (positionInput && button.dataset.defaultPosition !== undefined) {
                positionInput.value = button.dataset.defaultPosition;
                toggleSubjects();
            }
        }
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

    const toggleTeacherFields = () => {
        if (!teacherPanel) {
            return;
        }
        const isTeacher = positionInput?.value?.toLowerCase().includes('enseignant');
        teacherInputs.forEach((input) => {
            input.disabled = !isTeacher;
        });
        teacherPanel.classList.toggle('is-disabled', !isTeacher);
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', () => openModal(button));
    });

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
    positionInput?.addEventListener('input', toggleTeacherFields);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    toggleSubjects();
    toggleTeacherFields();

    if (modal.dataset.openOnLoad === 'true') {
        openModal();
    }
});
