<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\IndexAdminController;
use App\Http\Controllers\InstructorsController;
use App\Http\Controllers\ListeningController;
use App\Http\Controllers\ReadingController;
use App\Http\Controllers\SkillPartQuestionController;
use App\Http\Controllers\SpeakingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentSubmissionController;
use App\Http\Controllers\TestsController;
use App\Http\Controllers\WritingController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckStudentRole;
use App\Http\Middleware\CheckLecturerRole;

Route::fallback(function () {
    return view('errors.404');
});

Route::get('/', [AuthController::class, 'showlogin'])->name('student.login');
Route::post('/login', [AuthController::class, 'login'])->name('loginAccount');

Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(CheckStudentRole::class)->group(function () {
        //WAITING ROOM
        Route::get('/lounge', [StudentController::class, 'index'])->name('student.index');
        Route::post('/saving', [StudentController::class, 'store'])->name('image.save');
        Route::get('/start-test', [StudentController::class, 'startTest'])->name('start-test');
        Route::get('/exam/{slug}', [StudentController::class, 'showTest'])->name('exam-page');

        //STUDENT SUBMISSIONS
        Route::post('/saveListening', [StudentSubmissionController::class, 'saveListening'])->name('saveListening');
        Route::post('/saveSpeaking', [StudentSubmissionController::class, 'saveSpeaking'])->name('saveSpeaking');
        Route::post('/saveReading', [StudentSubmissionController::class, 'saveReading'])->name('saveReading');
        Route::post('/saveWriting', [StudentSubmissionController::class, 'saveWriting'])->name('saveWriting');
    });

    Route::middleware(CheckLecturerRole::class)->group(function () {
        Route::get('/index-lecturer', [IndexAdminController::class, 'index'])->name('admin.index');
        
        // INSTRUCTORS
        Route::get('/list-lecturer', [InstructorsController::class, 'index'])->name('tableLecturer.index');
        Route::get('/create-lecturer', [InstructorsController::class, 'create'])->name('createInstructor.create');
        Route::post('/create-lecturer', [AuthController::class, 'registerPost'])->name('createInstructor.store');
        Route::get('/lecturers/{slug}/edit', [InstructorsController::class, 'edit'])->name('createInstructor.edit');
        Route::put('/lecturers/{slug}', [InstructorsController::class, 'update'])->name('createInstructor.update');
        Route::delete('/lecturers/{slug}', [InstructorsController::class, 'destroy'])->name('createInstructor.destroy');
        //STUDENTS
        Route::get('/list-student', [InstructorsController::class, 'indexStudent'])->name('tableStudent.index');
        Route::get('/create-student', [InstructorsController::class, 'createStudent'])->name('createStudent.create');
        Route::get('/students/{slug}/edit', [InstructorsController::class, 'editStudent'])->name('createStudent.edit');
        Route::put('/students/{slug}', [InstructorsController::class, 'update'])->name('createStudent.update');
        Route::delete('/students/{slug}', [InstructorsController::class, 'destroy'])->name('createStudent.destroy');
        //TESTS
        Route::get('/list-test', [TestsController::class, 'index'])->name('tableTest.index');
        Route::get('/tests/create', [TestsController::class, 'create'])->name('test.create');
        Route::post('/tests', [TestsController::class, 'store'])->name('test.store');
        Route::get('/tests/{test_slug}/edit', [TestsController::class, 'edit'])->name('test.edit');
        Route::put('/tests/{test_slug}', [TestsController::class, 'update'])->name('test.update');
        Route::delete('/tests/{test_slug}', [TestsController::class, 'destroy'])->name('test.destroy');

        //EACH SKILL OF THE TEST
        Route::get('/tests/{test_slug}/skills', [TestsController::class, 'show'])->name('testSkills.show');
        //SHOW 4 PARTS OF THE SKILL FOR EACH TEST
        Route::get('/tests/{test_slug}/skills/{skill_slug}/parts', [TestsController::class, 'showDetails'])->name('test.skill.parts');

        //VIEW INPUT QUESTION FOR READING
        Route::get('/test/{test_slug}/skills/{skill_slug}/view', [TestsController::class, 'addSkillQuestions'])->name('skill.add.questions');
        Route::get('/edit-skill/{test_slug}/{skill_slug}/edit', [TestsController::class, 'editSkillQuestions'])->name('skill.edit.questions');

        //FUNCTIONS FOR SAVE AND UPDATE READING
        Route::post('/save-reading/{test_slug}/{skill_id}/reading', [ReadingController::class, 'storeReading'])->name('reading.questions.store');
        Route::put('/edit-reading/{test_slug}/{skill_slug}/update', [ReadingController::class, 'updateReading'])->name('reading.questions.update');

        //FUNCTIONS FOR SAVE AND UPDATE WRITING
        Route::post('/save-writing/{test_slug}/{skill_id}/writing', [WritingController::class, 'storeWriting'])->name('writing.questions.store');
        Route::put('/update-writing/{test_slug}/{skill_slug}/update', [WritingController::class, 'updateWriting'])->name('writing.questions.update');

        //FUNCTIONS FOR SAVE AND UPDATE LISTENING
        Route::post('/save-listening/{test_slug}/{skill_id}/listening', [ListeningController::class, 'storeListening'])->name('listening.questions.store');
        Route::put('/update-listening/{test_slug}/{skill_slug}/update', [ListeningController::class, 'updateListening'])->name('listening.questions.update');

        //FUNCTIONS FOR SAVE AND UPDATE SPEAKING
        Route::post('/save-speaking/{test_slug}/{skill_id}/speaking', [SpeakingController::class, 'storeSpeaking'])->name('speaking.questions.store');
        Route::put('/update-speaking/{test_slug}/{skill_slug}/update', [SpeakingController::class, 'updateSpeaking'])->name('speaking.questions.update');

        //FUNCTIONS FOR CREATING SKILL-PART-QUESTION
        Route::get('/create-skill-part', [SkillPartQuestionController::class, 'create'])->name('create.skill.part');

        Route::post('/store-skill-part', [SkillPartQuestionController::class, 'store'])->name('storeSkillPart');
        Route::get('/show-speaking-part/{skillName}/{partName}', [SkillPartQuestionController::class, 'showSpeakingPart'])->name('showSpeakingPart');
        Route::get('/show-reading-part/{skillName}/{partName}', [SkillPartQuestionController::class, 'showReadingPart'])->name('showReadingPart');
        Route::get('/show-writing-part/{skillName}/{partName}', [SkillPartQuestionController::class, 'showWritingPart'])->name('showWritingPart');
        Route::get('/show-listening-part/{skillName}/{partName}', [SkillPartQuestionController::class, 'showListeningPart'])->name('showListeningPart');

        //FUNCTIONS FOR STORE/SAVE QUESTION BY EACH PART
        Route::post('/store-question-writing', [SkillPartQuestionController::class, 'storeQuestionWriting'])->name('storeQuestionWriting');
        Route::post('/store-question-reading', [SkillPartQuestionController::class, 'storeQuestionReading'])->name('storeQuestionReading');
        Route::post('/store-question-listening', [SkillPartQuestionController::class, 'storeQuestionListening'])->name('storeQuestionListening');
        Route::post('/store-question-speaking', [SkillPartQuestionController::class, 'storeQuestionSpeaking'])->name('storeQuestionSpeaking');

        //FUNCTIONS FOR EDIT AND UPDATE QUESTION BY EACH PART
        Route::get('/edit-question-writing/{test_slug}/{part_name}/edit', [SkillPartQuestionController::class, 'editQuestionWriting'])->name('editQuestionWriting');
        Route::put('/update-question-writing', [SkillPartQuestionController::class, 'updateQuestionWriting'])->name('updateQuestionWriting');

        Route::get('/edit-question-reading/{test_slug}/{part_name}/edit', [SkillPartQuestionController::class, 'editQuestionReading'])->name('editQuestionReading');
        Route::put('/update-question-reading', [SkillPartQuestionController::class, 'updateQuestionReading'])->name('updateQuestionReading');

        Route::get('/edit-question-listening/{test_slug}/{part_name}/edit', [SkillPartQuestionController::class, 'editQuestionListening'])->name('editQuestionListening');
        Route::put('/update-question-listening', [SkillPartQuestionController::class, 'updateQuestionListening'])->name('updateQuestionListening');

        Route::get('/edit-question-speaking/{test_slug}/{part_name}/edit', [SkillPartQuestionController::class, 'editQuestionSpeaking'])->name('editQuestionSpeaking');
        Route::put('/update-question-speaking', [SkillPartQuestionController::class, 'updateQuestionSpeaking'])->name('updateQuestionSpeaking');
        
        //QUESTION BANK
        Route::get('/question-bank', [IndexAdminController::class, 'show'])->name('questionBank.index');
        Route::get('/question-bank-writing', [IndexAdminController::class, 'showTableOfWritingQuestionBank'])->name('questionBank.writing');
        Route::get('/question-bank-reading', [IndexAdminController::class, 'showTableOfReadingQuestionBank'])->name('questionBank.reading');
        Route::get('/question-bank-listening', [IndexAdminController::class, 'showTableOfListeningQuestionBank'])->name('questionBank.listening');
        Route::get('/question-bank-speaking', [IndexAdminController::class, 'showTableOfSpeakingQuestionBank'])->name('questionBank.speaking');
    
        Route::delete('/test_skill/{test_skill_slug}', [SkillPartQuestionController::class, 'destroy'])->name('test.skill.destroy');
    });
    
});
