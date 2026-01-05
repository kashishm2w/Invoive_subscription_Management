<span class="close" onclick="closeEditProductModal()">&times;</span>
<h2>Edit Product</h2>

<form id="editProductForm" method="POST" action="/dashboard/products/edit" enctype="multipart/form-data" class="product-form">
    <input type="hidden" name="id" value="<?= $product['id'] ?>">

    <div class="form-group">
        <label for="edit_name">Product Name</label>
        <input type="text" id="edit_name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
    </div>

    <div class="form-group">
        <label for="edit_description">Description</label>
        <textarea id="edit_description" name="description" rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
    </div>

    <div class="form-group">
        <label for="edit_price">Price</label>
        <input type="number" step="0.01" id="edit_price" name="price" value="<?= $product['price'] ?>" required>
    </div>

    <div class="form-group">
        <label for="edit_tax_percent">Tax (%)</label>
        <input type="number" step="0.01" id="edit_tax_percent" name="tax_percent" value="<?= $product['tax_percent'] ?>">
    </div>

    <div class="form-group">
        <label for="edit_quantity">Quantity</label>
        <select id="edit_quantity" name="quantity">
            <?php for ($i = 1; $i <= 100; $i++): ?>
                <option value="<?= $i ?>" <?= ($product['quantity'] == $i) ? 'selected' : '' ?>>
                    <?= $i ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="edit_poster">Product Poster</label>
        <input type="file" id="edit_poster" name="poster">
        <?php if (!empty($product['poster'])): ?>
            <p class="current-image-label">Current Image:</p>
            <img src="/uploads/<?= htmlspecialchars($product['poster']) ?>" class="current-poster-preview" style="width:80px;">
        <?php endif; ?>
    </div>

    <button type="submit" class="form-button">Update Product</button>
</form>
