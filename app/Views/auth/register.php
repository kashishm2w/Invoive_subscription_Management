<link rel="stylesheet" href="/assets/css/style.css">
<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>

<div class="form-container">
    <h2 class="form-title">Register</h2>

    <?php if (!empty($errors)): ?>
        <ul class="error-list">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/register" class="register-form">
        <input type="text" name="name" id="name" placeholder="Name" maxlength="50"class="form-input">
        <input type="email" name="email" id="email" placeholder="Email" class="form-input">
        <input type="password" name="password" id="password" placeholder="Password" class="form-input">
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-input">
        <button type="submit" class="form-button">Register</button>
    </form>

    <p class="login-link">
        Already have an account? <a href="/login">Login here</a>
    </p>
</div>
<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>

<script src="/assets/js/register.js"></script>
