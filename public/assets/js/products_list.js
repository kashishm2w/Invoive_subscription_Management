function addToCart(productId) {
    let btn = document.getElementById('cart-btn-' + productId);

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `product_id=${productId}&quantity=1` // default quantity 1
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                btn.textContent = "Added";
                btn.disabled = true;
                btn.style.backgroundColor = "#6c757d";
                btn.style.cursor = "not-allowed";

                // Show SweetAlert immediately
                Swal.fire({
                    icon: 'success',
                    title: 'Added to Cart!',
                    text: 'Product added successfully',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error || 'Failed to add to cart',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        })
        .catch(err => {
            console.error('Error:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to add to cart. Please try again.',
                timer: 2000,
                showConfirmButton: false
            });
        });
}

// Delete Product with SweetAlert confirmation
function deleteProduct(productId) {
    Swal.fire({
        title: 'Delete Product?',
        text: 'Are you sure you want to delete this product? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/dashboard/products/delete?id=${productId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message || 'Product has been deleted.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error || 'Failed to delete product',
                            confirmButtonColor: '#d33'
                        });
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete product. Please try again.',
                        confirmButtonColor: '#d33'
                    });
                });
        }
    });
}

// Add to Cart from View Modal
function addToCartFromModal(productId) {
    let qty = document.getElementById('modal-qty-' + productId).value;
    let btn = event.target;

    btn.disabled = true;
    btn.textContent = 'Adding...';

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `product_id=${productId}&quantity=${qty}`
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                btn.textContent = 'Added!';
                btn.style.background = '#6c757d';

                // Also update the button in the table if exists
                let tableBtn = document.getElementById('cart-btn-' + productId);
                if (tableBtn) {
                    tableBtn.textContent = 'Added';
                    tableBtn.disabled = true;
                    tableBtn.style.backgroundColor = '#6c757d';
                    tableBtn.style.cursor = 'not-allowed';
                }

                // Close modal after a short delay
                setTimeout(() => {
                    closeViewProductModal();
                }, 1000);
            } else {
                alert(data.error || 'Failed to add to cart');
                btn.disabled = false;
                btn.textContent = 'Add to Cart';
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Failed to add to cart. Please try again.');
            btn.disabled = false;
            btn.textContent = 'Add to Cart';
        });
}

// Open Edit Product Modal
function openEditProductModal(productId) {
    fetch(`/dashboard/products/edit?id=${productId}&ajax=1`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('editFormContainer').innerHTML = html;
            document.getElementById('editProductModal').style.display = 'block';

            // Attach form submit handler after loading
            const form = document.getElementById('editProductForm');
            if (form) {
                form.addEventListener('submit', handleEditFormSubmit);
            }
        })
        .catch(err => {
            console.error('Error loading edit form:', err);
            alert('Failed to load edit form. Please try again.');
        });
}

// Close Edit Product Modal
function closeEditProductModal() {
    document.getElementById('editProductModal').style.display = 'none';
    document.getElementById('editFormContainer').innerHTML = '';
}

// Handle Edit Form Submit via AJAX
function handleEditFormSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');

    // Disable button and show loading state
    submitBtn.disabled = true;
    submitBtn.textContent = 'Updating...';

    fetch('/dashboard/products/edit', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(res => {
            const contentType = res.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return res.json().then(data => ({ ok: res.ok, data }));
            } else {
                // Non-JSON response (likely server error or redirect)
                if (res.ok || res.redirected) {
                    return { ok: true, data: { success: true, message: 'Product updated successfully!' } };
                }
                throw new Error('Server returned non-JSON response');
            }
        })
        .then(({ ok, data }) => {
            if (ok && data.success) {
                // Success - close modal and show success alert
                closeEditProductModal();
                Swal.fire({
                    icon: 'success',
                    title: 'Product Updated!',
                    text: data.message || 'The product has been updated successfully.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                // Error - show validation errors
                const errorMessage = data.errors ? data.errors.join('\n') : 'Failed to update product.';
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: errorMessage,
                    confirmButtonColor: '#d33'
                });
                submitBtn.disabled = false;
                submitBtn.textContent = 'Update Product';
            }
        })
        .catch(err => {
            console.error('Error updating product:', err);
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: 'Failed to update product. Please try again.',
                confirmButtonColor: '#d33'
            });
            submitBtn.disabled = false;
            submitBtn.textContent = 'Update Product';
        });
}

// Open View Product Modal
function openViewProductModal(productId) {
    fetch(`/dashboard/product?id=${productId}&ajax=1`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('viewProductContainer').innerHTML = html;
            document.getElementById('viewProductModal').style.display = 'block';
        })
        .catch(err => {
            console.error('Error loading product details:', err);
            alert('Failed to load product details. Please try again.');
        });
}

// Close View Product Modal
function closeViewProductModal() {
    document.getElementById('viewProductModal').style.display = 'none';
    document.getElementById('viewProductContainer').innerHTML = '';
}

// Open Add Product Modal
function openAddProductModal() {
    fetch('/add-product?ajax=1')
        .then(res => res.text())
        .then(html => {
            document.getElementById('addFormContainer').innerHTML = html;
            document.getElementById('addProductModal').style.display = 'block';

            // Attach form submit handler after loading
            const form = document.getElementById('addProductForm');
            if (form) {
                form.addEventListener('submit', handleAddFormSubmit);
            }
        })
        .catch(err => {
            console.error('Error loading add product form:', err);
            alert('Failed to load add product form. Please try again.');
        });
}

