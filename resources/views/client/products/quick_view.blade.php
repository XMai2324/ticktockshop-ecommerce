<div class="modal-content">
    <span class="close-modal">&times;</span>


    <div class="modal-quickview-left">
        @php
            $imageUrl = '';
            // Sản phẩm đồng hồ
            if (isset($product)) {
                $catSlug = \Illuminate\Support\Str::slug(optional($product->category)->name ?? '');
                if ($catSlug === 'nam')       $folder = 'Watch/Watch_nam';
                elseif ($catSlug === 'cap-doi') $folder = 'Watch/Watch_cap';
                else                           $folder = 'Watch/Watch_nu';
                $imageUrl = asset('storage/' . $folder . '/' . ($product->image ?? ''));
            }
            // Phụ kiện
            elseif (isset($item)) {
                // nếu folder dùng "glass" cho glasses thì:
                $accTypeFolder = ($type === 'glasses') ? 'glass' : $type;
                $imageUrl = asset('storage/accessories/' . $accTypeFolder . '/' . ($item->image ?? ''));
            }
        @endphp

        <img src="{{ $imageUrl }}" alt="{{ $product->name ?? $item->name ?? 'Hình ảnh' }}" style="max-width:100%;height:auto;">
    </div>

    <div class="modal-quickview-right">
        <h2>{{ $product->name ?? $item->name ?? 'Sản phẩm' }}</h2>
        <p class="price">{{ number_format(($product->price ?? $item->price ?? 0), 0, ',', '.') }}<sup>đ</sup></p>

        @if (isset($product))
            <p><strong>Thương hiệu:</strong> {{ optional($product->brand)->name ?? 'Không rõ' }}</p>
            <p><strong>Danh mục:</strong> {{ optional($product->category)->name ?? 'Không rõ' }}</p>
        @elseif (isset($item))
            <p><strong>Loại phụ kiện:</strong> {{ ucfirst($type) }}</p>
        @endif

        <p class="description">{{ $product->description ?? $item->description ?? 'Không có mô tả' }}</p>

        <div class="action-row">
            <div class="quantity-box">
                <label for="quantity">Số lượng:</label>
                <input type="number" id="quantity" name="quantity" min="1" value="1">
            </div>

            <button class="btn-add-to-cart"
                    data-id="{{ $product->id ?? $item->id }}"
                    data-type="{{ isset($product) ? 'product' : $type }}">
                <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
            </button>
        </div>
    </div>
</div>