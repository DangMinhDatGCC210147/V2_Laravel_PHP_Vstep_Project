<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\QuestionHomework;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAssignmentController extends Controller
{
    public function showAssignment(Assignment $assignment)
    {
        if (!$assignment->isEnable) {
            return redirect()->back()->with('error', 'Assignment is not enabled.');
        }

        $questions = $assignment->questions()->with('multipleChoiceOptions', 'fillInTheBlanks', 'trueFalse', 'matchingHeadlines')->get();

        return view('students.assignments.take', [
            'assignment' => $assignment,
            'questions' => $questions,
        ]);
    }

    public function submitAssignment(Request $request, Assignment $assignment)
    {
        $studentId = Auth::id();
        $questions = $assignment->questions;

        // Tìm số lần làm bài hiện tại
        $latestAttempt = StudentAnswer::where('student_id', $studentId)
            ->whereIn('question_id', $questions->pluck('id'))
            ->max('attempt_number');

        $newAttemptNumber = $latestAttempt ? $latestAttempt + 1 : 1;

        foreach ($questions as $question) {
            $answerText = $request->input('question_' . $question->id);

            if ($answerText !== null) {
                // Chuyển đổi đáp án dạng Matching thành chuỗi JSON
                if ($question->question_type == 'matching_headline') {
                    $answerText = json_encode($answerText);
                }

                StudentAnswer::create([
                    'student_id' => $studentId,
                    'question_id' => $question->id,
                    'answer_text' => $answerText,
                    'is_correct' => $this->checkAnswer($question, $answerText),
                    'attempt_number' => $newAttemptNumber,
                ]);
            }
        }

        return redirect()->route('assignments.result', $assignment);
    }

    public function resultAssignment(Assignment $assignment)
    {
        $studentId = Auth::id();

        // Lấy số lần làm bài mới nhất
        $latestAttemptNumber = StudentAnswer::where('student_id', $studentId)
            ->whereIn('question_id', $assignment->questions->pluck('id'))
            ->max('attempt_number');

        // Lấy các đáp án của lần làm bài mới nhất
        $answers = StudentAnswer::where('student_id', $studentId)
            ->whereIn('question_id', $assignment->questions->pluck('id'))
            ->where('attempt_number', $latestAttemptNumber)
            ->get();

        // Giải mã chuỗi JSON thành mảng cho dạng Matching
        $answers->each(function ($answer) {
            if ($answer->question->question_type == 'matching_headline') {
                $answer->answer_text = json_decode($answer->answer_text, true);
            }
        });

        $correctAnswers = 0;
        $totalQuestions = 0;

        foreach ($answers as $answer) {
            $question = $answer->question;

            if ($question->question_type == 'matching_headline') {
                // Chỉ lấy các cặp hợp lệ
                $correctHeadlines = $question->matchingHeadlines()
                    ->whereNotNull('headline')
                    ->where('headline', '!=', '')
                    ->whereNotNull('match_text')
                    ->where('match_text', '!=', '')
                    ->pluck('headline', 'match_text');

                $totalQuestions += $correctHeadlines->count();

                foreach ($correctHeadlines as $matchText => $headline) {
                    if (in_array($headline, $answer->answer_text ?? [])) {
                        $correctAnswers++;
                    }
                }
            } else {
                $totalQuestions++;
                if ($answer->is_correct) {
                    $correctAnswers++;
                }
            }
        }

        return view('students.assignments.result', [
            'assignment' => $assignment,
            'answers' => $answers,
            'correctAnswers' => $correctAnswers,
            'totalQuestions' => $totalQuestions,
        ]);
    }


    private function checkAnswer(QuestionHomework $question, $answerText)
    {
        switch ($question->question_type) {
            case 'multiple_choice':
                return $question->multipleChoiceOptions()
                    ->where('option_text', $answerText)
                    ->where('is_correct', true)
                    ->exists();
            case 'true_false':
                return $question->trueFalse->correct_answer === $answerText;
            case 'fill_in_the_blank':
                return $question->fillInTheBlanks()
                    ->where('correct_answer', $answerText)
                    ->exists();
            case 'matching_headline':
                $correctHeadlines = $question->matchingHeadlines()
                    ->whereNotNull('headline')
                    ->where('headline', '!=', '')
                    ->whereNotNull('match_text')
                    ->where('match_text', '!=', '')
                    ->pluck('headline', 'match_text');

                $answerArray = json_decode($answerText, true);

                foreach ($correctHeadlines as $matchText => $headline) {
                    if (in_array($headline, $answerArray ?? [])) {
                        return true;
                    }
                }
                return false;
            default:
                return false;
        }
    }
}
