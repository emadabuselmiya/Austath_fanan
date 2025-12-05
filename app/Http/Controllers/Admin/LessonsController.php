<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Helpers\MainHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Product;
use App\Models\StudentClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LessonsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            ## Read value
            $draw = $request->get('draw');
            $start = $request->get("start");
            $rowperpage = $request->get("length"); // Rows display per page

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $order_arr = $request->get('order');
            $search_arr = $request->get('search');

            $columnIndex = $columnIndex_arr[0]['column']; // Column index
            $columnName = $columnName_arr[$columnIndex]['data']; // Column name
            $columnSortOrder = $order_arr[0]['dir']; // asc or desc
            $searchValue = $search_arr['value']; // Search value

            $subject_id = $request->subject ?? null;
            $course_id = $request->course ?? null;

            // Total records
            $totalRecords = Lesson::select('count(*) as allcount')->count();
            $totalRecordswithFilter = Lesson::select('count(*) as allcount')
                ->when($subject_id, function ($query) use ($subject_id) {
                    $query->where('subject_id', '=', $subject_id);
                })
                ->when($course_id, function ($query) use ($course_id) {
                    $query->where('course_id', '=', $course_id);
                })
                ->when($searchValue, function ($query, $searchValue) {
                    $query->where('id', 'like', '%' . $searchValue . '%')
                        ->orWhere('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('description', 'like', '%' . $searchValue . '%');
                })
                ->count();

            $records = Lesson::orderBy($columnName, $columnSortOrder)
                ->when($subject_id, function ($query) use ($subject_id) {
                    $query->where('subject_id', '=', $subject_id);
                })
                ->when($course_id, function ($query) use ($course_id) {
                    $query->where('course_id', '=', $course_id);
                })
                ->when($searchValue, function ($query, $searchValue) {
                    $query->where('id', 'like', '%' . $searchValue . '%')
                        ->orWhere('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('description', 'like', '%' . $searchValue . '%');
                })
                ->select('*')
                ->skip($start)
                ->take($rowperpage)
                ->get();

            // Fetch records

            $data_arr = array();

            foreach ($records as $record) {

                $edit = '<a class="text-success" href="' . route('admin.lessons.edit', $record->id) . '">
                           <span class="iconify" data-icon="bx:edit" data-width="20" data-height="20"></span></a>';

                $delete = '<a class="text-danger" onclick="deleteForm(' . $record->id . ')"><span class="iconify" data-icon="fluent:delete-20-filled" data-width="25" data-height="25"></span></a>';

                $copy = '<a href="' . route('admin.lessons.copy', $record->id) . '" class="mx-1 my-1 text-warning">
                       <span class="iconify" data-icon="ph:copy-bold" data-width="20" data-height="20"></span></a>';

                $name = '<a class="d-flex align-items-center">
                            <div class="d-flex justify-content-start align-items-center">
                                <div class="me-3">
                                    <img
                                        onerror="this.src=\'/assets/img/default.png\'"
                                        src="' . $record->img_url . '" alt="' . $record->name . '"
                                        height="50">
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="mb-0 fw-semibold">' . $record->name . '</p>
                                </div>
                            </div>
                    </a>';

                $data_arr[] = [
                    "id" => $record->id,
                    "name" => $name,
                    "description" => $record->description,
                    "subject_id" => $record->subject?->name,
                    "course_id" => $record->course ? $record->course?->name : $record->subject->course->name,
                    "created_at" => $record->created_at->toDateTimeString(),
                    "actions" => $edit . $copy . $delete,
                ];
            }

            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $data_arr
            );

            return response()->json($response);

        }

        return view('admin.lessons.index');
    }

    public function create(Request $request)
    {
        return view('admin.lessons.create');
    }

    public function store(Request $request)
    {
        ini_set('max_execution_time', '3600'); // 1 hour
        ini_set('memory_limit', '-1');

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['required', 'string', 'max:255', 'min:3'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'video' => ['required', 'file', 'mimes:mp4,mkv,avi,mov', 'max:524288'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => MainHelper::error_processor($validator)]);
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'course_id' => $request->course_id,
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['img'] = $request->file('image')->store('uploads/lessons', 'public');
        }

        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $data['video'] = $request->file('video')->store('videos', 'public');
        }

        Lesson::create($data);

        Helpers::sendNotificationByCourse($data['course_id']);

        return response()->json([], 200);
    }

    public function copy($id)
    {
        $lesson = Lesson::findOrFail($id);
        return view('admin.lessons.copy', compact('lesson'));

    }

    public function store_copy(Request $request, $id)
    {
        ini_set('max_execution_time', '3600'); // 1 hour
        ini_set('memory_limit', '-1');

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['required', 'string', 'max:255', 'min:3'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'video' => ['nullable', 'file', 'mimes:mp4,mkv,avi,mov', 'max:524288'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => MainHelper::error_processor($validator)]);
        }

        $copy = Lesson::findOrFail($id);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'course_id' => $request->course_id,
        ];


        if (!$request->hasFile('image')) {
            $imageOld = $copy->img;
            $imageInfo = pathinfo($imageOld);
            $imageNew = 'uploads/lessons/' . uniqid() . '.' . $imageInfo['extension'];

            if (Storage::disk('public')->exists($imageOld)) {
                Storage::disk('public')->copy($imageOld, $imageNew);
                $data['img'] = $imageNew;
            }
        }

        if (!$request->hasFile('video')) {
            $videoOld = $copy->video;
            $videoInfo = pathinfo($videoOld);
            $videoNew = 'videos/' . uniqid() . '.' . $videoInfo['extension'];

            if (Storage::disk('public')->exists($videoOld)) {
                Storage::disk('public')->copy($videoOld, $videoNew);
                $data['video'] = $videoNew;
            }
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['img'] = $request->file('image')->store('uploads/lessons', 'public');
        }

        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $data['video'] = $request->file('video')->store('videos', 'public');
        }

        Lesson::create($data);
        Helpers::sendNotificationByCourse($data['course_id']);

        return response()->json([], 200);
    }

    public function edit($id)
    {
        $lesson = Lesson::findOrFail($id);
        return view('admin.lessons.edit', compact('lesson'));
    }

    public function update(Request $request, $id)
    {
        ini_set('max_execution_time', '3600'); // 1 hour
        ini_set('memory_limit', '-1');

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['required', 'string', 'max:255', 'min:3'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'video' => ['nullable', 'file', 'mimes:mp4,mkv,avi,mov', 'max:524288'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => MainHelper::error_processor($validator)]);
        }

        $lesson = Lesson::findOrFail($id);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'course_id' => $request->course_id,
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($lesson->img && Storage::disk('public')->exists($lesson->img)) {
                Storage::disk('public')->delete($lesson->img);
            }
            $data['img'] = $request->file('image')->store('uploads/lessons', 'public');
        }

        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            if ($lesson->video && Storage::disk('public')->exists($lesson->video)) {
                Storage::disk('public')->delete($lesson->video);
            }
            $data['video'] = $request->file('video')->store('videos', 'public');
        }

        $lesson->update($data);

        return response()->json([], 200);
    }

    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        if ($lesson->img && Storage::disk('public')->exists($lesson->img)) {
            Storage::disk('public')->delete($lesson->img);
        }
        if ($lesson->video && Storage::disk('public')->exists($lesson->video)) {
            Storage::disk('public')->delete($lesson->video);
        }

        $lesson->delete();

        return redirect()->back()->with('success', 'تم حذف الدرس بنجاح');
    }
}
