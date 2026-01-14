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
                            <?php if (strtolower($invoice['status'])==='paid' && $dueAmount <= 0): ?>
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
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <a href="javascript:void(0)" onclick="loadPage(<?= $i ?>)" <?= $i === $pagination['current_page'] ? 'class="active"' : '' ?>>
                    <?= $i ?>
                </a>
            <?php endfor; ?>
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

// Render pagination links
function renderPagination(pagination) {
    if (!paginationContainer) return;
    
    let html = '';
    for (let i = 1; i <= pagination.total_pages; i++) {
        const activeClass = i === pagination.current_page ? 'class="active"' : '';
        html += `<a href="javascript:void(0)" onclick="loadPage(${i})" ${activeClass}>${i}</a>`;
    }
    paginationContainer.innerHTML = html;
}

// Filter change event
filterSelect?.addEventListener('change', () => {
    currentPage = 1; // Reset to page 1 when filter changes
    fetchInvoices();
});
</script>

