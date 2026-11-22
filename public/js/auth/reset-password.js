document.querySelectorAll(".toggle-password").forEach(function (button) {
        button.addEventListener("click", function () {
            const targetId = button.dataset.target || button.getAttribute("data-target");
            const input = targetId ? document.getElementById(targetId) : null;
            if (!input) return;

            const isPassword = input.type === "password";
            input.type = isPassword ? "text" : "password";

            const eyeOpen = button.querySelector(".eye-open");
            const eyeClosed = button.querySelector(".eye-closed");
            if (eyeOpen && eyeClosed) {
                eyeOpen.style.display = isPassword ? "none" : "block";
                eyeClosed.style.display = isPassword ? "block" : "none";
            }
            button.setAttribute("aria-label", isPassword ? "Ẩn mật khẩu" : "Hiện mật khẩu");
            button.setAttribute("aria-pressed", String(isPassword));
        });
    });
