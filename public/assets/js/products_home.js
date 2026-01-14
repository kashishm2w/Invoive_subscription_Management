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
                    timerProgressBar: true
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
                    timerProgressBar: true
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
