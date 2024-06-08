<?php

namespace App\Http\Controllers;

use App\Models\ListeningResponses;
use App\Models\SpeakingResponse;
use App\Models\ReadingResponses;
use App\Models\StudentResponses;
use App\Models\WritingResponse;
use FFMpeg\FFMpeg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentSubmissionController extends Controller
{
    public function saveListening(Request $request)
    {
        // Log::info('Received data for Listening:', $request->all());

        $validated = $request->validate([
            'skill_id' => 'required|integer',
            'responses' => 'required|array',
            'responses.*' => 'nullable|string|max:255',
        ]);

        $studentId = auth()->id();
        foreach ($request->responses as $questionId => $response) {
            if (!empty($response)) {
                StudentResponses::updateOrCreate(
                    [
                        'skill_id' => $request->skill_id,
                        'student_id' => $studentId,
                        'question_id' => $questionId
                    ],
                    ['text_response' => $response]
                );
            }
        }

        return response()->json(['success' => true, 'message' => 'The listening skill answer has been saved successfully.']);
    }

    public function saveReading(Request $request)
    {
        // Log::info('Received data for Reading:', $request->all());

        $validated = $request->validate([
            'skill_id' => 'required|integer',
            'responses' => 'required|array',
            'responses.*' => 'nullable|string',
        ]);

        $studentId = auth()->id();
        foreach ($request->responses as $questionId => $response) {
            if (!empty($response)) {
                StudentResponses::updateOrCreate(
                    [
                        'skill_id' => $request->skill_id,
                        'student_id' => $studentId,
                        'question_id' => $questionId
                    ],
                    ['text_response' => $response]
                );
            }
        }

        return response()->json(['success' => true, 'message' => 'The reading skill answer has been saved successfully.']);
    }
    public function saveWriting(Request $request)
    {
        // Log::info('Received data for Writing:', $request->all());

        $validated = $request->validate([
            'skill_id' => 'required|integer',
            'responses' => 'required|array',
            'responses.*' => 'nullable|string',
        ]);

        $studentId = auth()->id();
        foreach ($request->responses as $questionId => $response) {
            if (!empty($response)) {
                StudentResponses::updateOrCreate(
                    [
                        'skill_id' => $request->skill_id,
                        'student_id' => $studentId,
                        'question_id' => $questionId
                    ],
                    ['text_response' => $response]
                );
            }
        }

        return response()->json(['success' => true, 'message' => 'The writing skill answer has been saved successfully.']);
    }


    public function saveSpeaking(Request $request)
    {
    }

    public function saveAnswer(Request $request)
    {
        $validatedData = $request->validate([
            'skill_id' => 'required|integer',
            'question_id' => 'required|integer',
            'option_id' => 'required|integer',
        ]);

        $userAnswer = StudentResponses::updateOrCreate(
            [
                'student_id' => auth()->id(), // Assuming you want to save the user ID as well
                'skill_id' => $validatedData['skill_id'],
                'question_id' => $validatedData['question_id'],
            ],
            [
                'text_response' => $validatedData['option_id'],
            ]
        );

        return response()->json(['message' => 'Answer saved successfully'], 200);
    }

    public function saveRecording(Request $request)
    { 

        // Validate the incoming request
        $validated = $request->validate([
            'recording' => 'required|file|mimes:mp3,webm,ogg',
            'skill_id' => 'required|integer',
            'question_id' => 'required|integer'
        ]);

        // Fetch the authenticated user
        $user = auth()->user();
        $accountId = $user->account_id;

        // Construct a unique file name
        $fileName = $accountId . '_' . time() . '.webm';
        // Store the file in a dedicated directory
        $path = $request->file('recording')->storeAs('studentResponse', $fileName, 'public');

        // Save or update the response in the database
        $studentId = $user->id; // Use the authenticated user's ID
        $response = StudentResponses::updateOrCreate(
            [
                'skill_id' => $validated['skill_id'],
                'student_id' => $studentId,
                'question_id' => $validated['question_id']
            ],
            ['text_response' => $path]
        );

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Recording saved successfully.',
            'path' => $path // Optional: return path for verification/debugging
        ]);
    }
}
