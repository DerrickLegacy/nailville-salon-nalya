// Import Chart.js
import {
    Chart, LineController, LineElement, Filler, PointElement, LinearScale, TimeScale, Tooltip,
} from 'chart.js';
import 'chartjs-adapter-moment';
import { chartAreaGradient } from '../app';
import { formatValue, getCssVariable, adjustColorOpacity } from '../utils';

Chart.register(LineController, LineElement, Filler, PointElement, LinearScale, TimeScale, Tooltip);

const dashboardCard02 = () => {
    const ctx = document.getElementById('dashboard-card-02');
    if (!ctx) return;

    const darkMode = localStorage.getItem('dark-mode') === 'true';

    const tooltipBodyColor = { light: '#6B7280', dark: '#9CA3AF' };
    const tooltipBgColor = { light: '#ffffff', dark: '#374151' };
    const tooltipBorderColor = { light: '#E5E7EB', dark: '#4B5563' };

    fetch('/json-dashboard-expense') // your route returning today vs yesterday data
        .then(res => res.json())
        .then(result => {
            // console.log("Results:", result.labels);

            // Map datasets directly from API
            const todayData = result.datasets.find(ds => ds.label === 'Today').data;
            const yesterdayData = result.datasets.find(ds => ds.label === 'Yesterday').data;

            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: result.labels,
                    datasets: [
                        {
                            label: 'Today',
                            data: todayData,
                            fill: true,
                            backgroundColor: function (context) {
                                const { chart, chartArea } = context;
                                return chartAreaGradient(chart.ctx, chartArea, [
                                    { stop: 0, color: adjustColorOpacity(getCssVariable('--color-violet-500'), 0) },
                                    { stop: 1, color: adjustColorOpacity(getCssVariable('--color-violet-500'), 0.2) }
                                ]);
                            },
                            borderColor: getCssVariable('--color-violet-500'),
                            borderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 3,
                            pointBackgroundColor: getCssVariable('--color-violet-500'),
                            pointHoverBackgroundColor: getCssVariable('--color-violet-500'),
                            tension: 0.2,
                        },
                        {
                            label: 'Yesterday',
                            data: yesterdayData,
                            borderColor: adjustColorOpacity(getCssVariable('--color-gray-500'), 0.25),
                            borderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 3,
                            pointBackgroundColor: adjustColorOpacity(getCssVariable('--color-gray-500'), 0.25),
                            pointHoverBackgroundColor: adjustColorOpacity(getCssVariable('--color-gray-500'), 0.25),
                            tension: 0.2,
                        },
                    ]
                },
                options: {
                    layout: { padding: 20 },
                    scales: {
                        y: { display: false, beginAtZero: true },
                        x: { display: true }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                title: () => false,
                                label: (context) => context.parsed.y.toLocaleString(),
                            },
                            bodyColor: darkMode ? tooltipBodyColor.dark : tooltipBodyColor.light,
                            backgroundColor: darkMode ? tooltipBgColor.dark : tooltipBgColor.light,
                            borderColor: darkMode ? tooltipBorderColor.dark : tooltipBorderColor.light,
                        },
                        legend: { display: false }
                    },
                    interaction: { intersect: false, mode: 'nearest' },
                    maintainAspectRatio: false,
                },
            });

            document.addEventListener('darkMode', (e) => {
                const { mode } = e.detail;
                chart.options.plugins.tooltip.bodyColor = mode === 'on' ? tooltipBodyColor.dark : tooltipBodyColor.light;
                chart.options.plugins.tooltip.backgroundColor = mode === 'on' ? tooltipBgColor.dark : tooltipBgColor.light;
                chart.options.plugins.tooltip.borderColor = mode === 'on' ? tooltipBorderColor.dark : tooltipBorderColor.light;
                chart.update('none');
            });
        });
};

export default dashboardCard02;
