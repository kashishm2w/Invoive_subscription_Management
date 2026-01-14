<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/setting.css">

<div class="settings-container">
    <h1>User Settings</h1>
    <form id="settingsForm" action="/settings/update" method="POST">
        <label>Name</label>
        <input type="text" name="name" id="name"
            value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>

        <label>Email</label>
        <input type="email" name="email" id="email"
            value="<?= htmlspecialchars($user['email'] ?? '') ?>">

        <label>Password (leave blank to keep current)</label>
        <input type="password" name="password" id="password">

        <button type="submit" id="submitBtn">Update Settings</button>
    </form>
</div>

<script>
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = document.getElementById('submitBtn');
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    
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
        submitBtn.innerHTML = 'Update Settings';
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Updated Successfully!',
                text: data.message,
                confirmButtonColor: '#3085d6',
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
                confirmButtonColor: '#d33'
            });
        }
    })
    .catch(error => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Update Settings';
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred. Please try again.',
            confirmButtonColor: '#d33'
        });
    });
});
</script>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>