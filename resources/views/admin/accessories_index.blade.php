@extends('admin.dashboard')

@section('title', 'QLSP_PhuKienDongHo - TickTock Shop')

@section('content')
<div class="container">
        <button id="btn-open-create-form">+ Thêm {{ $type === 'straps' ? 'dây đeo' : ($type === 'boxes' ? 'hộp đựng' : 'kính cường lực') }}</button>

        <div id="create-form" class="form-overlay" style="display: none;">
            <form method="POST" action="{{ route('admin.accessories.store', ['type' => $type]) }}" enctype="multipart/form-data" class="accessory-form">
                @csrf
                <h3>Thêm {{ $type === 'straps' ? 'dây đeo' : ($type === 'boxes' ? 'hộp đựng' : 'kính cường lực') }}</h3>

                <label>Tên:</label>
                <input type="text" name="name" required>

                <label>Giá:</label>
                <input type="number" name="price" required>

                @if($type === 'straps' || $type === 'glasses')
                    <label>Chất liệu:</label>
                    <input type="text" name="material" required>

                    <label>Màu sắc:</label>
                    <input type="text" name="color" required>
                @endif

                <label>Ảnh:</label>
                <input type="file" name="image" required>

                <label>Mô tả:</label>
                <textarea name="description" rows="4"></textarea>

                

                <div style="margin-top: 10px;">
                    <button type="submit">Lưu</button>
                    <button type="button" id="btn-cancel-create">Hủy</button>
                </div>
            </form>
        </div>


    <div class="row">

        @if($type === 'straps')
            @forelse($items as $strap)
                <div class="col-3">
                    <div class="product-card">
                        <a href="javascript:void(0);" class="product-quick-view" data-slug="{{ $strap->slug }}" data-type="straps">
                        <img src="{{ asset('storage/accessories/straps/' . $strap->image) }}" alt="{{ $strap->name }}" class="product-image">
                        <h3 class="product-name">{{ $strap->name }}</h3>
                        <p class="product-price">{{ number_format($strap->price, 0, ',', '.') }}₫</p>
                        <p class="product-desc">{{ $strap->description }}</p>
                        <div class="action-buttons">
                            <a href="{{ route('admin.accessories.edit', ['type' => 'straps', 'id' => $strap->id]) }}" class="btn-edit">Sửa</a>
                            <form method="POST" action="{{ route('admin.accessories.delete', ['type' => 'straps', 'id' => $strap->id]) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
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
                    <div class="product-card">
                        <!-- <a href="javascript:void(0);" class="product-quick-view" data-slug="{{ $box->slug }}" data-type="boxes"> -->
                        <img src="{{ asset('storage/accessories/boxes/' . $box->image) }}" alt="{{ $box->name }}" class="product-image">
                        <h3 class="product-name">{{ $box->name }}</h3>
                        <p class="product-price">{{ number_format($box->price, 0, ',', '.') }}₫</p>
                        <p class="product-desc">{{ $box->description }}</p>
                        <div class="action-buttons">
                            <a href="{{ route('admin.accessories.edit', ['type' => 'boxes', 'id' => $box->id]) }}" class="btn-edit">Sửa</a>
                            <form method="POST" action="{{ route('admin.accessories.delete', ['type' => 'boxes', 'id' => $box->id]) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
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
                    <div class="product-card">
                        <!-- <a href="javascript:void(0);" class="product-quick-view" data-slug="{{ $glass->slug }}" data-type="glasses"> -->
                        <img src="{{ asset('storage/accessories/glass/' . $glass->image) }}" alt="{{ $glass->name }}" class="product-image">
                        <h3 class="product-name">{{ $glass->name }}</h3>
                        <p class="product-price">{{ number_format($glass->price, 0, ',', '.') }}₫</p>
                        <p class="product-desc">{{ $glass->description }}</p>
                        <div class="action-buttons">
                            <a href="{{ route('admin.accessories.edit', ['type' => 'glasses', 'id' => $glass->id]) }}" class="btn-edit">Sửa</a>
                            <form method="POST" action="{{ route('admin.accessories.delete', ['type' => 'glasses', 'id' => $glass->id]) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
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

@endsection

<script src="{{ asset('js/admin/accessories.js') }}"></script>
