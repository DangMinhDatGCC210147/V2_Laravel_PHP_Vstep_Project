<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\Question;
use App\Models\ReadingsAudio;
use App\Models\SkillPart;
use App\Models\Test;
use App\Models\TestSkill;
use App\Models\User;
use Illuminate\Http\Request;

class TestsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tests = Test::all();
        return view('admin.tableTest', compact('tests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lecturers = User::where('role', '1')->get();
        return view('admin.createTest', compact('lecturers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Validate the request data
        $request->validate([
            'test_code' => 'required|string|unique:tests,test_code',
            'test_name' => 'required|string',
            'duration' => 'required|date_format:H:i',
            'instructor_id' => 'required|integer',
            // 'start_date' => 'required|date',
            // 'end_date' => 'required|date',
            'test_status' => 'required|string',
        ]);
        // Check if the test_code already exists
        if (Test::where('test_code', $request->test_code)->exists()) {
            return redirect()->back()->withErrors(['test_code' => 'The test code already exists. Please choose a different one.'])->withInput();
        }

        // Create a new Test instance and save it
        $test = new Test();
        $test->test_code = $request->test_code;
        $test->test_name = $request->test_name;
        $test->duration = $request->duration;
        $test->start_date = $request->start_date;
        $test->end_date = $request->end_date;
        $test->instructor_id = $request->instructor_id;
        $test->test_status = $request->test_status;
        $test->save();

        // Define the skills data
        $skills = [
            ['skill_name' => 'Speaking', 'time_limit' => '00:12:30', 'part_count' => 3],
            ['skill_name' => 'Writing', 'time_limit' => '01:00:00', 'part_count' => 2],
            ['skill_name' => 'Reading', 'time_limit' => '01:00:00', 'part_count' => 4],
            ['skill_name' => 'Listening', 'time_limit' => '00:47:00', 'part_count' => 3],
        ];

        // Create each skill linked to the test
        foreach ($skills as $skill) {
            $testSkill = new TestSkill();
            $testSkill->test_id = $test->id;
            $testSkill->skill_name = $skill['skill_name'];
            $testSkill->time_limit = $skill['time_limit'];
            $testSkill->save();
        }

        return redirect()->route('tableTest.index')->with('success', 'Test and associated skills and parts created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Test $test_slug)
    {
        // dd($test_slug);
        // Load the test with associated test skills and skill parts, including a count of questions for each part
        $test = $test_slug->load([
            'testSkills' => function ($query) {
                $query->withCount('questions') // This will add a `questions_count` attribute to each skill part
                    ->leftJoin('test_parts as tp', 'tp.test_skill_id', '=', 'test_skills.id')
                    ->orderByRaw("FIELD(test_skills.skill_name, 'Listening', 'Speaking', 'Reading', 'Writing')");
            }
        ]);

        return view('admin.testSkills', compact('test'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Test $test_slug)
    {
        $lecturers = User::all(); // Assuming you have a Lecturer model
        return view('admin.createTest', compact('test_slug', 'lecturers'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Test $test_slug)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'test_code' => 'required|string',
            'duration' => 'required|string',
            // 'start_date' => 'required|date',
            // 'end_date' => 'required|date',
            'instructor_id' => 'required|exists:users,id',
            'test_status' => 'required|string',
        ]);

        // Update the test with validated data
        $test_slug->update($validatedData);
        // Redirect back to the index page with a success message
        return redirect()->route('tableTest.index')->with('success', 'Test updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Test $test_slug)
    {
        $test_slug->delete();
        return redirect()->route('tableTest.index')->with('success', 'Test deleted successfully');
    }

    //Show view which use to add questions
    public function addSkillQuestions(Test $test_slug, TestSkill $skill_slug)
    {
        $test = Test::where('slug', $test_slug->slug)->firstOrFail();
        $skill = TestSkill::where('slug', $skill_slug->slug)->firstOrFail();
        $passages = null;

        switch ($skill->skill_name) {
            case 'Listening':
                return view('admin.questions.manageListening', compact('test', 'skill', 'passages', 'test_slug', 'skill_slug'));
            case 'Speaking':
                return view('admin.questions.manageSpeaking', compact('test', 'skill', 'passages', 'test_slug', 'skill_slug'));
            case 'Reading':
                return view('admin.questions.manageReading', compact('test', 'skill', 'passages', 'test_slug', 'skill_slug'));
            case 'Writing':
                return view('admin.questions.manageWriting', compact('test', 'skill', 'passages', 'test_slug', 'skill_slug'));
            default:
                abort(404);
        }
    }

    public function editSkillQuestions(Test $test_slug, TestSkill $skill_slug)
    {
        $test = Test::where('slug', $test_slug->slug)->firstOrFail();
        $skill = TestSkill::where('slug', $skill_slug->slug)->firstOrFail();

        $passages = ReadingsAudio::where('test_skill_id', $skill_slug->id)->get();
        $questions = Question::with('options')->where('test_skill_id', $skill_slug->id)->get();

        switch ($skill_slug->skill_name) {
            case 'Listening':
                return view('admin.questions.manageListening', compact('test_slug', 'skill_slug', 'passages', 'questions'));
            case 'Speaking':
                return view('admin.questions.manageSpeaking', compact('test_slug', 'skill_slug', 'passages', 'questions'));
            case 'Reading':
                return view('admin.questions.manageReading', compact('test_slug', 'skill_slug', 'passages', 'questions'));
            case 'Writing':
                return view('admin.questions.manageWriting', compact('test_slug', 'skill_slug', 'passages', 'questions'));
            default:
                abort(404);
        }
    }
}
