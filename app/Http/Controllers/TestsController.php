<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\Question;
use App\Models\ReadingsAudio;
use App\Models\SkillPart;
use App\Models\Student;
use App\Models\Test;
use App\Models\TestSkill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    // public function create()
    // {
    //     $lecturers = User::where('role', '1')->get();
    //     return view('admin.createTest', compact('lecturers'));
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {

    //     // Validate the request data
    //     $request->validate([
    //         'test_code' => 'required|string|unique:tests,test_code',
    //         'test_name' => 'required|string',
    //         'duration' => 'required|date_format:H:i',
    //         'instructor_id' => 'required|integer',
    //         // 'start_date' => 'required|date',
    //         // 'end_date' => 'required|date',
    //         'test_status' => 'required|string',
    //     ]);
    //     // Check if the test_code already exists
    //     if (Test::where('test_code', $request->test_code)->exists()) {
    //         return redirect()->back()->withErrors(['test_code' => 'The test code already exists. Please choose a different one.'])->withInput();
    //     }

    //     // Create a new Test instance and save it
    //     $test = new Test();
    //     $test->test_code = $request->test_code;
    //     $test->test_name = $request->test_name;
    //     $test->duration = $request->duration;
    //     $test->start_date = $request->start_date;
    //     $test->end_date = $request->end_date;
    //     $test->instructor_id = $request->instructor_id;
    //     $test->test_status = $request->test_status;
    //     $test->save();

    //     // Define the skills data
    //     $skills = [
    //         ['skill_name' => 'Speaking', 'time_limit' => '00:12:00', 'part_count' => 3],
    //         ['skill_name' => 'Writing', 'time_limit' => '01:00:00', 'part_count' => 2],
    //         ['skill_name' => 'Reading', 'time_limit' => '01:00:00', 'part_count' => 4],
    //         ['skill_name' => 'Listening', 'time_limit' => '00:47:00', 'part_count' => 3],
    //     ];

    //     // Create each skill linked to the test
    //     foreach ($skills as $skill) {
    //         $testSkill = new TestSkill();
    //         $testSkill->test_id = $test->id;
    //         $testSkill->skill_name = $skill['skill_name'];
    //         $testSkill->time_limit = $skill['time_limit'];
    //         $testSkill->save();
    //     }

    //     return redirect()->route('tableTest.index')->with('success', 'Test and associated skills and parts created successfully!');
    // }

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
     * Remove the specified resource from storage.
     */
    public function destroy(Test $test_slug)
    {
        DB::transaction(function () use ($test_slug) {
            // Lấy ID của Test
            $testId = $test_slug->id;
            // Xoá các record trong bảng students có test_id bằng với testId
            Student::where('test_id', $testId)->delete();
            // Xoá test
            $test_slug->delete();
        });

        // Chuyển hướng người dùng với thông báo thành công
        return redirect()->route('tableTest.index')->with('success', 'Test and related student records deleted successfully');
    }

    public function destroyAll()
    {
        DB::transaction(function () {
            // Xoá các students liên quan đến mỗi test
            $tests = Test::all();
            foreach ($tests as $test) {
                $test->students()->delete(); // Xoá các bản ghi trong students liên quan đến test
                $test->delete();
            }
        });

        return response()->json(['message' => 'All tests and related student records have been deleted successfully.']);
    }
}
