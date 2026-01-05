<link rel="stylesheet" href="/assets/css/style.css">

<div class="form-container">
    <h2 class="form-title">Login</h2>

   <?php
$redirect = $redirect ?? '';
require APP_ROOT . '/app/Views/auth/login_form.php';
?>
</div>

<script src="/assets/js/login.js"></script>