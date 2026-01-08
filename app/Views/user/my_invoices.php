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
    </select>
</div>

    <?php if (!empty($invoices)): ?>
        <table id="invoice-table" class="invoice-table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Date</th>
                    <th>Due Date</th>
                    <th>Total(&#8377;)</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Payment</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?= htmlspecialchars($invoice['invoice_number']) ?></td>
                        <td><?= date('d M Y', strtotime($invoice['invoice_date'])) ?></td>
                        <td><?= date('d M Y', strtotime($invoice['due_date'])) ?></td>
                        <td>&#8377;<?= number_format($invoice['total_amount'], 2) ?></td>
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
                            <?php if (strtolower($invoice['status'])==='paid'): ?>
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
        <div class="pagination">
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <a href="?page=<?= $i ?>" <?= $i === $pagination['current_page'] ? 'class="active"' : '' ?>>
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>

    <?php else: ?>
        <p class="no-data">You don't have any invoices yet.</p>
    <?php endif; ?>
</div>

<style>
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 28px;
        margin-bottom: 20px;
    }

    .pagination a {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        color: #374151;
        background: #fff;
        border: 1px solid #e5e7eb;
    }

    .pagination a:hover {
        background: #f3f4f6;
    }

    .pagination a.active {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: #fff;
        border-color: transparent;
    }
</style>
<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
<script>
const filterSelect = document.getElementById('status_filter');
const invoiceTableBody = document.querySelector('#invoice-table tbody');

filterSelect.addEventListener('change', () => {
    const status = filterSelect.value;

    fetch(`/invoice/fetchFilteredInvoices?status=${status}`)
        .then(res => {
            if (!res.ok) throw new Error('Network response not ok');
            return res.json();
        })
        .then(data => {
            invoiceTableBody.innerHTML = '';
            if (!data || data.length === 0) {
                invoiceTableBody.innerHTML = `<tr><td colspan="7">No invoices found.</td></tr>`;
                return;
            }

            data.forEach(invoice => {
                const today = new Date();
                const dueDate = new Date(invoice.due_date);

                let paymentCell = '';
                if (invoice.status.toLowerCase() === 'paid') {
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
                        <td>&#8377;${parseFloat(invoice.total_amount).toFixed(2)}</td>
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
        })
        .catch(err => {
            console.error('Error fetching invoices:', err);
            invoiceTableBody.innerHTML = `<tr><td colspan="7">Failed to load invoices.</td></tr>`;
        });
});
</script>
