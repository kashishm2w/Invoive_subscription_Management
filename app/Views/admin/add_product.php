<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/add_products.css">
<div class="form-container">
    <?php
    $redirect = $redirect ?? '';
    require APP_ROOT . '/app/Views/admin/add_product_form.php';
    ?>
</div>