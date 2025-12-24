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

// Real-time validation
emailInput.addEventListener("input", () => {
    if (!emailRegex.test(emailInput.value.trim())) {
        showError(emailInput, "Invalid email format");
    } else {
        clearError(emailInput);
    }
});

passwordInput.addEventListener("input", () => {
    if (passwordInput.value.length < 6) {
        showError(passwordInput, "Password must be at least 6 characters");
    } else {
        clearError(passwordInput);
    }
});

// Submit check
form.addEventListener("submit", (e) => {
    let hasError = false;

    if (!emailRegex.test(emailInput.value.trim())) hasError = true;
    if (passwordInput.value.length < 6) hasError = true;

    if (hasError) e.preventDefault();
});
