
<link rel="stylesheet" href="/assets/css/style.css">
<?php if (!empty($errors ?? [])): ?>
        <ul class="error-list">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/login" class="register-form" id="loginForm">
        <input type="email" name="email" id="email" placeholder="Email" class="form-input">
        <input type="password" name="password" id="password" placeholder="Password" class="form-input">
        <input type="hidden" name="redirect_after_login" value="<?= $redirect ?? '' ?>">

        <button type="submit" class="form-button">Login</button>
    </form>

    <p class="login-link">
        Don't have an account? <a href="/register">Register here</a>
    </p>