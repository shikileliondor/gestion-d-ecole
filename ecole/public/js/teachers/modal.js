document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('teacher-modal');
    const modalTitle = modal?.querySelector('#teacher-modal-title');
    const openButtons = document.querySelectorAll('[data-teacher-modal-open]');
    const closeButtons = modal?.querySelectorAll('[data-teacher-modal-close]') || [];
    const tabButtons = modal?.querySelectorAll('[data-tab]') || [];
    const panels = modal?.querySelectorAll('[data-panel]') || [];
    const archiveForm = modal?.querySelector('[data-archive-form]');
    const photoPreview = modal?.querySelector('[data-photo-preview]');
    const photoFallback = modal?.querySelector('[data-photo-fallback]');

    if (!modal) {
        return;
    }

    const infoFields = {
        staff_number: modal.querySelector('[data-field="staff_number"]'),
        first_name: modal.querySelector('[data-field="first_name"]'),
        last_name: modal.querySelector('[data-field="last_name"]'),
        email: modal.querySelector('[data-field="email"]'),
        telephone_1: modal.querySelector('[data-field="telephone_1"]'),
        specialite: modal.querySelector('[data-field="specialite"]'),
        type_enseignant: modal.querySelector('[data-field="type_enseignant"]'),
        statut: modal.querySelector('[data-field="statut"]'),
    };

    const listFields = {
        documents: modal.querySelector('[data-field="documents"]'),
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

    const setPhoto = (url, initials) => {
        if (!photoPreview || !photoFallback) {
            return;
        }
        if (url) {
            photoPreview.src = url;
            photoPreview.classList.remove('is-hidden');
            photoFallback.classList.add('is-hidden');
            return;
        }
        photoPreview.src = '';
        photoPreview.classList.add('is-hidden');
        photoFallback.textContent = initials || '—';
        photoFallback.classList.remove('is-hidden');
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
        statut: { ACTIF: 'Actif', SUSPENDU: 'Suspendu', PARTI: 'Archivé' },
    };

    const openModal = (teacherUrl, teacherName, archiveUrl) => {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        activateTab('info');

        if (modalTitle) {
            modalTitle.textContent = `Fiche enseignant - ${teacherName}`;
        }

        if (archiveForm && archiveUrl) {
            archiveForm.setAttribute('action', archiveUrl);
        }

        setText(infoFields.staff_number, '—');
        setText(infoFields.first_name, '—');
        setText(infoFields.last_name, '—');
        setText(infoFields.email, '—');
        setText(infoFields.telephone_1, '—');
        setText(infoFields.specialite, '—');
        setText(infoFields.type_enseignant, '—');
        setText(infoFields.statut, '—');
        setList(listFields.documents, [], () => '', 'Aucun document enregistré.');
        setPhoto(null, teacherName?.[0] ?? '—');

        if (!teacherUrl) {
            return;
        }

        fetch(teacherUrl, {
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
                const teacher = data.teacher || {};
                setText(infoFields.staff_number, teacher.staff_number);
                setText(infoFields.first_name, teacher.first_name);
                setText(infoFields.last_name, teacher.last_name);
                setText(infoFields.email, teacher.email);
                setText(infoFields.telephone_1, teacher.telephone_1);
                setText(infoFields.specialite, teacher.specialite);
                setText(infoFields.type_enseignant, teacher.type_enseignant);
                setText(infoFields.statut, labelMaps.statut[teacher.statut] || teacher.statut);
                setPhoto(teacher.photo_url, `${teacher.first_name?.[0] ?? ''}${teacher.last_name?.[0] ?? ''}`.trim());

                setList(
                    listFields.documents,
                    data.documents || [],
                    (document) => `
                        <div>
                            <p class="label">Document</p>
                            <p class="value">${document.libelle || '—'}</p>
                        </div>
                        <div>
                            <p class="label">Fichier</p>
                            <a class="link-button" href="${document.url || '#'}" target="_blank" rel="noreferrer">Télécharger</a>
                        </div>
                    `,
                    'Aucun document enregistré.'
                );
            })
            .catch(() => {
                setList(listFields.documents, [], () => '', 'Impossible de charger les informations.');
            });
    };

    const closeModal = () => {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', () => {
            openModal(button.dataset.teacherUrl, button.dataset.teacherName || '', button.dataset.archiveUrl);
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
