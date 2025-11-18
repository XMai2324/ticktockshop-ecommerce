@extends('client.layouts.app')

@section('title')
    {{ $product->name }} - TickTock Shop
@endsection

@section('content')
<main>
    <section class="product-detail">
        <div class="container">
            <div class="product-detail-row row">
                <div class="product-detail-left">
                    @php
                        $folder = 'Watch/Watch_nu';
                        $catSlug = \Illuminate\Support\Str::slug(optional($product->category)->name ?? '');
                        if ($catSlug === 'nam') $folder = 'Watch/Watch_nam';
                        elseif ($catSlug === 'cap-doi') $folder = 'Watch/Watch_cap';
                    @endphp
                    <img src="{{ asset('storage/' . $folder . '/' . $product->image) }}" alt="{{ $product->name }}">
                </div>

                <div class="product-detail-right">
                    <h1>{{ $product->name }}</h1>
                    <p class="price">{{ number_format($product->price, 0, ',', '.') }}<sup>đ</sup></p>
                    <p><strong>Thương hiệu:</strong> {{ optional($product->brand)->name }}</p>
                    <p><strong>Danh mục:</strong> {{ optional($product->category)->name }}</p>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-rate');
    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const url = form.action;
        const formData = new FormData(form);

        const token = document.querySelector('meta[name=csrf-token]')?.getAttribute('content');

        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: formData
        });

        if (res.ok) {
            const data = await res.json();
            alert(data.message || 'Đã gửi đánh giá');
            // reload ratings list
            location.reload();
        } else {
            const err = await res.json();
            alert('Lỗi: ' + (err.message || JSON.stringify(err.errors || err)));
        }
    });
});
</script>
@endsection
