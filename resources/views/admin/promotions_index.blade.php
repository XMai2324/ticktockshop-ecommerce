@extends('admin.dashboard')

@section('title', 'QL Khuyến mãi - TickTock Shop')

@section('content')
<div class="promotions-layout">

    {{-- ========== LEFT: FORM ADD/EDIT ========== --}}
    <aside class="panel-left">
        <h3 id="form-title" >Thêm khuyến mãi</h3>

        {{-- Hiện lỗi validate nếu có --}}
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="promotion-form" method="POST" action="{{ route('admin.promotions.store') }}">
            @csrf
            {{-- Khi vào chế độ sửa, JS sẽ thêm input _method=PUT và đổi action --}}

            <div class="grid-2">
                <div class="field">
                    <label>Tên</label>
                    <input type="text" name="name" id="f-name" value="{{ old('name') }}">
                    @error('name') <small class="text-error">{{ $message }}</small> @enderror
                </div>
                <div class="field">
                    <label>Mã</label>
                    <input type="text" name="code" id="f-code" value="{{ old('code') }}" required>
                    @error('code') <small class="text-error">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="grid-2">
                <div class="field">
                    <label>Loại</label>
                    <select name="type" id="f-type" required>
                        <option value="percent" {{ old('type')==='percent' ? 'selected' : '' }}>Giảm %</option>
                        <option value="fixed"   {{ old('type')==='fixed'   ? 'selected' : '' }}>Giảm tiền</option>
                    </select>
                    @error('type') <small class="text-error">{{ $message }}</small> @enderror
                </div>
                <div class="field">
                    <label>Giá trị</label>
                    <input type="number" step="0.01" min="0.01" name="value" id="f-value" value="{{ old('value') }}" required>
                    @error('value') <small class="text-error">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="field">
                <label>Giảm tối đa (cho loại %)</label>
                <input type="number" step="0.01" min="0" name="max_discount" id="f-max-discount" value="{{ old('max_discount') }}">
                @error('max_discount') <small class="text-error">{{ $message }}</small> @enderror
            </div>

            <div class="field">
                <label>Đơn tối thiểu</label>
                <input type="number" step="0.01" min="0" name="min_order_value" id="f-min-order" value="{{ old('min_order_value', 0) }}">
                @error('min_order_value') <small class="text-error">{{ $message }}</small> @enderror
            </div>

            <div class="grid-2">
                <div class="field">
                    <label>Giới hạn lượt dùng</label>
                    <input type="number" min="0" name="usage_limit" id="f-usage-limit" value="{{ old('usage_limit') }}">
                    @error('usage_limit') <small class="text-error">{{ $message }}</small> @enderror
                </div>
                <div class="field">
                    <label>Giới hạn mỗi user</label>
                    <input type="number" min="0" name="per_user_limit" id="f-per-user-limit" value="{{ old('per_user_limit') }}">
                    @error('per_user_limit') <small class="text-error">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="grid-2">
                <div class="field">
                    <label>Bắt đầu</label>
                    <input type="datetime-local" name="start_at" id="f-start" value="{{ old('start_at') }}">
                    @error('start_at') <small class="text-error">{{ $message }}</small> @enderror
                </div>
                <div class="field">
                    <label>Kết thúc</label>
                    <input type="datetime-local" name="end_at" id="f-end" value="{{ old('end_at') }}">
                    @error('end_at') <small class="text-error">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="field">
                <label>Trạng thái</label>
                <input type="hidden" name="is_active" value="0">
                <label style="display:inline-flex;align-items:center;gap:8px;">
                    <input type="checkbox" id="f-active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                    Đang hoạt động
                </label>
                @error('is_active') <small class="text-error">{{ $message }}</small> @enderror
            </div>

            <button class="btn-primary">Lưu</button>
        </form>
    </aside>

    {{-- ========== RIGHT: DANH SÁCH ========== --}}
    <section class="panel-right">
        <h3 id="form-title">Danh sách khuyến mãi</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>Tên</th>
                    <th>Mã</th>
                    <th>Loại</th>
                    <th>Giá trị</th>
                    <th>Giảm tối đa</th>
                    <th>Đơn tối thiểu</th>
                    <th>Giới hạn lượt dùng</th>
                    <th>Giới hạn mỗi user</th>
                    <th>Bắt đầu</th>
                    <th>Kết thúc</th>
                    <th>Trạng thái</th>
                    <th>Sử dụng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($promotions as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->code }}</td>
                    <td>{{ $p->type }}</td>
                    <td>{{ $p->value }}</td>
                    <td>{{ $p->max_discount ?? '∞' }}</td>
                    <td>{{ $p->min_order ?? 0 }}</td>
                    <td>{{ $p->usage_limit ?? '∞' }}</td>
                    <td>{{ $p->per_user_limit ?? '∞' }}</td>
                    <td>{{ $p->start_at ? $p->start_at->format('d/m/Y') : '-' }}</td>
                    <td>{{ $p->end_at ? $p->end_at->format('d/m/Y') : '-' }}</td>
                    <td>
                        <span class="status {{ $p->is_active ? 'active' : 'inactive' }}">
                            {{ $p->is_active ? 'Đang hoạt động' : 'Đã tắt' }}
                        </span>
                    </td>
                    <td>{{ $p->used_count }}/{{ $p->usage_limit ?? '∞' }}</td>
                    <td>
                        <button class="btn-edit" onclick='editPromotion(@json($p), "{{ route("admin.promotions.update", $p->id) }}")'>
                            Sửa
                        </button>

                        <form action="{{ route('admin.promotions.delete', $p->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Xóa khuyến mãi này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</div>
@endsection

<script src="{{ asset('js/admin/promotions.js') }}"></script>

