@extends('admin.dashboard')

@section('content')
<div class="container container-nhap-hang mt-4">
    <h2>Nhập hàng / Cập nhật sản phẩm</h2>
    <div class="filter-form mb-3 d-flex gap-2 align-items-center">
        <form method="GET" action="{{ route('admin.nhapHang_index') }}" class="d-flex gap-2 align-items-center">
            <select name="category_id" class="form-control">
                <option value="">-- Tất cả danh mục --</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>

            <select name="brand_id" class="form-control">
                <option value="">-- Tất cả thương hiệu --</option>
                @foreach($brands as $b)
                    <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>
                        {{ $b->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Lọc</button>
            <a href="{{ route('admin.nhapHang_index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <input type="text" id="searchInput" placeholder="Tìm sản phẩm theo tên...">

    <div class="top-buttons">
        <button type="button" id="btnAddNew" class="btn btn-primary">+ Thêm sản phẩm mới</button>
        <button type="submit" form="formNhapHang" class="btn btn-success">Lưu tất cả</button>
    </div>

    <form id="formNhapHang" action="{{ route('admin.nhapHang_savePreview') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <table class="table table-bordered" id="tableProducts">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên SP</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <th>Giá nhập (vnd)</th>
                    <th>Giá bán (vnd)</th>
                    <th>Số lượng tồn</th>
                    <th>Số lượng nhập</th>
                    <!-- <th>Ảnh</th> -->
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                <tr data-id="{{ $p->id }}">
                    <td>{{ $p->id }}</td>
                    <td><input type="text" readonly name="products[{{ $p->id }}][name]" value="{{ $p->name }}" class="form-control name-field"></td>
                    <td>
                        <input type="hidden" name="products[{{ $p->id }}][category_id]" value="{{ $p->category_id }}">
                        <input type="text" class="form-control" value="{{ $p->category->name ?? '' }}" readonly>
                    </td>

                    <td>
                        <input type="hidden" name="products[{{ $p->id }}][brand_id]" value="{{ $p->brand_id }}">
                        <input type="text" class="form-control" value="{{ $p->brand->name ?? '' }}" readonly>
                    </td>
                    <td><input type="number" min="0" class="form-control cost-price" name="products[{{ $p->id }}][cost_price]" value="{{ $p->cost_price }}"></td>
                    <td><input type="number" readonly class="form-control price-display" value="{{ $p->price }}"></td>
                    <td><input type="number" readonly class="form-control" name="products[{{ $p->id }}][quantity]" value="{{ $p->quantity }}"></td>
                    <td><input type="number" min="0" class="form-control" name="products[{{ $p->id }}][quantity_add]"></td>
                    <!-- <td>{{ basename($p->image) ?? '' }}</td> -->
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
    <!-- Nút lên đầu trang -->
    <button id="btnScrollTop" title="Lên đầu trang">⬆</button>
</div>

<script>
window.categoriesOptions = `{!! $categories->map(fn($c)=>"<option value='{$c->id}'>$c->name</option>")->implode('') !!}`;
window.brandsOptions = `{!! $brands->map(fn($b)=>"<option value='{$b->id}'>$b->name</option>")->implode('') !!}`;
</script>
<script src="/js/admin/nhapHang.js"></script>
@endsection
