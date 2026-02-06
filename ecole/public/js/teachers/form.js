document.addEventListener('DOMContentLoaded', () => {
    const photoInput = document.querySelector('[data-photo-input]');
    const photoPreviewWrapper = document.querySelector('[data-photo-preview-wrapper]');
    const photoPreview = document.querySelector('[data-photo-preview]');
    const documentsStack = document.querySelector('[data-documents-stack]');
    const addDocumentButton = document.querySelector('[data-add-document]');

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

    const attachRemoveHandler = (row) => {
        const removeButton = row.querySelector('[data-remove-document]');
        if (!removeButton) {
            return;
        }
        removeButton.addEventListener('click', () => {
            if (!documentsStack) {
                return;
            }
            const rows = documentsStack.querySelectorAll('[data-document-row]');
            if (rows.length <= 1) {
                row.querySelectorAll('input').forEach((input) => {
                    input.value = '';
                });
                return;
            }
            row.remove();
        });
    };

    const createDocumentRow = () => {
        const wrapper = document.createElement('div');
        wrapper.className = 'document-row';
        wrapper.dataset.documentRow = 'true';
        wrapper.innerHTML = `
            <div class="form-grid">
                <div class="form-field">
                    <label>Libellé</label>
                    <input type="text" name="documents_labels[]" placeholder="Contrat, diplôme, CV...">
                </div>
                <div class="form-field">
                    <label>Fichier</label>
                    <input type="file" name="documents[]" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                </div>
            </div>
            <div class="document-actions">
                <button class="secondary-button is-light" type="button" data-remove-document>Retirer</button>
            </div>
        `;
        return wrapper;
    };

    photoInput?.addEventListener('change', (event) => {
        updatePhotoPreview(event.target.files?.[0]);
    });

    documentsStack?.querySelectorAll('[data-document-row]').forEach((row) => {
        attachRemoveHandler(row);
    });

    addDocumentButton?.addEventListener('click', () => {
        if (!documentsStack) {
            return;
        }
        const newRow = createDocumentRow();
        documentsStack.appendChild(newRow);
        attachRemoveHandler(newRow);
        const input = newRow.querySelector('input[type="text"]');
        input?.focus();
    });
});
