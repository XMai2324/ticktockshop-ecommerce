@extends('admin.dashboard')

@section('title', 'Quản lý đánh giá')

@section('content')
    <div class="container-fluid mt-3">

        {{-- 2 CỘT: FORM TRÁI – BẢNG PHẢI --}}
        <div class="row g-3">

            {{-- FORM CHI TIẾT BÊN TRÁI --}}
            <div class="col-md-3">
                <div class="card p-3 rating-detail-card">
                    <h3 class="mb-3">Chi tiết đánh giá</h3>

                    <div class="mb-2">
                        <label>ID đánh giá:</label>
                        <input type="text" id="detail-id" class="form-control" disabled>
                    </div>

                    <div class="mb-2">
                        <label>Người đánh giá:</label>
                        <input type="text" id="detail-user" class="form-control" disabled>
                    </div>

                    <div class="mb-2">
                        <label>Sản phẩm:</label>
                        <textarea id="detail-product" class="form-control" rows="3" disabled></textarea>
                    </div>

                    <div class="mb-2">
                        <label>Đơn hàng:</label>
                        <input id="detail-order" class="form-control" disabled></input>
                    </div>

                    <div class="mb-2">
                        <label>Số sao:</label>
                        <input type="text" id="detail-rating" class="form-control" disabled>
                    </div>

                    <div class="mb-2">
                        <label>Bình luận:</label>
                        <textarea id="detail-comment" class="form-control" rows="3" disabled></textarea>
                    </div>

                    <div class="mb-2">
                        <label>Thời gian:</label>
                        <input type="text" id="detail-time" class="form-control" disabled>
                    </div>

                    <div class="mb-2">
                        <label>Phản hồi từ người bán:</label>
                        <textarea id="detail-response" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button id="save-response" class="btn btn-success w-100" type="submit">Lưu phản hồi</button>
                    </div>

                </div>
            </div>

            {{-- ✅ BẢNG BÊN PHẢI --}}
            <div class="col-md-9">
                <div class="card p-3 rating-list-card">
                    <h3 class="mb-4">Danh sách đánh giá</h3>

                    {{-- BỘ LỌC --}}
                    <div class="card mb-4 p-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Số sao:</label>
                                <select id="filter-rating" class="form-control">
                                    <option value="">Tất cả</option>
                                    <option value="5">5 sao</option>
                                    <option value="4">4 sao</option>
                                    <option value="3">3 sao</option>
                                    <option value="2">2 sao</option>
                                    <option value="1">1 sao</option>
                                </select>
                            </div>

                            {{-- Lọc theo phản hồi --}}
                            <div class="col-md-3">
                                <label>Phản hồi:</label>
                                <select id="filter-response" class="form-control">
                                    <option value="">Tất cả</option>
                                    <option value="1">Đã phản hồi</option>
                                    <option value="0">Chưa phản hồi</option>
                                </select>
                            </div>

                            {{-- Ngày bắt đầu --}}
                            <div class="col-md-3">
                                <label>Từ ngày:</label>
                                <input type="date" id="filter-date-from" class="form-control">
                            </div>

                            {{-- Ngày kết thúc --}}
                            <div class="col-md-3">
                                <label>Đến ngày:</label>
                                <input type="date" id="filter-date-to" class="form-control">
                            </div>

                            <div class="col-md-3 mt-3">
                                <button id="reset-filter" class="btn btn-secondary w-100">Reset</button>
                            </div>

                            <div class="col-md-3 align-self-end">
                                <button id="apply-filter" class="btn btn-primary w-100">Lọc</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive border rounded" style="max-height: 700px; overflow-y: auto;">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Người đánh giá</th>
                                    <th>Sản phẩm</th>
                                    <th>Đơn hàng</th>
                                    <th>Sao</th>
                                    <th>Bình luận</th>
                                    <th>Phản hồi</th>
                                    <th>Thời gian</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="ratings-list-js">
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/rating.js') }}"></script>
@endsection
