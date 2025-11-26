document.addEventListener("DOMContentLoaded", function () {

    const btnAddNew = document.getElementById("btnAddNew");
    const tableBody = document.querySelector("#tableProducts tbody");
    const form = document.getElementById("formNhapHang");

    /* ----------------------------------------
     * 1. CHẶN PHÍM ENTER GỬI FORM
     ---------------------------------------- */
    document.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
        }
    });

    

    /* ----------------------------------------
     * 2. TÍNH GIÁ BÁN = GIÁ NHẬP + 20%
     ---------------------------------------- */
    function updatePrice(row) {
        let cost = parseFloat(row.querySelector(".cost-price").value);
        if (!isNaN(cost)) {
            row.querySelector(".price-display").value = Math.round(cost * 1.2 / 1000) * 1000;
        }
    }

    document.addEventListener("input", function (e) {
        if (e.target.classList.contains("cost-price")) {
            updatePrice(e.target.closest("tr"));
        }
    });

    /* ----------------------------------------
     * 3. HÀM KIỂM TRA DỮ LIỆU KHI THÊM MỚI
     ---------------------------------------- */
    function validateNewRow(row) {
        let name = row.querySelector("input[name*='name']").value.trim();
        let category = row.querySelector("select[name*='category_id']").value;
        let brand = row.querySelector("select[name*='brand_id']").value;
        let cost = row.querySelector("input[name*='cost_price']").value;

        if (name === "") {
            alert("Tên sản phẩm không được để trống");
            return false;
        }
        if (category === "" || category == 0) {
            alert("Bạn phải chọn danh mục");
            return false;
        }
        if (brand === "" || brand == 0) {
            alert("Bạn phải chọn thương hiệu");
            return false;
        }
        if (cost === "" || cost < 0) {
            alert("Giá nhập không hợp lệ");
            return false;
        }
        return true;
    }

    /* ----------------------------------------
     * 4. THÊM SẢN PHẨM MỚI Ở DÒNG ĐẦU
     ---------------------------------------- */
    btnAddNew.addEventListener("click", function () {

        let newId = "new_" + Date.now();

        let row = document.createElement("tr");
        row.id = `row-${newId}`; 

        row.innerHTML = `
            <td>New</td>

            <td><input type="text" class="form-control" name="products[${newId}][name]"></td>

            <td>
                <select name="products[${newId}][category_id]" class="form-control">
                    <option value="">-- Chọn --</option>
                    ${window.categoriesOptions}
                </select>
            </td>

            <td>
                <select name="products[${newId}][brand_id]" class="form-control">
                    <option value="">-- Chọn --</option>
                    ${window.brandsOptions}
                </select>
            </td>

            <td><input type="number" min="0" class="form-control cost-price" name="products[${newId}][cost_price]" value=""></td>

            <td><input type="number" class="form-control price-display" readonly></td>

            <td><input type="number" readonly class="form-control" name="products[${newId}][quantity]" value="0"></td>

            <td><input type="number" min="0" class="form-control" name="products[${newId}][quantity_add]" value="0"></td>

            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row" data-id="${newId}">
                    Xóa
                </button>
            </td>
        `;

        // THÊM LÊN ĐẦU
        tableBody.prepend(row);

        // Focus vào ô tên cho tiện nhập liệu
        row.querySelector("input[name*='name']").focus();

    });

    /* ----------------------------------------
     * 5. KIỂM TRA TOÀN BỘ TRƯỚC KHI LƯU
     ---------------------------------------- */
    form.addEventListener("submit", function (e) {

        let rows = tableBody.querySelectorAll("tr");

        for (let row of rows) {
            let isNew = row.querySelector("td").innerText === "New";

            if (isNew) {
                if (!validateNewRow(row)) {
                    e.preventDefault(); 
                    return alert("Có dòng sản phẩm mới chưa điền đủ!");
                }
            }
        }
    });
    // Xoá bỏ dòng sản phẩm
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            const id = e.target.getAttribute('data-id');
            const row = document.getElementById(`row-${id}`);

            if (row) row.remove();
        }
    });
    
    const input = document.getElementById("searchInput");
    input.addEventListener("input", function () {
        const keyword = input.value.toLowerCase();
        document.querySelectorAll("#tableProducts tbody tr").forEach(row => {
            const name = row.querySelector("input.name-field").value.toLowerCase();
            row.style.display = name.includes(keyword) ? "" : "none";
        });
    });

    // Hiển thị nút khi cuộn
    window.addEventListener('scroll', function() {
        const btn = document.getElementById('btnScrollTop');
        if (document.documentElement.scrollTop > 200) {
            btn.style.display = 'block';
        } else {
            btn.style.display = 'none';
        }
    });

    // Khi click, scroll lên đầu trang
    document.getElementById('btnScrollTop').addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

});
