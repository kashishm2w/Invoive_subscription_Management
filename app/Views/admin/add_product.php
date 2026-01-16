<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/add_products.css">
<div class="form-container">
    <form method="POST" action="/add-product" class="product-form" enctype="multipart/form-data">
        <h2>Add New Product</h2>

        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" required>
            <span class="error-message"></span>
        </div>

        <div class="form-group">
            <label for="description">About the product</label>
            <textarea id="description" name="description" rows="3"></textarea>
            <span class="error-message"></span>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" step="0.01" id="price" name="price" required>
            <span class="error-message"></span>
        </div>

        <div class="form-group">
            <label for="tax_percent">Tax (%)</label>
            <select name="tax_percent" id="tax_percent">
                <option value="18">18%</option>
                <option value="0">Tax Free</option>
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input
                type="number"
                id="quantity"
                name="quantity"
                min="1"
                step="1"
                required
                placeholder="Enter quantity">
            <span class="error-message"></span>
        </div>
        <div class="form-group">
            <label for="poster">Product Poster</label>
            <input type="file" id="poster" name="poster" accept="image/*">
            <span class="error-message"></span>
        </div>


        <button type="submit" class="form-button">Add Product</button>
    </form>

</div>