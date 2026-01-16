<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>

<link rel="stylesheet" href="/assets/css/invoice.css">

<div class="invoice-container">
    <h1>My Invoices</h1>
    <div class="invoice-filter">
        <label for="status_filter">Filter:</label>
        <select id="status_filter">
            <option value="">All</option>
            <option value="paid">Paid</option>
            <option value="unpaid">Unpaid</option>
            <option value="overdue">Overdue</option>
            <option value="partial">Partial</option>

        </select>
    </div>

    <?php if (!empty($invoices)): ?>
        <table id="invoice-table" class="invoice-table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Date</th>
                    <th>Due Date</th>
                    <th>Total(&#36;)</th>
                    <th>Due Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Payment</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                    <?php
                    $dueAmount = (float)($invoice['due_amount'] ?? 0);
                    $totalAmount = (float)$invoice['total_amount'];
                    $amountPaid = (float)($invoice['amount_paid'] ?? 0);
                    // If due_amount is 0 but total > 0 and amount_paid < total, calculate it
                    if ($dueAmount == 0 && $totalAmount > 0 && $amountPaid < $totalAmount) {
                        $dueAmount = $totalAmount - $amountPaid;
                    }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($invoice['invoice_number']) ?></td>
                        <td><?= date('d M Y', strtotime($invoice['invoice_date'])) ?></td>
                        <td><?= date('d M Y', strtotime($invoice['due_date'])) ?></td>
                        <td>&#36;<?= number_format($invoice['total_amount'], 2) ?></td>
                        <td>
                            <?php if ($dueAmount > 0): ?>
                                <span class="due-amount">&#36;<?= number_format($dueAmount, 2) ?></span>
                            <?php else: ?>
                                <span class="paid">&#36;0.00</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status <?= strtolower($invoice['status']) ?>">
                                <?= ucfirst($invoice['status']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="/invoice/show?id=<?= $invoice['id'] ?>" class="btn-back">View</a>
                            <a href="/invoice/pdf?id=<?= $invoice['id'] ?>" class="btn-back">Download</a>
                            <a href="/invoice/send-email?id=<?= $invoice['id'] ?>" class="btn-back">Send Email</a>
                        </td>
                        <td>
                            <?php if (strtolower($invoice['status']) === 'paid' && $dueAmount <= 0): ?>
                                <span class="paid"> Already Paid</span>
                            <?php else: ?>
                                <a href="/invoice/pay?id=<?= $invoice['id'] ?>" class="btn-pay">Pay Now</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
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

                    <?php if ($totalPages > 2): ?>
                        <?php if ($totalPages > 3): ?>
                            <span class="ellipsis">...</span>
                        <?php endif; ?>
                        <a href="?page=<?= $totalPages ?>"><?= $totalPages ?></a>
                    <?php endif; ?>

                <?php
                /* PAGE 3 */
                elseif ($currentPage === 3):
                ?>
                    <a href="?page=1">1</a>
                    <a href="?page=2">2</a>
                    <a href="?page=3" class="active">3</a>

                    <?php if ($totalPages >= 4): ?>
                        <a href="?page=4">4</a>
                    <?php endif; ?>

                    <?php if ($totalPages > 4): ?>
                        <span class="ellipsis">...</span>
                        <a href="?page=<?= $totalPages ?>"><?= $totalPages ?></a>
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

    <?php else: ?>
        <p class="no-data">You don't have any invoices yet.</p>
    <?php endif; ?>
</div>
<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
<script>
    const filterSelect = document.getElementById('status_filter');
    const invoiceTableBody = document.querySelector('#invoice-table tbody');
    const paginationContainer = document.getElementById('pagination-container');

    let currentPage = <?= $pagination['current_page'] ?? 1 ?>;
    let currentStatus = '';

    // Load invoices with pagination
    function loadPage(page) {
        currentPage = page;
        fetchInvoices();
    }

    // Fetch invoices with filter and pagination
    function fetchInvoices() {
        const status = filterSelect ? filterSelect.value : '';
        currentStatus = status;

        fetch(`/invoice/fetchFilteredInvoices?status=${status}&page=${currentPage}`)
            .then(res => {
                if (!res.ok) throw new Error('Network response not ok');
                return res.json();
            })
            .then(response => {
                const data = response.invoices || response;
                const pagination = response.pagination || null;

                invoiceTableBody.innerHTML = '';
                if (!data || data.length === 0) {
                    invoiceTableBody.innerHTML = `<tr><td colspan="8">No invoices found.</td></tr>`;
                    if (paginationContainer) paginationContainer.innerHTML = '';
                    return;
                }

                data.forEach(invoice => {
                    const today = new Date();
                    const dueDate = new Date(invoice.due_date);
                    const totalAmount = parseFloat(invoice.total_amount) || 0;
                    const amountPaid = parseFloat(invoice.amount_paid) || 0;
                    let dueAmount = parseFloat(invoice.due_amount) || 0;

                    // Calculate due_amount if not set
                    if (dueAmount === 0 && totalAmount > 0 && amountPaid < totalAmount) {
                        dueAmount = totalAmount - amountPaid;
                    }

                    // Due amount cell
                    const dueAmountClass = dueAmount > 0 ? 'due-amount' : 'paid';
                    const dueAmountCell = `<span class="${dueAmountClass}">&#36;${dueAmount.toFixed(2)}</span>`;

                    let paymentCell = '';
                    // Show "Already Paid" only if status is paid AND no due amount
                    if (invoice.status.toLowerCase() === 'paid' && dueAmount <= 0) {
                        paymentCell = `<span class="paid">Already Paid</span>`;
                    } else if (dueDate < today) {
                        paymentCell = `<span class="overdue"><a href="/invoice/pay?id=${invoice.id}" class="btn-pay">Overdue Pay Now</a></span>`;
                    } else {
                        paymentCell = `<a href="/invoice/pay?id=${invoice.id}" class="btn-pay">Pay Now</a>`;
                    }

                    invoiceTableBody.innerHTML += `
                    <tr>
                        <td>${invoice.invoice_number}</td>
                        <td>${new Date(invoice.invoice_date).toLocaleDateString('en-GB')}</td>
                        <td>${new Date(invoice.due_date).toLocaleDateString('en-GB')}</td>
                        <td>&#36;${parseFloat(invoice.total_amount).toFixed(2)}</td>
                        <td>${dueAmountCell}</td>
                        <td><span class="status ${invoice.status.toLowerCase()}">${invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1)}</span></td>
                        <td>
                            <a href="/invoice/show?id=${invoice.id}" class="btn-back">View</a>
                            <a href="/invoice/pdf?id=${invoice.id}" class="btn-back">Download</a>
                            <a href="/invoice/send-email?id=${invoice.id}" class="btn-back">Send Email</a>
                        </td>
                        <td>${paymentCell}</td>
                    </tr>
                `;
                });

                // Update pagination
                if (pagination && paginationContainer) {
                    renderPagination(pagination);
                }
            })
            .catch(err => {
                console.error('Error fetching invoices:', err);
                invoiceTableBody.innerHTML = `<tr><td colspan="8">Failed to load invoices.</td></tr>`;
            });
    }

    // Render pagination links with ellipsis pattern
    function renderPagination(pagination) {
        if (!paginationContainer) return;

        const currentPage = pagination.current_page;
        const totalPages = pagination.total_pages;

        if (totalPages <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        let html = '';

        // Previous button
        if (currentPage > 1) {
            html += `<a href="javascript:void(0)" onclick="loadPage(${currentPage - 1})" class="nav-btn">&laquo; Previous</a>`;
        }

        // Page 1 logic
        if (currentPage === 1) {
            html += `<a href="javascript:void(0)" onclick="loadPage(1)" class="active">1</a>`;
            if (totalPages >= 2) {
                html += `<a href="javascript:void(0)" onclick="loadPage(2)">2</a>`;
            }
            if (totalPages > 3) {
                html += `<span class="ellipsis">...</span>`;
            }
            if (totalPages > 2) {
                html += `<a href="javascript:void(0)" onclick="loadPage(${totalPages})">${totalPages}</a>`;
            }
        }
        // Page 2 logic
        else if (currentPage === 2) {
            html += `<a href="javascript:void(0)" onclick="loadPage(1)">1</a>`;
            html += `<a href="javascript:void(0)" onclick="loadPage(2)" class="active">2</a>`;
            if (totalPages > 2) {
                if (totalPages > 3) {
                    html += `<span class="ellipsis">...</span>`;
                }
                html += `<a href="javascript:void(0)" onclick="loadPage(${totalPages})">${totalPages}</a>`;
            }
        }
        // Page 3 logic
        else if (currentPage === 3) {
            html += `<a href="javascript:void(0)" onclick="loadPage(1)">1</a>`;
            html += `<a href="javascript:void(0)" onclick="loadPage(2)">2</a>`;
            html += `<a href="javascript:void(0)" onclick="loadPage(3)" class="active">3</a>`;
            if (totalPages >= 4) {
                html += `<a href="javascript:void(0)" onclick="loadPage(4)">4</a>`;
            }
            if (totalPages > 4) {
                html += `<span class="ellipsis">...</span>`;
                html += `<a href="javascript:void(0)" onclick="loadPage(${totalPages})">${totalPages}</a>`;
            }
        }
        // Page >= 4 logic
        else {
            html += `<a href="javascript:void(0)" onclick="loadPage(1)">1</a>`;
            html += `<span class="ellipsis">...</span>`;
            html += `<a href="javascript:void(0)" onclick="loadPage(${currentPage - 1})">${currentPage - 1}</a>`;
            html += `<a href="javascript:void(0)" onclick="loadPage(${currentPage})" class="active">${currentPage}</a>`;
            if (currentPage + 1 <= totalPages) {
                html += `<a href="javascript:void(0)" onclick="loadPage(${currentPage + 1})">${currentPage + 1}</a>`;
            }
            if (currentPage + 1 < totalPages) {
                html += `<span class="ellipsis">...</span>`;
                html += `<a href="javascript:void(0)" onclick="loadPage(${totalPages})">${totalPages}</a>`;
            }
        }

        // Next button
        if (currentPage < totalPages) {
            html += `<a href="javascript:void(0)" onclick="loadPage(${currentPage + 1})" class="nav-btn">Next &raquo;</a>`;
        }

        paginationContainer.innerHTML = html;
    }

    // Filter change event
    filterSelect?.addEventListener('change', () => {
        currentPage = 1; // Reset to page 1 when filter changes
        fetchInvoices();
    });
</script>