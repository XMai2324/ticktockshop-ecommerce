function editOrder(order, updateUrl) {
    // Đổi tiêu đề form
    document.getElementById("form-title").innerText =
        "Cập nhật đơn hàng #" + order.id;

    // Lấy form
    let form = document.getElementById("order-form");
    form.action = updateUrl;

    // Thêm input hidden _method=PUT nếu chưa có
    let methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
        methodInput = document.createElement("input");
        methodInput.type = "hidden";
        methodInput.name = "_method";
        methodInput.value = "PUT";
        form.appendChild(methodInput);
    } else {
        methodInput.value = "PUT"; // đề phòng bị ghi sai
    }

    // Gán dữ liệu từ order vào form
    document.getElementById("f-code").value = order.id;
    document.getElementById("f-customer").value = order.user
        ? order.user.name
        : "N/A";
    document.getElementById("f-total").value =
        new Intl.NumberFormat("vi-VN").format(order.total_price) + " đ";
    document.getElementById("f-status").value = order.status;

    // Scroll tới form để admin dễ nhìn thấy
    form.scrollIntoView({ behavior: "smooth" });
}
