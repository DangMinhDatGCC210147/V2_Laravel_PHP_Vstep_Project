<?php

namespace App\Http\Controllers;

use App\Imports\LecturersImport;
use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Str;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class AuthController extends Controller
{
    public function registerExcelStudents(Request $request)
    {
        if ($request->hasFile('excel_file')) {
            try {
                Log::info("Received file: " . $request->file('excel_file')->getClientOriginalName());

                Excel::import(new UsersImport, $request->file('excel_file'));

                return redirect()->route('tableStudent.index')->with('success', 'All users registered successfully');
            } catch (\Exception $e) {
                Log::error("Error during Excel import: " . $e->getMessage());
                return redirect()->route('tableStudent.index')->with('error', 'There was an error processing the Excel file.');
            }
        } else {
            return redirect()->route('tableStudent.index')->with('error', 'No file was uploaded.');
        }
    }

    public function registerExcelLecturers(Request $request)
    {
        if ($request->hasFile('excel_file')) {
            try {
                Log::info("Received file: " . $request->file('excel_file')->getClientOriginalName());

                Excel::import(new LecturersImport, $request->file('excel_file'));

                return redirect()->route('tableLecturer.index')->with('success', 'All users registered successfully');
            } catch (\Exception $e) {
                Log::error("Error during Excel import: " . $e->getMessage());
                return redirect()->route('tableLecturer.index')->with('error', 'There was an error processing the Excel file.');
            }
        } else {
            return redirect()->route('tableLecturer.index')->with('error', 'No file was uploaded.');
        }
    }

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
        if ($user->role == 1) {
            return redirect()->route('tableLecturer.index')->with('success', 'Registered successfully');
        } else {
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
            $request->session()->put('slug', $user->slug);
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
