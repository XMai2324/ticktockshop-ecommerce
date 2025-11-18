document.addEventListener("DOMContentLoaded", function () {
    const csrfTag = document.querySelector('meta[name="csrf-token"]');
    if (!csrfTag) {
        console.error('Thiếu <meta name="csrf-token"> trong layout admin!');
        return;
    }
    const csrfToken = csrfTag.getAttribute("content");

    const toggleButtons = document.querySelectorAll(".btn-toggle");

    // --- Khởi tạo giao diện theo trạng thái từ DB ---
    toggleButtons.forEach(btn => {
        const id = btn.getAttribute("data-id");
        const hidden = btn.getAttribute("data-hidden") === "1";
        const card = document.getElementById("product-" + id);

        if (!card) return;

        // Đổi text nút + hiệu ứng card
        if (hidden) {
            btn.textContent = "Hiện";
            card.classList.add("product-hidden");
        } else {
            btn.textContent = "Ẩn";
            card.classList.remove("product-hidden");
        }
    });

    // --- Xử lý click Ẩn/Hiện ---
    toggleButtons.forEach(btn => {
        btn.addEventListener("click", async function () {
            const id = btn.getAttribute("data-id");
            const card = document.getElementById("product-" + id);
            if (!id || !card) return;

            btn.disabled = true;

            try {
                const res = await fetch(`/admin/products/toggle/${id}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({}),
                });

                if (!res.ok) {
                    throw new Error("Lỗi server: " + res.status);
                }

                const data = await res.json();
                const isHidden = !!data.hidden;

                // Cập nhật lại dataset để lần sau dùng
                btn.setAttribute("data-hidden", isHidden ? "1" : "0");
                card.dataset.hidden = isHidden ? "1" : "0"; // 🔥 THÊM DÒNG NÀY

                // Đổi text nút + cập nhật giao diện
                if (isHidden) {
                    btn.textContent = "Hiện";
                    card.classList.add("product-hidden");
                } else {
                    btn.textContent = "Ẩn";
                    card.classList.remove("product-hidden");
                }

            } catch (e) {
                console.error(e);
                alert("Có lỗi khi đổi trạng thái ẩn/hiện sản phẩm!");
            } finally {
                btn.disabled = false;
            }
        });
    });

    // ========== FILTER ẨN / HIỆN ==========
    const btnFilterHidden = document.getElementById('btn-filter-hidden');

    if (btnFilterHidden) {
        let filterOn = false; // mặc định: tắt bộ lọc

        btnFilterHidden.addEventListener('click', function () {
            filterOn = !filterOn;

            // Cập nhật giao diện nút
            btnFilterHidden.classList.toggle('active', filterOn);
            btnFilterHidden.textContent = filterOn
                ? 'Chỉ hiển thị sản phẩm ẩn'
                : 'Hiện sản phẩm ẩn';

            // Lọc từng card sản phẩm
            document.querySelectorAll('.product-card').forEach(function (card) {
                const hidden = card.dataset.hidden === '1';

                if (filterOn) {
                    // Bật lọc → chỉ hiện sản phẩm ẩn
                    card.style.display = hidden ? 'block' : 'none';
                } else {
                    // Tắt lọc → hiện tất cả (ẩn thì vẫn bị làm mờ bằng CSS)
                    card.style.display = 'block';
                }
            });
        });
    }
});
