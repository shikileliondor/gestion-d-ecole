document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('student-modal');
    const modalTitle = modal?.querySelector('#student-modal-title');
    const openButtons = document.querySelectorAll('[data-student-id]');
    const closeButtons = modal?.querySelectorAll('[data-student-modal-close]') || [];
    const tabButtons = modal?.querySelectorAll('[data-tab]') || [];
    const panels = modal?.querySelectorAll('[data-panel]') || [];

    if (!modal) {
        return;
    }

    const infoFields = {
        admission_number: modal.querySelector('[data-field="admission_number"]'),
        date_of_birth: modal.querySelector('[data-field="date_of_birth"]'),
        class_name: modal.querySelector('[data-field="class_name"]'),
        phone: modal.querySelector('[data-field="phone"]'),
        parent_name: modal.querySelector('[data-field="parent_name"]'),
        parent_phone: modal.querySelector('[data-field="parent_phone"]'),
        address: modal.querySelector('[data-field="address"]'),
        email: modal.querySelector('[data-field="email"]'),
    };

    const listFields = {
        grades: modal.querySelector('[data-field="grades"]'),
        payments: modal.querySelector('[data-field="payments"]'),
        documents: modal.querySelector('[data-field="documents"]'),
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

    const openModal = (studentUrl, studentName) => {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        activateTab('info');
        if (modalTitle) {
            modalTitle.textContent = `Fiche élève - ${studentName}`;
        }

        setText(infoFields.admission_number, '—');
        setText(infoFields.date_of_birth, '—');
        setText(infoFields.class_name, '—');
        setText(infoFields.phone, '—');
        setText(infoFields.parent_name, '—');
        setText(infoFields.parent_phone, '—');
        setText(infoFields.address, '—');
        setText(infoFields.email, '—');

        setList(listFields.grades, [], () => '', 'Aucune note disponible.');
        setList(listFields.payments, [], () => '', 'Aucun paiement enregistré.');
        setList(listFields.documents, [], () => '', 'Aucun document disponible.');

        if (!studentUrl) {
            return;
        }

        fetch(studentUrl, {
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
                const student = data.student || {};
                const parent = data.parent || {};
                const studentClass = data.class || {};

                setText(infoFields.admission_number, student.admission_number);
                setText(infoFields.date_of_birth, formatDate(student.date_of_birth));
                setText(infoFields.class_name, studentClass.name);
                setText(infoFields.phone, student.phone);
                setText(
                    infoFields.parent_name,
                    parent.first_name ? `${parent.first_name} ${parent.last_name || ''}`.trim() : null
                );
                setText(infoFields.parent_phone, parent.phone);
                setText(infoFields.address, student.address || student.city || student.country);
                setText(infoFields.email, student.email);

                setList(
                    listFields.grades,
                    data.grades,
                    (grade) => `
                        <div>
                            <p class="label">${grade.assessment || 'Évaluation'}</p>
                            <p class="value">${grade.score ?? '—'} / 20</p>
                        </div>
                        <div>
                            <p class="label">Appréciation</p>
                            <p class="value">${grade.remark || '—'}</p>
                        </div>
                        <div>
                            <p class="label">Date</p>
                            <p class="value">${formatDate(grade.graded_at)}</p>
                        </div>
                    `,
                    'Aucune note disponible.'
                );

                setList(
                    listFields.payments,
                    data.payments,
                    (payment) => `
                        <div>
                            <p class="label">Frais</p>
                            <p class="value">${payment.fee || '—'}</p>
                        </div>
                        <div>
                            <p class="label">Montant payé</p>
                            <p class="value">${payment.amount_paid ?? '—'} FCFA</p>
                        </div>
                        <div>
                            <p class="label">Statut</p>
                            <p class="value">${payment.status || '—'}</p>
                        </div>
                        <div>
                            <p class="label">Date</p>
                            <p class="value">${formatDate(payment.payment_date)}</p>
                        </div>
                    `,
                    'Aucun paiement enregistré.'
                );

                setList(
                    listFields.documents,
                    data.documents,
                    (documentItem) => `
                        <div>
                            <p class="label">Document</p>
                            <p class="value">${documentItem.name || '—'}</p>
                        </div>
                        <div>
                            <p class="label">Catégorie</p>
                            <p class="value">${documentItem.category || '—'}</p>
                        </div>
                        <div>
                            <p class="label">Statut</p>
                            <p class="value">${documentItem.status || '—'}</p>
                        </div>
                    `,
                    'Aucun document disponible.'
                );
            })
            .catch(() => {
                setText(infoFields.admission_number, '—');
                setText(infoFields.date_of_birth, '—');
                setText(infoFields.class_name, '—');
                setText(infoFields.phone, '—');
                setText(infoFields.parent_name, '—');
                setText(infoFields.parent_phone, '—');
                setText(infoFields.address, '—');
                setText(infoFields.email, '—');

                setList(listFields.grades, [], () => '', 'Aucune note disponible.');
                setList(listFields.payments, [], () => '', 'Aucun paiement enregistré.');
                setList(listFields.documents, [], () => '', 'Aucun document disponible.');
            });
    };

    const closeModal = () => {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const studentUrl = button.getAttribute('data-student-url');
            const studentName = button.getAttribute('data-student-name') || 'Élève';
            openModal(studentUrl, studentName);
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
