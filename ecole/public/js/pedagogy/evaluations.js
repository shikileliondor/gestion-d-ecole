document.addEventListener('DOMContentLoaded', () => {
    const editModal = document.querySelector('[data-modal="evaluation-edit"]');
    const editForm = editModal?.querySelector('[data-evaluation-edit-form]');
    const routeTemplateEl = document.querySelector('[data-evaluation-edit-route]');

    if (!editModal || !editForm || !routeTemplateEl) {
        return;
    }

    const getRoute = (id) => routeTemplateEl.dataset.evaluationEditRoute.replace('__ID__', id);

    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-evaluation-id]');
        if (!button) {
            return;
        }
        const evaluationId = button.dataset.evaluationId;
        if (!evaluationId) {
            return;
        }

        editForm.setAttribute('action', getRoute(evaluationId));
        const periodSelect = editForm.querySelector('[data-evaluation-edit-period]');
        const typeSelect = editForm.querySelector('[data-evaluation-edit-type]');
        const dateInput = editForm.querySelector('[data-evaluation-edit-date]');
        const titleInput = editForm.querySelector('[data-evaluation-edit-title]');
        const scaleInput = editForm.querySelector('[data-evaluation-edit-scale]');

        if (periodSelect) {
            periodSelect.value = button.dataset.evaluationPeriod || '';
        }
        if (typeSelect) {
            typeSelect.value = button.dataset.evaluationType || '';
        }
        if (dateInput) {
            dateInput.value = button.dataset.evaluationDate || '';
        }
        if (titleInput) {
            titleInput.value = button.dataset.evaluationTitle || '';
        }
        if (scaleInput) {
            scaleInput.value = button.dataset.evaluationScale || '';
        }
    });
});
