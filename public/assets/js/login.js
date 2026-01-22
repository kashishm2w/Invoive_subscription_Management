// Get form and inputs
const form = document.getElementById("loginForm");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");

const emailRegex = /^[a-z0-9._]+@[a-z0-9.-]+\.[a-z]{2,}$/i;

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

// Real-time validation
emailInput.addEventListener("input", () => {
    if (!emailRegex.test(emailInput.value.trim())) {
        showError(emailInput, "Invalid email format");
    } else {
        clearError(emailInput);
    }
});

passwordInput.addEventListener("input", () => {
    if (passwordInput.value.length < 8) {
        showError(passwordInput, "Invalid Password");
    } else {
        clearError(passwordInput);
    }
});

// AJAX Form submission
form.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Client-side validation
    let hasError = false;

    if (!emailRegex.test(emailInput.value.trim())) {
        showError(emailInput, "Invalid email format");
        hasError = true;
    }
    if (passwordInput.value.length < 8) {
        showError(passwordInput, "Invalid Password");
        hasError = true;
    }

    if (hasError) return;

    // Get submit button and show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Logging in...';

    try {
        const formData = new FormData(form);

        const response = await fetch('/ajax/login', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showSuccessMessage(data.message);
            // Redirect after short delay
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            showServerErrors(data.errors);
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    } catch (error) {
        console.error('Login error:', error);
        showServerErrors(['An unexpected error occurred. Please try again.']);
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
});
