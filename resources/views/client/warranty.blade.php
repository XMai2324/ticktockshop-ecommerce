@extends('client.home')

@section('title', 'Tra cứu bảo hành')

@section('content')
<div class="container warranty-lookup" style="margin-top: 30px;">
    <div class="row" style="display: flex; flex-wrap: wrap; gap: 30px;">

        {{-- Cột trái: Chính sách bảo hành --}}
        <div class="policy-column" style="flex: 1 1 50%;">
            <h2><i class="fas fa-shield-alt"></i> Chính sách bảo hành TickTock Shop</h2>
            <p>TickTock Shop cam kết bảo hành toàn bộ sản phẩm đồng hồ chính hãng trong thời gian 12 tháng kể từ ngày mua. Chính sách áp dụng cho tất cả khách hàng đã mua sản phẩm tại hệ thống cửa hàng hoặc website của chúng tôi.</p>

            <ul style="list-style: none; padding-left: 0; font-size: 16px;">
                <li><i class="fas fa-clock"></i> <strong>Thời hạn:</strong> 12 tháng kể từ ngày mua hàng (tính theo ngày trên hóa đơn).</li>
                <li><i class="fas fa-cogs"></i> <strong>Phạm vi bảo hành:</strong> Lỗi kỹ thuật do nhà sản xuất bao gồm bộ máy, kim, núm chỉnh giờ, pin bị chai bất thường, v.v.</li>
                <li><i class="fas fa-ban"></i> <strong>Không áp dụng bảo hành:</strong>
                    <ul style="margin-top: 5px;">
                        <li>❌ Vỡ kính, trầy xước do va chạm, móp méo vỏ/dây do sử dụng sai cách.</li>
                        <li>❌ Vào nước đối với mẫu không chống nước hoặc sử dụng sai mức kháng nước quy định.</li>
                        <li>❌ Tự ý can thiệp sửa chữa tại nơi không phải TickTock Shop.</li>
                    </ul>
                </li>
                <li><i class="fas fa-store"></i> <strong>Hình thức tiếp nhận:</strong> Vui lòng mang sản phẩm đến trực tiếp cửa hàng hoặc gửi về trung tâm bảo hành theo hướng dẫn.</li>
                <li><i class="fas fa-file-alt"></i> <strong>Yêu cầu:</strong> Cần có mã bảo hành hoặc hóa đơn mua hàng hợp lệ.</li>
            </ul>

            <p style="margin-top: 10px;"><i class="fas fa-info-circle"></i> Mọi thông tin chi tiết, xin liên hệ hotline: <strong>1900 9999</strong> hoặc email: <strong>support@ticktockshop.vn</strong>.</p>
        </div>

      

    </div>
</div>
@endsection
