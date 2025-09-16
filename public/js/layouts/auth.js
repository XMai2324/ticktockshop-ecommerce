document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("warranty-form");
    const button = document.getElementById("lookup-button");

    const loginIcon = document.getElementById("login-icon");
    const loginOverlay = document.getElementById("login-overlay");
    const loginForm = document.getElementById("login-form");
    const registerForm = document.getElementById("register-form");
    const toRegisterBtn = document.getElementById("to-register");
    const toLoginBtn = document.getElementById("to-login");

    // Nếu có lỗi login từ session, tự động mở form login
    const hasLoginError = document.querySelector('meta[name="login-error"]');
    if (hasLoginError && loginOverlay && loginForm) {
        loginOverlay.style.display = "flex";
        loginForm.style.display = "block";
        if (registerForm) {
            registerForm.style.display = "none";
        }
    }

    // Nếu có lỗi đăng ký
    const hasRegisterError = document.querySelector('meta[name=register-error]');
    if (hasRegisterError && loginOverlay && registerForm) {
        loginOverlay.style.display = "flex";
        loginForm.style.display = "none";
        registerForm.style.display = "block";
    }

    // Click biểu tượng login
    if (loginIcon) {
        loginIcon.addEventListener("click", function (e) {
            e.preventDefault();
            loginOverlay.style.display = "flex";
            loginForm.style.display = "block";
            if (registerForm) {
                registerForm.style.display = "none";
            }
        });
    }

    // Click nền tối để đóng overlay
    if (loginOverlay) {
        loginOverlay.addEventListener("click", function (e) {
            if (e.target === loginOverlay) {
                loginOverlay.style.display = "none";
            }
        });
    }

    // Chuyển giữa form đăng ký và đăng nhập
    if (toRegisterBtn) {
        toRegisterBtn.addEventListener("click", function () {
            loginForm.style.display = "none";
            if (registerForm) registerForm.style.display = "block";
        });
    }

    if (toLoginBtn) {
        toLoginBtn.addEventListener("click", function () {
            registerForm.style.display = "none";
            loginForm.style.display = "block";
        });
    }

    // Xử lý nút tra cứu bảo hành
    if (button && form) {
        button.addEventListener("click", function () {
            if (!IS_AUTHENTICATED) {
                alert("Vui lòng đăng nhập trước khi tra cứu bảo hành!");
                // Hiện overlay đăng nhập thay 
                if (loginOverlay && loginForm) {
                    loginOverlay.style.display = "flex";
                    loginForm.style.display = "block";
                    if (registerForm) registerForm.style.display = "none";
                }
            } else {
                form.submit();
            }
        });
    }
});

