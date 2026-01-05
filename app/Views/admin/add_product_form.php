<span class="close" onclick="closeAddProductModal()">&times;</span>
<h2>Add New Product</h2>

<form id="addProductForm" method="POST" action="/dashboard/add-product" enctype="multipart/form-data" class="product-form">

    <div class="form-group">
        <label for="add_name">Product Name</label>
        <input type="text" id="add_name" name="name" required>
    </div>

    <div class="form-group">
        <label for="add_description">About the product</label>
        <textarea id="add_description" name="description" rows="3"></textarea>
    </div>

    <div class="form-group">
        <label for="add_price">Price</label>
        <input type="number" step="0.01" id="add_price" name="price" required>
    </div>

    <div class="form-group">
        <label for="add_tax_percent">Tax (%)</label>
        <input type="number" step="0.01" id="add_tax_percent" name="tax_percent" value="0">
    </div>

    <div class="form-group">
        <label for="add_quantity">Quantity</label>
        <select id="add_quantity" name="quantity">
            <?php for ($i = 1; $i <= 100; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?></option>
            <?php endfor; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="add_poster">Product Poster</label>
        <input type="file" id="add_poster" name="poster" accept="image/*">
    </div>

    <button type="submit" class="form-button">Add Product</button>
</form>
