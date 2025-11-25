<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\courseController;
use App\Http\Controllers\subjectController;
use App\Http\Controllers\lessonController;
use App\Http\Controllers\ActivationCodeController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\StudentClassController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\GeneralDataController;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/signup', [Controller::class, 'signUp']);
Route::post('/signin', [Controller::class, 'signIn']);
Route::get("/privacy-policy", [Controller::class, 'policy']);
Route::get('/', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about');
});



Route::get('/contact', function () {
    return view('contact');
});
Route::get('/get-classes', [StudentClassController::class, 'getClasses']);
Route::post('/reset-password', [Controller::class, 'sendResetPasswordCode']);
Route::post('/update-password', [Controller::class, 'updateUserPassword']);
Route::post('/email-confirmation', [Controller::class, 'emailConfirmation']);
Route::post('/delete-account-hfg', [Controller::class, 'deleteUser']);

Route::middleware('api.token')->group(function () {
    Route::post('/courses', [courseController::class, 'store']);

    Route::post('/subjects', [subjectController::class, 'store']);
    Route::delete('/subject/{subject_id}', [SubjectController::class, 'delete']);
    Route::post('/lessons', [lessonController::class, 'store']);
    Route::post('/copy-lesson', [lessonController::class, 'moveLesson']);
    Route::delete('/lesson/{lesson_id}', [LessonController::class, 'deleteLesson']);
    Route::post('/teachers', [TeacherController::class, 'store']);
    Route::delete('/teacher/{teacherId}', [TeacherController::class, 'deleteTeacher']);
    Route::delete('/branch', [BranchController::class, 'destroy']);
    Route::put('/branch', [BranchController::class, 'update']);
    Route::post('/branch', [BranchController::class, 'store']);
    Route::post('/classes', [StudentClassController::class, 'store']);
    Route::delete('/delete-classes', [StudentClassController::class, 'destroy']);
    Route::post('/class-update', [StudentClassController::class, 'update']);
    Route::post('/sale', [SaleController::class, 'store']);
    Route::post('/sale-update', [SaleController::class, 'update']);
    Route::delete('/attachments/{id}', [AttachmentController::class, 'destroy']);
    Route::delete('/subjects', [subjectController::class, 'destroy']);
    Route::delete('/course', [courseController::class, 'destroy']);
    Route::put('/course', [courseController::class, 'update']);
    Route::put('/teacher', [TeacherController::class, 'update']);
    Route::post('/update-subject', [subjectController::class, 'update']);
    Route::post('/update-lesson', [lessonController::class, 'updateLesson']);
    Route::post('/attachments', [AttachmentController::class, 'store']);
    Route::get('/course/video/{id}', [courseController::class, 'getCourseDemo']);
    Route::post('/subject/lessons/reorder/{subject_id}', [subjectController::class, 'saveLessonsOrder']);
    Route::post('/course/subjects/reorder/{course_id}', [courseController::class, 'saveSubjectsOrder']);
    Route::post('/class/courses/reorder/{class_id}', [StudentClassController::class, 'saveCoursesOrder']);


});


Route::get('/get-courses', [courseController::class, 'getCourses']);
Route::get('/get-course-subjects/{course_id}', [SubjectController::class, 'getCourseSubjects']);
Route::get('/get-lesson/{lesson_id}', [LessonController::class, 'getLesson']);
Route::get('/get-subject-lessons/{subject_id}', [subjectController::class, 'getSubjectLessons']);
Route::post('/activate-code', [ActivationCodeController::class, 'activateCode']);
Route::get('/get-teachers', [TeacherController::class, 'getTeachers']);
Route::get('/get-branches', [BranchController::class, 'getBranches']);




Route::get('/sale', [SaleController::class, 'getData']);




Route::get('/lessons/{lessonId}/attachments', [AttachmentController::class, 'index']);
Route::get('comments/{lesson_id}', [CommentController::class, 'index']);
Route::post('comments', [CommentController::class, 'store']);
Route::put('comments/{id}', [CommentController::class, 'update']);
Route::delete('comments/{id}', [CommentController::class, 'destroy']);
Route::get('/get-class-courses/{class_id}', [StudentClassController::class, 'getCoursesForClass']);
Route::get('/check-activation', [Controller::class, 'checkActivation']);


Route::post("/course/activation/check", [ActivationCodeController::class, 'checkCourseActivation']);
Route::post("/course/activate", [ActivationCodeController::class, 'activateCourse']);
Route::post("/about/update", [GeneralDataController::class, 'editAbout']);

Route::get('/general/data', [GeneralDataController::class, 'getData']);

// Logout route
Route::post('/logout', function (Request $request) {
    // // Get the token from the request header
    // $token = $request->header('Authorization');

    // if (!$token) {
    //     return response()->json(['error' => 'Token not provided'], 401);
    // }

    // // Remove "Bearer " from the token
    // $token = str_replace('Bearer ', '', $token);

    // // Find the user with the given token
    // $user = User::where('remember_token', $token)->first();

    // if (!$user) {
    //     return response()->json(['error' => 'Invalid token'], 401);
    // }

    //   if($user->email != "baraa5hfg@gmail.com"){
    //    // Clear the user's API token
    // $user->remember_token = null; 
    // $user->save();
    //   }
    // Return success response
    return response()->json(['message' => 'Logged out successfully'], 200);
});

Route::post("/video/portal/admin", [Controller::class, "loginPortal"])->name("login")->middleware('web');
Route::get("/video/portal/admin/form", [Controller::class, "loadLoginPortal"])->name("load.login");
Route::get("/admin/video/protal", [Controller::class, "loadPortal"]);

// In routes/web.php
Route::get('/download/video/{lesson}', function($lessonId) {
    try{
        $lesson = \App\Models\Lesson::findOrFail($lessonId);
    
    // Since videos are in storage/app/public/videos
    $videoPath =  $lesson->video;
    
    if (!$lesson->video || !Storage::disk('public')->exists($videoPath)) {
        abort(404, 'Video not found');
    }
    
    return Storage::disk('public')->download($videoPath, $lesson->name . '.mp4');
    }catch(\Exception $ex){
        \Log::error($ex);
        return  response()->json(["error" =>$ex], 500);

    }
   
})->name('download.video');

