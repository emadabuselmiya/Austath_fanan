<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\CentralLogics\NotificationLogic;
use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data_search = [
                'status' => $request->status,
            ];

            $addons = PushNotification::when($data_search['status'], function ($query) use ($data_search) {
                $query->where('status', '=', $data_search['status']);
            })->latest()->get();

            return datatables()->of($addons)
                ->editColumn('created_at', function (PushNotification $notification) {
                    return $notification->created_at->toDateTimeString();
                })->editColumn('status', function (PushNotification $notification) {
                    if ($notification->status == 1) {
                        $text = '<span class="badge bg-label-success">' . translate('ارسال فوري') . '</span>';

                    } else {
                        $text = '<span class="badge bg-label-info">' . translate('اشعار مجدول') . '</span>';
                        $text .= '<br><span class="badge bg-label-warning">' . Carbon::parse($notification->schedule_at)->format('Y-m-d g:i A') . '</span>';

                        if ($notification->is_sent == 1) {
                            $text .= '<br><span class="badge bg-label-success">' . translate('مرسل') . '</span>';
                        }

                    }
                    return $text;
                })
                ->editColumn('image', function (PushNotification $notification) {
                    if ($notification->image) {
                        return '<img src="' . $notification->image_url . '" alt="' . $notification->title . '" height="50" width="50">';

                    } else {
                        return '';
                    }
                })
                ->rawColumns(['actions', 'image', 'status'])
                ->addIndexColumn()
                ->make(true);
        }


        return view('admin.notifications.index');
    }

    public function create()
    {
        return view('admin.notifications.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:191',
            'description' => 'required|max:1000',
            'url' => 'required_if:type,url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $notification = new PushNotification();
        $notification->title = $request->title;
        $notification->description = $request->description;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $notification->image = $request->file('image')->store('notifications', 'public');
        }
        $notification->url = $request->url;
        $notification->save();

        $data = [
            'title' => str_replace('"', "'", $notification->title),
            'description' => str_replace('"', "'", $notification->description),
            'image_url' => $notification->image_url,
            'url' => $notification->url,
        ];

        NotificationLogic::send_push_notif_to_topic($data, 'all_student');

        return response()->json([], 200);
    }

}
