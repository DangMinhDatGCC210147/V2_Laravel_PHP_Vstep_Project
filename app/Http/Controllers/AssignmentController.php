<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use App\Models\QuestionHomework;
use App\Models\MultipleChoiceOption;
use App\Models\TrueFalse;
use App\Models\MatchingHeadline;
use App\Models\FillInTheBlank;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignments = Assignment::all();
        return view('admin.tableAssignments', compact('assignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return View('admin.createAssignment');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'numberQuestion' => 'required|integer|min:1',
            'typeQuestion' => 'required|string',
        ]);

        return redirect()->route('show' . $validatedData['typeQuestion'] . 'Type', ['quantity' => $validatedData['numberQuestion']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    public function showMultiplechoiceType($quantity){
        return view('admin.assignment_type_question.typeMultiplechoice', compact('quantity'));
    }

    public function storeMultiplechoiceType(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isEnable' => 'required|boolean',
            'show_detailed_feedback' => 'nullable|boolean',
            'questions.*.question_text' => 'required|string',
            'questions.*.options.*.option_text' => 'required|string',
            'questions.*.is_correct' => 'required|integer'
        ]);

        $teacher_id = Auth::id();

        // Tạo Assignment
        $assignment = Assignment::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'isEnable' => $validatedData['isEnable'],
            'teacher_id' => $teacher_id
        ]);

        // Tạo các câu hỏi và lựa chọn cho Assignment
        foreach ($validatedData['questions'] as $questionIndex => $questionData) {
            $question = QuestionHomework::create([
                'assignment_id' => $assignment->id,
                'question_text' => $questionData['question_text'],
                'question_type' => 'multiple_choice',
                'show_detailed_feedback' => $request->has('show_detailed_feedback') ? $validatedData['show_detailed_feedback'] : false
            ]);

            foreach ($questionData['options'] as $optionIndex => $optionData) {
                MultipleChoiceOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optionData['option_text'],
                    'is_correct' => $optionIndex == $questionData['is_correct']
                ]);
            }
        }

        return redirect()->route('tableAssignment.index')->with('success', 'Assignment and multiple choice questions created successfully.');
    }

    public function showFillintheblankType($quantity){
        return view('admin.assignment_type_question.typeFillintheblank', compact('quantity'));
    }

    public function storeFillintheblankType(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isEnable' => 'required|boolean',
            'show_detailed_feedback' => 'nullable|boolean',
            'questions.*.question_text' => 'required|string',
            'questions.*.blanks.*.blank_position' => 'required|integer',
            'questions.*.blanks.*.correct_answer' => 'required|string'
        ]);

        // Lấy teacher_id từ người dùng đang đăng nhập
        $teacher_id = Auth::id();

        // Tạo Assignment
        $assignment = Assignment::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'isEnable' => $validatedData['isEnable'],
            'teacher_id' => $teacher_id,
            'show_detailed_feedback' => $request->has('show_detailed_feedback') ? $validatedData['show_detailed_feedback'] : false
        ]);

        // Tạo các câu hỏi Fill In The Blank cho Assignment
        foreach ($validatedData['questions'] as $questionIndex => $questionData) {
            $question = QuestionHomework::create([
                'assignment_id' => $assignment->id,
                'question_text' => $questionData['question_text'],
                'question_type' => 'fill_in_the_blank'
            ]);

            foreach ($questionData['blanks'] as $blankIndex => $blankData) {
                FillInTheBlank::create([
                    'question_id' => $question->id,
                    'blank_position' => $blankData['blank_position'],
                    'correct_answer' => $blankData['correct_answer']
                ]);
            }
        }

        return redirect()->route('tableAssignment.index')->with('success', 'Assignment and Fill In The Blank questions created successfully.');
    }

    public function showTruefalseType($quantity){
        return view('admin.assignment_type_question.typeTruefalse', compact('quantity'));
    }

    public function storeTruefalseType(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isEnable' => 'required|boolean',
            'show_detailed_feedback' => 'nullable|boolean',
            'questions.*.question_text' => 'required|string',
            'questions.*.correct_answer' => 'required|in:true,false,not_given'
        ]);

        $teacher_id = Auth::id();

        // Tạo Assignment
        $assignment = Assignment::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'isEnable' => $validatedData['isEnable'],
            'teacher_id' => $teacher_id,
            'show_detailed_feedback' => $request->has('show_detailed_feedback') ? $validatedData['show_detailed_feedback'] : false
        ]);

        // Tạo các câu hỏi True/False/Not Given cho Assignment
        foreach ($validatedData['questions'] as $questionIndex => $questionData) {
            $question = QuestionHomework::create([
                'assignment_id' => $assignment->id,
                'question_text' => $questionData['question_text'],
                'question_type' => 'true_false'
            ]);

            TrueFalse::create([
                'question_id' => $question->id,
                'correct_answer' => $questionData['correct_answer']
            ]);
        }

        return redirect()->route('tableAssignment.index')->with('success', 'Assignment and True/False/Not Given questions created successfully.');
    }

    public function showMatchingType($quantity){
        return view('admin.assignment_type_question.typeMatching', compact('quantity'));
    }

    public function storeMatchingType(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isEnable' => 'required|boolean',
            'show_detailed_feedback' => 'nullable|boolean',
            'questions.*.question_text' => 'required|string',
            'questions.*.headlines.*.headline' => 'required|string',
            'questions.*.headlines.*.match_text' => 'nullable|string'
        ]);

        // Lấy teacher_id từ người dùng đang đăng nhập
        $teacher_id = Auth::id();

        // Tạo Assignment
        $assignment = Assignment::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'isEnable' => $validatedData['isEnable'],
            'teacher_id' => $teacher_id,
            'show_detailed_feedback' => $request->has('show_detailed_feedback') ? $validatedData['show_detailed_feedback'] : false
        ]);

        // Tạo các câu hỏi Matching Headline cho Assignment
        foreach ($validatedData['questions'] as $questionIndex => $questionData) {
            $question = QuestionHomework::create([
                'assignment_id' => $assignment->id,
                'question_text' => $questionData['question_text'],
                'question_type' => 'matching_headline'
            ]);

            foreach ($questionData['headlines'] as $headlineIndex => $headlineData) {
                // Lưu headline dù có hoặc không có match_text
                MatchingHeadline::create([
                    'question_id' => $question->id,
                    'headline' => $headlineData['headline'],
                    'match_text' => $headlineData['match_text'] ?? ''
                ]);
            }
        }

        return redirect()->route('tableAssignment.index')->with('success', 'Assignment and Matching Headline questions created successfully.');
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
