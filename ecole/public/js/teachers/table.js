document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('[data-teacher-search]');
    const cards = document.querySelectorAll('[data-teacher-card]');

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
});
