<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/setting.css">

<div class="settings-container">
    <h1>Company Settings</h1>
    <h3>Add or Update</h3>


<form method="POST" action="/settings/company" enctype="multipart/form-data">

    <label>Company Name</label>
    <input 
        type="text" 
        name="company_name" 
        value="<?= htmlspecialchars(\App\Helpers\Session::get('company_name') ?? '') ?>" 
        required
    >

    <label>Email</label>
    <input
        type="email"
        name="email"
        value="<?= htmlspecialchars(\App\Helpers\Session::get('email') ?? '') ?>"
        required
    >

    <label>Phone</label>
    <input
        type="text"
        name="phone"
        value="<?= htmlspecialchars(\App\Helpers\Session::get('phone') ?? '') ?>"
        required
    >

    <label>Address</label>
    <textarea name="address" required><?= htmlspecialchars(\App\Helpers\Session::get('address') ?? '') ?></textarea>

    <label>Tax Number (GST)</label>
    <input
        type="text"
        name="tax_number"
        value="<?= htmlspecialchars(\App\Helpers\Session::get('tax_number') ?? '') ?>"
    >

    <!-- <label>Logo</label>
    <input type="file" name="logo"> -->

    <button type="submit">Save Company</button>
</form>
</div>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
22AAAAA0000A1Z5
Hisar ,op. jindal
Invoice and sub
7408787382