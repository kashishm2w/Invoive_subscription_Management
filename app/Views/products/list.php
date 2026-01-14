<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/products.css">

<main class="main-content">
<div class="dashboard-header">
    <h2>Product Listing</h2>

    <div class="product-search">
      <input type="text" id="product_search" placeholder="Search by product name.." autocomplete="off">
    </div>

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
                    <th>Price (&#36;)</th>
                    <th>Tax %</th>
                <?php else: ?>
                    <th>Total Price (&#36;)</th>
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
                        <td><strong>&#36;<?= number_format($total, 2) ?></strong></td>
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
    window.IS_ADMIN = <?= \App\Helpers\Session::get('role') === 'admin' ? 'true' : 'false' ?>;
</script>
<script src="/assets/js/products_list.js"></script>

