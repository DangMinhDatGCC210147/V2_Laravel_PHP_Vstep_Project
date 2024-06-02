<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InstructorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $lecturers = User::all();
        $lecturers = User::where('role', 1)
            ->orderBy('id', 'asc')
            ->get();
        return view('admin.tableLecturer', compact('lecturers'));
    }

    public function indexStudent()
    {
        // $lecturers = User::all();
        $students = User::where('role', 2)
            ->orderBy('id', 'asc')
            ->get();
        return view('admin.tableStudent', compact('students'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.createInstructor');
    }
    public function createStudent()
    {
        return view('admin.createStudent');
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $slug)
    {
        $user = User::findOrFail($slug->id);
        // Pass the user data to the view for editing
        return view('admin.createInstructor', compact('user'));
    }

    public function editStudent(User $slug)
    {
        $user = User::findOrFail($slug->id);
        // Pass the user data to the view for editing
        return view('admin.createStudent', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $slug)
    {
        // dd($request->all(), $slug);
        $user = User::findOrFail($slug->id);
        // Update the user data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->lecturer_id = $request->lecturer_id;
        $user->student_id = $request->student_id;

        if ($user->lecturer_id != null) {
            $user->role = '1';
        } else {
            $user->role = '2';
        }
        // Save the updated user
        $user->save();

        // Redirect back with a success message
        if ($user->lecturer_id != null) {
            return redirect()->route('tableLecturer.index')->with('success', 'Lecturer updated successfully');
        } else {
            return redirect()->route('tableStudent.index')->with('success', 'Student updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $slug)
    {
        // Find the user by ID
        $user = User::findOrFail($slug->id);
        // Delete the user
        $user->delete();
        // Redirect back with a success message
        return back()->with('success', 'User deleted successfully');
    }
}
