<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/setting.css">

<div class="settings-container">
    <h1>User Settings</h1>
<form action="/settings/update" method="POST">
    <label>Name</label>
<input type="text" name="name" 
       value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>

    <label>Email</label>
<input type="email" name="email" 
       value="<?= htmlspecialchars($user['email'] ?? '') ?>">

    <label>Phone Number</label>
<input type="text" name="phone" 
       value="<?= htmlspecialchars($user['phone_no'] ?? '') ?>" required>

    <label>Address</label>
<textarea name="address" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>




    <label>Password (leave blank to keep current)</label>
    <input type="password" name="password">

    <button type="submit">Update Settings</button>
</form>
</div>
<?php if (\App\Helpers\Session::get('profile_updated')): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Updated Successfully',
        text: 'Your profile has been updated.',
        confirmButtonColor: '#3085d6'
    });
</script>
<?php \App\Helpers\Session::remove('profile_updated'); ?>
<?php endif; ?>

