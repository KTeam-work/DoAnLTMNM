<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller{
    
   public function login(Request $request)
    {
       $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            if ($request->user()->role === 'owner') {
                return redirect()->route('landlord.home')->with('success', 'Đăng nhập thành công!');
            }
            
           
            return redirect('/tenant')->with('success', 'Đăng nhập thành công!');
        }

       
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

}
