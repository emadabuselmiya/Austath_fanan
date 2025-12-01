<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MainHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LessonsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $classes = Course::withCount(['subjects'])->get();

            return datatables()->of($classes)
                ->editColumn('image', function (Course $course) {
                    return '<img src="' . $course->img_url . '" alt="' . $course->name . '" height="50" width="50">';
                })
                ->addColumn('actions', function (Course $course) {

                    $edit = ' <a href = "javascript:void(0);" id="editForm" onclick="editForm(' . $course->id . ',\'' . $course->name . '\',\'' . $course->class_id . '\',\'' . $course->img_url . '\',\'' . $course->demo_url . '\')"
                            class="text-secondary"><span class="iconify" data-icon="bx:edit" data-width="20" data-height="20"></span></a> ';
                    return $edit;
                })
                ->rawColumns(['actions', 'image', 'name'])
                ->addIndexColumn()
                ->make(true);

        }

        return view('admin.courses.index');
    }

    public function store(Request $request)
    {
        if ($request->course_id != -1) {
            return $this->update($request, $request->course_id);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'class_id' => ['required', 'exists:students_classes,id'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'video' => 'nullable|file|mimes:mp4,mkv,avi,mov|max:524288',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => MainHelper::error_processor($validator)]);
        }

        $student_class = StudentClass::find($request->class_id);

        $data = [
            'name' => $request->name,
            'type' => $student_class->name,
            'class_id' => $request->class_id,
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['img'] = $request->file('image')->store('uploads/courses/', 'public');
        }

        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $data['demo'] = $request->file('video')->store('demo/', 'public');
        }

        Course::create($data);


        return response()->json([], 200);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'class_id' => ['required', 'exists:students_classes,id'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'video' => 'nullable|file|mimes:mp4,mkv,avi,mov|max:524288',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => MainHelper::error_processor($validator)]);
        }

        $course = Course::findOrFail($id);
        $student_class = StudentClass::find($request->class_id);

        $data = [
            'name' => $request->name,
            'type' => $student_class->name,
            'class_id' => $request->class_id,
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($course->img && Storage::disk('public')->exists($course->img)) {
                Storage::disk('public')->delete($course->img);
            }
            $data['img'] = $request->file('image')->store('uploads/courses/', 'public');
        }

        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            if ($course->demo && Storage::disk('public')->exists($course->demo)) {
                Storage::disk('public')->delete($course->demo);
            }
            $data['demo'] = $request->file('video')->store('demo/', 'public');
        }

        $course->update($data);

        return response()->json([], 200);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        if ($course->img && Storage::disk('public')->exists($course->img)) {
            Storage::disk('public')->delete($course->img);
        }
        if ($course->demo && Storage::disk('public')->exists($course->demo)) {
            Storage::disk('public')->delete($course->demo);
        }

        $course->delete();

        return redirect()->back()->with('success', 'تم حذف الدورة بنجاح');
    }
}
