<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\TestResult;
use App\Models\TestSkill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class IndexAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = User::where('role', 2)
            ->with(['sessions' => function ($query) {
                $query->whereNotNull('session_end')
                    ->whereRaw('session_end >= session_start');
            }, 'testResults']) // Bổ sung mối quan hệ để tính toán số lượng bài kiểm tra
            ->withCount('testResults as tests_count') // Thêm số lượng bài kiểm tra
            ->get()
            ->map(function ($user) {
                // Tính toán tổng thời gian làm việc
                $totalMinutes = $user->sessions->sum('duration');
                $hours = intdiv($totalMinutes, 60);
                $minutes = $totalMinutes % 60;
                $seconds = ($totalMinutes * 60) % 60;

                $user->total_duration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                return $user;
            })
            ->sortByDesc('total_duration');

        $person = User::where('role', 2) 
            ->withCount('testResults')
            ->orderByDesc('test_results_count')
            ->first();

        $highestListening = TestResult::join('users', 'test_results.student_id', '=', 'users.id')
            ->select('users.name', 'test_results.listening_correctness')
            ->orderByDesc('listening_correctness')
            ->first();
        $highestReading = TestResult::join('users', 'test_results.student_id', '=', 'users.id')
            ->select('users.name', 'test_results.reading_correctness')
            ->orderByDesc('reading_correctness')
            ->first();

        $count  = User::where('role', 2) // Giả sử role = 2 là sinh viên
            ->whereHas('testResults') // Kiểm tra người dùng đã làm ít nhất một bài kiểm tra
            ->count();
        $totalStudentsCount = User::where('role', 2)->count();

        return view('admin.index', compact('students', 'person', 'highestListening', 'highestReading', 'count', 'totalStudentsCount'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return view('admin.questionBank');
    }
    public function showTableOfWritingQuestionBank()
    {
        $writingQuestionBank = TestSkill::where('skill_name', 'Writing')
            ->get();
        $questions = [];

        foreach ($writingQuestionBank as $index => $writingQuestion) {
            $question = Question::where('test_skill_id', $writingQuestion->id)->first();
            $questions[$index] = $question ? $question->part_name : 'No question available';
        }

        return view('admin.listQuestionBank.listOfWriting', compact('writingQuestionBank', 'questions'));
    }

    public function showTableOfListeningQuestionBank()
    {
        $listeningQuestionBank = TestSkill::where('skill_name', 'Listening')
            ->get();
        $questions = [];

        foreach ($listeningQuestionBank as $index => $listeningQuestion) {
            $question = Question::where('test_skill_id', $listeningQuestion->id)->first();
            $questions[$index] = $question ? $question->part_name : 'No question available';
        }

        return view('admin.listQuestionBank.listOfListening', compact('listeningQuestionBank', 'questions'));
    }
    public function showTableOfReadingQuestionBank()
    {
        $readingQuestionBank = TestSkill::where('skill_name', 'Reading')
            ->get();
        $questions = [];

        foreach ($readingQuestionBank as $index => $readingQuestion) {
            $question = Question::where('test_skill_id', $readingQuestion->id)->first();
            $questions[$index] = $question ? $question->part_name : 'No question available';
        }

        return view('admin.listQuestionBank.listOfReading', compact('readingQuestionBank', 'questions'));
    }
    public function showTableOfSpeakingQuestionBank()
    {
        $speakingQuestionBank = TestSkill::where('skill_name', 'Speaking')
            ->get();
        $questions = [];

        foreach ($speakingQuestionBank as $index => $speakingQuestion) {
            $question = Question::where('test_skill_id', $speakingQuestion->id)->first();
            $questions[$index] = $question ? $question->part_name : 'No question available';
        }

        return view('admin.listQuestionBank.listOfSpeaking', compact('speakingQuestionBank', 'questions'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
