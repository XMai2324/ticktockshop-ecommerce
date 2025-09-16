<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PromotionsController extends Controller
{
    /**
     * Danh sách + lọc + phân trang
     */
    public function index(Request $request)
    {
        $q      = $request->input('q');
        $status = $request->input('status');

        $promotions = Promotion::query()
            ->when($q, function ($builder) use ($q) {
                $builder->where(function ($qB) use ($q) {
                    $qB->where('name', 'like', "%{$q}%")
                       ->orWhere('code', 'like', "%{$q}%");
                });
            })
            ->when($status, function ($builder) use ($status) {
                $now = Carbon::now();
                if ($status === 'inactive') {
                    $builder->where('is_active', false);
                } elseif ($status === 'upcoming') {
                    $builder->where('is_active', true)
                            ->whereNotNull('start_at')
                            ->where('start_at', '>', $now);
                } elseif ($status === 'expired') {
                    $builder->whereNotNull('end_at')
                            ->where('end_at', '<', $now);
                } elseif ($status === 'active') {
                    $builder->where('is_active', true)
                            ->where(function ($qB) use ($now) {
                                $qB->whereNull('start_at')->orWhere('start_at', '<=', $now);
                            })
                            ->where(function ($qB) use ($now) {
                                $qB->whereNull('end_at')->orWhere('end_at', '>=', $now);
                            });
                }
            })
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('admin.promotions_index', compact('promotions'));
    }

    /**
     * Tạo mới
     */
    public function store(Request $request)
    {
        // Validate dữ liệu từ form
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'code'           => 'required|string|max:50|unique:promotions,code',
            'type'           => 'required|in:percent,fixed',
            'value'          => 'required|numeric|min:0.01',
            'max_discount'   => 'nullable|numeric|min:0',
            'min_order_value'=> 'nullable|numeric|min:0',
            'usage_limit'    => 'nullable|integer|min:0',
            'per_user_limit' => 'nullable|integer|min:0',
            'start_at'       => 'nullable|date',
            'end_at'         => 'nullable|date|after_or_equal:start_at',
            'is_active'      => 'sometimes|boolean',
        ]);

        // Chuyển datetime-local về Carbon hoặc null
        $data['start_at'] = $data['start_at'] ? Carbon::parse($data['start_at']) : null;
        $data['end_at']   = $data['end_at']   ? Carbon::parse($data['end_at'])   : null;

        // max_discount chỉ dùng khi type = percent
        if ($data['type'] !== 'percent') {
            $data['max_discount'] = null;
        }

        // Thiết lập mặc định cho các trường numeric nếu null
        $data['min_order_value'] = $data['min_order_value'] ?? 0;
        $data['usage_limit']     = $data['usage_limit'] ?? 0;
        $data['per_user_limit']  = $data['per_user_limit'] ?? 0;

        // Khởi tạo số lượt dùng
        $data['used_count'] = 0;

        // Checkbox không tick -> 0
        $data['is_active'] = $data['is_active'] ?? 0;

        // Tạo bản ghi trong database
        Promotion::create($data);

        // Redirect về trang danh sách kèm thông báo thành công
        return redirect()->route('admin.promotions_index')
                        ->with('success', 'Đã tạo khuyến mãi thành công');
    }

    /**
     * Cập nhật
     */
    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $data = $this->validateData($request, $promotion->id);

        $data['start_at'] = $this->toCarbonOrNull($request->input('start_at'));
        $data['end_at']   = $this->toCarbonOrNull($request->input('end_at'));

        if ($data['start_at'] && $data['end_at'] && $data['end_at']->lt($data['start_at'])) {
            return back()->withInput()->withErrors(['end_at' => 'Thời gian kết thúc phải sau thời gian bắt đầu']);
        }

        if ($data['type'] !== 'percent') {
            $data['max_discount'] = null;
        }

        $promotion->update($data);

        return redirect()
            ->route('admin.promotions_index')
            ->with('success', 'Đã cập nhật khuyến mãi');
    }

    /**
     * Xoá
     */
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        return redirect()
            ->route('admin.promotions_index')
            ->with('success', 'Đã xoá khuyến mãi');
    }

    /**
     * Validate chung cho store/update
     */
    protected function validateData(Request $request, $ignoreId = null): array
    {
        $rules = [
            'name'             => ['nullable', 'string', 'max:255'],
            'code'             => [
                'required', 'string', 'max:100',
                Rule::unique('promotions', 'code')->ignore($ignoreId),
            ],
            'type'             => ['required', Rule::in(['percent', 'fixed'])],
            'value'            => ['required', 'numeric', 'min:0.01'],
            'max_discount'     => ['nullable', 'numeric', 'min:0'],
            'min_order_value'  => ['nullable', 'numeric', 'min:0'],
            'usage_limit'      => ['nullable', 'integer', 'min:0'],
            'per_user_limit'   => ['nullable', 'integer', 'min:0'],
            'is_active'        => ['required', 'boolean'],
            'start_at'         => ['nullable', 'date'],
            'end_at'           => ['nullable', 'date'],
        ];


        $data = $this->validateData($request, $ignoreId);
        $data['is_active'] = $request->boolean('is_active');

        // Nếu muốn ràng buộc thời gian: end_at phải sau start_at
        if (!empty($data['start_at']) && !empty($data['end_at']) && $data['end_at'] < $data['start_at']) {
            return back()->withErrors(['end_at' => 'Thời điểm kết thúc phải sau thời điểm bắt đầu'])->withInput();
        }



        // Ràng buộc theo type
        // percent: value trong khoảng 0 < value <= 100
        if ($request->input('type') === 'percent') {
            $rules['value'][] = 'max:100';
        }

        $messages = [
            'code.unique' => 'Mã khuyến mãi đã tồn tại',
            'value.max'   => 'Giá trị phần trăm tối đa là 100',
        ];

        $data = $request->validate($rules, $messages);

        // Ép kiểu boolean cho is_active
        $data['is_active'] = (bool) $request->boolean('is_active');

        return $data;
    }

    /**
     * Chuyển input datetime-local sang Carbon|null
     */
    protected function toCarbonOrNull($value)
    {
        if (empty($value)) return null;
        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
