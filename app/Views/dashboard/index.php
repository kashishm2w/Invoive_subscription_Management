<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/dashboard.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Admin Dashboard</h1>
        <p class="dashboard-subtitle">Overview of your invoice statistics for <?= date('F Y') ?></p>
    </div>
    <!-- Statistics Cards -->

    <div class="stats-grid">
        <!-- Total Amount Card -->
        <div class="stat-card total">
            <div class="stat-icon">
                <span style="font-size: 32px; font-weight: bold;">&#36;</span>
            </div>
            <div class="stat-info">
                <h3>Total Amount</h3>
                <p class="stat-value">&#36;<?= number_format($stats['total_amount'] ?? 0, 2) ?></p>
            </div>
        </div>
        <!-- Paid Card -->
        <div class="stat-card paid">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div class="stat-info">
                <h3>Paid</h3>
                <p class="stat-value">&#36;<?= number_format($stats['total_received'] ?? 0, 2) ?></p>

            </div>
        </div>
        <!-- Unpaid Card -->
        <div class="stat-card unpaid">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
            </div>
            <div class="stat-info">
                <h3>Unpaid </h3>
                <p class="stat-value">&#36;<?= number_format($stats['total_outstanding'] ?? 0, 2) ?></p>


            </div>
        </div>

    </div>
    <h3>Choose date</h3>
    <div id="reportrange" class="calendar">
        <input type="text" name="datefilter" id="datefilter" placeholder="Select date range" />
        <i class="fa fa-calendar"></i>&nbsp;
        <span></span> <i class="fa fa-caret-down"></i>
    </div>
    <script>
        $(function() {

            var start = moment().subtract(29, 'days');
            var end = moment();

            function updateDisplay(start, end) {
                // Update input
                $('#datefilter').val(
                    start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY')
                );

                // Update visual range
                $('#reportrange span').html(
                    start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY')
                );
            }

            // Fetch chart data via AJAX and update chart/stats
            function fetchChartData(startDate, endDate) {
                const startFormatted = startDate.format('YYYY-MM-DD');
                const endFormatted = endDate.format('YYYY-MM-DD');

                // Show loading state
                $('.chart-container').css('opacity', '0.5');
                $('.stat-value').text('Loading...');

                fetch(`/dashboard/chart-data?start_date=${startFormatted}&end_date=${endFormatted}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update chart
                            const labels = data.salesData.map(item => item.date);
                            const totalData = data.salesData.map(item => item.total);
                            const paidData = data.salesData.map(item => item.paid);
                            const unpaidData = data.salesData.map(item => item.unpaid);

                            invoiceChart.data.labels = labels;
                            invoiceChart.data.datasets[0].data = totalData;
                            invoiceChart.data.datasets[1].data = paidData;
                            invoiceChart.data.datasets[2].data = unpaidData;
                            invoiceChart.update();

                            // Update stats cards
                            $('.stat-card.total .stat-value').text('$' + data.stats.total_amount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                            $('.stat-card.paid .stat-value').text('$' + data.stats.total_received.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                            $('.stat-card.unpaid .stat-value').text('$' + data.stats.total_outstanding.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));

                            // Update pie chart
                            invoicePieChart.data.datasets[0].data = [
                                data.stats.total_received,
                                data.stats.total_outstanding
                            ];
                            invoicePieChart.update();

                            // Update chart period text
                            $('.chart-period').text(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format('MMMM D, YYYY'));
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching chart data:', error);
                    })
                    .finally(() => {
                        $('.chart-container').css('opacity', '1');
                    });
            }

            $('#reportrange, #datefilter').daterangepicker({
                startDate: start,
                endDate: end,
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [
                        moment().subtract(1, 'month').startOf('month'),
                        moment().subtract(1, 'month').endOf('month')
                    ]
                }
            }, function(start, end) {
                updateDisplay(start, end);
                fetchChartData(start, end);
            });

            // Apply
            $('#datefilter').on('apply.daterangepicker', function(ev, picker) {
                updateDisplay(picker.startDate, picker.endDate);
                fetchChartData(picker.startDate, picker.endDate);
            });

            // Clear
            $('#datefilter').on('cancel.daterangepicker', function() {
                $(this).val('');
                $('#reportrange span').html('');
            });

            // Init
            updateDisplay(start, end);
        });
    </script>



    <!-- Chart Section -->
    <div class="charts-row">
        <div class="chart-section">
            <div class="chart-header">
                <h2>Daily Invoice Analytics</h2>
                <span class="chart-period"><?= date('F Y') ?></span>
            </div>
            <div class="chart-container">
                <canvas id="invoiceChart"></canvas>
            </div>
        </div>

        <div class="chart-section">
            <div class="chart-header">
                <h2>Invoice Status Distribution</h2>
                <span class="chart-period">
                    <?= 'December 2025 - ' . date('F Y') ?>
                </span>
            </div>
            <div class="chart-container" style="max-width: 400px; margin: auto;">
                <canvas id="invoicePieChart"></canvas>
            </div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('invoiceChart').getContext('2d');
    // PHP data
    const invoiceData = <?= json_encode($salesData ?? []) ?>;
    const labels = invoiceData.map(item => item.date);
    const totalData = invoiceData.map(item => item.total);
    const paidData = invoiceData.map(item => item.paid);
    const unpaidData = invoiceData.map(item => item.unpaid);

    const invoiceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                    label: 'Total',
                    data: totalData,
                    backgroundColor: 'rgba(52, 152, 219, 0.8)',
                    borderColor: 'rgba(52, 152, 219, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'Paid',
                    data: paidData,
                    backgroundColor: 'rgba(39, 174, 96, 0.8)',
                    borderColor: 'rgba(39, 174, 96, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'Unpaid',
                    data: unpaidData,
                    backgroundColor: 'rgba(231, 76, 60, 0.8)',
                    borderColor: 'rgba(231, 76, 60, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return ':' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 15,
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    const pieCtx = document.getElementById('invoicePieChart').getContext('2d');

    const invoicePieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Total Received (Paid)', 'Total Outstanding (Unpaid)'],
            datasets: [{
                data: [
                    <?= $stats['total_received'] ?? 0 ?>,
                    <?= $stats['total_outstanding'] ?? 0 ?>,
                ],
                backgroundColor: [
                    'rgba(39, 174, 96, 0.85)',
                    'rgba(231, 76, 60, 0.85)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': $' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>