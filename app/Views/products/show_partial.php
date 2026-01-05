<span class="close" onclick="closeViewProductModal()">&times;</span>

<div class="product-view-content">
    <?php if (!empty($product['poster'])): ?>
        <div class="product-image-container">
            <img src="/uploads/<?= htmlspecialchars($product['poster']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-view-image">
        </div>
    <?php endif; ?>

    <h2 class="product-view-title"><?= htmlspecialchars($product['name']) ?></h2>

    <div class="product-view-description">
        <label>Description:</label>
        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
    </div>

    <?php
    $price = (float)$product['price'];
    $tax   = (float)$product['tax_percent'];
    $total = $price + ($price * $tax / 100);
    ?>

    <div class="product-view-price">
        <?php if (\App\Helpers\Session::get('role') === 'admin'): ?>
            <div class="price-row">
                <span class="price-label">Base Price:</span>
                <span class="price-value">&#8377;<?= number_format($price, 2) ?></span>
            </div>
            <div class="price-row">
                <span class="price-label">Tax:</span>
                <span class="price-value"><?= htmlspecialchars($product['tax_percent']) ?>%</span>
            </div>
        <?php endif; ?>
        <div class="price-row total">
            <span class="price-label">Total Price:</span>
            <span class="price-value">&#8377;<?= number_format($total, 2) ?></span>
        </div>
    </div>

    <?php if (\App\Helpers\Session::get('role') === 'admin'): ?>
        <div class="product-view-stock">
            <span class="stock-label">Available Stock:</span>
            <span class="stock-value"><?= (int)$product['quantity'] ?> units</span>
        </div>
        
        <div class="admin-product-actions">
            <button type="button" class="btn-edit" onclick="closeViewProductModal(); openEditProductModal(<?= $product['id'] ?>)">
                Edit Product
            </button>
            <button type="button" class="btn-delete" onclick="confirmDeleteProduct(<?= $product['id'] ?>)">
                Delete Product
            </button>
        </div>
    <?php endif; ?>

    <?php if (\App\Helpers\Session::get('role') !== 'admin'): ?>
        <div class="product-view-actions">
            <?php if ($isInCart): ?>
                <!-- Product already in cart -->
                <div class="already-in-cart">
                    <span class="added-icon"></span>
                    <span class="added-text">Already in Cart</span>
                </div>
            <?php else: ?>
                <!-- Product not in cart - show add form -->
                <label for="modal-qty-<?= $product['id'] ?>">Quantity:</label>
                <input type="number" 
                       id="modal-qty-<?= $product['id'] ?>" 
                       value="1" 
                       min="1" 
                       max="<?= max(1, (int)$product['quantity']) ?>"
                       class="qty-input">
                <button type="button" onclick="addToCartFromModal(<?= $product['id'] ?>)" class="btn-add-cart">
                    Add to Cart
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
