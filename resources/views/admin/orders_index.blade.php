@extends('admin.dashboard')

@section('title', 'QL Đơn hàng - TickTock Shop')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

@section('content')
<div class="orders-layout">

    {{-- ========== LEFT: FORM CHI TIẾT / CHỈNH SỬA ========== --}}
    <aside class="panel-left">
        <h3 id="form-title">Chi tiết đơn hàng</h3>

        {{-- Khi bấm Sửa từ bảng, JS sẽ load dữ liệu đơn vào form này --}}
        <form id="order-form" method="POST">
            @csrf
            {{-- JS sẽ thay đổi action & thêm @method PUT khi edit --}}

            <div class="field">
                <label>Mã đơn hàng</label>
                <input type="text" id="f-code" name="code" readonly>
            </div>

            <div class="field">
                <label>Khách hàng</label>
                <input type="text" id="f-customer" readonly>
            </div>

            <div class="field">
                <label>Tổng tiền</label>
                <input type="text" id="f-total" readonly>
            </div>

            <div class="field">
                <label>Trạng thái</label>
                <select name="status" id="f-status" required>
                    <option value="pending">Chờ xử lý</option>
                    <option value="confirmed">Đã xác nhận</option>
                    <option value="shipping">Đang giao</option>
                    <option value="delivered">Hoàn thành</option>
                    <option value="cancelled">Đã hủy</option>
                </select>
            </div>

            <button class="btn-primary">Cập nhật</button>
        </form>
    </aside>

    {{-- ========== RIGHT: DANH SÁCH ========== --}}
    <section class="panel-right">
        <h3>Danh sách đơn hàng</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $o)
                <tr>
                    <td>{{ $o->id }}</td>
                    <td>{{ $o->user->name ?? 'N/A' }}</td>
                    <td>{{ number_format($o->total_price, 0, ',', '.') }} đ</td>
                    <td>{{ ucfirst($o->status) }}</td>
                    <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <button class="btn-edit" onclick='editOrder(@json($o), "{{ route("admin.orders.update", $o->id) }}")'>
                            <i class="bi bi-pencil-square"></i> Sửa
                        </button>

                        <form action="{{ route('admin.orders.delete', $o->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Xóa đơn hàng này?')">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </form>

                        <a href="{{ route('admin.orders.show', $o->id) }}" class="btn-show">
                            <i class="bi bi-eye"></i> Xem
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Phân trang --}}
        <div class="pagination">
            {{ $orders->links() }}
        </div>
    </section>
</div>
@endsection


<link rel="stylesheet" href="{{ asset('/css/admin/orders_ad.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script src="{{ asset('js/admin/orders.js') }}"></script>
