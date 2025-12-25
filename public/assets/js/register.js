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

// Regex patterns
const nameRegex = /^[a-zA-Z ]+$/;
const emailRegex = /^[a-z0-9._]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

// Real-time validation
nameInput.addEventListener("input", () => {
    const value = nameInput.value.trim();
    if (!nameRegex.test(value)) {
        showError(nameInput, "Name can only contain letters and spaces");
    } else if (value.length < 2 || value.length > 30) {
        showError(nameInput, "Name should contain 2 to 30 characters");
    } else {
        clearError(nameInput); //  error disappears if valid
    }
});

emailInput.addEventListener("input", () => {
    const value = emailInput.value.trim();
    if (!emailRegex.test(value)) {
        showError(emailInput, "Invalid email format");
    } else if (value.length > 25) {
        showError(emailInput, "Email must not exceed 25 characters");
    } else {
        clearError(emailInput); //  error disappears if valid
    }
});

passwordInput.addEventListener("input", () => {
    const value = passwordInput.value;
    if (!passwordRegex.test(value)) {
        showError(passwordInput, "Password must have at least 8 characters including uppercase, lowercase, and number");
    } else {
        clearError(passwordInput); //  error disappears if valid
    }

    // Check confirm password if user typed in password field
    if (confirmInput.value && confirmInput.value !== value) {
        showError(confirmInput, "Passwords do not match");
    } else {
        clearError(confirmInput); //  error disappears if matches
    }
});

confirmInput.addEventListener("input", () => {
    if (confirmInput.value !== passwordInput.value) {
        showError(confirmInput, "Passwords do not match");
    } else {
        clearError(confirmInput); 
    }
});

// Final check on submit
form.addEventListener("submit", (e) => {
    let hasError = false;

    const nameValue = nameInput.value.trim();
    const emailValue = emailInput.value.trim();
    const passwordValue = passwordInput.value;
    const confirmValue = confirmInput.value;

    // Validation
    if (!nameRegex.test(nameValue) || nameValue.length < 2 || nameValue.length > 30) {
        showError(nameInput, "Name can only contain letters and spaces, 2–30 characters");
        hasError = true;
    }
    if (!emailRegex.test(emailValue) || emailValue.length > 25) {
        showError(emailInput, "Invalid email or too long");
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

    if (hasError) e.preventDefault(); 
});
