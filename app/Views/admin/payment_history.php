<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/payment.css">

<main class="main-content">
    <div class="dashboard-header">
        <a href="/dashboard" class="btn-back">Back to Dashboard</a>
        <h2>All Payments</h2>
    </div>

    <?php if (!empty($payments)): ?>
        <table class="payment-table" id="payment-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Invoice</th>
                    <th>Amount Paid</th>
                    <th>Invoice Total</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Transaction ID</th>
                </tr>
            </thead>
            <tbody id="payment-table-body">
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?= date('d M Y, h:i A', strtotime($payment['created_at'])) ?></td>
                        <td>
                            <div class="user-info">
                                <strong><?= htmlspecialchars($payment['user_name'] ?? 'N/A') ?></strong>
                                <small><?= htmlspecialchars($payment['user_email'] ?? '') ?></small>
                            </div>
                        </td>
                        <td>
                            <a href="/invoice/show?id=<?= $payment['invoice_id'] ?>" class="invoice-link">
                                <?= htmlspecialchars($payment['invoice_number'] ?? 'N/A') ?>
                            </a>
                        </td>
                        <td class="amount">&#36;<?= number_format($payment['amount'], 2) ?></td>
                        <td>&#36;<?= number_format($payment['invoice_total'] ?? 0, 2) ?></td>
                        <td>
                            <span class="method-badge method-<?= strtolower($payment['payment_method']) ?>">
                                <?= ucfirst($payment['payment_method']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-<?= strtolower($payment['status']) ?>">
                                <?= ucfirst($payment['status']) ?>
                            </span>
                        </td>
                        <td class="transaction-id">
                            <?= htmlspecialchars($payment['transaction_id'] ?? '-') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
            <?php
            $currentPage = (int)$pagination['current_page'];
            $totalPages  = (int)$pagination['total_pages'];
            $range = 1;
            ?>
            <div class="pagination" id="pagination-container">
                <?php if ($currentPage > 1): ?>
                    <a href="javascript:void(0)" onclick="loadPage(<?= $currentPage - 1 ?>)" class="nav-btn">&laquo; Previous</a>
                <?php endif; ?>

                <a href="javascript:void(0)" onclick="loadPage(1)" <?= $currentPage === 1 ? 'class="active"' : '' ?>>1</a>

                <?php if ($currentPage > $range + 2): ?>
                    <span class="ellipsis">...</span>
                <?php endif; ?>

                <?php for ($i = max(2, $currentPage - $range); $i <= min($totalPages - 1, $currentPage + $range); $i++): ?>
                    <a href="javascript:void(0)" onclick="loadPage(<?= $i ?>)" <?= $i === $currentPage ? 'class="active"' : '' ?>><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages - $range - 1): ?>
                    <span class="ellipsis">...</span>
                <?php endif; ?>

                <?php if ($totalPages > 1): ?>
                    <a href="javascript:void(0)" onclick="loadPage(<?= $totalPages ?>)" <?= $currentPage === $totalPages ? 'class="active"' : '' ?>><?= $totalPages ?></a>
                <?php endif; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="javascript:void(0)" onclick="loadPage(<?= $currentPage + 1 ?>)" class="nav-btn">Next &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state">
            <p>No payments found.</p>
        </div>
    <?php endif; ?>
</main>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>

<script>
    const paymentTableBody = document.getElementById('payment-table-body');
    const paginationContainer = document.getElementById('pagination-container');
    let currentPage = <?= $pagination['current_page'] ?? 1 ?>;

    function loadPage(page) {
        currentPage = page;
        fetchPayments();
    }

    function fetchPayments() {
        fetch(`/admin/payments/ajax?page=${currentPage}`)
            .then(res => res.json())
            .then(response => {
                const payments = response.payments || [];
                const pagination = response.pagination || null;

                if (!paymentTableBody) return;

                paymentTableBody.innerHTML = '';
                if (payments.length === 0) {
                    paymentTableBody.innerHTML = `<tr><td colspan="8">No payments found.</td></tr>`;
                    return;
                }

                payments.forEach(payment => {
                    const date = new Date(payment.created_at).toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    paymentTableBody.innerHTML += `
                    <tr>
                        <td>${date}</td>
                        <td><div class="user-info"><strong>${payment.user_name || 'N/A'}</strong><small>${payment.user_email || ''}</small></div></td>
                        <td><a href="/invoice/show?id=${payment.invoice_id}" class="invoice-link">${payment.invoice_number || 'N/A'}</a></td>
                        <td class="amount">&#36;${parseFloat(payment.amount).toFixed(2)}</td>
                        <td>&#36;${parseFloat(payment.invoice_total || 0).toFixed(2)}</td>
                        <td><span class="method-badge method-${payment.payment_method.toLowerCase()}">${payment.payment_method.charAt(0).toUpperCase() + payment.payment_method.slice(1)}</span></td>
                        <td><span class="status-badge status-${payment.status.toLowerCase()}">${payment.status.charAt(0).toUpperCase() + payment.status.slice(1)}</span></td>
                        <td class="transaction-id">${payment.transaction_id || '-'}</td>
                    </tr>
                `;
                });

                if (pagination && paginationContainer) {
                    renderPagination(pagination);
                }
            })
            .catch(err => {
                console.error('Error:', err);
                if (paymentTableBody) {
                    paymentTableBody.innerHTML = `<tr><td colspan="8">Failed to load payments.</td></tr>`;
                }
            });
    }

   function renderPagination(pagination) {
    if (!paginationContainer || pagination.total_pages <= 1) return;

    const currentPage = pagination.current_page;
    const totalPages = pagination.total_pages;
    const range = 1;
    let html = '';

    // Previous button
    if (currentPage > 1) {
        html += `<a href="javascript:void(0)" onclick="loadPage(${currentPage - 1})" class="nav-btn">&laquo; Previous</a>`;
    }

    // First page
    html += `<a href="javascript:void(0)" onclick="loadPage(1)" ${currentPage === 1 ? 'class="active"' : ''}>1</a>`;

    // Ellipsis after first page
    if (currentPage > range + 2) {
        html += `<span class="ellipsis">...</span>`;
    }

    // Pages around current page
    for (let i = Math.max(2, currentPage - range); i <= Math.min(totalPages - 1, currentPage + range); i++) {
        html += `<a href="javascript:void(0)" onclick="loadPage(${i})" ${i === currentPage ? 'class="active"' : ''}>${i}</a>`;
    }

    // Ellipsis before last page
    if (currentPage < totalPages - range - 1) {
        html += `<span class="ellipsis">...</span>`;
    }

    // Last page (if more than 1 page)
    if (totalPages > 1) {
        html += `<a href="javascript:void(0)" onclick="loadPage(${totalPages})" ${currentPage === totalPages ? 'class="active"' : ''}>${totalPages}</a>`;
    }

    // Next button
    if (currentPage < totalPages) {
        html += `<a href="javascript:void(0)" onclick="loadPage(${currentPage + 1})" class="nav-btn">Next &raquo;</a>`;
    }

    paginationContainer.innerHTML = html;
}
</script>