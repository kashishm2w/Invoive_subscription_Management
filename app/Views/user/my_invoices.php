<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>

<link rel="stylesheet" href="/assets/css/invoice.css">

<div class="invoice-container">
    <h1>My Invoices</h1>
 <!-- Search box -->
    <input
        type="text"
        id="searchInput"
        placeholder="Search invoice number, status, amount..."
        style="width:100%; padding:8px; margin-bottom:10px;"
        onkeyup="searchInvoices()"
    >

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
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination container -->
        <div id="pagination" style="margin-top:10px; text-align:center;"></div>

    <?php else: ?>
        <p class="no-data">You don't have any invoices yet.</p>
    <?php endif; ?>
</div>
<script>
    const rowsPerPage = 5;
    const table = document.getElementById('invoice-table');
    const tbody = table.querySelector('tbody');
    const allRows = Array.from(tbody.querySelectorAll('tr'));
    let filteredRows = [...allRows];
    let currentPage = 1;

    function renderTable() {
        tbody.innerHTML = '';

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        filteredRows.slice(start, end).forEach(row => tbody.appendChild(row));

        renderPagination();
    }

    function renderPagination() {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        if (totalPages <= 1) return;

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.style.margin = '0 4px';

            if (i === currentPage) {
                btn.style.fontWeight = 'bold';
            }

            btn.onclick = () => {
                currentPage = i;
                renderTable();
            };
            pagination.appendChild(btn);
        }
    }

    function searchInvoices() {
        const query = document.getElementById('searchInput').value.toLowerCase();

        filteredRows = allRows.filter(row => {
            return row.textContent.toLowerCase().includes(query);
        });

        currentPage = 1;
        renderTable();
    }

    renderTable();
</script>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
