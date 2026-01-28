document.addEventListener('DOMContentLoaded', () => {
    const photoInput = document.querySelector('[data-photo-input]');
    const photoPreviewWrapper = document.querySelector('[data-photo-preview-wrapper]');
    const photoPreview = document.querySelector('[data-photo-preview]');
    const documentsInput = document.querySelector('[data-documents-input]');
    const documentsList = document.querySelector('[data-documents-list]');

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

    const updateDocumentsList = (files) => {
        if (!documentsList) {
            return;
        }
        documentsList.innerHTML = '';
        if (!files || files.length === 0) {
            return;
        }
        Array.from(files).forEach((file) => {
            const item = document.createElement('div');
            item.className = 'file-list__item';
            item.textContent = file.name;
            documentsList.appendChild(item);
        });
    };

    photoInput?.addEventListener('change', (event) => {
        updatePhotoPreview(event.target.files?.[0]);
    });

    documentsInput?.addEventListener('change', (event) => {
        updateDocumentsList(event.target.files);
    });
});
