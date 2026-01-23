document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('staff-form-modal');
    const openButtons = document.querySelectorAll('[data-form-modal-open]');
    const closeButtons = modal?.querySelectorAll('[data-form-modal-close]') || [];
    const tabButtons = modal?.querySelectorAll('[data-form-tab]') || [];
    const panels = modal?.querySelectorAll('[data-form-panel]') || [];
    const formTitle = modal?.querySelector('[data-form-title]');
    const formEyebrow = modal?.querySelector('[data-form-eyebrow]');
    const documentsStack = modal?.querySelector('[data-documents-stack]');
    const addDocumentButton = modal?.querySelector('[data-document-add]');

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
        }
    };

    const closeModal = () => {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    };

    const clearRowInputs = (row) => {
        const fields = row.querySelectorAll('input, select, textarea');
        fields.forEach((field) => {
            if (field.type === 'file') {
                field.value = '';
                return;
            }
            if (field.tagName === 'SELECT') {
                field.selectedIndex = 0;
                return;
            }
            field.value = '';
        });
    };

    const reindexDocuments = () => {
        if (!documentsStack) {
            return;
        }
        const rows = documentsStack.querySelectorAll('[data-document-row]');
        rows.forEach((row, index) => {
            const fields = row.querySelectorAll('input, select, textarea');
            fields.forEach((field) => {
                const name = field.getAttribute('name');
                if (!name) {
                    return;
                }
                field.setAttribute('name', name.replace(/documents\[\d+\]/, `documents[${index}]`));
            });
        });
        rows.forEach((row) => {
            const removeButton = row.querySelector('[data-document-remove]');
            if (removeButton) {
                removeButton.disabled = rows.length <= 1;
            }
        });
    };

    const addDocumentRow = () => {
        if (!documentsStack) {
            return;
        }
        const template = documentsStack.querySelector('[data-document-row]');
        if (!template) {
            return;
        }
        const clone = template.cloneNode(true);
        clearRowInputs(clone);
        documentsStack.appendChild(clone);
        reindexDocuments();
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

    addDocumentButton?.addEventListener('click', addDocumentRow);

    documentsStack?.addEventListener('click', (event) => {
        const removeButton = event.target.closest('[data-document-remove]');
        if (!removeButton || !documentsStack) {
            return;
        }
        const row = removeButton.closest('[data-document-row]');
        if (!row) {
            return;
        }
        const rows = documentsStack.querySelectorAll('[data-document-row]');
        if (rows.length <= 1) {
            return;
        }
        row.remove();
        reindexDocuments();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    reindexDocuments();

    if (modal.dataset.openOnLoad === 'true') {
        openModal();
    }
});
