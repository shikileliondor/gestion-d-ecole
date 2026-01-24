import Chart from 'chart.js/auto';

const chartRoot = document.getElementById('dashboard-charts');

if (chartRoot) {
    const trendLabels = JSON.parse(chartRoot.dataset.trendLabels || '[]');
    const trendEntries = JSON.parse(chartRoot.dataset.trendEntries || '[]');
    const trendPayments = JSON.parse(chartRoot.dataset.trendPayments || '[]');
    const classLabels = JSON.parse(chartRoot.dataset.classLabels || '[]');
    const classValues = JSON.parse(chartRoot.dataset.classValues || '[]');

    const enrollmentChartEl = document.getElementById('enrollmentChart');
    if (enrollmentChartEl) {
        new Chart(enrollmentChartEl, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [
                    {
                        label: 'Inscriptions',
                        data: trendEntries,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        tension: 0.35,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#3B82F6',
                    },
                    {
                        label: 'Paiements',
                        data: trendPayments,
                        borderColor: '#FB7185',
                        backgroundColor: 'rgba(251, 113, 133, 0.2)',
                        tension: 0.35,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#FB7185',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        ticks: {
                            precision: 0,
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.15)',
                        },
                    },
                },
            },
        });
    }

    const classChartEl = document.getElementById('classChart');
    if (classChartEl) {
        new Chart(classChartEl, {
            type: 'doughnut',
            data: {
                labels: classLabels,
                datasets: [
                    {
                        data: classValues,
                        backgroundColor: [
                            '#3B82F6',
                            '#22C55E',
                            '#F59E0B',
                            '#8B5CF6',
                            '#EC4899',
                            '#06B6D4',
                        ],
                        borderWidth: 0,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        });
    }
}
