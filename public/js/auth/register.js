// =====================================================
    // KIỂM TRA EMAIL GMAIL VÀ SỐ ĐIỆN THOẠI
    // =====================================================

    function showFieldError(input, errorId, message) {
        const errorElement = document.getElementById(errorId);

        if (errorElement) {
            errorElement.textContent = message;
        }

        input.setCustomValidity(message);
        input.classList.toggle("input-error", message !== "");
    }

    function validateEmail(input) {
        if (!input) return false;

        const email = input.value;
        let message = "";

        if (email.length === 0) {
            message = "Vui lòng nhập địa chỉ email.";
        } else if (/\s/.test(email)) {
            message = "Email không được chứa khoảng trắng.";
        } else if (!email.includes("@")) {
            message = "Email chưa có ký tự @.";
        } else if ((email.match(/@/g) || []).length > 1) {
            message = "Email chỉ được chứa một ký tự @.";
        } else {
            const parts = email.split("@");
            const username = parts[0];
            const domain = parts[1];

            if (!username) {
                message = "Vui lòng nhập tên email trước ký tự @.";
            } else if (!domain) {
                message = "Vui lòng nhập tên miền sau ký tự @.";
            } else if (!/^[A-Za-z0-9._%+-]+$/.test(username)) {
                message = "Tên email chứa ký tự không hợp lệ.";
            } else if (username.startsWith(".") || username.endsWith(".")) {
                message = "Tên email không được bắt đầu hoặc kết thúc bằng dấu chấm.";
            } else if (username.includes("..")) {
                message = "Tên email không được chứa hai dấu chấm liên tiếp.";
            } else if (domain.toLowerCase() !== "gmail.com") {
                if (domain.toLowerCase() === "gmail") {
                    message = "Tên miền chưa hoàn chỉnh. Vui lòng nhập gmail.com.";
                } else if (!domain.includes(".")) {
                    message = "Tên miền email chưa hoàn chỉnh.";
                } else {
                    message = "Email phải sử dụng tên miền @gmail.com.";
                }
            }
        }

        showFieldError(input, "email-error", message);
        return message === "";
    }

    function handlePhoneInput(input) {
        input.value = input.value.replace(/\D/g, "").slice(0, 10);
        validatePhone(input);
    }

    function validatePhone(input) {
        if (!input) return false;

        const phone = input.value;
        let message = "";

        if (phone.length === 0) {
            message = "Vui lòng nhập số điện thoại.";
        } else if (!/^\d+$/.test(phone)) {
            message = "Số điện thoại chỉ được chứa chữ số.";
        } else if (phone.length < 10) {
            message = "Số điện thoại chưa đủ 10 chữ số. Hiện có " + phone.length + " chữ số.";
        } else if (phone.length > 10) {
            message = "Số điện thoại chỉ được phép có đúng 10 chữ số.";
        }

        showFieldError(input, "phone-error", message);
        return message === "";
    }



    // =====================================================
    // KIỂM TRA ĐỘ MẠNH MẬT KHẨU
    // =====================================================

    function checkPasswordStrength(input) {
        if (!input) return false;

        const password = input.value;
        const checks = {
            length: password.length >= 8,
            lower: /[a-z]/.test(password),
            upper: /[A-Z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9\s]/.test(password)
        };

        const isValid = Object.values(checks).every(Boolean);
        input.classList.toggle("input-error", password.length > 0 && !isValid);

        const passError = document.getElementById("password-error");
        if (passError) {
            passError.textContent = (password.length > 0 && !isValid)
                ? "Mật khẩu tối thiểu 8 ký tự, gồm chữ hoa, chữ thường, chữ số và ký tự đặc biệt."
                : "";
        }

        validatePasswordConfirmation();

        return isValid;
    }

    function validatePasswordConfirmation() {
        const password = document.getElementById("password");
        const confirmation = document.getElementById("password_confirmation");
        const errorElement = document.getElementById("confirm-password-error");

        if (!password || !confirmation || !errorElement) return false;

        let message = "";

        if (confirmation.value.length > 0 && confirmation.value !== password.value) {
            message = "Mật khẩu xác nhận không khớp.";
        }

        errorElement.textContent = message;
        confirmation.setCustomValidity(message);
        confirmation.classList.toggle("input-error", message !== "");

        return message === "";
    }

    /*
    |--------------------------------------------------------------------------
    | Đổi background theo role
    |--------------------------------------------------------------------------
    */

    const tenantRadio = document.getElementById("tenant");
    const ownerRadio = document.getElementById("owner");
    const background = document.getElementById("background");

    function updateBackground() {

        if (ownerRadio.checked) {

            background.classList.add("owner-active");

        } else {

            background.classList.remove("owner-active");

        }

    }

    tenantRadio.addEventListener("change", updateBackground);

    ownerRadio.addEventListener("change", updateBackground);

    updateBackground();


    /*
    |--------------------------------------------------------------------------
    | Chặn submit nhiều lần
    |--------------------------------------------------------------------------
    */

    const registerForm = document.getElementById("registerForm");
    const registerButton = document.getElementById("registerButton");

    if (registerForm && registerButton) {

        registerForm.addEventListener("submit", function (event) {

            const emailInput = document.getElementById("email");
            const phoneInput = document.getElementById("phone");
            const passwordInput = document.getElementById("password");
            const confirmationInput = document.getElementById("password_confirmation");

            const emailValid = validateEmail(emailInput);
            const phoneValid = validatePhone(phoneInput);
            const passwordValid = checkPasswordStrength(passwordInput);
            const confirmationValid = validatePasswordConfirmation();

            if (!emailValid || !phoneValid || !passwordValid || !confirmationValid) {
                event.preventDefault();

                if (!emailValid) emailInput.focus();
                else if (!phoneValid) phoneInput.focus();
                else if (!passwordValid) passwordInput.focus();
                else confirmationInput.focus();

                return;
            }

            registerButton.disabled = true;
            registerButton.innerText = "Đang tạo tài khoản...";
        });

    }

    const passwordInput = document.getElementById("password");
    const confirmationInput = document.getElementById("password_confirmation");

    if (passwordInput) {
        passwordInput.addEventListener("input", function () {
            checkPasswordStrength(passwordInput);
        });
    }

    if (confirmationInput) {
        confirmationInput.addEventListener("input", validatePasswordConfirmation);
        confirmationInput.addEventListener("blur", validatePasswordConfirmation);
    }

    if (passwordInput && passwordInput.value.length > 0) {
        checkPasswordStrength(passwordInput);
    }

    /*
|--------------------------------------------------------------------------
| Ẩn / hiện mật khẩu
|--------------------------------------------------------------------------
*/

    document.querySelectorAll(".toggle-password").forEach(function (button) {
        button.addEventListener("click", function () {
            const input = document.getElementById(button.dataset.target);
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

    /*
|--------------------------------------------------------------------------
| BẬT / TẮT BÓNG ĐÈN TƯƠNG TÁC
|--------------------------------------------------------------------------
*/
    const hangingLamp = document.getElementById("hangingLamp");
    const lampBulb = document.getElementById("lampBulb");
    const pullChain = document.getElementById("pullChain");
    const pageEl = document.querySelector(".page");

    function playSwitchSound(isLit) {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            osc.type = "sine";
            osc.frequency.setValueAtTime(isLit ? 650 : 420, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(isLit ? 1100 : 250, ctx.currentTime + 0.06);

            gain.gain.setValueAtTime(0.12, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.06);

            osc.connect(gain);
            gain.connect(ctx.destination);

            osc.start();
            osc.stop(ctx.currentTime + 0.06);
        } catch (e) {}
    }

    function toggleLamp() {
        if (!hangingLamp) return;
        const isLit = hangingLamp.classList.toggle("is-on");

        if (pageEl) {
            pageEl.classList.toggle("lamp-lit", isLit);
        }

        playSwitchSound(isLit);
    }

    if (lampBulb) {
        lampBulb.addEventListener("click", toggleLamp);
    }

    if (pullChain) {
        pullChain.addEventListener("click", function () {
            pullChain.style.transform = "translateY(16px)";
            setTimeout(function () {
                pullChain.style.transform = "";
            }, 180);
            toggleLamp();
        });
    }
