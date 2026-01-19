// Get form and inputs
const form = document.getElementById("registerForm");
const nameInput = document.getElementById("name");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const confirmInput = document.getElementById("confirm_password");

// Function to show inline error
function showError(input, message) {
    let errorEl = input.nextElementSibling;
    if (!errorEl || !errorEl.classList.contains("inline-error")) {
        errorEl = document.createElement("div");
        errorEl.className = "inline-error";
        errorEl.style.color = "red";
        errorEl.style.fontSize = "13px";
        input.parentNode.insertBefore(errorEl, input.nextSibling);
    }
    errorEl.textContent = message;
}

// Function to clear error
function clearError(input) {
    const errorEl = input.nextElementSibling;
    if (errorEl && errorEl.classList.contains("inline-error")) {
        errorEl.textContent = "";
    }
}

// Clear existing error list if present
function clearServerErrors() {
    const errorList = document.querySelector('.error-list');
    if (errorList) {
        errorList.remove();
    }
    const successMsg = document.querySelector('.success-message');
    if (successMsg) {
        successMsg.remove();
    }
}

// Show server-side errors in error list format
function showServerErrors(errors) {
    clearServerErrors();

    const errorList = document.createElement('ul');
    errorList.className = 'error-list';

    errors.forEach(error => {
        const li = document.createElement('li');
        li.textContent = error;
        errorList.appendChild(li);
    });

    // Insert after title but before form
    const formContainer = form.closest('.form-container') || form.parentElement;
    const formTitle = formContainer.querySelector('.form-title');
    if (formTitle) {
        formTitle.insertAdjacentElement('afterend', errorList);
    } else {
        formContainer.insertBefore(errorList, form);
    }
}

// Show success message
function showSuccessMessage(message) {
    clearServerErrors();

    const successDiv = document.createElement('div');
    successDiv.className = 'success-message';
    successDiv.style.cssText = 'background-color: #d1fae5; border-left: 4px solid #10b981; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;';
    successDiv.textContent = message;

    const formContainer = form.closest('.form-container') || form.parentElement;
    const formTitle = formContainer.querySelector('.form-title');
    if (formTitle) {
        formTitle.insertAdjacentElement('afterend', successDiv);
    } else {
        formContainer.insertBefore(successDiv, form);
    }
}
// Regex patterns
const nameRegex = /^[a-zA-Z0-9 ]+$/;
const emailRegex = /^[a-z0-9._]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

// Real-time validation
nameInput.addEventListener("input", () => {
    const value = nameInput.value.trim();
    if (value && !nameRegex.test(value)) {
        showError(nameInput, "Name can only contain letters and spaces");
    } else {
        clearError(nameInput);
    }
});

emailInput.addEventListener("input", () => {
    const value = emailInput.value.trim();
    if (value && !emailRegex.test(value)) {
        showError(emailInput, "Invalid email format");
    } else {
        clearError(emailInput);
    }
});

passwordInput.addEventListener("input", () => {
    const value = passwordInput.value;
    if (value && !passwordRegex.test(value)) {
        showError(passwordInput, "Password must have at least 8 characters including uppercase, lowercase, and number");
    } else {
        clearError(passwordInput);
    }

    // Check confirm password if user typed in password field
    if (confirmInput.value && confirmInput.value !== value) {
        showError(confirmInput, "Passwords do not match");
    } else if (confirmInput.value) {
        clearError(confirmInput);
    }
});

confirmInput.addEventListener("input", () => {
    if (confirmInput.value !== passwordInput.value) {
        showError(confirmInput, "Passwords do not match");
    } else {
        clearError(confirmInput);
    }
});

// AJAX Form submission
form.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Client-side validation
    let hasError = false;

    const nameValue = nameInput.value.trim();
    const emailValue = emailInput.value.trim();
    const passwordValue = passwordInput.value;
    const confirmValue = confirmInput.value;

    if (!nameValue || !nameRegex.test(nameValue)) {
        showError(nameInput, "Name can only contain letters and spaces");
        hasError = true;
    }
    if (!emailValue || !emailRegex.test(emailValue)) {
        showError(emailInput, "Invalid email format");
        hasError = true;
    }
    if (!passwordRegex.test(passwordValue)) {
        showError(passwordInput, "Password must have at least 8 characters including uppercase, lowercase, and number");
        hasError = true;
    }
    if (passwordValue !== confirmValue) {
        showError(confirmInput, "Passwords do not match");
        hasError = true;
    }

    if (hasError) return;

    // Get submit button and show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Registering...';

    try {
        const formData = new FormData(form);

        const response = await fetch('/ajax/register', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showSuccessMessage(data.message);
            // Clear form
            form.reset();
            // Redirect after short delay
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1500);
        } else {
            showServerErrors(data.errors);
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    } catch (error) {
        console.error('Registration error:', error);
        showServerErrors(['An unexpected error occurred. Please try again.']);
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
});
