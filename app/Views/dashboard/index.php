<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/dashboard.css">

<div class="dashboard-header">
    <h1>Daily Invoice Analytics (Current Month)</h1>
</div>
 
<div class="dashboard-analytics">
    <canvas id="invoiceChart" width="400" height="200"></canvas>
</div>
 
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('invoiceChart').getContext('2d');
 
// PHP data
const invoiceData = <?= json_encode($salesData ?? []) ?>;
const labels = invoiceData.map(item => item.date);
const data = invoiceData.map(item => item.total);
 
const invoiceChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Daily Invoice Total ($)',
            data: data,
            backgroundColor: '#4CAF50'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: { display: true, text: 'Daily Invoice Totals (Current Month)' }
        },
        scales: {
            y: { beginAtZero: true },
            x: { ticks: { autoSkip: true, maxTicksLimit: 15 } } // prevent clutter
        }
    }
});
</script>
 
 