<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/products.css">

<main class="main-content">
<div class="dashboard-header">
    <h2>Product Listing</h2>

    <?php if (\App\Helpers\Session::get('role') === 'admin'): ?>
        <button type="button" class="btn-add-product" onclick="openAddProductModal()">+ Add Product</button>
    <?php endif; ?>
</div>

<?php if (!empty($products)): ?>
    <table class="product-table" border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Poster</th>
                <th>Product Name</th>
                <?php if (\App\Helpers\Session::get('role') === 'admin'): ?>
                    <th>Price (&#8377;)</th>
                    <th>Tax %</th>
                <?php else: ?>
                    <th>Total Price (&#8377;)</th>
                <?php endif; ?>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td>
                        <?php if (!empty($product['poster']) && file_exists(APP_ROOT . '/public/uploads/' . $product['poster'])): ?>
                            <img src="/uploads/<?= htmlspecialchars($product['poster']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-poster" style="width:60px; height:auto;">
                        <?php else: ?>
                            <span>No Image</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($product['name']) ?></td>

                    <?php if (\App\Helpers\Session::get('role') === 'admin'): ?>
                        <td><?= number_format($product['price'], 2) ?></td>
                        <td><?= htmlspecialchars($product['tax_percent']) ?>%</td>
                    <?php else: ?>
                        <?php
                        $price = (float)$product['price'];
                        $tax   = (float)$product['tax_percent'];
                        $total = $price + ($price * $tax / 100);
                        ?>
                        <td><strong>&#8377;<?= number_format($total, 2) ?></strong></td>
                    <?php endif; ?>

                    <td>
                        <button type="button" class="btn-view" onclick="openViewProductModal(<?= $product['id'] ?>)">View</button>

                        <?php if (\App\Helpers\Session::get('role') === 'admin'): ?>
<button type="button" onclick="openEditProductModal(<?= $product['id'] ?>)">
    Edit
</button>
                            | <a href="/dashboard/products/delete?id=<?= $product['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        <?php else: ?>
                            | <button
                                id="cart-btn-<?= $product['id'] ?>"
                                onclick="addToCart(<?= $product['id'] ?>)"
                                <?= in_array($product['id'], $cartProductIds) ? 'disabled' : '' ?>>
                                <?= in_array($product['id'], $cartProductIds) ? 'Added' : 'Add to Cart' ?>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="pagination">
        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
            <a href="?page=<?= $i ?>" <?= $i === $pagination['current_page'] ? 'class="active"' : '' ?>>
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
<?php else: ?>
    <p>No products found.</p>
<?php endif; ?>

<?php if (\App\Helpers\Session::get('role') !== 'admin'): ?>
    <div class="button-container">
        <a href="/cart" class="view-cart">View Cart</a>
    </div>
<?php endif; ?>

<!-- View Product Modal -->
<div id="viewProductModal" class="modal">
    <div class="modal-content" id="viewProductContainer">
        <!-- Product details will be loaded here via AJAX -->
    </div>
</div>

<!-- Add Product Modal -->
<div id="addProductModal" class="modal">
    <div class="modal-content" id="addFormContainer">
        <!-- Form content will be loaded here via AJAX -->
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editProductModal" class="modal">
    <div class="modal-content" id="editFormContainer">
        <!-- Form content will be loaded here via AJAX -->
    </div>
</div>
</main>
<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>

<script>
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
    fetch('/dashboard/add-product?ajax=1')
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
    
    fetch('/dashboard/add-product', {
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
window.onclick = function(event) {
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
document.addEventListener('keydown', function(event) {
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

</script>

