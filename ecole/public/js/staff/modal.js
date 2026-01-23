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

    const rhFields = {
        type_contrat: modal.querySelector('[data-field="type_contrat"]'),
        date_debut_service: modal.querySelector('[data-field="date_debut_service"]'),
        date_fin_service: modal.querySelector('[data-field="date_fin_service"]'),
        num_cni: modal.querySelector('[data-field="num_cni"]'),
        date_expiration_cni: modal.querySelector('[data-field="date_expiration_cni"]'),
        photo_url: modal.querySelector('[data-field="photo_url"]'),
    };

    const urgenceFields = {
        contact_urgence_nom: modal.querySelector('[data-field="contact_urgence_nom"]'),
        contact_urgence_lien: modal.querySelector('[data-field="contact_urgence_lien"]'),
        contact_urgence_tel: modal.querySelector('[data-field="contact_urgence_tel"]'),
    };

    const paieFields = {
        mode_paiement: modal.querySelector('[data-field="mode_paiement"]'),
        numero_paiement: modal.querySelector('[data-field="numero_paiement"]'),
        salaire_base: modal.querySelector('[data-field="salaire_base"]'),
    };

    const listFields = {
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

        Object.values(infoFields).forEach((field) => setText(field, '—'));
        Object.values(rhFields).forEach((field) => setText(field, '—'));
        Object.values(urgenceFields).forEach((field) => setText(field, '—'));
        Object.values(paieFields).forEach((field) => setText(field, '—'));
        setList(listFields.documents, [], () => '', 'Aucun document enregistré.');

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

                setText(infoFields.code_personnel, staff.code_personnel);
                setText(infoFields.nom, staff.nom);
                setText(infoFields.prenoms, staff.prenoms);
                setText(infoFields.sexe, labelMaps.sexe[staff.sexe] || staff.sexe);
                setText(infoFields.date_naissance, formatDate(staff.date_naissance));
                setText(
                    infoFields.categorie_personnel,
                    labelMaps.categorie_personnel[staff.categorie_personnel] || staff.categorie_personnel
                );
                setText(infoFields.poste, staff.poste);
                setText(
                    infoFields.contact,
                    [staff.telephone_1, staff.telephone_2, staff.email].filter((item) => item).join(' · ')
                );
                setText(infoFields.adresse, staff.adresse);
                setText(infoFields.commune, staff.commune);
                setText(infoFields.statut, labelMaps.statut[staff.statut] || staff.statut);

                setText(rhFields.type_contrat, labelMaps.type_contrat[staff.type_contrat] || staff.type_contrat);
                setText(rhFields.date_debut_service, formatDate(staff.date_debut_service));
                setText(rhFields.date_fin_service, formatDate(staff.date_fin_service));
                setText(rhFields.num_cni, staff.num_cni);
                setText(rhFields.date_expiration_cni, formatDate(staff.date_expiration_cni));
                setText(rhFields.photo_url, staff.photo_url);

                setText(urgenceFields.contact_urgence_nom, staff.contact_urgence_nom);
                setText(
                    urgenceFields.contact_urgence_lien,
                    labelMaps.contact_urgence_lien[staff.contact_urgence_lien] || staff.contact_urgence_lien
                );
                setText(urgenceFields.contact_urgence_tel, staff.contact_urgence_tel);

                setText(
                    paieFields.mode_paiement,
                    labelMaps.mode_paiement[staff.mode_paiement] || staff.mode_paiement
                );
                setText(paieFields.numero_paiement, staff.numero_paiement);
                setText(paieFields.salaire_base, formatCurrency(staff.salaire_base));

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
                        <div>
                            <p class="label">Ajouté le</p>
                            <p class="value">${formatDate(document.created_at)}</p>
                        </div>
                        <div>
                            <p class="label">Fichier</p>
                            <p class="value">${document.url ? `<a href="${document.url}" target="_blank" rel="noreferrer">Télécharger</a>` : '—'}</p>
                        </div>
                    `,
                    'Aucun document enregistré.'
                );
            })
            .catch(() => {
                setList(listFields.documents, [], () => '', 'Impossible de charger les documents.');
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
