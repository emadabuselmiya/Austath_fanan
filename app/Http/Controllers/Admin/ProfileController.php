<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{

    public function index()
    {
        return view('admin.profile');
    }

    public function updatePassword(Request $request)
    {
        $user = auth('admin')->user();
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'], // password_confirmation
        ]);
        if (Hash::check($request->input('current_password'), $user->password)) {

            $user->update([
                'password' => Hash::make($request->input('password')),
            ]);
            $message = ['success' => 'تمت العملية بنجاح'];
        } else {
            $message = ['error' => 'كلمة المرور غير صحيحة'];
        }

        return redirect()->route('admin.profile.index')->with($message);
    }

    public function updatePersonal(Request $request)
    {
        $user = auth('admin')->user();

        $request->validate([
            'password' => ['required', 'string', 'min:8'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'max:255', "unique:admins,phone,".$user->id],
        ]);
        $data = $request->except('image', 'password');
        if (Hash::check($request->password, $user->password)) {
            $user->update($data);

            $message = ['success' => 'تمت العملية بنجاح'];
        } else {
            $message = ['error' => 'كلمة المرور غير صحيحة'];
        }

        return redirect()->route('admin.profile.index')->with($message);
    }



}




