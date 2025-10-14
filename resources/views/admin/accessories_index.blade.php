@extends('admin.dashboard')

@section('title', 'QLSP_PhuKienDongHo - TickTock Shop')

@section('content')
<div class="container" style="display: flex; gap: 30px; margin-top: 30px;">

    

    {{-- Danh sách phụ kiện --}}
    <div style="flex: 1;">

        <button id="btn-open-create-form" class="btn-create-product">
            + Thêm {{ ($type ?? '') === 'straps' ? 'dây đeo' : ( ($type ?? '') === 'boxes' ? 'hộp đựng' : ( ($type ?? '') === 'glasses' ? 'kính cường lực' : 'phụ kiện mới' ) ) }}
        </button>

        {{-- Modal tạo mới --}}
        <div id="create-form-modal" class="modal-overlay">
            <div class="modal-content">
                <span class="close-modal">&times;</span>

                <form method="POST" action="{{ route('admin.accessories.store', ['type' => $type]) }}" enctype="multipart/form-data" class="accessory-form">
                    @csrf
                    <h3>Thêm {{ $type === 'straps' ? 'dây đeo' : ($type === 'boxes' ? 'hộp đựng' : 'kính cường lực') }}</h3>

                    <label>Tên:</label>
                    <input type="text" name="name" required>

                    <label>Giá:</label>
                    <input type="number" name="price" required>

                    <label>Số lượng:</label>
                    <input type="number" name="quantity" min="0" value="0" required>

                    @if($type === 'straps' || $type === 'glasses')
                        <label>Chất liệu:</label>
                        <input type="text" name="material" required>

                        <label>Màu sắc:</label>
                        <input type="text" name="color" required>
                    @endif

                    <label>Ảnh:</label>
                    <input type="file" name="image" id="image-input" required>
                    <img id="preview" style="display:none; max-width: 200px; margin-top: 10px; border:1px solid #ccc;">

                    <label>Mô tả:</label>
                    <textarea name="description" rows="4"></textarea>

                    <div style="margin-top: 10px;">
                        <button type="submit">Lưu</button>
                        <button type="button" class="btn-cancel">Hủy</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal sửa --}}
        <div id="edit-form-modal" class="modal-overlay">
            <div class="modal-content">
                <span class="close-modal">&times;</span>

                <form method="POST" action="" id="edit-form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h3>Sửa {{ $type === 'straps' ? 'dây đeo' : ($type === 'boxes' ? 'hộp đựng' : 'kính cường lực') }}</h3>

                    <label>Tên:</label>
                    <input type="text" name="name" id="edit-name" required>

                    <label>Giá:</label>
                    <input type="number" name="price" id="edit-price" required>

                    <label>Số lượng:</label>
                    <input type="number" name="quantity" id="edit-quantity" min="0" required>

                    @if($type === 'straps' || $type === 'glasses')
                        <div id="wrap-material-color">
                            <label>Chất liệu:</label>
                            <input type="text" name="material" id="edit-material" required>

                            <label>Màu sắc:</label>
                            <input type="text" name="color" id="edit-color" required>
                        </div>
                    @endif

                    <label>Ảnh mới nếu thay:</label>
                    <input type="file" name="image" id="edit-image-input">
                    <img id="edit-preview" style="display:none; max-width:200px; margin-top:10px; border:1px solid #ccc;">

                    <label>Mô tả:</label>
                    <textarea name="description" id="edit-description" rows="4"></textarea>

                    <button type="submit">Cập nhật</button>
                </form>
            </div>
        </div>

        {{-- Lưới item giống products --}}
        <div class="row">
            @php
                // Helper chọn thư mục theo type
                $folderMap = [
                    'straps' => 'accessories/straps',
                    'boxes'  => 'accessories/boxes',
                    'glasses'=> 'accessories/glasses',
                ];
                $folder = $folderMap[$type] ?? 'accessories';
            @endphp

            @forelse($items as $item)
                <div class="col-3">
                    <div class="product-card">
                        <img src="{{ asset('storage/' . $folder . '/' . $item->image) }}"
                             alt="{{ $item->name }}" class="product-image">

                        <h3 class="product-name">{{ $item->name }}</h3>
                        <p class="product-price">{{ number_format($item->price, 0, ',', '.') }}₫</p>
                        <p class="product-desc">{{ \Illuminate\Support\Str::limit($item->description, 60) }}</p>

                        <div class="action-buttons">
                            <button type="button"
                                class="btn-edit open-edit"
                                data-type="{{ $type }}"
                                data-id="{{ $item->id }}"
                                data-name="{{ $item->name }}"
                                data-price="{{ $item->price }}"
                                data-quantity="{{ $item->quantity }}"
                                @if($type == 'straps' || $type == 'glasses')
                                    data-material="{{ $item->material }}"
                                    data-color="{{ $item->color }}"
                                @endif
                                data-description="{{ $item->description }}"
                                data-image="{{ asset('storage/' . $folder . '/' . $item->image) }}"
                                data-update-url="{{ route('admin.accessories.update', ['type' => $type, 'id' => $item->id]) }}"
                            >Sửa</button>

                            <form method="POST" action="{{ route('admin.accessories.delete', ['type' => $type, 'id' => $item->id]) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>Chưa có mục nào!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

<script src="{{ asset('js/admin/accessories.js') }}"></script>
