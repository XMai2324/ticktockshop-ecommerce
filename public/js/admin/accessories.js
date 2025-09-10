document.addEventListener("DOMContentLoaded", function () {
    const openBtn = document.getElementById("btn-open-create-form");
    const closeBtn = document.getElementById("btn-cancel-create");
    const formOverlay = document.getElementById("create-form");

    if (openBtn && closeBtn && formOverlay) {
        openBtn.addEventListener("click", () => {
            formOverlay.style.display = "flex";
        });

        closeBtn.addEventListener("click", () => {
            formOverlay.style.display = "none";
        });

        // Đóng khi click ra ngoài form
        formOverlay.addEventListener("click", function (e) {
            if (e.target === formOverlay) {
                formOverlay.style.display = "none";
            }
        });
    }
});

