let filterTimeout;
const tableBody = document.getElementById('subscription-table-body');
const paginationContainer = document.getElementById('pagination-container');

// Debounced search for text input
document.getElementById('filter_email').addEventListener('input', function () {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => filterSubscriptions(1), 300);
});

// Immediate filter for selects
['filter_plan', 'filter_billing_cycle', 'filter_status'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('change', () => filterSubscriptions(1));
    }
});

// Clear filters
document.getElementById('clear_filters').addEventListener('click', function () {
    document.getElementById('filter_email').value = '';
    document.getElementById('filter_plan').value = '';
    document.getElementById('filter_billing_cycle').value = '';
    document.getElementById('filter_status').value = '';
    filterSubscriptions(1);
});

function filterSubscriptions(page = 1) {
    const params = new URLSearchParams({
        email: document.getElementById('filter_email').value,
        plan_id: document.getElementById('filter_plan').value,
        billing_cycle: document.getElementById('filter_billing_cycle').value,
        status: document.getElementById('filter_status').value,
        page: page
    });

    fetch(`/admin/subscriptions/filter?${params}`)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }
            updateTable(data.subscriptions);
            updatePagination(data.pagination);
        })
        .catch(err => console.error('Filter error:', err));
}

function updateTable(subscriptions) {
    if (!subscriptions || subscriptions.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7">No subscriptions found.</td></tr>';
        return;
    }

    tableBody.innerHTML = '';
    subscriptions.forEach(sub => {
        tableBody.innerHTML += `
            <tr>
                <td>${escapeHtml(sub.user_name || '-')}</td>
                <td>${escapeHtml(sub.user_email || '-')}</td>
                <td>${escapeHtml(sub.plan_name)}</td>
                <td>${capitalize(sub.billing_cycle)}</td>
                <td>${formatDate(sub.start_date)}</td>
                <td>${formatDate(sub.end_date)}</td>
                <td><span class="status ${sub.status.toLowerCase()}">${capitalize(sub.status)}</span></td>
            </tr>
        `;
    });
}

function updatePagination(pagination) {
    paginationContainer.innerHTML = '';

    if (pagination.total_pages <= 1) return;

    const currentPage = pagination.current_page;
    const totalPages = pagination.total_pages;
    const range = 2; // Pages to show around current page

    // Helper to create page link
    const createLink = (page, text, isActive = false, isNav = false) => {
        const link = document.createElement('a');
        link.href = '#';
        link.textContent = text || page;
        link.className = isActive ? 'active' : (isNav ? 'nav-btn' : '');
        link.onclick = (e) => {
            e.preventDefault();
            filterSubscriptions(page);
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

    // First page
    paginationContainer.appendChild(createLink(1, '1', currentPage === 1));

    // Ellipsis after first page if needed
    if (currentPage > range + 2) {
        paginationContainer.appendChild(createEllipsis());
    }

    // Pages around current page
    for (let i = Math.max(2, currentPage - range); i <= Math.min(totalPages - 1, currentPage + range); i++) {
        paginationContainer.appendChild(createLink(i, i.toString(), i === currentPage));
    }

    // Ellipsis before last page if needed
    if (currentPage < totalPages - range - 1) {
        paginationContainer.appendChild(createEllipsis());
    }

    // Last page (if more than 1 page)
    if (totalPages > 1) {
        paginationContainer.appendChild(createLink(totalPages, totalPages.toString(), currentPage === totalPages));
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
