document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("warranty-form");
  const button = document.getElementById("lookup-button");

  const loginIcon = document.getElementById("login-icon");
  const loginOverlay = document.getElementById("login-overlay");

  const loginForm = document.getElementById("login-form");
  const registerForm = document.getElementById("register-form");
  const forgotForm = document.getElementById("forgot-form");

  const toRegisterBtn = document.getElementById("to-register");
  const toLoginBtn = document.getElementById("to-login");
  const toForgotLink = document.getElementById("to-forgot");
  const toLoginFromForgot = document.getElementById("to-login-from-forgot");

  // Helper: ẩn tất cả panel và mở panel chỉ định
  function openPanel(panel) {
    if (!loginOverlay) return;
    loginOverlay.style.display = "flex";
    [loginForm, registerForm, forgotForm].forEach(p => {
      if (p) p.style.display = "none";
    });
    if (panel) panel.style.display = "block";
  }

  // Nếu có lỗi login từ session, tự động mở form login
  const hasLoginError = document.querySelector('meta[name="login-error"]');
  if (hasLoginError && loginForm) openPanel(loginForm);

  // Nếu có lỗi đăng ký từ session, tự động mở form đăng ký
  const hasRegisterError = document.querySelector('meta[name="register-error"]');
  if (hasRegisterError && registerForm) openPanel(registerForm);

  // Nếu có lỗi/quy định mở forgot từ server
  const hasForgotError = document.querySelector('meta[name="forgot-error"]');
  const openForgot = document.querySelector('meta[name="open-forgot"]');
  if ((hasForgotError || openForgot) && forgotForm) openPanel(forgotForm);

  // Click biểu tượng login
  if (loginIcon) {
    loginIcon.addEventListener("click", function (e) {
      e.preventDefault();
      openPanel(loginForm);
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

  // ESC để đóng overlay
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && loginOverlay) {
      loginOverlay.style.display = "none";
    }
  });

  // Chuyển Login -> Register
  if (toRegisterBtn) {
    toRegisterBtn.addEventListener("click", function () {
      openPanel(registerForm);
    });
  }

  // Chuyển Register -> Login
  if (toLoginBtn) {
    toLoginBtn.addEventListener("click", function () {
      openPanel(loginForm);
    });
  }

  // Chuyển Login -> Forgot
  if (toForgotLink) {
    toForgotLink.addEventListener("click", function (e) {
      e.preventDefault();
      openPanel(forgotForm);
    });
  }

  // Chuyển Forgot -> Login
  if (toLoginFromForgot) {
    toLoginFromForgot.addEventListener("click", function () {
      openPanel(loginForm);
    });
  }

  // Xử lý nút tra cứu bảo hành
  if (button && form) {
    button.addEventListener("click", function () {
      if (!window.IS_AUTHENTICATED) {
        alert("Vui lòng đăng nhập trước khi tra cứu bảo hành!");
        openPanel(loginForm);
      } else {
        form.submit();
      }
    });
  }
});
