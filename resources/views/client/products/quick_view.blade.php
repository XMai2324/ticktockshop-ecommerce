<div class="modal-content">
    <span class="close-modal" aria-label="Đóng">&times;</span>

    <div class="modal-quickview-left">
        @php
            $imageUrl = '';
            if (isset($product)) {
                $folder = 'Watch/Watch_nu';
                $catSlug = \Illuminate\Support\Str::slug(optional($product->category)->name ?? '');
                if ($catSlug === 'nam')        $folder = 'Watch/Watch_nam';
                elseif ($catSlug === 'cap-doi') $folder = 'Watch/Watch_cap';

                $imageUrl = asset('storage/' . $folder . '/' . ($product->image ?? ''));
            } elseif (isset($item)) {
                $accTypeFolder = $type; // giữ đồng nhất tên thư mục
                $imageUrl = asset('storage/accessories/' . $accTypeFolder . '/' . ($item->image ?? ''));
            }
            $name  = $product->name ?? $item->name ?? 'Sản phẩm';
            $price = $product->price ?? $item->price ?? 0;
            $desc  = $product->description ?? $item->description ?? 'Không có mô tả';
        @endphp

        <img src="{{ $imageUrl }}" alt="{{ $name }}">
    </div>

    <div class="modal-quickview-right">
        <h2>{{ $name }}</h2>
        <p class="price">{{ number_format($price, 0, ',', '.') }}<sup>đ</sup></p>

        @if (isset($product))
            <p><strong>Thương hiệu:</strong> {{ optional($product->brand)->name ?? 'Không rõ' }}</p>
            <p><strong>Danh mục:</strong> {{ optional($product->category)->name ?? 'Không rõ' }}</p>
        @elseif (isset($item))
            <p><strong>Loại phụ kiện:</strong> {{ ucfirst($type) }}</p>
        @endif

        <p class="description">{{ $desc }}</p>

        <div class="action-row">
            <div class="quantity-box">
                <label for="quantity">Số lượng:</label>
                <input type="number" id="quantity" name="quantity" min="1" value="1" inputmode="numeric">
            </div>

            <button type="button"
                    class="btn-add-to-cart"
                    data-id="{{ $product->id ?? $item->id }}"
                    data-type="{{ isset($product) ? 'product' : $type }}">
                <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
            </button>
        </div>
    </div>
</div>
