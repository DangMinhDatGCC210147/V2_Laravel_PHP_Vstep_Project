<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\TestSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class IndexAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
