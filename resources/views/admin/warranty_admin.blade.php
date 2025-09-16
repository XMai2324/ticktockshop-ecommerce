@extends('admin.dashboard')

@section('title', 'Tra cứu bảo hành')

@section('content')
<div class="container warranty-lookup" style="margin-top: 30px;">
    <div class="row" style="display: flex; flex-wrap: wrap; gap: 30px;">

        {{-- Cột trái: Chính sách bảo hành --}}
        <div class="policy-column" style="flex: 1 1 50%;">
            <h2><i class="fas fa-shield-alt"></i> Chính sách bảo hành TickTock Shop</h2>
            <p>TickTock Shop cam kết bảo hành toàn bộ sản phẩm đồng hồ chính hãng trong thời gian 12 tháng kể từ ngày mua. Chính sách áp dụng cho tất cả khách hàng đã mua sản phẩm tại hệ thống cửa hàng hoặc website của chúng tôi.</p>

            <ul style="list-style: none; padding-left: 0; font-size: 16px;">
                <li><i class="fas fa-clock"></i> <strong>Thời hạn:</strong> 12 tháng kể từ ngày mua hàng (tính theo ngày trên hóa đơn hoặc mã bảo hành).</li>
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

        {{-- Cột phải: Form tra cứu và kết quả --}}
        <div class="form-column" style="flex: 1 1 40%; border-left: 1px solid #ccc; padding-left: 30px;">
            <h2>🔍 Tra cứu bảo hành</h2>

            <form id="warranty-form" action="{{ route('warranty.lookup') }}" method="POST">
                @csrf
                <input type="text" name="warranty_code" placeholder="Nhập mã bảo hành" required>
                <button type="button" id="lookup-button">Tra cứu</button>
            </form>


            @if (isset($warranty))
                @if ($warranty)
                    <div class="warranty-result" style="background: #f9f9f9; padding: 15px; border: 1px solid #ccc;">
                        <p><strong>Mã bảo hành:</strong> {{ $warranty->warranty_code }}</p>
                        <p><strong>Thời hạn:</strong> {{ $warranty->start_date }} đến {{ $warranty->end_date }}</p>
                        <p><strong>Trạng thái:</strong> 
                            @if ($warranty->status === 'valid')
                                <span style="color:green;">Còn hiệu lực</span>
                            @elseif ($warranty->status === 'expired')
                                <span style="color:red;">Đã hết hạn</span>
                            @else
                                <span>Đã sử dụng</span>
                            @endif
                        </p>
                    </div>
                @else
                    <p style="color:red;">Không tìm thấy mã bảo hành.</p>
                @endif
            @endif
        </div>

    </div>
</div>
@endsection
