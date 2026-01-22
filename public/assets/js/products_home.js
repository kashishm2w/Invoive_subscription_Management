// Pagination for home page products
let currentPage = 1;
const productsGrid = document.querySelector('.products-grid');
const paginationContainer = document.getElementById('pagination-container');

function loadPage(page) {
    currentPage = page;
    fetchProducts();
}

function fetchProducts() {
    fetch(`/home?ajax=1&page=${currentPage}`)
        .then(res => res.json())
        .then(data => {
            renderProducts(data.products, data.isLoggedIn, data.isAdmin);
            if (data.pagination && paginationContainer) {
                renderPagination(data.pagination);
            }
        })
        .catch(err => {
            console.error('Error fetching products:', err);
            if (productsGrid) {
                productsGrid.innerHTML = '<p class="no-products">Failed to load products.</p>';
            }
        });
}

function renderProducts(products, isLoggedIn, isAdmin) {
    if (!productsGrid) return;

    if (!products || products.length === 0) {
        productsGrid.innerHTML = '<p class="no-products">No products available.</p>';
        return;
    }

    productsGrid.innerHTML = '';
    products.forEach(product => {
        const posterSrc = product.poster ? `/uploads/${product.poster}` : '/uploads/default.jpg';
        const hasPoster = product.poster && product.poster.trim() !== '';

        let buttonHtml = '';
        if (isLoggedIn && !isAdmin) {
            if (product.in_cart) {
                buttonHtml = `<button class="btn-added" disabled onclick="event.stopPropagation();">Added</button>`;
            } else {
                buttonHtml = `<button class="btn-add-to-cart" id="home-cart-btn-${product.id}" onclick="event.stopPropagation(); addToCartFromHome(${product.id})">Add to Cart</button>`;
            }
        } else if (!isLoggedIn) {
            if (product.in_cart) {
                buttonHtml = `<button class="btn-added" disabled onclick="event.stopPropagation();">Added</button>`;
            } else {
                buttonHtml = `<button class="btn-add-to-cart" id="home-cart-btn-${product.id}" onclick="event.stopPropagation(); addToCartFromHome(${product.id})">Add to Cart</button>`;
            }
        }

        productsGrid.innerHTML += `
            <div class="product-card" onclick="openViewProductModal(${product.id})">
                <div class="product-image">
                    ${hasPoster
                ? `<img src="${posterSrc}" alt="${escapeHtml(product.name)}">`
                : '<div class="no-image">No Image</div>'}
                </div>
                <div class="product-info">
                    <h3 class="product-name">${escapeHtml(product.name)}</h3>
                    <p class="product-price">&#36;${parseFloat(product.total).toFixed(2)}</p>
                    ${buttonHtml}
                </div>
            </div>
        `;
    });
}

function renderPagination(pagination) {
    if (!paginationContainer) return;

    const currentPage = pagination.current_page;
    const totalPages = pagination.total_pages;
    const range = 1;

    if (totalPages <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }

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

    // Last page
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

// Add to Cart from Home Page
function addToCartFromHome(productId) {
    let btn = document.getElementById('home-cart-btn-' + productId);

    btn.disabled = true;
    btn.textContent = 'Adding...';

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `product_id=${productId}&quantity=1`
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                btn.textContent = ' Added';
                btn.className = 'btn-added';
                Swal.fire({
                    icon: 'success',
                    title: 'Added to Cart!',
                    text: 'Product has been added to your cart.',
                    confirmButtonColor: '#3085d6',
                    timer: 2000,

                });
            } else {
                // Show warning for stock exceeded
                if (data.error && data.error.includes('stock')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Quantity Exceeds Stock',
                        text: data.error || 'Quantity exceeds available stock',
                        confirmButtonColor: '#f0ad4e'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to Add',
                        text: data.error || 'Failed to add to cart',
                        confirmButtonColor: '#d33'
                    });
                }
                btn.disabled = false;
                btn.textContent = 'Add to Cart';
            }
        })
        .catch(err => {
            console.error('Error:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to add to cart. Please try again.',
                confirmButtonColor: '#d33'
            });
            btn.disabled = false;
            btn.textContent = 'Add to Cart';
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

// Add to Cart from Modal (for the modal)
function addToCartFromModal(productId) {
    let qty = document.getElementById('modal-qty-' + productId).value;
    let maxStock = document.getElementById('modal-qty-' + productId).getAttribute('max');
    let btn = event.target;

    // Check for zero or negative quantity
    if (parseInt(qty) < 1) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Quantity',
            text: 'Please add at least one item to cart.',
            confirmButtonColor: '#f0ad4e'
        });
        return;
    }

    // Client-side stock validation
    if (parseInt(qty) > parseInt(maxStock)) {
        Swal.fire({
            icon: 'warning',
            title: 'Quantity Exceeds Stock',
            text: 'Only ' + maxStock + ' items available in stock.',
            confirmButtonColor: '#f0ad4e'
        });
        return;
    }

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

                // Update the card button too
                let cardBtn = document.getElementById('home-cart-btn-' + productId);
                if (cardBtn) {
                    cardBtn.textContent = ' Added';
                    cardBtn.className = 'btn-added';
                    cardBtn.disabled = true;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Added to Cart!',
                    text: 'Product has been added to your cart.',
                    confirmButtonColor: '#3085d6',
                    timer: 2000,

                }).then(() => {
                    closeViewProductModal();
                });
            } else {
                // Show warning for stock exceeded
                if (data.error && data.error.includes('stock')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Quantity Exceeds Stock',
                        text: data.error || 'Quantity exceeds available stock',
                        confirmButtonColor: '#f0ad4e'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to Add',
                        text: data.error || 'Failed to add to cart',
                        confirmButtonColor: '#d33'
                    });
                }
                btn.disabled = false;
                btn.textContent = 'Add to Cart';
            }
        })
        .catch(err => {
            console.error('Error:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to add to cart. Please try again.',
                confirmButtonColor: '#d33'
            });
            btn.disabled = false;
            btn.textContent = 'Add to Cart';
        });
}

// Close modal on outside click
window.onclick = function (event) {
    const viewModal = document.getElementById('viewProductModal');
    if (event.target == viewModal) {
        closeViewProductModal();
    }
}

// Close modal on Escape key
document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        const viewModal = document.getElementById('viewProductModal');
        if (viewModal.style.display === 'block') {
            closeViewProductModal();
        }
    }
});

// Open Edit Product Modal (for admin)
function openEditProductModal(productId) {
    fetch(`/dashboard/products/edit?id=${productId}&ajax=1`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('viewProductContainer').innerHTML = html;
            document.getElementById('viewProductModal').style.display = 'block';
        })
        .catch(err => {
            console.error('Error loading edit form:', err);
            alert('Failed to load edit form. Please try again.');
        });
}

// Close Edit Product Modal (for admin)
function closeEditProductModal() {
    document.getElementById('viewProductModal').style.display = 'none';
    document.getElementById('viewProductContainer').innerHTML = '';
}

// Confirm Delete Product (for admin)
function confirmDeleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        window.location.href = '/dashboard/products/delete?id=' + productId;
    }
}
