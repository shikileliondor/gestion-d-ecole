document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('[data-staff-search]');
    const cards = document.querySelectorAll('[data-staff-card]');

    if (!searchInput) {
        return;
    }

    const normalize = (value) => (value || '').toLowerCase();

    const filterRows = () => {
        const query = normalize(searchInput.value);
        cards.forEach((card) => {
            const staffId = normalize(card.querySelector('[data-staff-id]')?.textContent);
            const staffName = normalize(card.querySelector('[data-staff-name]')?.textContent);
            const staffPosition = normalize(card.querySelector('[data-staff-position]')?.textContent);
            const matches = [staffId, staffName, staffPosition].some((field) => field.includes(query));
            card.style.display = matches ? '' : 'none';
        });
    };

    searchInput.addEventListener('input', filterRows);
});
