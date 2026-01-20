document.addEventListener('DOMContentLoaded', () => {
    const modals = document.querySelectorAll('[data-modal]');
    const openButtons = document.querySelectorAll('[data-modal-open]');
    const closeButtons = document.querySelectorAll('[data-modal-close]');

    const closeModal = (modal) => {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    };

    const openModal = (modal, trigger) => {
        const actionTarget = modal.querySelector('[data-action-target]');
        const classLabel = modal.querySelector('[data-class-label]');
        const actionInput = modal.querySelector('[data-action-input]');
        const classLabelInput = modal.querySelector('[data-class-input]');
        const subjectSummary = modal.querySelector('[data-subject-summary]');

        if (actionTarget) {
            const action = trigger?.dataset?.action || actionTarget.dataset.actionFallback;
            if (action) {
                actionTarget.setAttribute('action', action);
            }
        }

        if (classLabel) {
            const label = trigger?.dataset?.className || classLabel.dataset.classFallback;
            if (label) {
                classLabel.textContent = label;
            }
        }

        if (actionInput && trigger?.dataset?.action) {
            actionInput.value = trigger.dataset.action;
        }

        if (classLabelInput && trigger?.dataset?.className) {
            classLabelInput.value = trigger.dataset.className;
        }

        if (subjectSummary) {
            const subjects = trigger?.dataset?.classSubjects
                ? JSON.parse(trigger.dataset.classSubjects)
                : [];
            subjectSummary.innerHTML = '';

            if (!subjects.length) {
                const empty = document.createElement('p');
                empty.className = 'helper-text';
                empty.textContent = 'Aucune matière affectée pour le moment.';
                subjectSummary.appendChild(empty);
            } else {
                subjects.forEach((subject) => {
                    const item = document.createElement('div');
                    item.className = 'subject-summary__item';

                    const header = document.createElement('div');
                    header.className = 'subject-summary__header';

                    const name = document.createElement('div');
                    name.className = 'subject-summary__name';
                    name.textContent = subject.name || 'Matière';

                    const meta = document.createElement('div');
                    meta.className = 'subject-summary__meta';
                    const levelParts = [];
                    if (subject.level) {
                        levelParts.push(subject.level);
                    }
                    if (subject.series) {
                        levelParts.push(`Série ${subject.series}`);
                    }
                    if (subject.coefficient) {
                        levelParts.push(`Coef. ${subject.coefficient}`);
                    }
                    meta.textContent = levelParts.length ? levelParts.join(' • ') : 'Tous niveaux';

                    header.appendChild(name);
                    header.appendChild(meta);

                    const teachers = document.createElement('div');
                    teachers.className = 'subject-summary__teachers';
                    if (subject.teachers && subject.teachers.length) {
                        subject.teachers.forEach((teacherName) => {
                            const badge = document.createElement('span');
                            badge.className = 'teacher-badge';
                            badge.textContent = teacherName;
                            teachers.appendChild(badge);
                        });
                    } else {
                        const emptyBadge = document.createElement('span');
                        emptyBadge.className = 'teacher-badge';
                        emptyBadge.textContent = 'Enseignant à définir';
                        teachers.appendChild(emptyBadge);
                    }

                    const color = document.createElement('div');
                    color.className = 'color-chip';
                    const swatch = document.createElement('span');
                    swatch.className = 'color-swatch';
                    swatch.style.backgroundColor = subject.color || '#e2e8f0';
                    const colorLabel = document.createElement('span');
                    colorLabel.textContent = subject.color ? `Couleur ${subject.color}` : 'Couleur automatique';
                    color.appendChild(swatch);
                    color.appendChild(colorLabel);

                    item.appendChild(header);
                    item.appendChild(teachers);
                    item.appendChild(color);
                    subjectSummary.appendChild(item);
                });
            }
        }

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const modalName = button.getAttribute('data-modal-open');
            const modal = document.querySelector(`[data-modal="${modalName}"]`);
            if (modal) {
                openModal(modal, button);
            }
        });
    });

    closeButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const modal = button.closest('[data-modal]');
            if (modal) {
                closeModal(modal);
            }
        });
    });

    modals.forEach((modal) => {
        if (modal.dataset.openOnLoad === 'true') {
            openModal(modal);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') {
            return;
        }

        modals.forEach((modal) => {
            if (modal.classList.contains('is-open')) {
                closeModal(modal);
            }
        });
    });
});
