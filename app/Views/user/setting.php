<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/setting.css">

<div class="settings-container">
    <h1>User Settings</h1>
    <form action="/settings/update" method="POST">
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars(\App\Helpers\Session::get('name') ?? '') ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars(\App\Helpers\Session::get('email') ?? '') ?>" required>

        <label>Phone Number</label>
        <input type="text" name="phone" value="<?= htmlspecialchars(\App\Helpers\Session::get('phone') ?? '') ?>" placeholder="Enter phone number" required>

        <label>Address</label>
        <textarea name="address" placeholder="Enter your address" required><?= htmlspecialchars(\App\Helpers\Session::get('address') ?? '') ?></textarea>

        <label>Password (leave blank to keep current)</label>
        <input type="password" name="password">

        <button type="submit">Update Settings</button>
    </form>
</div>
<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
