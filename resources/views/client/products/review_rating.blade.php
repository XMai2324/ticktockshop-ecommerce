<div id="review-overlay" class="review-overlay">
    <div class="review-popup">

        {{-- Header --}}
        <div class="popup-header">
            <h3>Đánh giá sản phẩm</h3>
            <button id="close-review-popup" class="close-btn">&times;</button>
        </div>

        @php
            // Đảm bảo ratings luôn là Collection
            $ratings = $product->ratings ?? collect();

            $countStars = [
                5 => $ratings->where('rating', 5)->count(),
                4 => $ratings->where('rating', 4)->count(),
                3 => $ratings->where('rating', 3)->count(),
                2 => $ratings->where('rating', 2)->count(),
                1 => $ratings->where('rating', 1)->count(),
            ];
            $totalReviews = $ratings->count();
        @endphp

        {{-- Bộ lọc --}}
        <div class="popup-filters">
            <div class="filter-stars">

                <button class="star-filter-btn active" data-star="">
                    <span class="number-rating">Tất cả</span>
                    <span class="star"> ★ </span> ({{ $totalReviews }})
                </button>

                <button class="star-filter-btn" data-star="5">
                    <span class="number-rating">5</span>
                    <span class="star"> ★ </span> ({{ $countStars[5] }})
                </button>

                <button class="star-filter-btn" data-star="4">
                    <span class="number-rating">4</span>
                    <span class="star"> ★ </span> ({{ $countStars[4] }})
                </button>

                <button class="star-filter-btn" data-star="3">
                    <span class="number-rating">3</span>
                    <span class="star"> ★ </span> ({{ $countStars[3] }})
                </button>

                <button class="star-filter-btn" data-star="2">
                    <span class="number-rating">2</span>
                    <span class="star"> ★ </span> ({{ $countStars[2] }})
                </button>

                <button class="star-filter-btn" data-star="1">
                    <span class="number-rating">1</span>
                    <span class="star"> ★ </span> ({{ $countStars[1] }})
                </button>

            </div>
        </div>

        {{-- Danh sách đánh giá --}}
        <div class="review-popup-content">
            @forelse($ratings as $r)
                <div class="review-item"
                     data-rating="{{ $r->rating }}"
                     data-response="{{ $r->response ? 1 : 0 }}">

                    <div class="review-header">
                        <strong>{{ $r->user->name ?? 'Người dùng' }}</strong>
                        <span class="stars">
                            {!! str_repeat('★', $r->rating) . str_repeat('☆', 5 - $r->rating) !!}
                        </span>
                    </div>

                    <p>{{ $r->comment }}</p>

                    <span class="review-time">
                        {{ $r->created_at ? $r->created_at->format('d/m/Y H:i') : '' }}
                    </span>

                    @if ($r->response)
                        <div class="admin-response">
                            <strong>Phản hồi shop:</strong>
                            <p>{{ $r->response }}</p>
                        </div>
                    @endif

                </div>
            @empty
                <p class="no-review-msg">Chưa có đánh giá.</p>
            @endforelse
        </div>

    </div>
</div>
