document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('staff-form-modal');
    const openButtons = document.querySelectorAll('[data-form-modal-open]');
    const closeButtons = modal?.querySelectorAll('[data-form-modal-close]') || [];
    const tabButtons = modal?.querySelectorAll('[data-form-tab]') || [];
    const panels = modal?.querySelectorAll('[data-form-panel]') || [];
    const formTitle = modal?.querySelector('[data-form-title]');
    const formEyebrow = modal?.querySelector('[data-form-eyebrow]');
    const photoInput = modal?.querySelector('[data-photo-input]');
    const photoPreviewWrapper = modal?.querySelector('[data-photo-preview-wrapper]');
    const photoPreview = modal?.querySelector('[data-photo-preview]');
    const documentsInput = modal?.querySelector('[data-documents-input]');
    const documentsList = modal?.querySelector('[data-documents-list]');

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

    const updatePhotoPreview = (file) => {
        if (!photoPreviewWrapper || !photoPreview) {
            return;
        }
        if (!file) {
            photoPreviewWrapper.classList.add('is-hidden');
            photoPreview.src = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = (event) => {
            photoPreview.src = event.target?.result;
            photoPreviewWrapper.classList.remove('is-hidden');
        };
        reader.readAsDataURL(file);
    };

    const formatFileSize = (size) => {
        if (!size && size !== 0) {
            return '';
        }
        const units = ['o', 'Ko', 'Mo', 'Go'];
        let index = 0;
        let value = size;
        while (value >= 1024 && index < units.length - 1) {
            value /= 1024;
            index += 1;
        }
        return `${value.toFixed(value >= 10 || index === 0 ? 0 : 1)} ${units[index]}`;
    };

    const updateDocumentsList = (files) => {
        if (!documentsList) {
            return;
        }
        documentsList.innerHTML = '';
        if (!files || files.length === 0) {
            return;
        }
        Array.from(files).forEach((file, index) => {
            const item = document.createElement('div');
            item.className = 'file-list__item';
            const details = document.createElement('div');
            details.className = 'file-list__details';
            const name = document.createElement('p');
            name.className = 'file-list__name';
            name.textContent = file.name;
            const meta = document.createElement('p');
            meta.className = 'file-list__meta';
            meta.textContent = formatFileSize(file.size);
            details.appendChild(name);
            details.appendChild(meta);

            const labelWrapper = document.createElement('div');
            labelWrapper.className = 'file-list__label';
            const label = document.createElement('label');
            const inputId = `documents-label-${index}`;
            label.setAttribute('for', inputId);
            label.textContent = 'Libellé du document';
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'documents_labels[]';
            input.id = inputId;
            input.placeholder = 'Ex : Contrat de travail, Diplôme';
            labelWrapper.appendChild(label);
            labelWrapper.appendChild(input);

            item.appendChild(details);
            item.appendChild(labelWrapper);
            documentsList.appendChild(item);
        });
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

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    if (modal.dataset.openOnLoad === 'true') {
        openModal();
    }

    photoInput?.addEventListener('change', (event) => {
        updatePhotoPreview(event.target.files?.[0]);
    });

    documentsInput?.addEventListener('change', (event) => {
        updateDocumentsList(event.target.files);
    });
});
