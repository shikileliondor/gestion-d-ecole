document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('[data-teacher-search]');
    const cards = document.querySelectorAll('[data-teacher-card]');
    const tabButtons = document.querySelectorAll('[data-teacher-tab]');
    const panels = document.querySelectorAll('[data-teacher-panel]');

    if (!searchInput) {
        return;
    }

    const normalize = (value) => (value || '').toLowerCase();

    const filterRows = () => {
        const query = normalize(searchInput.value);
        cards.forEach((card) => {
            const teacherName = normalize(card.querySelector('[data-teacher-name]')?.textContent);
            const teacherSpecialite = normalize(card.querySelector('.staff-role')?.textContent);
            const teacherCode = normalize(card.querySelector('.staff-identifier span:last-child')?.textContent);
            const matches = [teacherName, teacherSpecialite, teacherCode].some((field) => field.includes(query));
            card.style.display = matches ? '' : 'none';
        });
    };

    searchInput.addEventListener('input', filterRows);

    const setActivePanel = (panelName) => {
        panels.forEach((panel) => {
            panel.classList.toggle('is-active', panel.dataset.teacherPanel === panelName);
        });
        tabButtons.forEach((button) => {
            const isActive = button.dataset.teacherTab === panelName;
            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });
    };

    tabButtons.forEach((button) => {
        button.addEventListener('click', () => {
            if (button.dataset.teacherTab) {
                setActivePanel(button.dataset.teacherTab);
                filterRows();
            }
        });
    });
});
