document.addEventListener("DOMContentLoaded", () => {

    const overlay = document.getElementById("review-overlay");
    const btnShow = document.getElementById("btn-show-reviews");
    const btnClose = document.getElementById("close-review-popup");

    const starButtons = document.querySelectorAll(".star-filter-btn");
    const reviewItems = document.querySelectorAll(".review-item");

    // ----- MỞ POPUP -----
    if (btnShow) {
        btnShow.addEventListener("click", () => {
            overlay.style.display = "flex";
        });
    }

    // ----- ĐÓNG POPUP -----
    if (btnClose) {
        btnClose.addEventListener("click", () => {
            overlay.style.display = "none";
        });
    }

    // Click ra ngoài thì đóng
    overlay.addEventListener("click", (e) => {
        if (e.target === overlay) {
            overlay.style.display = "none";
        }
    });

    // ----- LỌC ĐÁNH GIÁ -----
    starButtons.forEach(btn => {
        btn.addEventListener("click", function () {

            // Reset active
            starButtons.forEach(b => b.classList.remove("active"));
            this.classList.add("active");

            const star = this.getAttribute("data-star");

            reviewItems.forEach(item => {
                const itemStar = item.getAttribute("data-rating");

                if (star === "" || itemStar === star) {
                    item.style.display = "block";
                } else {
                    item.style.display = "none";
                }
            });

        });
    });

    // filterStar.addEventListener("change", applyFilters);
    // filterResponse.addEventListener("change", applyFilters);

});
