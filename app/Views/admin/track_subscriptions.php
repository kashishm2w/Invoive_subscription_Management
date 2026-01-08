<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/track_invoices.css">

<main class="main-content">
    <div class="dashboard-header">
        <h2>Track Subscriptions</h2>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-row">
            <div class="filter-group">
                <label>Email</label>
                <input type="text" id="filter_email" placeholder="Search by email...">
            </div>
            <div class="filter-group">
                <label>Plan</label>
                <select id="filter_plan">
                    <option value="">All Plans</option>
                    <?php
                    $planModel = new \App\Models\SubscriptionPlan();
                    $plans = $planModel->getAll();
                    foreach ($plans as $plan): ?>
                        <option value="<?= $plan['id'] ?>"><?= htmlspecialchars($plan['plan_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <label>Billing Cycle</label>
                <select id="filter_billing_cycle">
                    <option value="">All</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select id="filter_status">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="expired">Expired</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <button type="button" id="clear_filters" class="btn-clear">Clear</button>
        </div>
        
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Plan</th>
                <th>Billing Cycle</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="subscription-table-body">
            <?php if (!empty($subscriptions)): ?>
                <?php foreach ($subscriptions as $sub): ?>
                    <tr>
                        <td><?= htmlspecialchars($sub['user_name'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($sub['user_email'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($sub['plan_name']) ?></td>
                        <td><?= ucfirst($sub['billing_cycle']) ?></td>
                        <td><?= date('d M Y', strtotime($sub['start_date'])) ?></td>
                        <td><?= date('d M Y', strtotime($sub['end_date'])) ?></td>
                        <td><span class="status <?= strtolower($sub['status']) ?>"><?= ucfirst($sub['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No subscriptions found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination" id="pagination-container">
        <?php if (isset($pagination)): ?>
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <a href="?page=<?= $i ?>" <?= $i === $pagination['current_page'] ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>
        <?php endif; ?>
    </div>
</main>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>

<script>
let filterTimeout;
const tableBody = document.getElementById('subscription-table-body');
const paginationContainer = document.getElementById('pagination-container');

// Debounced search for text input
document.getElementById('filter_email').addEventListener('input', function() {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => filterSubscriptions(1), 300);
});

// Immediate filter for selects and dates
['filter_plan', 'filter_billing_cycle', 'filter_status', 'filter_start_date', 'filter_end_date'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('change', () => filterSubscriptions(1));
    }
});

// Clear filters
document.getElementById('clear_filters').addEventListener('click', function() {
    document.getElementById('filter_email').value = '';
    document.getElementById('filter_plan').value = '';
    document.getElementById('filter_billing_cycle').value = '';
    document.getElementById('filter_status').value = '';
    document.getElementById('filter_start_date').value = '';
    document.getElementById('filter_end_date').value = '';
    filterSubscriptions(1);
});

function filterSubscriptions(page = 1) {
    const params = new URLSearchParams({
        email: document.getElementById('filter_email').value,
        plan_id: document.getElementById('filter_plan').value,
        billing_cycle: document.getElementById('filter_billing_cycle').value,
        status: document.getElementById('filter_status').value,
        start_date: document.getElementById('filter_start_date').value,
        end_date: document.getElementById('filter_end_date').value,
        page: page
    });

    fetch(`/admin/subscriptions/filter?${params}`)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }
            updateTable(data.subscriptions);
            updatePagination(data.pagination);
        })
        .catch(err => console.error('Filter error:', err));
}

function updateTable(subscriptions) {
    if (!subscriptions || subscriptions.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7">No subscriptions found.</td></tr>';
        return;
    }

    tableBody.innerHTML = '';
    subscriptions.forEach(sub => {
        tableBody.innerHTML += `
            <tr>
                <td>${escapeHtml(sub.user_name || '-')}</td>
                <td>${escapeHtml(sub.user_email || '-')}</td>
                <td>${escapeHtml(sub.plan_name)}</td>
                <td>${capitalize(sub.billing_cycle)}</td>
                <td>${formatDate(sub.start_date)}</td>
                <td>${formatDate(sub.end_date)}</td>
                <td><span class="status ${sub.status.toLowerCase()}">${capitalize(sub.status)}</span></td>
            </tr>
        `;
    });
}

function updatePagination(pagination) {
    paginationContainer.innerHTML = '';
    for (let i = 1; i <= pagination.total_pages; i++) {
        const link = document.createElement('a');
        link.href = '#';
        link.textContent = i;
        link.className = i === pagination.current_page ? 'active' : '';
        link.onclick = (e) => {
            e.preventDefault();
            filterSubscriptions(i);
        };
        paginationContainer.appendChild(link);
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}
</script>