// Close Add Product Modal
function closeAddProductModal() {
    document.getElementById('addProductModal').style.display = 'none';
    document.getElementById('addFormContainer').innerHTML = '';
}

// Handle Add Form Submit via AJAX
function handleAddFormSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');

    // Disable button and show loading state
    submitBtn.disabled = true;
    submitBtn.textContent = 'Adding...';

    fetch('/add-product', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(res => {
            const contentType = res.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return res.json().then(data => ({ ok: res.ok, data }));
            } else {
                // Non-JSON response (likely server error or redirect)
                if (res.ok || res.redirected) {
                    return { ok: true, data: { success: true, message: 'Product added successfully!' } };
                }
                throw new Error('Server returned non-JSON response');
            }
        })
        .then(({ ok, data }) => {
            if (ok && data.success) {
                // Success - close modal and show success alert
                closeAddProductModal();
                Swal.fire({
                    icon: 'success',
                    title: 'Product Added!',
                    text: data.message || 'The product has been added successfully.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Redirect to page 1 to see the newly added product
                    window.location.href = '/products?page=1';
                });
            } else {
                // Error - show validation errors
                const errorMessage = data.errors ? data.errors.join('\n') : 'Failed to add product.';
                Swal.fire({
                    icon: 'error',
                    title: 'Add Product Failed',
                    text: errorMessage,
                    confirmButtonColor: '#d33'
                });
                submitBtn.disabled = false;
                submitBtn.textContent = 'Add Product';
            }
        })
        .catch(err => {
            console.error('Error adding product:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to add product. Please try again.',
                confirmButtonColor: '#d33'
            });
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add Product';
        });
}

// Close modal on outside click
window.onclick = function (event) {
    const editModal = document.getElementById('editProductModal');
    const viewModal = document.getElementById('viewProductModal');
    const addModal = document.getElementById('addProductModal');

    if (event.target == editModal) {
        closeEditProductModal();
    }
    if (event.target == viewModal) {
        closeViewProductModal();
    }
    if (event.target == addModal) {
        closeAddProductModal();
    }
}

// Close modal on Escape key
document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        const editModal = document.getElementById('editProductModal');
        const viewModal = document.getElementById('viewProductModal');
        const addModal = document.getElementById('addProductModal');

        if (editModal.style.display === 'block') {
            closeEditProductModal();
        }
        if (viewModal.style.display === 'block') {
            closeViewProductModal();
        }
        if (addModal.style.display === 'block') {
            closeAddProductModal();
        }
    }
});

// Product Search AJAX
let searchTimeout;
const searchInput = document.getElementById('product_search');
const productTableBody = document.querySelector('.product-table tbody');
const paginationContainer = document.querySelector('.pagination');
const isAdmin = window.IS_ADMIN;

if (searchInput) {
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchProducts(this.value, 1);
        }, 300); // Debounce 300ms
    });
}

function searchProducts(search, page = 1) {
    const url = `/products/search?search=${encodeURIComponent(search)}&page=${page}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            updateProductTable(data.products, data.cartProductIds);
            updatePagination(data.pagination, search);
        })
        .catch(err => {
            console.error('Search error:', err);
        });
}

function updateProductTable(products, cartProductIds) {
    if (!productTableBody) return;

    if (!products || products.length === 0) {
        productTableBody.innerHTML = `<tr><td colspan="${isAdmin ? 5 : 4}">No products found.</td></tr>`;
        return;
    }

    productTableBody.innerHTML = '';
    products.forEach(product => {
        const price = parseFloat(product.price);
        const tax = parseFloat(product.tax_percent);
        const total = price + (price * tax / 100);
        const isInCart = cartProductIds.includes(product.id);

        let row = `<tr>
            <td>
                ${product.poster && product.poster !== 'default.png'
                ? `<img src="/uploads/${product.poster}" alt="${product.name}" class="product-poster" style="width:60px; height:auto;">`
                : '<span>No Image</span>'}
            </td>
            <td>${escapeHtml(product.name)}</td>`;

        if (isAdmin) {
            row += `<td>${parseFloat(product.price).toFixed(2)}</td>
                    <td>${product.tax_percent}%</td>`;
        } else {
            row += `<td><strong>&#36;${total.toFixed(2)}</strong></td>`;
        }

        row += `<td>
            <button type="button" class="btn-view" onclick="openViewProductModal(${product.id})">View</button>`;

        if (isAdmin) {
            row += ` <button type="button" onclick="openEditProductModal(${product.id})">Edit</button>
                    | <button type="button" class="btn-delete" onclick="deleteProduct(${product.id})">Delete</button>`;
        } else {
            row += ` | <button id="cart-btn-${product.id}" onclick="addToCart(${product.id})" ${isInCart ? 'disabled' : ''}>
                        ${isInCart ? 'Added' : 'Add to Cart'}
                    </button>`;
        }

        row += `</td></tr>`;
        productTableBody.innerHTML += row;
    });
}

function updatePagination(pagination, search) {
    if (!paginationContainer) return;

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
            searchProducts(search, page);
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
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}