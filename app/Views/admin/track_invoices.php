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
                    <option value="partial">Partial</option>
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
                        <td>&#36;<?= number_format($invoice['total_amount'], 2) ?></td>
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

<script src="/assets/js/track_invoice.js"></script>
