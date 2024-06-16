<?php

namespace App\Http\Controllers;

use App\Models\StudentResponses;
use App\Models\Test;
use App\Models\TestResult;
use App\Models\TestSkill;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ShowListResultsController extends Controller
{
    public function index()
    {
        $testResults = TestResult::with('student')->get();
        return view('admin.showResult', compact('testResults'));
    }

    public function downloadResponse($studentId, $testName)
    {

        $testId = Test::where('test_name', $testName)->value('id');
        if (!$testId) {
            return redirect()->back()->with('error', 'Test is not available.');
        }

        $speakingSkillIds = TestSkill::where('skill_name', 'Speaking')->pluck('id');
        $writingSkillIds = TestSkill::where('skill_name', 'Writing')->pluck('id');

        if ($speakingSkillIds->isEmpty() || $writingSkillIds->isEmpty()) {
            return redirect()->back()->with('error', 'Skill IDs for Speaking or Writing not found.');
        }

        $accountId = User::where('id', $studentId)->value('account_id');
        $studentName = User::where('id', $studentId)->value('slug');
        $responses = StudentResponses::where('student_id', $studentId)
            ->where('test_id', $testId)
            ->whereIn('skill_id', $speakingSkillIds->merge($writingSkillIds)->toArray())
            ->get();

        if ($responses->isEmpty()) {
            return redirect()->back()->with('error', 'Responses not found.');
        }
        $responsesFolderPath = storage_path('app/public/' . $accountId . '_' . $studentName);
        mkdir($responsesFolderPath . '/speaking', 0777, true); // Ensure speaking directory exists
        mkdir($responsesFolderPath . '/writing', 0777, true); // Ensure writing directory exists
        if (!file_exists($responsesFolderPath)) {
            mkdir($responsesFolderPath, 0777, true);
        }

        foreach ($responses as $response) {
            if ($speakingSkillIds->contains($response->skill_id)) {
                // Đối với kỹ năng nói, kiểm tra file tồn tại và sao chép
                $filePath = str_replace('\\', '/', public_path('storage/' . $response->text_response));
                if (file_exists($filePath)) {
                    copy($filePath, $responsesFolderPath . '/speaking/' . basename($filePath));
                } else {
                    return redirect()->back()->with('error', 'Student did not submit Speaking or Writing');
                }
            } elseif ($writingSkillIds->contains($response->skill_id)) {
                // Đối với kỹ năng viết, tạo file docx từ text
                $phpWord = new \PhpOffice\PhpWord\PhpWord();
                $section = $phpWord->addSection();
                $section->addText($response->text_response);
                $docxFilePath = $responsesFolderPath . '/writing/writing_response_' . $response->id . '.docx';
                $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
                $writer->save($docxFilePath);
            }
        }

        // Zip the responses folder
        $zipFilePath = storage_path('app/public/' . $accountId . '_' . $studentName . '.zip');
        $zip = new \ZipArchive();
        if ($zip->open($zipFilePath, \ZipArchive::CREATE) === TRUE) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($responsesFolderPath));
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($responsesFolderPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            // Return the response with the ZIP file download
            $this->deleteDirectory($responsesFolderPath);
            // Return the response with the ZIP file download
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('error', 'Could not create zip file.');
        }
    }

    protected function deleteDirectory($dirPath) {
        if (is_dir($dirPath)) {
            $files = scandir($dirPath);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    $fullPath = "$dirPath/$file";
                    if (is_dir($fullPath)) {
                        $this->deleteDirectory($fullPath);
                    } else {
                        unlink($fullPath);
                    }
                }
            }
            rmdir($dirPath);
        }
    }
}
