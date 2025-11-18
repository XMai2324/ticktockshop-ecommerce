@extends('admin.dashboard')

@section('title', 'Phụ kiện đồng hồ - TickTock Shop')

@section('content')
<div class="container" style="margin-top: 30px;">

    {{-- Header + nút thêm --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>
            Quản lý phụ kiện 
            @if($type === 'straps') - Dây đeo
            @elseif($type === 'boxes') - Hộp đựng
            @elseif($type === 'glasses') - Kính cường lực
            @endif
        </h2>

        <button id="btn-open-accessory-create" class="btn-create-product">
            + Thêm phụ kiện mới
        </button>
    </div>

    {{-- ========== MODAL THÊM PHỤ KIỆN ========== --}}
    <div id="accessory-create-modal" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <span class="close-modal" data-close="#accessory-create-modal">&times;</span>

            <form method="POST" action="{{ route('admin.accessories.store', ['type' => $type]) }}" enctype="multipart/form-data">
                @csrf

                <h3>Thêm phụ kiện mới</h3>

                <label>Tên phụ kiện:</label>
                <input type="text" name="name" required>

                <label>Giá:</label>
                <input type="number" name="price" min="0" required>

                <label>Ảnh:</label>
                <input type="file" name="image" id="accessory-image-input" required>
                <img id="accessory-preview" style="display:none; max-width: 200px; margin-top: 10px; border:1px solid #ccc;">

                <label>Mô tả:</label>
                <textarea name="description" rows="4"></textarea>

                <button type="submit">Lưu</button>
            </form>
        </div>
    </div>

    {{-- ========== MODAL SỬA PHỤ KIỆN ========== --}}
    <div id="accessory-edit-modal" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <span class="close-modal" data-close="#accessory-edit-modal">&times;</span>

            <form method="POST" action="" id="accessory-edit-form" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h3>Sửa phụ kiện</h3>

                <label>Tên phụ kiện:</label>
                <input type="text" name="name" id="edit-accessory-name" required>

                <label>Giá:</label>
                <input type="number" name="price" id="edit-accessory-price" min="0" required>

                <label>Ảnh mới (nếu thay):</label>
                <input type="file" name="image" id="edit-accessory-image-input">
                <img id="edit-accessory-preview" style="display:none; max-width: 200px; margin-top: 10px; border:1px solid #ccc;">

                <label>Mô tả:</label>
                <textarea name="description" id="edit-accessory-description" rows="4"></textarea>

                <button type="submit">Cập nhật</button>
            </form>
        </div>
    </div>

    {{-- ========== DANH SÁCH PHỤ KIỆN ========== --}}
    <div class="row">
        @if($type === 'straps')
            @forelse($items as $strap)
                <div class="col-3">
                    <div class="product-card {{ $strap->is_hidden ? 'accessory-hidden' : '' }}" id="accessory-{{ $strap->id }}">
                        <img src="{{ asset('storage/accessories/straps/' . $strap->image) }}" alt="{{ $strap->name }}" class="product-image">
                        <h3 class="product-name">{{ $strap->name }}</h3>
                        <p class="product-price">{{ number_format($strap->price, 0, ',', '.') }}₫</p>
                        <p class="product-desc">{{ $strap->description }}</p>

                        <div class="action-buttons">
                            <a href="javascript:void(0);"
                               class="btn-accessory-edit"
                               data-id="{{ $strap->id }}"
                               data-type="straps"
                               data-name="{{ $strap->name }}"
                               data-price="{{ $strap->price }}"
                               data-description="{{ $strap->description }}">
                                Sửa
                            </a>

                            <button class="btn-toggle-accessory"
                                    data-id="{{ $strap->id }}"
                                    data-type="straps"
                                    data-hidden="{{ $strap->is_hidden ? 1 : 0 }}">
                                {{ $strap->is_hidden ? 'Hiện' : 'Ẩn' }}
                            </button>

                            <form method="POST" action="{{ route('admin.accessories.delete', ['type' => 'straps', 'id' => $strap->id]) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xoá phụ kiện này?')">Xoá</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>Chưa có dây đeo đồng hồ nào!</p>
            @endforelse

        @elseif($type === 'boxes')
            @forelse($items as $box)
                <div class="col-3">
                    <div class="product-card {{ $box->is_hidden ? 'accessory-hidden' : '' }}" id="accessory-{{ $box->id }}">
                        <img src="{{ asset('storage/accessories/boxes/' . $box->image) }}" alt="{{ $box->name }}" class="product-image">
                        <h3 class="product-name">{{ $box->name }}</h3>
                        <p class="product-price">{{ number_format($box->price, 0, ',', '.') }}₫</p>
                        <p class="product-desc">{{ $box->description }}</p>

                        <div class="action-buttons">
                            <a href="javascript:void(0);"
                               class="btn-accessory-edit"
                               data-id="{{ $box->id }}"
                               data-type="boxes"
                               data-name="{{ $box->name }}"
                               data-price="{{ $box->price }}"
                               data-description="{{ $box->description }}">
                                Sửa
                            </a>

                            <button class="btn-toggle-accessory"
                                    data-id="{{ $box->id }}"
                                    data-type="boxes"
                                    data-hidden="{{ $box->is_hidden ? 1 : 0 }}">
                                {{ $box->is_hidden ? 'Hiện' : 'Ẩn' }}
                            </button>

                            <form method="POST" action="{{ route('admin.accessories.delete', ['type' => 'boxes', 'id' => $box->id]) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xoá phụ kiện này?')">Xoá</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>Chưa có hộp đựng đồng hồ nào!</p>
            @endforelse

        @elseif($type === 'glasses')
            @forelse($items as $glass)
                <div class="col-3">
                    <div class="product-card {{ $glass->is_hidden ? 'accessory-hidden' : '' }}" id="accessory-{{ $glass->id }}">
                        <img src="{{ asset('storage/accessories/glasses/' . $glass->image) }}" alt="{{ $glass->name }}" class="product-image">
                        <h3 class="product-name">{{ $glass->name }}</h3>
                        <p class="product-price">{{ number_format($glass->price, 0, ',', '.') }}₫</p>
                        <p class="product-desc">{{ $glass->description }}</p>

                        <div class="action-buttons">
                            <a href="javascript:void(0);"
                               class="btn-accessory-edit"
                               data-id="{{ $glass->id }}"
                               data-type="glasses"
                               data-name="{{ $glass->name }}"
                               data-price="{{ $glass->price }}"
                               data-description="{{ $glass->description }}">
                                Sửa
                            </a>

                            <button class="btn-toggle-accessory"
                                    data-id="{{ $glass->id }}"
                                    data-type="glasses"
                                    data-hidden="{{ $glass->is_hidden ? 1 : 0 }}">
                                {{ $glass->is_hidden ? 'Hiện' : 'Ẩn' }}
                            </button>

                            <form method="POST" action="{{ route('admin.accessories.delete', ['type' => 'glasses', 'id' => $glass->id]) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xoá phụ kiện này?')">Xoá</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>Chưa có kính cường lực nào!</p>
            @endforelse
        @endif
    </div>
</div>

<script src="{{ asset('js/admin/accessories_ad.js') }}"></script>
@endsection
