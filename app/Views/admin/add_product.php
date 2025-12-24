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
<!-- 
<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/add_products.css">

<div class="form-container">
<form method="POST" action="/dashboard/add-product" class="product-form" enctype="multipart/form-data">

    <div class="form-group">
        <label>Product Name</label>
        <input type="text" id="name" name="name" required>
    </div>

    <div class="form-group">
        <label>About the product</label>
        <textarea id="description" name="description" rows="3"></textarea>
    </div>

    <div class="form-group">
        <label>Price (per item)</label>
        <input type="number" step="0.01" id="price" name="price" required>
    </div>

    <div class="form-group">
        <label>Quantity</label>
        <select id="quantity" name="quantity">
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?></option>
            <?php endfor; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Tax (%) per item</label>
        <input type="number" step="0.01" id="tax_percent" name="tax_percent" value="0">
    </div>

    <!-- 🔹 CALCULATED FIELDS
    <div class="form-group">
        <label>Subtotal</label>
        <input type="text" id="subtotal" readonly>
    </div>

    <div class="form-group">
        <label>Tax Amount</label>
        <input type="text" id="tax_amount" readonly>
    </div>

    <div class="form-group">
        <label>Total Amount</label>
        <input type="text" id="total_amount" readonly>
    </div>

    <div class="form-group">
        <label>Product Poster</label>
        <input type="file" id="poster" name="poster" accept="image/*">
    </div>

    <button type="submit" class="form-button">Add Product</button>
</form>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {

    const price = document.getElementById('price');
    const quantity = document.getElementById('quantity');
    const tax = document.getElementById('tax_percent');

    const subtotalField = document.getElementById('subtotal');
    const taxField = document.getElementById('tax_amount');
    const totalField = document.getElementById('total_amount');

    function calculate() {
        const p = parseFloat(price.value) || 0;
        const q = parseInt(quantity.value) || 0;
        const t = parseFloat(tax.value) || 0;

        const subtotal = p * q;
        const taxAmount = subtotal * (t / 100);
        const total = subtotal + taxAmount;

        subtotalField.value = subtotal.toFixed(2);
        taxField.value = taxAmount.toFixed(2);
        totalField.value = total.toFixed(2);
    }

    price.addEventListener('input', calculate);
    quantity.addEventListener('change', calculate);
    tax.addEventListener('input', calculate);
});
</script> -->
