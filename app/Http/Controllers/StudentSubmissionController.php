<?php

namespace App\Http\Controllers;

use App\Models\ListeningResponses;
use App\Models\SpeakingResponse;
use App\Models\ReadingResponses;
use App\Models\StudentResponses;
use App\Models\WritingResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentSubmissionController extends Controller
{
    public function saveListening(Request $request) {
        $validated = $request->validate([
            'skill_id' => 'required|integer',
            'responses' => 'required|array',
            'responses.*' => 'required|string|max:255',
        ]);
        $studentId = auth()->id(); 
        // Duyệt qua mỗi câu trả lời trong mảng responses
        foreach ($request->responses as $questionId => $response) {
            StudentResponses::updateOrCreate(
                [
                    'skill_id' => $request->skill_id,
                    'student_id' => $studentId,
                    'question_id' => $questionId
                ],
                ['text_response' => $response]
            );
        }
    
        return back()->with('success', 'The listening skill answer has been saved successfully.');
    }
    
    public function saveSpeaking(Request $request) {
        
    }
    
    public function saveReading(Request $request) {
        
        $validated = $request->validate([
            'skill_id' => 'required|integer',
            'responses' => 'required|array',
            'responses.*' => 'required|string',
        ]);
        $studentId = auth()->id(); 
        // Duyệt qua mỗi câu trả lời trong mảng responses
        foreach ($request->responses as $questionId => $response) {
            StudentResponses::updateOrCreate(
                [
                    'skill_id' => $request->skill_id,
                    'student_id' => $studentId,
                    'question_id' => $questionId
                ],
                ['text_response' => $response]
            );
        }
    
        return back()->with('success', 'The reading skill answer has been saved successfully.');
    }     
    
    public function saveWriting(Request $request) {
        // Log::info('Received data:', $request->all());
        // Validate the incoming data
        $validated = $request->validate([
            'skill_id' => 'required|integer',
            'responses' => 'required|array',
            'responses.*' => 'nullable|string',  // Increase the max size if needed
        ]);
    
        // Get the student's ID, assuming they are authenticated
        $studentId = auth()->id();
    
        // Loop through each response and save or update it
        foreach ($request->responses as $questionId => $responseText) {
            StudentResponses::updateOrCreate(
                [
                    'skill_id' => $request->skill_id,
                    'student_id' => $studentId,
                    'question_id' => $questionId
                ],
                ['text_response' => $responseText]
            );
        }
    
        // Optionally, return a response indicating success
        return response()->json(['message' => 'Writing responses saved successfully']);
    }    
    
}
