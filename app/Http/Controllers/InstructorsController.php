<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function indexAdmin()
    {
        // $lecturers = User::all();
        $admins = User::where('role', 0)
            ->orderBy('id', 'asc')
            ->get();
        return view('admin.tableAdmin', compact('admins'));
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
    public function createAdmin()
    {
        return view('admin.createAdmin');
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

    public function editAdmin(User $slug)
    {
        $user = User::findOrFail($slug->id);
        // Pass the user data to the view for editing
        return view('admin.createAdmin', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $slug)
    {
        // dd($request->all(), $slug);
        $user = User::findOrFail($slug->id);
        $request->validate([
            'password' => 'nullable|min:8',
        ]);
        // Update the user data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->account_id = $request->account_id;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        // Save the updated user
        $user->save();

        // Redirect back with a success message
        if ($user->role == 1) {
            return redirect()->route('tableLecturer.index')->with('success', 'Lecturer updated successfully');
        } else if ($user->role == 2) {
            return redirect()->route('tableStudent.index')->with('success', 'Student updated successfully');
        } else{
            return redirect()->route('tableAdmin.index')->with('success', 'Admin updated successfully');
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

    public function inactiveStudents(Request $request)
    {
        $studentIds = $request->input('student_ids', []);

        if (!empty($studentIds)) {
            User::whereIn('id', $studentIds)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }else{
            return redirect()->route('tableStudent.index')->with('error', 'No students selected for deactivation.');
        }

        return redirect()->route('tableStudent.index')->with('success', 'Selected students have been deactivated.');
    }

    public function activeStudents(Request $request)
    {
        $studentIds = $request->input('student_ids', []);

        if (!empty($studentIds)) {
            // Chỉ cập nhật những user có is_active == false
            User::whereIn('id', $studentIds)
                ->where('is_active', false)
                ->update(['is_active' => true]);
        }else{
            return redirect()->route('tableStudent.index')->with('error', 'No students selected for activation.');
        }
        return redirect()->route('tableStudent.index')->with('success', 'Selected students have been activated.');
    }

    public function inactiveLecturers(Request $request)
    {
        $studentIds = $request->input('student_ids', []);

        if (!empty($studentIds)) {
            User::whereIn('id', $studentIds)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }else{
            return redirect()->route('tableLecturer.index')->with('error', 'No lecturers selected for deactivation.');
        }

        return redirect()->route('tableLecturer.index')->with('success', 'Selected lecturers have been deactivated.');
    }

    public function activeLecturers(Request $request)
    {
        $studentIds = $request->input('student_ids', []);

        if (!empty($studentIds)) {
            // Chỉ cập nhật những user có is_active == false
            User::whereIn('id', $studentIds)
                ->where('is_active', false)
                ->update(['is_active' => true]);
        }else{
            return redirect()->route('tableLecturer.index')->with('error', 'No lecturers selected for activation.');
        }
        return redirect()->route('tableLecturer.index')->with('success', 'Selected lecturers have been activated.');
    }
}
