@extends('admin.dashboard')

@section('title', 'Quản lý đồng hồ - TickTock Shop')

@section('content')
<div class="container" style="display: flex; gap: 30px; margin-top: 30px;">

    {{-- ✅ Sidebar hãng --}}
    <aside class="sidebar-brand">
    <h3>Thương hiệu</h3>
    <ul>
        <li>
            <a href="{{ route('admin.products_index') }}" 
               class="{{ !request()->has('brand') ? 'active' : '' }}">
               Tất cả
            </a>
        </li>
        @foreach($brands as $brand)
            <li>
                <a href="{{ route('admin.products_index', ['brand' => $brand->id]) }}"
                   class="{{ request('brand') == $brand->id ? 'active' : '' }}">
                    {{ $brand->name }}
                </a>
            </li>
        @endforeach
    </ul>
</aside>


    {{-- ✅ Danh sách sản phẩm --}}
    <div style="flex: 1;">

        <button id="btn-open-create-form" class="btn-create-product">
            + Thêm sản phẩm mới
        </button>

        <div id="create-form-modal" class="modal-overlay">
            <div class="modal-content">
                <span class="close-modal">&times;</span>

                <form method="POST" action="{{ route('admin.store') }}" enctype="multipart/form-data">
                    @csrf

                    <h3>Thêm sản phẩm mới</h3>

                    <label>Tên sản phẩm:</label>
                    <input type="text" name="name" required>

                    <label>Thương hiệu:</label>
                    <select name="brand_id">
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>

                    <label>Danh mục:</label>
                    <select name="category_id">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <label>Giá:</label>
                    <input type="number" name="price" required>

                    <label>Ảnh:</label>
                    <input type="file" name="image" id="image-input" required>
                    <img id="preview" style="display:none; max-width: 200px; margin-top: 10px; border:1px solid #ccc;">

                    <label>Mô tả:</label>
                    <textarea name="description" rows="4"></textarea>

                    <button type="submit">Lưu</button>
                </form>
            </div>
        </div>

        <div id="edit-form-modal" class="modal-overlay">
            <div class="modal-content">
                <span class="close-modal">&times;</span>

                <form method="POST" action="" id="edit-form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h3>Sửa sản phẩm</h3>

                    <label>Tên sản phẩm:</label>
                    <input type="text" name="name" id="edit-name" required>

                    <label>Thương hiệu:</label>
                    <select name="brand_id" id="edit-brand">
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>

                    <label>Danh mục:</label>
                    <select name="category_id" id="edit-category">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <label>Giá:</label>
                    <input type="number" name="price" id="edit-price" required>

                    <label>Ảnh mới (nếu thay):</label>
                    <input type="file" name="image" id="edit-image-input">
                    <img id="edit-preview" style="display:none; max-width: 200px; margin-top: 10px; border:1px solid #ccc;">

                    <label>Mô tả:</label>
                    <textarea name="description" id="edit-description" rows="4"></textarea>

                    <button type="submit">Cập nhật</button>
                </form>
            </div>
        </div>





        <div class="row">
            @forelse($products as $product)
                <div class="col-3">
                    <div class="product-card">
                        <!-- <img src="{{ asset('storage/Watch/' . $product->image) }}" alt="{{ $product->name }}" class="product-image"> -->
                         @php
                            $folder = 'Watch/Watch_nu'; // mặc định
                            $slug = \Illuminate\Support\Str::slug($product->category->name ?? '');

                            if ($slug === 'nam') {
                                $folder = 'Watch/Watch_nam';
                            } elseif ($slug === 'cap-doi') {
                                $folder = 'Watch/Watch_cap';
                            }
                        @endphp

                        <img src="{{ asset('storage/' . $folder . '/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">

                        <h3 class="product-name">{{ $product->name }}</h3>
                        <p class="product-price">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                        <p class="product-desc">{{ \Illuminate\Support\Str::limit($product->description, 60) }}</p>
                        <div class="action-buttons">
                            <a href="javascript:void(0);" 
                            class="btn-edit" 
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}"
                            data-price="{{ $product->price }}"
                            data-description="{{ $product->description }}"
                            data-brand="{{ $product->brand_id }}"
                            data-category="{{ $product->category_id }}"
                            data-image="{{ $product->image }}"
                            data-folder="{{ $folder }}"> Sửa </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xoá đồng hồ này?')">Xoá</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>Không có đồng hồ nào!</p>
            @endforelse
        </div>
    </div>

</div>
@endsection

<script src="{{ asset('js/admin/create.js') }}"></script>
<script src="{{ asset('js/admin/edit.js') }}"></script>

