@extends('admin.dashboard')

@section('content')
<div class="container mt-4">
    <h2>Lịch sử nhập hàng</h2>

    <form method="GET" class="d-flex gap-2 mb-3">
        <input type="date" name="from" value="{{ request('from') }}" class="form-control">
        <input type="date" name="to" value="{{ request('to') }}" class="form-control">
        <button type="submit" class="btn btn-primary">Lọc</button>
        <a href="{{ route('admin.import_history') }}" class="btn btn-primary">Reset</a>
        <a href="{{ route('admin.nhapHang_index') }}" class="btn btn-secondary">Quay lại</a>

    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Ngày</th>
                <th>Tên SP</th>
                <th>Tồn kho trước</th>
                <th>Số lượng nhập</th>
                <th>Tồn kho sau</th>
                <th>Giá nhập trước</th>
                <th>Giá nhập sau</th>
                <th>Giá bán</th>
            </tr>
        </thead>
        <tbody>
            @forelse($history as $h)
            <tr>
                <td>{{ $h->created_at }}</td>
                <td>{{ $h->product->name ?? '' }}</td>
                <td>{{ $h->quantity_before }}</td>
                <td>{{ $h->quantity_added }}</td>
                <td>{{ $h->quantity_after }}</td>
                <td>{{ number_format($h->cost_price_before) }}</td>
                <td>{{ number_format($h->cost_price_after) }}</td>
                <td>{{ number_format($h->sell_price_after) }}</td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center">Không có dữ liệu</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $history->withQueryString()->links() }}
</div>
@endsection
