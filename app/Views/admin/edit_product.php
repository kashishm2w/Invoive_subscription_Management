<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/add_products.css">

<div class="form-container">
    <h2>Edit Product</h2>

    <form method="POST"
          action="/dashboard/products/edit"
          enctype="multipart/form-data"
          class="product-form">

        <input type="hidden" name="id" value="<?= $product['id'] ?>">

        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text"
                   id="name"
                   name="name"
                   value="<?= htmlspecialchars($product['name']) ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="description">About the product</label>
            <textarea id="description"
                      name="description"
                      rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number"
                   step="0.01"
                   id="price"
                   name="price"
                   value="<?= $product['price'] ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="tax_percent">Tax (%)</label>
            <input type="number"
                   step="0.01"
                   id="tax_percent"
                   name="tax_percent"
                   value="<?= $product['tax_percent'] ?>">
        </div>

        <div class="form-group">
            <label for="quantity">Quantity</label>
            <select id="quantity" name="quantity">
                <?php for ($i = 1; $i <= 100; $i++): ?>
                    <option value="<?= $i ?>" <?= ($product['quantity'] == $i) ? 'selected' : '' ?>>
                        <?= $i ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="poster">Product Poster</label>
            <input type="file" id="poster" name="poster" accept="image/*">

            <?php if (!empty($product['poster'])): ?>
                <p>Current Image:</p>
                <img src="/uploads/<?= htmlspecialchars($product['poster']) ?>" style="width:80px; margin-top:5px;">
            <?php endif; ?>
        </div>

        <?php
            $price = (float)$product['price'];
            $tax = (float)$product['tax_percent'];
            $total = $price + ($price * $tax / 100);
        ?>
        <p><strong>Total Amount:</strong> ₹<?= number_format($total, 2) ?></p>

        <button type="submit" class="form-button">Update Product</button>
    </form>
</div>
