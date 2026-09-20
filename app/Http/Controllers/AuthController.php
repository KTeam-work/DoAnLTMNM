<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);


        $remember = $request->boolean('remember');


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA ĐĂNG NHẬP
        |--------------------------------------------------------------------------
        */

        if (!Auth::attempt($credentials, $remember)) {

            return back()
                ->withErrors([
                    'email' => 'Email hoặc mật khẩu không chính xác.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }


        /*
        |--------------------------------------------------------------------------
        | TẠO SESSION MỚI
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | LẤY USER
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | PHÂN QUYỀN
        |--------------------------------------------------------------------------
        */

        switch ($user->role) {

            case 'admin':

                return redirect()
                    ->route('admin.dashboard');


            case 'owner':

                return redirect()
                    ->route('landlord.dashboard');


            case 'tenant':

                return redirect()
                    ->route('tenant.dashboard');


            default:

                Auth::logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' => 'Tài khoản chưa được phân quyền hợp lệ.',
                    ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('auth.register');
    }


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
                'email',
                'max:255',
                'unique:users,email',
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
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

            'terms' => [
                'accepted',
            ],

        ], [
            'name.required' =>
                'Vui lòng nhập họ và tên.',

            'email.required' =>
                'Vui lòng nhập email.',

            'email.email' =>
                'Email không hợp lệ.',

            'email.unique' =>
                'Email này đã được sử dụng.',

            'phone.required' =>
                'Vui lòng nhập số điện thoại.',

            'password.required' =>
                'Vui lòng nhập mật khẩu.',

            'password.min' =>
                'Mật khẩu phải có ít nhất 6 ký tự.',

            'password.confirmed' =>
                'Mật khẩu xác nhận không khớp.',

            'role.required' =>
                'Vui lòng chọn vai trò.',

            'role.in' =>
                'Vai trò không hợp lệ.',

            'terms.accepted' =>
                'Bạn cần đồng ý với điều khoản sử dụng.',
        ]);


        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);


        return redirect()
            ->route('login')
            ->with(
                'success',
                'Đăng ký thành công! Vui lòng đăng nhập.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Bạn đã đăng xuất thành công.'
            );
    }
}