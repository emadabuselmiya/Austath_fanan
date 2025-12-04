<?php

namespace App\Http\Controllers;

use App\Mail\EmailConfirmation;
use App\Mail\ResetPasswordMail;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function signIn(Request $request)
    {
        $user = User::where("email", $request->email)->first();
        if ($user) {
            if ($user->verified == 2) {
                return response()->json(["message" => "لقد تم حذف هذا الحساب الرجاء انشاء حساب بايميل مختلف"], 200);
            }
        }
        // Attempt to authenticate the user
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            // Authentication passed
            $user = Auth::user();
            if ($user->email != "baraa5hfg@gmail.com") {
                $token = Str::random(60);
                $user->remember_token = $token;
                $user->save();
            }

            return response()->json([
                'message' => 'Successfully signed in',
                'user' => $user
            ], 200);


        } else {
            // Authentication failed
            return response()->json([
                'message' => 'كلمة المرور او الايميل غير صحيح'
            ], 401);
        }
    }

    public function signOut()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully signed out'
        ]);
    }

    public function signUp(Request $request)
    {

        $user = User::where("email", $request->email)->first();
        if ($user) {
            if ($user->verified == 2) {
                return response()->json(["message" => "لقد تم حذف هذا الحساب الرجاء انشاء حساب بايميل مختلف"], 200);
            }
            return response()->json(["message" => "هذا الايميل مستخدم الرجاء تسجيل الدخول"], 200);
        }
        // Create a new user in the database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'class_id' => $request->class_id,
            'verified' => $request->verified,
            'uuid' => $request->uuid,
        ]);
        $token = Str::random(60);
        $user->remember_token = $token;
        $user->save();
        Auth::login($user);

        return response()->json([
            'message' => 'Successfully signed up',
            'user' => $user,
            'token' => $user->token,
        ], 200);
    }

    public function sendResetPasswordCode(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $resetCode = rand(100000, 999999);
        Mail::to($user->email)->send(new ResetPasswordMail($resetCode));

        return response()->json(['message' => 'Reset code sent to your email', 'code' => $resetCode], 200);
    }

    public function updateUserPassword(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            return response()->json(['message' => 'user not found'], 200);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();
        return response()->json(['message' => 'user password updated successfully'], 200);
    }

    public function emailConfirmation(Request $request)
    {
        $user = User::where("email", $request->email)->first();
        if ($user) {
            if ($user->verified == 2) {
                return response()->json("لقد تم حذف هذا الحساب الرجاء انشاء حساب بايميل مختلف", 400);
            }
            return response()->json("هذا الايميل مستخدم الرجاء تسجيل الدخول", 400);
        }

        $resetCode = rand(100000, 999999);
        Mail::to($request->input('email'))->send(new EmailConfirmation($resetCode));
        return response()->json(['message' => 'Confirmation code sent to your email', 'code' => $resetCode], 200);

    }

    public function checkActivation(Request $request)
    {
        $user = User::findOrFail($request->id);
        if ($user) {
            return response()->json(["verified" => $user->verified]);
        }
    }

    public function policy()
    {
        return view('privacy-bolicy');
    }

    public function deleteUser(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);
            $user->verified = 2;
            $user->save();
            return response()->json("لقد تم حذف حساب المستخدم بنجاح", 200);
        } catch (Exception $exception) {
            \Log::error($exception->getMessage());
            return response()->json(["error" => $exception->getMessage()], 500);
        }
    }

    public function loginPortal(Request $request)
    {

        // Validate the incoming request
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if ($request->email != "baraa5hfg@gmail.com") {
            return response()->json([
                'message' => 'Access forbidden'
            ], 403);
        }

        // Add remember me option if needed
        $remember = $request->boolean('remember');

        // Attempt to authenticate the user
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            // Redirect to intended page or dashboard
            return redirect()->intended('/admin/video/protal')->with('success', 'Welcome back!');
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function loadLoginPortal()
    {
        try {
            return view('login');

        } catch (Exception $ex) {
            return response()->json(["error" => $ex], 500);
        }

    }

    public function loadPortal()
    {
        if (!Auth::check()) {
            abort(404, "not found");
        } else if (auth()->user()->email != "baraa5hfg@gmail.com") {
            abort(404, "not found");

        } else {
            $courses = Course::with(['subjects.lessons', 'studentClass'])->get();
            return view("video-portal", compact('courses'));
        }

    }

    public function update_fcm_token(Request $request)
    {
        User::where('id', auth()->id())->update([
            'fcm_token' => $request['fcm_token']
        ]);

        return response()->json(['status' => true, 'message' => 'successfully updated!'], 200);
    }

}
