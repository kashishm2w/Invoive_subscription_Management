<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/setting.css">

<div class="settings-wrapper">
    <div class="settings-container user-form">
        <h1>Account Settings</h1>
        
        <div class="user-avatar">
            <div class="avatar-circle">
                <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
            </div>
            <p class="user-email"><?= htmlspecialchars($user['email'] ?? '') ?></p>
        </div>

        <form id="settingsForm" action="/settings/update" method="POST">
            
            <!-- Name Field -->
            <div class="form-row full-width">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name"
                        value="<?= htmlspecialchars($user['name'] ?? '') ?>" 
                        placeholder="Enter your full name"
                        required>
                </div>
            </div>

            <!-- Email Field -->
            <div class="form-row full-width">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email"
                        value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                        placeholder="Enter your email address">
                </div>
            </div>

            <!-- Password Field -->
            <div class="form-row full-width">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" id="password"
                        placeholder="Leave blank to keep current password">
                    <span class="field-hint">Password must be at least 8 characters with uppercase, lowercase, and number</span>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-actions">
                <button type="submit" id="submitBtn" class="btn-save">
                    <span class="btn-icon"></span> Update Settings
                </button>
            </div>

        </form>
    </div>
</div>

<script>
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = document.getElementById('submitBtn');
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="btn-icon">⏳</span> Updating...';
    
    fetch('/settings/update', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span class="btn-icon"></span> Update Settings';
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Updated Successfully!',
                text: data.message,
                confirmButtonColor: '#4ecdc4',
                timer: 3000,
                timerProgressBar: true
            }).then(() => {
                // Clear password field after successful update
                document.getElementById('password').value = '';
            });
        } else {
            // Handle validation errors
            let errorMessages = [];
            if (data.errors) {
                for (let field in data.errors) {
                    errorMessages.push(data.errors[field]);
                }
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                html: errorMessages.join('<br>'),
                confirmButtonColor: '#e74c3c'
            });
        }
    })
    .catch(error => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span class="btn-icon"></span> Update Settings';
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred. Please try again.',
            confirmButtonColor: '#e74c3c'
        });
    });
});
</script>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>