// loadPage function for pagination - called by onclick handlers in PHP
function loadPage(page) {
    filterInvoices(page);
}

let filterTimeout;
const filterInputs = ['filter_invoice_number', 'filter_email'];
const filterSelect = document.getElementById('filter_status');
const invoiceTableBody = document.getElementById('invoice-table-body');
const paginationContainer = document.getElementById('pagination-container');

// Debounced search for text inputs
filterInputs.forEach(id => {
    const input = document.getElementById(id);
    if (input) {
        input.addEventListener('input', function () {
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
document.getElementById('clear_filters').addEventListener('click', function () {
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
                <td>&#36;${parseFloat(invoice.total_amount).toFixed(2)}</td>
                <td><span class="status ${invoice.status.toLowerCase()}">${capitalize(invoice.status)}</span></td>
                <td><a href="/invoice/show?id=${invoice.id}" class="btn-view">View</a></td>
            </tr>
        `;
    });
}

function updatePagination(pagination) {
    if (!paginationContainer) return;

    // Clear pagination first
    paginationContainer.innerHTML = '';

    // Don't show pagination for 1 or fewer pages
    if (pagination.total_pages <= 1) return;

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
