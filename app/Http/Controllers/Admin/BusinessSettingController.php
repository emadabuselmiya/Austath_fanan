<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BusinessSettingController extends Controller
{
    public function preview(Request $request, $tab = 'info')
    {
        if ($tab == 'info') {
            return view('admin.business-setting.general');
        } else if ($tab == 'mail') {
            return view('admin.business-setting.mail');
        } else if ($tab == 'recaptcha') {
            return view('admin.business-setting.recaptcha');
        } else {
            return view('admin.business-setting.general');
        }
    }

    public function general_update(Request $request)
    {
        $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:svg,jpg,jpeg,png'],
            'fav_icon' => ['nullable', 'image', 'mimes:svg,jpg,jpeg,png'],
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'business_name'], [
            'value' => $request['business_name']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'phone'], [
            'value' => $request['phone']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'email'], [
            'value' => $request['email']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'tax_percentage'], [
            'value' => $request['tax_percentage']
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'languages'], [
            'value' => json_encode($request['languages'])
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'payment_periods'], [
            'value' => json_encode($request['payment_periods'])
        ]);


        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $logo = get_business_settings('logo', false);
            if ($logo) {
                Storage::disk('public')->delete($logo);
            }
            $logo = $request->file('logo')->store('business', 'public');

            DB::table('business_settings')->updateOrInsert(['key' => 'logo'], [
                'value' => $logo
            ]);
        }


        if ($request->hasFile('fav_icon') && $request->file('fav_icon')->isValid()) {
            $fav_icon = get_business_settings('fav_icon', false);
            if ($fav_icon) {
                Storage::disk('public')->delete($fav_icon);
            }

            $fav_icon = $request->file('fav_icon')->store('business', 'public');

            DB::table('business_settings')->updateOrInsert(['key' => 'fav_icon'], [
                'value' => $fav_icon
            ]);
        }


        return redirect()->back()->with('success', translate('Settings saved successfully'));
    }

    public function mail_config(Request $request)
    {
        BusinessSetting::updateOrInsert(['key' => 'mail_config'], [
                'value' => json_encode([
                    "status" => $request['status'] ?? 0,
                    "name" => $request['name'],
                    "host" => $request['host'],
                    "driver" => $request['driver'],
                    "port" => $request['port'],
                    "username" => $request['username'],
                    "email_id" => $request['email'],
                    "encryption" => $request['encryption'],
                    "password" => $request['password']
                ]),
                'updated_at' => now()
            ]
        );

        return redirect()->back()->with('success', translate('Settings saved successfully'));
    }

    public function recaptcha_update(Request $request)
    {

        DB::table('business_settings')->updateOrInsert(['key' => 'recaptcha'], [
            'value' => json_encode([
                'status' => $request['status'],
                'site_key' => $request['site_key'],
                'secret_key' => $request['secret_key'],
            ]),
            'updated_at' => now(),
        ]);


        return redirect()->back()->with('success', translate('Settings saved successfully'));
    }
}
