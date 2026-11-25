document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");
    const loginButton = document.getElementById("loginButton");
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");
  

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener("click", function () {
            const showing = passwordInput.type === "password";

            passwordInput.type = showing ? "text" : "password";
            const eyeOpen = togglePassword.querySelector(".eye-open");
            const eyeClosed = togglePassword.querySelector(".eye-closed");
            if (eyeOpen && eyeClosed) {
                eyeOpen.style.display = showing ? "none" : "block";
                eyeClosed.style.display = showing ? "block" : "none";
            }
            togglePassword.setAttribute(
                "aria-label",
                showing ? "Ẩn mật khẩu" : "Hiện mật khẩu"
            );
            togglePassword.setAttribute("aria-pressed", String(showing));
        });
    }

    if (form && loginButton) {
        form.addEventListener("submit", function (event) {
            if (!form.checkValidity()) {
                return;
            }

            loginButton.disabled = true;
            loginButton.textContent = "Đang đăng nhập...";

            // Nếu trình duyệt hủy gửi form, cho phép thử lại.
            window.setTimeout(function () {
                if (document.visibilityState === "visible") {
                    loginButton.disabled = false;
                    loginButton.textContent = "Đăng nhập";
                }
            }, 10000);
        });
    }

    //BẬT / TẮT BÓNG ĐÈN TƯƠNG TÁC
  
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

});
