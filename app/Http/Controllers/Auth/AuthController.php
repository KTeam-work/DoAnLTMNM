<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN PAGE
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'string',
            ],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Email hoặc mật khẩu không chính xác.',
                ]);
        }

        // Chống session fixation
        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        // Kiểm tra tài khoản bị khóa
        if ($user && $user->status === 'inactive') {
            $unlockToken = $user->unlock_token;

            if (empty($unlockToken)) {
                $unlockToken = \Illuminate\Support\Str::random(48);

                User::query()
                    ->where('id', $user->id)
                    ->update(['unlock_token' => $unlockToken]);
            }

            $unlockUrl = route('account.unlock.show', $unlockToken);

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')
                ->with('locked_unlock_url', $unlockUrl)
                ->withErrors([
                    'email' => 'Tài khoản của bạn đã bị tạm khóa bởi quản trị viên. Vui lòng kiểm tra Gmail hoặc bấm vào đường dẫn bên dưới để gửi yêu cầu xin mở khóa.',
                ]);
        }

        // Admin
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard')
                ->with('success', 'Đăng nhập thành công.');
        }

        // Chủ trọ
        if ($user->role === 'owner') {
            return redirect('/')
                ->with('success', 'Đăng nhập thành công.');
        }

        // Người thuê
        if ($user->role === 'tenant') {
            return redirect('/')
                ->with('success', 'Đăng nhập thành công.');
        }

        // Role không hợp lệ
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->withErrors([
                'email' => 'Tài khoản có vai trò không hợp lệ.',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER PAGE
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('auth.register');
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                'regex:/\A[A-Za-z0-9._%+-]+@gmail\.com\z/i',
                'unique:users,email',
            ],

            'phone' => [
                'required',
                'regex:/\A[0-9]{10}\z/',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],

            'role' => [
                'required',
                'in:tenant,owner',
            ],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',

            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.regex' => 'Chỉ được sử dụng địa chỉ Gmail có dạng example@gmail.com, không được chứa khoảng trắng.',
            'email.unique' => 'Email này đã được sử dụng.',

            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại phải gồm chính xác 10 chữ số và không chứa ký tự khác.',

            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',

            'role.required' => 'Vui lòng chọn vai trò.',
            'role.in' => 'Vai trò không hợp lệ.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | TẠO TÀI KHOẢN
        |--------------------------------------------------------------------------
        */

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'status' => 'active',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CHUYỂN VỀ TRANG ĐĂNG NHẬP
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login')
            ->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Bạn đã đăng xuất.');
    }
}
