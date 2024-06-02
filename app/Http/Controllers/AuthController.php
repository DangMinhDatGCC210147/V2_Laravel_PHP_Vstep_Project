<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Str;
use Illuminate\Auth\AuthenticationException;

class AuthController extends Controller
{
    public function registerPost(Request $request)
    {
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->account_id = $request->account_id;
        $user->role = $request->role;
        $user->password = Hash::make($request->password);
        $user->save();
        // return back()->with('success','Registered successfully');
        if($user->role == 1){
            return redirect()->route('tableLecturer.index')->with('success', 'Registered successfully');
        }else{
            return redirect()->route('tableStudent.index')->with('success', 'Registered successfully');
        }
    }

    public function showlogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
        
            // Lấy thông tin người dùng đã đăng nhập
            $user = Auth::user();
        
            // Lưu thông tin người dùng vào session
            $request->session()->put('role', $user->role);
            $request->session()->put('user_name', $user->name);
            $request->session()->put('user_email', $user->email);
            $request->session()->put('account_id', $user->id);
            // Kiểm tra thông tin session đã lưu
            // dd($request->session()->all());
            if ($user->role == 1) {
                return redirect()->route('admin.index')->with('success', 'Login successfully!');
            } else {
                return redirect()->route('student.index')->with('success', 'Login successfully!');
            }
        }

        return back()->withErrors([
            'email' => 'Wrong email or password, please re-enter for information.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Xoá session đã tạo
        $request->session()->invalidate();

        // Tạo lại token CSRF mới
        $request->session()->regenerateToken();

        // Chuyển hướng người dùng về trang chủ hoặc trang đăng nhập
        return redirect('/');
    }
}
