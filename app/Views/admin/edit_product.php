<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/edit_product.css">
<div class="form-container">
    <h2>Edit Product</h2>

    <?php
    // Load the form partial
    $errors = $errors ?? [];
    require APP_ROOT . '/app/Views/admin/edit_product_form.php';
    ?>
</div>

<script>
    // If you want to use this inside a modal
    function openEditProductModal() {
        document.querySelector('.form-container').style.display = 'block';
    }
    function closeEditProductModal() {
        document.querySelector('.form-container').style.display = 'none';
    }
</script>
<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
