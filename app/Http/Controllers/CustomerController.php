<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    // Hiển thị trang khách hàng (form + bảng)
    public function index()
    {
        $customers = User::withCount('orders')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.customers_index', compact('customers'));
    }

    // Lưu khách hàng mới
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            // 'role'     => 'required|in:user,admin',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'phone'     => $request->phone,
            'address'   => $request->address,
            'role'      => 'user',
            'is_active' => true,
        ]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Thêm khách hàng thành công');
    }

    // Reset mật khẩu về 123456
    public function resetPassword(User $customer)
    {
        $customer->password = Hash::make('123456');
        $customer->save();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Đã reset mật khẩu về 123456 cho khách hàng ID ' . $customer->id);
    }

    // Khóa / Mở tài khoản (toggle is_active)
    public function toggleActive(User $customer)
    {
        $customer->is_active = !$customer->is_active;   // đảo trạng thái
        $customer->save();

        $msg = $customer->is_active
            ? 'Đã mở khóa tài khoản khách hàng ID ' . $customer->id
            : 'Đã vô hiệu hóa tài khoản khách hàng ID ' . $customer->id;

        return redirect()->route('admin.customers.index')
            ->with('success', $msg);
    }

    // Xóa khách hàng
    public function destroy(User $customer)
    {
        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Đã xóa khách hàng');
    }
}
