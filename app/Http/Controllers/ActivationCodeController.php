<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\ActivationCode;
use App\Models\StudentCourseActivation;

use Illuminate\Support\Facades\Auth;

class ActivationCodeController extends Controller
{

    public function activateCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:8',
            'user_id' => 'required|integer|exists:users,id', // Ensure user_id is an integer and exists in the users table
        ]);
        $code = ActivationCode::where('code', strtoupper($request->input('code')))
            ->where('expired_at', '<=', now()->toDateString())->first();

        if (!$code) {
            return response()->json(['error' => 'Invalid activation code.'], 400);
        }

        if ($code->is_used) {
            return response()->json(['error' => 'This code has already been used.'], 400);
        }
        $user = User::find(id: $request->input('user_id'));
        if (!$user) {
            return response()->json(['error' => 'user not found'], 404);

        }
        $user->verified = true;
        $user->save();
        $code->is_used = true;
        $code->user_id = $request->input('user_id');
        $code->save();

        return response()->json(['message' => 'Code activated successfully.'], 200);
    }


    public function activateCourse(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:8',
            'user_id' => 'required|integer|exists:users,id', // Ensure user_id is an integer and exists in the users table
            'course_id' => 'required|integer|exists:courses,id', // Ensure course_id is an integer and exists in the courses table
        ]);

        $code = ActivationCode::where('code', strtoupper($request->input('code')))->first();

        if (!$code) {
            return response()->json(['error' => 'الكود غير صحيح الرجاء ادخال كود معتمد'], 400);
        }

        if ($code->is_used) {
            return response()->json(['error' => 'هذا الكود مستخدم من قبل الرجاء استخدام كود اخر , يمكنك التواصل معنا للحصول على كود جديد'], 400);
        }

        $user = User::find($request->input('user_id'));
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $course = Course::find($request->input('course_id'));
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        StudentCourseActivation::create([
            'student_id' => $request->user_id,
            'course_id' => $request->course_id,
            'activation_code_id' => $code->id,
            'status' => true
        ]);

        $code->is_used = true;
        $code->user_id = $request->input('user_id');
        $code->save();

        return response()->json(['message' => 'تم تفعيل المادة بنجاح'], 200);
    }

    public function checkCourseActivation(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id', // Ensure user_id is an integer and exists in the users table
            'course_id' => 'required|integer|exists:courses,id', // Ensure course_id is an integer and exists in the courses table
        ]);

        $user = User::find($request->input('user_id'));
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $course = Course::find($request->input('course_id'));
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $activation = StudentCourseActivation:: where('student_id', $request->input('user_id'))
            ->where('course_id', $request->input('course_id'))
            ->where('status', true)
            ->first();

        if ($activation) {
            return response()->json(['message' => 'Course is already activated for this user.'], 200);
        } else {
            return response()->json(['message' => 'لا يمكنك الدخول الى هذه المادة الرجاء الاشتراك اولا '], 400);
        }

    }


}
