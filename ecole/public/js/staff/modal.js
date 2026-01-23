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
        code_personnel: modal.querySelector('[data-field="code_personnel"]'),
        nom: modal.querySelector('[data-field="nom"]'),
        prenoms: modal.querySelector('[data-field="prenoms"]'),
        sexe: modal.querySelector('[data-field="sexe"]'),
        date_naissance: modal.querySelector('[data-field="date_naissance"]'),
        categorie_personnel: modal.querySelector('[data-field="categorie_personnel"]'),
        poste: modal.querySelector('[data-field="poste"]'),
        contact: modal.querySelector('[data-field="contact"]'),
        adresse: modal.querySelector('[data-field="adresse"]'),
        commune: modal.querySelector('[data-field="commune"]'),
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
        sexe: { M: 'M', F: 'F', AUTRE: 'Autre' },
        categorie_personnel: {
            ADMINISTRATION: 'Administration',
            SURVEILLANCE: 'Surveillance',
            INTENDANCE: 'Intendance',
            COMPTABILITE: 'Comptabilité',
            TECHNIQUE: 'Technique',
            SERVICE: 'Service',
        },
        type_contrat: { CDI: 'CDI', CDD: 'CDD', VACATAIRE: 'Vacataire', STAGE: 'Stage' },
        statut: { ACTIF: 'Actif', SUSPENDU: 'Suspendu', PARTI: 'Parti' },
        mode_paiement: { MOBILE_MONEY: 'Mobile Money', VIREMENT: 'Virement', CASH: 'Cash' },
        contact_urgence_lien: {
            PERE: 'Père',
            MERE: 'Mère',
            CONJOINT: 'Conjoint',
            FRERE_SOEUR: 'Frère/Soeur',
            TUTEUR: 'Tuteur',
            AUTRE: 'Autre',
        },
        type_document: {
            CNI: 'CNI',
            CONTRAT: 'Contrat',
            DIPLOME: 'Diplôme',
            CV: 'CV',
            ATTESTATION: 'Attestation',
            AUTRE: 'Autre',
        },
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
                    [staff.telephone_1, staff.telephone_2, staff.email].filter((item) => item).join(' · ')
                );
                setText(infoFields.adresse, staff.adresse);
                setText(infoFields.commune, staff.commune);
                setText(infoFields.statut, labelMaps.statut[staff.statut] || staff.statut);

                setList(
                    listFields.documents,
                    data.documents,
                    (document) => `
                        <div>
                            <p class="label">Libellé</p>
                            <p class="value">${document.libelle || '—'}</p>
                        </div>
                        <div>
                            <p class="label">Type</p>
                            <p class="value">${labelMaps.type_document[document.type_document] || document.type_document || '—'}</p>
                        </div>
                        <div>
                            <p class="label">Description</p>
                            <p class="value">${document.description || '—'}</p>
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
