```javascript
// =========================================
// Company Portal Login
// login.js
// =========================================

document.addEventListener("DOMContentLoaded", function () {

    // ===============================
    // Show / Hide Password
    // ===============================

    const password = document.getElementById("password");
    const toggle = document.getElementById("togglePassword");

    if (toggle && password) {

        toggle.addEventListener("click", function () {

            if (password.type === "password") {

                password.type = "text";

                this.innerHTML =
                    '<i class="bi bi-eye-slash"></i>';

            } else {

                password.type = "password";

                this.innerHTML =
                    '<i class="bi bi-eye"></i>';

            }

        });

    }

    // ===============================
    // Auto Focus Username
    // ===============================

    const username = document.querySelector(
        'input[name="username"]'
    );

    if (username) {

        username.focus();

    }

    // ===============================
    // Login Button Animation
    // ===============================

    const form = document.querySelector("form");

    const loginButton =
        document.querySelector(".login-btn");

    if (form && loginButton) {

        form.addEventListener("submit", function () {

            loginButton.disabled = true;

            loginButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Signing In...
            `;

        });

    }

    // ===============================
    // Enter Key Login
    // ===============================

    document.addEventListener("keypress", function (e) {

        if (e.key === "Enter") {

            if (form) {

                form.submit();

            }

        }

    });

    // ===============================
    // Live Date & Time
    // ===============================

    function updateClock() {

        const clock = document.getElementById("clock");

        if (!clock) return;

        const now = new Date();

        clock.innerHTML = now.toLocaleString();

    }

    updateClock();

    setInterval(updateClock, 1000);

});

```
