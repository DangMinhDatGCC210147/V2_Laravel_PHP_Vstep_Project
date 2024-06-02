<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Student;
use App\Models\Test;
use App\Models\TestSkill;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $user_name = Session::get('user_name');
        $user_email = Session::get('user_email');
        $parts = explode('@', $user_email);
        $user_id_student = $parts[0];
        $account_id = Session::get('account_id');

        // Truyền dữ liệu đến view
        return view('students.index', [
            'user_name' => $user_name,
            'user_email' => $user_email,
            'user_id_student' => $user_id_student,
            'account_id' => $account_id
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5048',
            'accountId' => 'required',
        ]);

        $test = Test::where('test_status', 'Active')->inRandomOrder()->first();
        $path = $request->file('image')->store('students', 'public');
        $url = Storage::url($path);

        // Lưu URL hình ảnh và thông tin người dùng vào database
        $student = Student::updateOrCreate(
            ['user_id' => $request->accountId], // Trường duy nhất để xác định Student
            ['image_file' => $url, 'test_id' => $test->id] // Cập nhật hoặc thêm mới các trường này
        );

        return response()->json(['message' => 'Hình ảnh đã được lưu thành công!']);
    }

    public function startTest()
    {
        $userId = auth()->user()->id; // Lấy user_id của người dùng hiện tại
        $student = Student::where('user_id', $userId)->first(); // Lấy thông tin sinh viên từ DB

        if ($student && $student->test_id) {
            $test = Test::find($student->test_id);
            if ($test && $test->slug) {
                // Chuyển hướng đến trang làm bài thi với slug tương ứng
                return redirect()->route('exam-page', ['slug' => $test->slug]);
            } else {
                return redirect()->back()->with('error', 'Không tìm thấy bài test hoặc bài test không có slug.');
            }
        } else {
            // Nếu không có test_id hoặc không tìm thấy bản ghi sinh viên, chuyển hướng với thông báo lỗi
            return redirect()->back()->with('error', 'Không tìm thấy bài test hoặc bạn chưa thực hiện chụp hình trước khi kiểm tra!.');
        }
    }

    public function showTest($slug)
    {
        $test = Test::with(['testSkills.questions.options', 'testSkills.readingsAudios'])
                    ->where('slug', $slug)
                    ->firstOrFail();
        $skills = $test->testSkills; // Lấy tất cả skills, bao gồm các parts    
        $sortedSkills = $skills->sortBy(function($skill) {
            $order = ['Listening', 'Reading', 'Writing', 'Speaking'];
            return array_search($skill->skill_name, $order);
        }); 
        
        return view('students.show', ['test' => $test, 'skills' => $sortedSkills ]);
    }  
    
}
