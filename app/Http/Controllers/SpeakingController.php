<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\ReadingsAudio;
use App\Models\Test;
use App\Models\TestSkill;
use Illuminate\Http\Request;

class SpeakingController extends Controller
{
    public function storeSpeaking(Request $request, Test $test_slug, $skill_id)
    {
        try {
            // Process Part 1
            for ($i = 1; $i <= 2; $i++) {
                // $readingsAudio = new ReadingsAudio([
                //     'test_skill_id' => $skill_id,
                //     'reading_audio_file' => null,
                //     'part_name' => 'Part_1',
                // ]);
                // $readingsAudio->save();

                $questionText = $request->input("part1_question_$i");

                $question = new Question([
                    'test_skill_id' => $skill_id,
                    // 'reading_audio_id' => $readingsAudio->id,
                    'question_text' => $questionText,
                    'question_type' => 'Text Speaking',
                    'question_number' => $i,
                    'part_name' => 'Part_1'
                ]);
                $question->save();
                // Save options for Part 1
                for ($j = 1; $j <= 3; $j++) {
                    $optionText = $request->input("part1_question_{$i}_option_{$j}");
                    $option = new Option([
                        'question_id' => $question->id,
                        'option_text' => $optionText
                    ]);
                    $option->save();
                }
            }

            // Process Part 2
            // $readingsAudio2 = new ReadingsAudio([
            //     'test_skill_id' => $skill_id,
            //     'reading_audio_file' => $request->input("part2_text"),
            //     'part_name' => 'Part_2',
            // ]);
            // $readingsAudio2->save();

            $part2Text = $request->input('part2_text');
            $questionPart2 = new Question([
                'test_skill_id' => $skill_id,
                // 'reading_audio_id' => $readingsAudio2->id,
                'question_text' => $part2Text,
                'question_type' => 'Text Speaking',
                'part_name' => 'Part_2',
                'question_number' => 1  // Assuming only one question in Part 2
            ]);
            $questionPart2->save();

            // Process Part 3
            if ($request->hasFile('part3_image')) {
                $imagePath = $request->file('part3_image')->store('images', 'public');
                $listeningAudio3 = new ReadingsAudio();
                $listeningAudio3->reading_audio_file = $imagePath;
                $listeningAudio3->test_skill_id = $skill_id;
                $listeningAudio3->part_name = 'Part_3';
                $listeningAudio3->save();

                $questionTextPart3 = $request->input('part3_question');
                $questionPart3 = new Question([
                    'test_skill_id' => $skill_id,
                    'reading_audio_id' => $listeningAudio3->id,
                    'question_text' => $questionTextPart3,
                    'question_type' => 'Text Speaking',
                    'question_number' => 1,  // Assuming only one question in Part 3
                    'part_name' => 'Part_3',
                ]);
                $questionPart3->save();

                // Save options for Part 3
                for ($k = 1; $k <= 3; $k++) {
                    $optionTextPart3 = $request->input("part3_option_$k");
                    $option = new Option([
                        'question_id' => $questionPart3->id,
                        'option_text' => $optionTextPart3
                    ]);
                    $option->save();
                }
            }

            return redirect()->route('testSkills.show', ['test_slug' => $test_slug])
                ->with('success', 'Speaking test created successfully!');
        } catch (\Exception $e) {
            return back()->withErrors('Error saving the speaking test: ' . $e->getMessage());
        }
    }

    public function updateSpeaking(Request $request, Test $test_slug, TestSkill $skill_slug)
    {
        try {
            $part1Questions = Question::where('part_name', 'Part_1')
                ->where('test_skill_id', $skill_slug->id)
                ->get();
            foreach ($part1Questions as $question) {
                $index = $question->question_number;
                $questionKey = "part1_question_{$index}";
                if ($request->has($questionKey)) {
                    $question->question_text = $request->input($questionKey);
                    $question->save();

                    // Cập nhật các tùy chọn dựa trên index của mỗi option
                    $options = $question->options;
                    for ($j = 0; $j < $options->count(); $j++) {
                        $optionKey = "{$questionKey}_option_" . ($j + 1);
                        if ($request->has($optionKey)) {
                            $options[$j]->option_text = $request->input($optionKey);
                            $options[$j]->save();
                        }
                    }
                }
            }

            // Cập nhật câu hỏi Part 2
            $part2Question = Question::where('part_name', 'Part_2')->where('test_skill_id', $skill_slug->id)->first();
            if ($part2Question && $request->has('part2_text')) {
                $part2Question->question_text = $request->input('part2_text');
                $part2Question->save();
            }

            // Cập nhật câu hỏi và hình ảnh Part 3
            $part3Question = Question::where('part_name', 'Part_3')->where('test_skill_id', $skill_slug->id)->first();
            if ($part3Question && $request->has('part3_question')) {
                $part3Question->question_text = $request->input('part3_question');
                $part3Question->save();
                if ($request->hasFile('part3_image')) {
                    $path = $request->file('part3_image')->store('images', 'public');
                    $readingAudio = ReadingsAudio::where('test_skill_id', $skill_slug->id)
                        ->where('id', $part3Question->reading_audio_id)
                        ->firstOrFail();
                    $readingAudio->reading_audio_file = $path;
                    $readingAudio->save();
                }

                $options = $part3Question->options;
                for ($k = 0; $k < $options->count(); $k++) {
                    $optionKey = "part3_option_" . ($k + 1);
                    if ($request->has($optionKey)) {
                        $options[$k]->option_text = $request->input($optionKey);
                        $options[$k]->save();
                    }
                }
            }

            return redirect()->route('testSkills.show', ['test_slug' => $test_slug])->with('success', 'Speaking test updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors('Error updating the speaking test: ' . $e->getMessage());
        }
    }
}
