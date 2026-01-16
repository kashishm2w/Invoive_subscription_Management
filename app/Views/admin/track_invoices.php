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
<?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
    <?php 
    $currentPage = $pagination['current_page'];
    $totalPages = $pagination['total_pages'];
    $range = 1; // Number of pages to show around current page
    ?>

    <!-- Previous Button -->
    <?php if ($currentPage > 1): ?>
        <a href="?page=<?= $currentPage - 1 ?>" class="nav-btn">&laquo; Previous</a>
    <?php endif; ?>

    <!-- First page -->
    <a href="?page=1" <?= $currentPage === 1 ? 'class="active"' : '' ?>>1</a>

    <!-- Ellipsis after first page -->
    <?php if ($currentPage > $range + 2): ?>
        <span class="ellipsis">...</span>
    <?php endif; ?>

    <!-- Pages around current page -->
    <?php for ($i = max(2, $currentPage - $range); $i <= min($totalPages - 1, $currentPage + $range); $i++): ?>
        <a href="?page=<?= $i ?>" <?= $i === $currentPage ? 'class="active"' : '' ?>><?= $i ?></a>
    <?php endfor; ?>

    <!-- Ellipsis before last page -->
    <?php if ($currentPage < $totalPages - $range - 1): ?>
        <span class="ellipsis">...</span>
    <?php endif; ?>

    <!-- Last page (if more than 1 page) -->
    <?php if ($totalPages > 1): ?>
        <a href="?page=<?= $totalPages ?>" <?= $currentPage === $totalPages ? 'class="active"' : '' ?>><?= $totalPages ?></a>
    <?php endif; ?>

    <!-- Next Button -->
    <?php if ($currentPage < $totalPages): ?>
        <a href="?page=<?= $currentPage + 1 ?>" class="nav-btn">Next &raquo;</a>
    <?php endif; ?>
<?php endif; ?>
</div>

</main>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>

<script src="/assets/js/track_invoice.js"></script>