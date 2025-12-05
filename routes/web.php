<?php

use App\CentralLogics\NotificationLogic;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ActivationCodeController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\StudentClassController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\GeneralDataController;
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

Route::post('/signup', [AuthController::class, 'signUp']);
Route::post('/signin', [AuthController::class, 'signIn']);
Route::get("/privacy-policy", [AuthController::class, 'policy']);
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
Route::post('/reset-password', [AuthController::class, 'sendResetPasswordCode']);
Route::post('/update-password', [AuthController::class, 'updateUserPassword']);
Route::post('/email-confirmation', [AuthController::class, 'emailConfirmation']);
Route::post('/delete-account-hfg', [AuthController::class, 'deleteUser']);

Route::middleware('api.token')->group(function () {
    Route::post('/courses', [CourseController::class, 'store']);
    Route::post('/update_fcm_token', [AuthController::class, 'update_fcm_token']);

    Route::post('/subjects', [SubjectController::class, 'store']);
    Route::delete('/subject/{subject_id}', [SubjectController::class, 'delete']);
    Route::delete('/subjects', [SubjectController::class, 'destroy']);

    Route::post('/lessons', [LessonController::class, 'store']);
    Route::post('/copy-lesson', [LessonController::class, 'moveLesson']);
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
    Route::delete('/course', [CourseController::class, 'destroy']);
    Route::put('/course', [CourseController::class, 'update']);
    Route::put('/teacher', [TeacherController::class, 'update']);
    Route::post('/update-lesson', [LessonController::class, 'updateLesson']);
    Route::post('/attachments', [AttachmentController::class, 'store']);
    Route::get('/course/video/{id}', [CourseController::class, 'getCourseDemo']);
    Route::post('/subject/lessons/reorder/{subject_id}', [SubjectController::class, 'saveLessonsOrder']);
    Route::post('/course/subjects/reorder/{course_id}', [CourseController::class, 'saveSubjectsOrder']);
    Route::post('/class/courses/reorder/{class_id}', [StudentClassController::class, 'saveCoursesOrder']);
});


Route::get('/get-courses', [CourseController::class, 'getCourses']);
Route::get('/get-course-subjects/{course_id}', [SubjectController::class, 'getCourseSubjects']);
Route::get('/get-lesson/{lesson_id}', [LessonController::class, 'getLesson']);
Route::get('/get-subject-lessons/{subject_id}', [SubjectController::class, 'getSubjectLessons']);
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
Route::get('/check-activation', [AuthController::class, 'checkActivation']);


Route::post("/course/activation/check", [ActivationCodeController::class, 'checkCourseActivation']);
Route::post("/course/activate", [ActivationCodeController::class, 'activateCourse']);
Route::post("/about/update", [GeneralDataController::class, 'editAbout']);

Route::get('/general/data', [GeneralDataController::class, 'getData']);

// Logout route
Route::post('/logout', function (Request $request) {
    return response()->json(['message' => 'Logged out successfully'], 200);
});

Route::post("/video/portal/admin", [AuthController::class, "loginPortal"])->name("login")->middleware('web');
Route::get("/video/portal/admin/form", [AuthController::class, "loadLoginPortal"])->name("load.login");
Route::get("/admin/video/protal", [AuthController::class, "loadPortal"]);

// In routes/web.php
Route::get('/download/video/{lesson}', function ($lessonId) {
    try {
        $lesson = \App\Models\Lesson::findOrFail($lessonId);

        // Since videos are in storage/app/public/videos
        $videoPath = $lesson->video;

        if (!$lesson->video || !Storage::disk('public')->exists($videoPath)) {
            abort(404, 'Video not found');
        }

        return Storage::disk('public')->download($videoPath, $lesson->name . '.mp4');
    } catch (\Exception $ex) {
        \Log::error($ex);
        return response()->json(["error" => $ex], 500);

    }

})->name('download.video');


Route::get('/test', function () {
    $data = [
        'title' => 'test',
        'description' => 'test',
        'image_url' => '',
        'url' => 'https://austathfanan.com',
    ];
    $fcm = "diAVmCiUQqK7y_0bVC2LiJ:APA91bGKuX6rfUIIN-0F52TkviiIyXQI7leX6Be8ZmN7e-sFrFzS_Jtw1_sBEt0NsK_RyY7ceN1Ov9-PGkR10N9E52GgAdCFlAmXWKB_5JcsjyDp09lyOKI";
    NotificationLogic::send_push_notif_to_device($fcm, $data);
//    NotificationLogic::send_push_notif_to_topic($data, 'all_student');
});
