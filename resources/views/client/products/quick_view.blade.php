<div class="modal-content">
    <span class="close-modal">&times;</span>


    <div class="modal-quickview-left">
        @php
            use Illuminate\Support\Str;

            $image = '';
            $folder = '';

            if (!empty($product)) {
                $slug = Str::slug($product->category->name ?? '');
                if ($slug === 'nam') {
                    $folder = 'Watch/Watch_nam';
                } elseif ($slug === 'cap-doi') {
                    $folder = 'Watch/Watch_cap';
                } else {
                    $folder = 'Watch/Watch_nu';
                }
                $image = asset('storage/' . $folder . '/' . $product->image);
            } elseif (!empty($item)) {
                $folder = 'accessories/' . $type;
                $image = asset('storage/' . $folder . '/' . $item->image);
            }
        @endphp

        <img src="{{ $image }}" alt="{{ $product->name ?? $item->name ?? 'Phụ kiện' }}">
    </div>

    <div class="modal-quickview-right">
        <h2>{{ $product->name ?? $item->name ?? 'Sản phẩm' }}</h2>
        <p class="price">
            {{ number_format(($product->price ?? $item->price ?? 0), 0, ',', '.') }}<sup>đ</sup>
        </p>

        @if (!empty($product))
            <p><strong>Thương hiệu:</strong> {{ $product->brand->name ?? 'Không rõ' }}</p>
            <p><strong>Danh mục:</strong> {{ $product->category->name ?? 'Không rõ' }}</p>
        @elseif (!empty($item))
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
