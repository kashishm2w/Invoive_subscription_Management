<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/setting.css">

<div class="settings-container">
    <h1>Company Setup</h1>
    <h3>Add or Update</h3>


<form method="POST" action="/settings/company" enctype="multipart/form-data">

    <label>Company Name</label>
    <input 
        type="text" 
        name="company_name" 
        value="<?= htmlspecialchars($company['company_name'] ?? '') ?>" 
        required
    >

    <label>Email</label>
    <input
        type="email"
        name="email"
        value="<?= htmlspecialchars($company['email'] ?? '') ?>"
        required
    >

    <label>Phone</label>
    <input
        type="text"
        name="phone"
        value="<?= htmlspecialchars($company['phone'] ?? '') ?>"
        required
    >

    <label>Address</label>
    <textarea name="address" required><?= htmlspecialchars($company['address'] ?? '') ?></textarea>

    <label>Tax Number (GST)</label>
    <input
        type="text"
        name="tax_number"
        value="<?= htmlspecialchars($company['tax_number'] ?? '') ?>"
    >

    <!-- <label>Logo</label>
    <input type="file" name="logo"> -->

    <button type="submit">Save Company</button>
</form>
</div>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>