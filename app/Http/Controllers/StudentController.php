<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\Student;
use App\Models\StudentResponses;
use App\Models\Test;
use App\Models\TestPart;
use App\Models\TestResult;
use App\Models\TestSkill;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        // // Lưu thông tin hình ảnh mới
        // $imagePath = $request->file('image')->store('imageStudents', 'public');

        // // Tìm kiếm student với user_id
        // $student = Student::where('user_id', $request->accountId)->first();

        // if ($student) {
        //     // Nếu đã có hình, xóa hình cũ
        //     if ($student->image_file && Storage::disk('public')->exists($student->image_file)) {
        //         Storage::disk('public')->delete($student->image_file);
        //     }
        //     // Cập nhật hình mới
        //     $student->update(['image_file' => $imagePath]);
        //     $message = 'Image updated successfully';
        // } else {
        //     // Tạo mới student với hình ảnh
        //     $student = Student::create([
        //         'user_id' => $request->accountId,
        //         'image_file' => $imagePath,
        //     ]);
        //     $message = 'Image created successfully';
        // }

        // return response()->json(['message' => $message, 'student' => $student], 200);
        if (!$request->hasFile('image')) {
            return response()->json(['message' => 'No image file found in the request'], 400);
        }

        // Kiểm tra tệp tin có hợp lệ không
        if (!$request->file('image')->isValid()) {
            return response()->json(['message' => 'Uploaded file is not valid'], 400);
        }

        // Lưu thông tin hình ảnh mới
        $imagePath = $request->file('image')->store('imageStudents', 'public');

        // Tạo mới student với hình ảnh
        $student = Student::create([
            'user_id' => $request->accountId,
            'image_file' => $imagePath,
        ]);

        $message = 'Image created successfully';

        return response()->json(['message' => $message, 'student' => $student], 200);
    }

    public function startTest()
    {
        $userId = Auth::user()->id; // Lấy user_id của người dùng hiện tại
        $student = Student::where('user_id', $userId)->orderBy('created_at', 'desc')->first(); // Lấy thông tin sinh viên từ DB

        if (!$student) {
            return redirect()->route('student.index')->with('error', 'Bạn cần chụp ảnh trước khi nhận đề thi.');
        }

        if ($student) {
            // Tạo bài test mới nếu người dùng chưa có test_id
            $randomNumbers = '';
            for ($i = 0; $i < 10; $i++) {
                $randomNumbers .= random_int(0, 9);
            }
            $testName = 'Test_' . $randomNumbers;
            
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

    public function showTestResult($testId)
    {
        // Lấy skill IDs cho Reading và Listening
        $skills = TestSkill::whereIn('skill_name', ['Reading', 'Listening'])->get();
        $readingSkillIds = $skills->where('skill_name', 'Reading')->pluck('id');
        $listeningSkillIds = $skills->where('skill_name', 'Listening')->pluck('id');

        $student = auth()->user();
        $studentId = $student->id; // Lưu ID của user trước khi logout
        $studentName = $student->name;
        $studentEmail = $student->email;
        // Lấy tên của bài kiểm tra
        // dd($testId);
        $testName = Test::find($testId)->test_name;

        // Lấy tất cả phản hồi của học sinh có skill_id là Reading hoặc Listening
        $studentResponses = StudentResponses::where('student_id', $studentId)
            ->whereIn('skill_id', $readingSkillIds->merge($listeningSkillIds))
            ->get();
        $correctAnswersReading = 0;
        $correctAnswersListening = 0;

        // Duyệt qua từng câu trả lời của học sinh và xác định nếu nó đúng
        foreach ($studentResponses as $response) {
            $option = Option::where('question_id', $response->question_id)
                ->where('id', $response->text_response)
                ->first();

            if ($option && $option->correct_answer) {
                if ($readingSkillIds->contains($response->skill_id)) {
                    $correctAnswersReading++;
                } elseif ($listeningSkillIds->contains($response->skill_id)) {
                    $correctAnswersListening++;
                }
            }
        }

        function roundToHalf($num)
        {
            $integerPart = floor($num); // Lấy phần nguyên
            $decimalPart = $num - $integerPart; // Lấy phần thập phân

            if ($decimalPart < 0.25) {
                return $integerPart; // Làm tròn xuống
            } elseif ($decimalPart < 0.75) {
                return $integerPart + 0.5; // Làm tròn đến 0.5
            } else {
                return ceil($num); // Làm tròn lên
            }
        }

        $scoreListening = roundToHalf(($correctAnswersListening * 10) / 35);
        $scoreReading = roundToHalf(($correctAnswersReading * 10) / 40);

        // Lưu kết quả vào bảng test_results
        TestResult::create([
            'student_id' => $studentId,
            'test_name' => $testName,
            'listening_correctness' => $correctAnswersListening,
            'reading_correctness' => $correctAnswersReading,
        ]);

        // Truyền dữ liệu vào view
        return view('students.resultStudent', [
            'correctAnswersReading' => $correctAnswersReading,
            'correctAnswersListening' => $correctAnswersListening,
            'scoreListening' => $scoreListening,
            'scoreReading' => $scoreReading,
            'testId' => $testId,
            'studentId' => $studentId,
            'studentName' => $studentName,
            'studentEmail' => $studentEmail,
        ]);
    }
}
