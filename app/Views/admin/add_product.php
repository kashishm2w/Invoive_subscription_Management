<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/add_products.css">
<div class="form-container">
   <form method="POST" action="/dashboard/add-product" class="product-form">

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
        <input type="number" step="0.01" id="tax_percent" name="tax_percent" value="0">
              <span class="error-message"></span>
    </div>
<div class="form-group">
    <label for="quantity">Quantity</label>
    <select id="quantity" name="quantity">
        <?php for ($i = 1; $i <= 100; $i++): ?>
            <option value="<?= $i ?>"><?= $i ?></option>
        <?php endfor; ?>
    </select>
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

