<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\ReadingsAudio;
use App\Models\Test;
use App\Models\TestSkill;
use Illuminate\Http\Request;

class WritingController extends Controller
{
    public function storeWriting(Request $request, Test $test_slug, $skill_id)
    {
        try {
            // Lưu Part 1
            $readingsAudio1 = new ReadingsAudio();
            $readingsAudio1->test_skill_id = $skill_id;
            $readingsAudio1->reading_audio_file = $request->input('passage1');
            $readingsAudio1->part_name = 'Part_1';
            $readingsAudio1->save();

            $question1 = new Question();
            $question1->test_skill_id = $skill_id;
            $question1->reading_audio_id = $readingsAudio1->id;
            $question1->question_text = $request->input('question1');
            $question1->question_type = 'Text Writing';
            $question1->question_number = "1";
            $question1->part_name = "Part_1";
            $question1->save();

            // Lưu Part 2
            $readingsAudio2 = new ReadingsAudio();
            $readingsAudio2->test_skill_id = $skill_id;
            $readingsAudio2->reading_audio_file = $request->input('passage2');
            $readingsAudio2->part_name = 'Part_2';
            $readingsAudio2->save();

            $question2 = new Question();
            $question2->test_skill_id = $skill_id;
            $question2->reading_audio_id = $readingsAudio2->id;
            $question2->question_text = $request->input('question2');
            $question2->question_type = 'Text Writing';
            $question2->question_number = "2";
            $question2->part_name = "Part_2";
            $question2->save();

            return redirect()->route('testSkills.show', ['test_slug' => $test_slug])->with('success', 'Writing skills saved successfully.');
        } catch (\Exception $e) {
            return back()->withErrors('Error saving writing skills: ' . $e->getMessage());
        }
    }

    public function updateWriting(Request $request, Test $test_slug, TestSkill $skill_slug)
    {
        try {
            // Get part 1 and update
            $question1 = Question::where('test_skill_id', $skill_slug->id)
                                    ->where('part_name', 'Part 1')
                                    ->firstOrFail();
            $readingsAudio1 = ReadingsAudio::findOrFail($question1->reading_audio_id);
            $readingsAudio1->reading_audio_file = $request->input('passage1');
            $readingsAudio1->save();
            $question1->question_text = $request->input('question1');
            $question1->save();
    
            // Get part 2 and update
            $question2 = Question::where('test_skill_id', $skill_slug->id)
                                    ->where('part_name', 'Part 2')
                                    ->firstOrFail();
            $readingsAudio2 = ReadingsAudio::findOrFail($question2->reading_audio_id);
            $readingsAudio2->reading_audio_file = $request->input('passage2');
            $readingsAudio2->save();
            $question2->question_text = $request->input('question2');
            $question2->save();
    
            return redirect()->route('testSkills.show', ['test_slug' => $test_slug])->with('success', 'Writing skills updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors('Error updating writing skills: ' . $e->getMessage());
        }
    }
}
