document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('[data-staff-search]');
    const rows = document.querySelectorAll('[data-staff-row]');

    if (!searchInput) {
        return;
    }

    const normalize = (value) => (value || '').toLowerCase();

    const filterRows = () => {
        const query = normalize(searchInput.value);
        rows.forEach((row) => {
            const staffId = normalize(row.querySelector('[data-staff-id]')?.textContent);
            const staffName = normalize(row.querySelector('[data-staff-name]')?.textContent);
            const staffPosition = normalize(row.querySelector('[data-staff-position]')?.textContent);
            const matches = [staffId, staffName, staffPosition].some((field) => field.includes(query));
            row.style.display = matches ? '' : 'none';
        });
    };

    searchInput.addEventListener('input', filterRows);
});
