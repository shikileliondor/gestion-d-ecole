document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('staff-modal');
    const modalTitle = modal?.querySelector('#staff-modal-title');
    const openButtons = document.querySelectorAll('[data-staff-modal-open]');
    const closeButtons = modal?.querySelectorAll('[data-staff-modal-close]') || [];
    const tabButtons = modal?.querySelectorAll('[data-tab]') || [];
    const panels = modal?.querySelectorAll('[data-panel]') || [];
    if (!modal) {
        return;
    }

    const infoFields = {
        staff_number: modal.querySelector('[data-field="staff_number"]'),
        first_name: modal.querySelector('[data-field="first_name"]'),
        last_name: modal.querySelector('[data-field="last_name"]'),
        email: modal.querySelector('[data-field="email"]'),
        statut: modal.querySelector('[data-field="statut"]'),
    };

    const listFields = {
        assignments: modal.querySelector('[data-field="assignments"]'),
    };

    const formatDate = (value) => {
        if (!value) {
            return '—';
        }
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) {
            return value;
        }
        return new Intl.DateTimeFormat('fr-FR').format(date);
    };

    const formatCurrency = (value) => {
        if (value === null || value === undefined || value === '') {
            return '—';
        }
        const numberValue = Number(value);
        if (Number.isNaN(numberValue)) {
            return value;
        }
        return `${new Intl.NumberFormat('fr-FR').format(numberValue)} FCFA`;
    };

    const setText = (element, value) => {
        if (!element) {
            return;
        }
        element.textContent = value || '—';
    };

    const setList = (element, items, formatter, emptyMessage) => {
        if (!element) {
            return;
        }
        element.innerHTML = '';
        if (!items || items.length === 0) {
            const empty = document.createElement('p');
            empty.className = 'empty';
            empty.textContent = emptyMessage;
            element.appendChild(empty);
            return;
        }
        items.forEach((item) => {
            const row = document.createElement('div');
            row.className = 'list-item';
            row.innerHTML = formatter(item);
            element.appendChild(row);
        });
    };

    const resetTabs = () => {
        tabButtons.forEach((button) => {
            button.classList.remove('is-active');
            button.setAttribute('aria-selected', 'false');
        });
        panels.forEach((panel) => panel.classList.remove('is-active'));
    };

    const activateTab = (tabName) => {
        resetTabs();
        const activeButton = modal.querySelector(`[data-tab="${tabName}"]`);
        const activePanel = modal.querySelector(`[data-panel="${tabName}"]`);
        if (activeButton) {
            activeButton.classList.add('is-active');
            activeButton.setAttribute('aria-selected', 'true');
        }
        if (activePanel) {
            activePanel.classList.add('is-active');
        }
    };

    const labelMaps = {
        statut: { ACTIF: 'Actif', SUSPENDU: 'Suspendu', PARTI: 'Parti' },
    };

    const openModal = (staffUrl, staffName) => {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        activateTab('info');
        if (modalTitle) {
            const prefix = modal.dataset.modalTitle || 'Fiche personnel';
            modalTitle.textContent = `${prefix} - ${staffName}`;
        }

        setText(infoFields.staff_number, '—');
        setText(infoFields.first_name, '—');
        setText(infoFields.last_name, '—');
        setText(infoFields.email, '—');
        setText(infoFields.statut, '—');
        setList(listFields.assignments, [], () => '', 'Aucune affectation enregistrée.');

        if (!staffUrl) {
            return;
        }

        fetch(staffUrl, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Erreur lors du chargement');
                }
                return response.json();
            })
            .then((data) => {
                const staff = data.staff || {};
                setText(infoFields.staff_number, staff.staff_number);
                setText(infoFields.first_name, staff.first_name);
                setText(infoFields.last_name, staff.last_name);
                setText(infoFields.email, staff.email);
                setText(infoFields.statut, labelMaps.statut[staff.statut] || staff.statut);

            })
            .catch(() => {
                setList(listFields.assignments, [], () => '', 'Impossible de charger les informations.');
            });
    };

    const closeModal = () => {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', () => {
            openModal(button.dataset.staffUrl, button.dataset.staffName || '');
        });
    });

    closeButtons.forEach((button) => {
        button.addEventListener('click', closeModal);
    });

    tabButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const tabName = button.getAttribute('data-tab');
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
});
