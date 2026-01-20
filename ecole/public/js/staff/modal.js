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
        full_name: modal.querySelector('[data-field="full_name"]'),
        position: modal.querySelector('[data-field="position"]'),
        contact: modal.querySelector('[data-field="contact"]'),
        contract: modal.querySelector('[data-field="contract"]'),
        hire_date: modal.querySelector('[data-field="hire_date"]'),
        status: modal.querySelector('[data-field="status"]'),
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

    const openModal = (staffUrl, staffName) => {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        activateTab('info');
        if (modalTitle) {
            const prefix = modal.dataset.modalTitle || 'Fiche personnel';
            modalTitle.textContent = `${prefix} - ${staffName}`;
        }

        setText(infoFields.staff_number, '—');
        setText(infoFields.full_name, '—');
        setText(infoFields.position, '—');
        setText(infoFields.contact, '—');
        setText(infoFields.contract, '—');
        setText(infoFields.hire_date, '—');
        setText(infoFields.status, '—');
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
                const contract = data.contract || {};

                setText(infoFields.staff_number, staff.staff_number);
                setText(infoFields.full_name, `${staff.last_name || ''} ${staff.first_name || ''}`.trim());
                setText(infoFields.position, staff.position);
                setText(
                    infoFields.contact,
                    [staff.phone, staff.email].filter((item) => item).join(' · ')
                );
                if (contract.contract_type) {
                    setText(
                        infoFields.contract,
                        `${contract.contract_type.toUpperCase()} (${contract.status || 'actif'})`
                    );
                }
                setText(infoFields.hire_date, formatDate(staff.hire_date));
                setText(infoFields.status, staff.status === 'active' ? 'Actif' : 'Inactif');

                setList(
                    listFields.assignments,
                    data.assignments,
                    (assignment) => `
                        <div>
                            <p class="label">Matière</p>
                            <p class="value">${assignment.subject || '—'}</p>
                        </div>
                        <div>
                            <p class="label">Classe</p>
                            <p class="value">${assignment.class || '—'}</p>
                        </div>
                        <div>
                            <p class="label">Période</p>
                            <p class="value">${formatDate(assignment.start_date)} → ${assignment.end_date ? formatDate(assignment.end_date) : 'en cours'}</p>
                        </div>
                        <div>
                            <p class="label">Statut</p>
                            <p class="value">${assignment.status || '—'}</p>
                        </div>
                    `,
                    'Aucune affectation enregistrée.'
                );
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
