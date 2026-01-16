<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/setting.css">

<div class="settings-wrapper">
    <div class="settings-container company-form">
        <h1>Company Profile Setup</h1>

        <form method="POST" action="/settings/company" enctype="multipart/form-data">
            
            <!-- Row 1: Company Name & Email -->
            <div class="form-row">
                <div class="form-group">
                    <label for="company_name">Company Name</label>
                    <input
                        type="text"
                        id="company_name"
                        name="company_name"
                        value="<?= htmlspecialchars($company['company_name'] ?? '') ?>"
                        placeholder="Enter company name"
                        required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($company['email'] ?? '') ?>"
                        placeholder="Enter email address"
                        required>
                </div>
            </div>

            <!-- Row 2: Phone & Tax Number -->
            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="<?= htmlspecialchars($company['phone'] ?? '') ?>"
                        placeholder="Enter phone number"
                        required>
                </div>
                <div class="form-group">
                    <label for="tax_number">Tax Number (GST)</label>
                    <input
                        type="text"
                        id="tax_number"
                        name="tax_number"
                        value="<?= htmlspecialchars($company['tax_number'] ?? '') ?>"
                        placeholder="e.g., 22AAAAA0000A1Z5">
                </div>
            </div>

            <!-- Row 3: Address (Full Width) -->
            <div class="form-row full-width">
                <div class="form-group">
                    <label for="address">Company Address</label>
                    <textarea 
                        id="address" 
                        name="address" 
                        placeholder="Enter complete address"
                        required><?= htmlspecialchars($company['address'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Row 4: Default Tax Rate -->
            <div class="form-row">
                <div class="form-group">
                    <label for="tax_percent">Default Tax Rate</label>
                    <select name="tax_percent" id="tax_percent">
                        <option value="0" <?= ($company['tax_percent'] ?? 18) == 0 ? 'selected' : '' ?>>0% (Tax Free)</option>
                        <option value="5" <?= ($company['tax_percent'] ?? 18) == 5 ? 'selected' : '' ?>>5%</option>
                        <option value="12" <?= ($company['tax_percent'] ?? 18) == 12 ? 'selected' : '' ?>>12%</option>
                        <option value="18" <?= ($company['tax_percent'] ?? 18) == 18 ? 'selected' : '' ?>>18%</option>
                        <option value="28" <?= ($company['tax_percent'] ?? 18) == 28 ? 'selected' : '' ?>>28%</option>
                    </select>
                </div>
                <div class="form-group tax-info">
                    <div class="info-box">
                        <span class="info-icon"></span>
                        <span>This tax rate will be applied to all products by default, unless marked as Tax Free.</span>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="form-actions">
                <button type="submit" class="btn-save">Save Company Profile</button>
                <button type="reset" class="btn-reset">Reset</button>
            </div>

        </form>
    </div>
</div>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>