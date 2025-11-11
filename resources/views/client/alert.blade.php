@if (session('success'))
    <div style="
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 15px;
    ">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div style="
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 15px;
    ">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div style="
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 15px;
    ">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
