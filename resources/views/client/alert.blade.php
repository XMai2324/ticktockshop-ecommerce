@if (session('success') || session('error') || $errors->any())
    <div class="alert-container">

        @if (session('success'))
            <div class="alert alert-success">
                <div class="alert-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="alert-content">
                    <h4>Thành công</h4>
                    <p>{{ session('success') }}</p>
                </div>
                <button class="alert-close" type="button">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <div class="alert-icon">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <div class="alert-content">
                    <h4>Lỗi</h4>
                    <p>{{ session('error') }}</p>
                </div>
                <button class="alert-close" type="button">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <div class="alert-icon">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="alert-content">
                    <h4>Đã có lỗi xảy ra</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button class="alert-close" type="button">&times;</button>
            </div>
        @endif

    </div>
@endif
