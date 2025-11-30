<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('client.auth.login');
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|regex:/@gmail\.com$/i',
            'password' => 'required'
        ], [
            'email.regex'    => 'Email phải có đuôi @gmail.com',
            'email.required' => 'Vui lòng nhập email',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('login_error', true);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // ❗ THÊM CHẶN TÀI KHOẢN BỊ KHÓA
            if (!$user->is_active) {
                Auth::logout();

                return back()
                    ->with('error', 'Tài khoản này đã bị khóa, vui lòng liên hệ quản trị viên.')
                    ->withInput()
                    ->with('login_error', true);
            }

            // Điều hướng theo role
            if ($user->role === 'admin') {
                return redirect()->route('admin.products_index');
            } elseif ($user->role === 'user') {
                return redirect()->route('client.home');
            } else {
                Auth::logout();
                return back()
                    ->with('error', 'Tài khoản không có quyền truy cập')
                    ->with('login_error', true);
            }
        }

        return back()
            ->with('error', 'Email hoặc mật khẩu không đúng')
            ->withInput()
            ->with('login_error', true);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|regex:/@gmail\.com$/i|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.regex'        => 'Email phải có đuôi @gmail.com',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('register_error', true);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'user',   // gán role mặc định
            'is_active' => true,    
        ]);

        Auth::login($user); // tự đăng nhập sau khi đăng ký

        return redirect()->route('client.home');
    }

    // Hiển thị form reset mật khẩu trực tiếp
    public function showResetForm()
    {
        return view('client.auth.reset_pass'); // form chỉ có email + mật khẩu mới + xác nhận
    }

    // Xử lý reset trực tiếp
    public function resetDirect(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
        ], [
            'email.required'     => 'Vui lòng nhập email',
            'email.email'        => 'Email không hợp lệ',
            'password.required'  => 'Vui lòng nhập mật khẩu',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'password.min'       => 'Mật khẩu tối thiểu 6 ký tự',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Không tìm thấy tài khoản với email này']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        Auth::login($user); // tự đăng nhập sau khi đổi mật khẩu
        return redirect()->route('client.home');
    }

    public function profile()
    {
        $user = Auth::user(); // nhớ đã đăng nhập thì mới có

        return view('client.profile', compact('user'));
    }


    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->save();

        return back()->with('success', 'Cập nhật thành công!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!\Hash::check($request->current_password, auth()->user()->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng!');
        }

        $user = auth()->user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }




}
