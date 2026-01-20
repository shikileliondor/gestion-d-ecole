document.addEventListener('DOMContentLoaded', () => {
    const modals = document.querySelectorAll('[data-modal]');
    const openButtons = document.querySelectorAll('[data-modal-open]');
    const closeButtons = document.querySelectorAll('[data-modal-close]');

    const closeModal = (modal) => {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    };

    const openModal = (modal, trigger) => {
        const actionTarget = modal.querySelector('[data-action-target]');
        const classLabel = modal.querySelector('[data-class-label]');
        const actionInput = modal.querySelector('[data-action-input]');
        const classLabelInput = modal.querySelector('[data-class-input]');

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
