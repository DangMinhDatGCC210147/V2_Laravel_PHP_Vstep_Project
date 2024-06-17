<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\ReadingsAudio;
use App\Models\SkillPart;
use App\Models\Test;
use App\Models\TestSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReadingController extends Controller
{

    public function storeReading(Request $request, Test $test_slug, $skill_id)
    {
        // dd($request->all());
        try {
            // Save each passage part
            foreach ($request->passages as $part => $passage) {
                // dd($passage);
                $readingAudio = new ReadingsAudio();
                $readingAudio->reading_audio_file = $passage;
                $readingAudio->test_skill_id = $skill_id;
                $readingAudio->part_name = 'Part_' . ($part - 1 + 1);
                $readingAudio->save();
                // Each part has 10 questions
                for ($q = 1; $q <= 10; $q++) {
                    $questionData = $request->questions[($part - 1) * 10 + $q];
                    $question = new Question;
                    $question->test_skill_id = $skill_id; // Assuming skill_part_id is provided correctly
                    $question->reading_audio_id = $readingAudio->id;
                    $question->question_number = ($part - 1) * 10 + $q;
                    $question->part_name = 'Part_' . ceil($question->question_number / 10);
                    $question->question_text = $questionData['text'];
                    $question->question_type = 'Multiple Choice Reading'; // Assuming all are multiple choice
                    // $question->correct_answer = $questionData['options'][$questionData['correct_answer']];
                    $question->save();
                    // Save options for the question
                    foreach ($questionData['options'] as $index => $optionText) {
                        $option = new Option;
                        $option->question_id = $question->id;
                        $option->option_text = $optionText;
                        $option->correct_answer = ($index == $questionData['correct_answer']);

                        $option->save();
                        
                    }
                }
            }
            // dd($test_slug);
            return redirect()->route('testSkills.show', ['test_slug' => $test_slug])
                ->with('success', 'Reading parts and questions saved successfully!');
        } catch (\Exception $e) {
            return back()->withErrors('Error saving the reading parts: ' . $e->getMessage());
        }
    }

    public function updateReading(Request $request, Test $test_slug, TestSkill $skill_slug)
    {
        try {
            foreach ($request->passages as $readingAudioId => $passageText) {
                $readingAudio = ReadingsAudio::where('test_skill_id', $skill_slug->id)
                    ->where('id', $readingAudioId)
                    ->firstOrFail();
                $readingAudio->reading_audio_file = $passageText;
                $readingAudio->save();

                // Cập nhật các câu hỏi cho mỗi đoạn đọc
                if (isset($request->questions[$readingAudioId])) {
                    foreach ($request->questions[$readingAudioId] as $questionData) {
                        $question = Question::findOrFail($questionData['id']);
                        $question->question_text = $questionData['text'];
                        // $question->correct_answer = $questionData['options'][$questionData['correct_answer']];
                        $question->save();

                        // Cập nhật các lựa chọn cho câu hỏi
                        if (isset($questionData['options'])) {
                            foreach ($questionData['options'] as $optionId => $optionText) {
                                $option = Option::findOrFail($optionId);
                                $option->option_text = $optionText;
                                $option->correct_answer = ($optionId == $questionData['correct_answer']);
                                $option->save();
                            }
                        }
                    }
                }
            }

            return redirect()->route('testSkills.show', ['test_slug' => $test_slug])
                ->with('success', 'Reading parts and questions updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors('Error updating the reading parts: ' . $e->getMessage());
        }
    }
}
