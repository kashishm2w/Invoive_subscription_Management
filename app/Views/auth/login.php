<link rel="stylesheet" href="/assets/css/style.css">
<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>

<div class="form-container">
    <h2 class="form-title">Login</h2>

   <?php
$redirect = $redirect ?? '';
require APP_ROOT . '/app/Views/auth/login_form.php';
?>
</div>
<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>

<script src="/assets/js/login.js"></script>