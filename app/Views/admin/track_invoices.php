<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/track_invoices.css">

<main class="main-content">
    <div class="dashboard-header">
        <h2>Track Invoices</h2>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-row">
            <div class="filter-group">
                <label>Invoice Number</label>
                <input type="text" id="filter_invoice_number" placeholder="Search invoice number...">
            </div>
            <div class="filter-group">
                <label>Email</label>
                <input type="text" id="filter_email" placeholder="Search by email...">
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select id="filter_status">
                    <option value="">All</option>
                    <option value="paid">Paid</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
            <button type="button" id="clear_filters" class="btn-clear">Clear</button>
        </div>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>Invoice </th>
                <th>User</th>
                <th>Email</th>
                <th>Date</th>
                <th>Due Date</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="invoice-table-body">
            <?php if (!empty($invoices)): ?>
                <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?= htmlspecialchars($invoice['invoice_number']) ?></td>
                        <td><?= htmlspecialchars($invoice['user_name'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($invoice['user_email'] ?? '-') ?></td>
                        <td><?= date('d M Y', strtotime($invoice['invoice_date'])) ?></td>
                        <td><?= date('d M Y', strtotime($invoice['due_date'])) ?></td>
                        <td>&#8377;<?= number_format($invoice['total_amount'], 2) ?></td>
                        <td><span class="status <?= strtolower($invoice['status']) ?>"><?= ucfirst($invoice['status']) ?></span></td>
                        <td>
                            <a href="/invoice/show?id=<?= $invoice['id'] ?>" class="btn-view">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">No invoices found.</td></tr>
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
const filterInputs = ['filter_invoice_number', 'filter_email'];
const filterSelect = document.getElementById('filter_status');
const invoiceTableBody = document.getElementById('invoice-table-body');
const paginationContainer = document.getElementById('pagination-container');

// Debounced search for text inputs
filterInputs.forEach(id => {
    const input = document.getElementById(id);
    if (input) {
        input.addEventListener('input', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(() => filterInvoices(1), 300);
        });
    }
});

// Immediate filter for select
if (filterSelect) {
    filterSelect.addEventListener('change', () => filterInvoices(1));
}

// Clear filters
document.getElementById('clear_filters').addEventListener('click', function() {
    filterInputs.forEach(id => {
        document.getElementById(id).value = '';
    });
    filterSelect.value = '';
    filterInvoices(1);
});

function filterInvoices(page = 1) {
    const params = new URLSearchParams({
        invoice_number: document.getElementById('filter_invoice_number').value,
        email: document.getElementById('filter_email').value,
        status: document.getElementById('filter_status').value,
        page: page
    });

    fetch(`/admin/invoices/filter?${params}`)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }
            updateTable(data.invoices);
            updatePagination(data.pagination);
        })
        .catch(err => console.error('Filter error:', err));
}

function updateTable(invoices) {
    if (!invoices || invoices.length === 0) {
        invoiceTableBody.innerHTML = '<tr><td colspan="8">No invoices found.</td></tr>';
        return;
    }

    invoiceTableBody.innerHTML = '';
    invoices.forEach(invoice => {
        invoiceTableBody.innerHTML += `
            <tr>
                <td>${escapeHtml(invoice.invoice_number)}</td>
                <td>${escapeHtml(invoice.user_name || '-')}</td>
                <td>${escapeHtml(invoice.user_email || '-')}</td>
                <td>${formatDate(invoice.invoice_date)}</td>
                <td>${formatDate(invoice.due_date)}</td>
                <td>&#8377;${parseFloat(invoice.total_amount).toFixed(2)}</td>
                <td><span class="status ${invoice.status.toLowerCase()}">${capitalize(invoice.status)}</span></td>
                <td><a href="/invoice/show?id=${invoice.id}" class="btn-view">View</a></td>
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
            filterInvoices(i);
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