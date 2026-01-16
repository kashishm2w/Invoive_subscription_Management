<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/payment_history.css">

<main class="main-content">
    <div class="dashboard-header">
        <a href="/my_invoices" class="btn-back">Back to Invoices</a>
        <h2>Payment History</h2>
    </div>

    <?php if (!empty($payments)): ?>
        <table class="payment-table" id="payment-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice </th>
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
            <div class="pagination" id="pagination-container">
                <?php if ($pagination['total_pages'] > 1): ?>
                    <?php
                    $currentPage = (int)$pagination['current_page'];
                    $totalPages  = (int)$pagination['total_pages'];
                    ?>

                    <!-- Previous -->
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?= $currentPage - 1 ?>" class="nav-btn">&laquo; Previous</a>
                    <?php endif; ?>

                    <?php
                    /* PAGE 1 */
                    if ($currentPage === 1):
                    ?>
                        <a href="?page=1" class="active">1</a>

                        <?php if ($totalPages >= 2): ?>
                            <a href="?page=2">2</a>
                        <?php endif; ?>

                        <?php if ($totalPages > 3): ?>
                            <span class="ellipsis">...</span>
                        <?php endif; ?>

                        <?php if ($totalPages > 2): ?>
                            <a href="?page=<?= $totalPages ?>"><?= $totalPages ?></a>
                        <?php endif; ?>

                    <?php
                    /* PAGE 2 */
                    elseif ($currentPage === 2):
                    ?>
                        <a href="?page=1">1</a>
                        <a href="?page=2" class="active">2</a>

                        <?php if ($totalPages > 3): ?>
                            <span class="ellipsis">...</span>
                        <?php endif; ?>

                        <a href="?page=<?= $totalPages ?>"><?= $totalPages ?></a>

                    <?php
                    /* PAGE 3 */
                    elseif ($currentPage === 3):
                    ?>
                        <span class="ellipsis">...</span>
                        <a href="?page=3" class="active">3</a>

                        <?php if ($totalPages >= 4): ?>
                            <a href="?page=4">4</a>
                        <?php endif; ?>

                        <?php if ($totalPages >= 5): ?>
                            <a href="?page=5">5</a>
                        <?php endif; ?>

                    <?php
                    /* PAGE ≥ 4 */
                    else:
                    ?>
                        <a href="?page=1">1</a>
                        <span class="ellipsis">...</span>

                        <a href="?page=<?= $currentPage - 1 ?>">
                            <?= $currentPage - 1 ?>
                        </a>

                        <a href="?page=<?= $currentPage ?>" class="active">
                            <?= $currentPage ?>
                        </a>

                        <?php if ($currentPage + 1 <= $totalPages): ?>
                            <a href="?page=<?= $currentPage + 1 ?>">
                                <?= $currentPage + 1 ?>
                            </a>
                        <?php endif; ?>

                        <?php if ($currentPage + 1 < $totalPages): ?>
                            <span class="ellipsis">...</span>
                            <a href="?page=<?= $totalPages ?>">
                                <?= $totalPages ?>
                            </a>
                        <?php endif; ?>

                    <?php endif; ?>

                    <!-- Next -->
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?= $currentPage + 1 ?>" class="nav-btn">Next &raquo;</a>
                    <?php endif; ?>

                <?php endif; ?>

            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state">
            <img src="/uploads/blank-cart.png" alt="No payments" style="max-width: 200px; opacity: 0.5;">
            <p>No payment history found.</p>
            <a href="/products" class="btn-primary">Start Shopping</a>
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
        fetch(`/payment-history/ajax?page=${currentPage}`)
            .then(res => res.json())
            .then(response => {
                const payments = response.payments || [];
                const pagination = response.pagination || null;

                if (!paymentTableBody) return;

                paymentTableBody.innerHTML = '';
                if (payments.length === 0) {
                    paymentTableBody.innerHTML = `<tr><td colspan="7">No payments found.</td></tr>`;
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
                    paymentTableBody.innerHTML = `<tr><td colspan="7">Failed to load payments.</td></tr>`;
                }
            });
    }

    function renderPagination(pagination) {
        if (!paginationContainer || pagination.total_pages <= 1) return;
        let html = '';
        for (let i = 1; i <= pagination.total_pages; i++) {
            const activeClass = i === pagination.current_page ? 'class="active"' : '';
            html += `<a href="javascript:void(0)" onclick="loadPage(${i})" ${activeClass}>${i}</a>`;
        }
        paginationContainer.innerHTML = html;
    }
</script>