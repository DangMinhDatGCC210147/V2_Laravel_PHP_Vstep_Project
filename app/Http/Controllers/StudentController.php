<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Student;
use App\Models\Test;
use App\Models\TestPart;
use App\Models\TestSkill;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Redirect;

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
        // Lưu thông tin hình ảnh
        $imagePath = $request->file('image')->store('imageStudents', 'public');

        // Tạo một bản ghi mới trong bảng student_tests với test_id là null
        $studentTest = Student::create([
            'user_id' => $request->accountId,
            'image_file' => $imagePath,
        ]);

        return response()->json(['message' => 'Image created successfully'], 200);
    }
    
    public function startTest()
    {
        $userId = Auth::user()->id; // Lấy user_id của người dùng hiện tại
        $student = Student::where('user_id', $userId)->first(); // Lấy thông tin sinh viên từ DB
        
        // Kiểm tra nếu sinh viên đã có test_id
        if ($student && $student->test_id) {
            $test = Test::find($student->test_id);
            // Kiểm tra nếu bài test tồn tại và có slug
            if ($test && $test->slug) {
                // Chuyển hướng đến trang làm bài thi với slug tương ứng
                return redirect()->route('examination-page', ['slug' => $test->slug]);
            } else {
                // Thông báo lỗi nếu không tìm thấy bài test hoặc bài test không có slug
                return Redirect::back()->with('error', 'Không tìm thấy bài test hoặc bài test không có slug.');
            }
        } else {
            // Tạo bài test mới nếu người dùng chưa có test_id
            $testName = 'Test_' . Uuid::uuid4()->toString();
            $test = Test::create([
                'duration' => '03:00:00',
                'test_name' => $testName,
            ]);
            $testId = $test->id;
            $student->test_id = $testId;
            $student->save();
            
            // Phân bổ ngẫu nhiên các phần thi cho sinh viên
            $skills = [
                'Listening' => ['Part_1', 'Part_2', 'Part_3'],
                'Reading' => ['Part_1', 'Part_2', 'Part_3', 'Part_4'],
                'Writing' => ['Part_1', 'Part_2'],
                'Speaking' => ['Part_1', 'Part_2', 'Part_3'],
            ];

            foreach ($skills as $skill => $parts) {
                foreach ($parts as $partName) {
                    $selectedPart = TestSkill::where('skill_name', $skill)
                        ->where('part_name', $partName)
                        ->inRandomOrder()
                        ->limit(1)
                        ->first();

                    if ($selectedPart) {
                        $testPart = TestPart::create([
                            'student_id' => $student->id,
                            'test_skill_id' => $selectedPart->id,
                            'test_id' => $testId,
                        ]);
                    }
                }
            }

            // Chuyển hướng đến trang làm bài thi mới tạo nếu có slug
            if ($test && $test->slug) {
                return redirect()->route('examination-page', ['slug' => $test->slug]);
            } else {
                return Redirect::back()->with('error', 'Không tạo được bài test mới.');
            }
        }
    }

    public function displayTest($slug)
    {
        if (empty($slug)) {
            return redirect()->back()->withErrors('No test parts found.');
        }

        $test = Test::with([
            'testParts.testSkill.questions.options',
            'testParts.testSkill.readingsAudios'
        ])->where('slug', $slug)->firstOrFail();
            
        $testParts = $test->testParts;
        // dd($testParts);
        return view('students.displayTest', compact('testParts', 'test'));
    }
}
