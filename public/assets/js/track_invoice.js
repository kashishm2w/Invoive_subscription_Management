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

    const currentPage = pagination.current_page;
    const totalPages = pagination.total_pages;

    paginationContainer.innerHTML = '';

    if (totalPages <= 1) return;

    // Helper to create page link
    const createLink = (page, text, isActive = false, isNav = false) => {
        const link = document.createElement('a');
        link.href = '#';
        link.textContent = text || page;
        link.className = isActive ? 'active' : (isNav ? 'nav-btn' : '');
        link.onclick = (e) => {
            e.preventDefault();
            filterInvoices(page);
        };
        return link;
    };

    // Helper to create ellipsis span
    const createEllipsis = () => {
        const span = document.createElement('span');
        span.className = 'ellipsis';
        span.textContent = '...';
        return span;
    };

    // Previous button
    if (currentPage > 1) {
        paginationContainer.appendChild(createLink(currentPage - 1, '« Previous', false, true));
    }

    // Page 1 logic
    if (currentPage === 1) {
        paginationContainer.appendChild(createLink(1, '1', true));
        if (totalPages >= 2) {
            paginationContainer.appendChild(createLink(2, '2'));
        }
        if (totalPages > 3) {
            paginationContainer.appendChild(createEllipsis());
        }
        if (totalPages > 2) {
            paginationContainer.appendChild(createLink(totalPages, totalPages.toString()));
        }
    }
    // Page 2 logic
    else if (currentPage === 2) {
        paginationContainer.appendChild(createLink(1, '1'));
        paginationContainer.appendChild(createLink(2, '2', true));
        if (totalPages > 2) {
            if (totalPages > 3) {
                paginationContainer.appendChild(createEllipsis());
            }
            paginationContainer.appendChild(createLink(totalPages, totalPages.toString()));
        }
    }
    // Page 3 logic
    else if (currentPage === 3) {
        paginationContainer.appendChild(createLink(1, '1'));
        paginationContainer.appendChild(createLink(2, '2'));
        paginationContainer.appendChild(createLink(3, '3', true));
        if (totalPages >= 4) {
            paginationContainer.appendChild(createLink(4, '4'));
        }
        if (totalPages > 4) {
            paginationContainer.appendChild(createEllipsis());
            paginationContainer.appendChild(createLink(totalPages, totalPages.toString()));
        }
    }
    // Page >= 4 logic
    else {
        paginationContainer.appendChild(createLink(1, '1'));
        paginationContainer.appendChild(createEllipsis());
        paginationContainer.appendChild(createLink(currentPage - 1, (currentPage - 1).toString()));
        paginationContainer.appendChild(createLink(currentPage, currentPage.toString(), true));
        if (currentPage + 1 <= totalPages) {
            paginationContainer.appendChild(createLink(currentPage + 1, (currentPage + 1).toString()));
        }
        if (currentPage + 1 < totalPages) {
            paginationContainer.appendChild(createEllipsis());
            paginationContainer.appendChild(createLink(totalPages, totalPages.toString()));
        }
    }

    // Next button
    if (currentPage < totalPages) {
        paginationContainer.appendChild(createLink(currentPage + 1, 'Next »', false, true));
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
