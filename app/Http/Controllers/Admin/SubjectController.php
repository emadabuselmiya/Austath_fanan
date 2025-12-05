<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Helpers\MainHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Product;
use App\Models\StudentClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $classes = Subject::withCount(['lessons'])->get();

            return datatables()->of($classes)
                ->editColumn('course', function (Subject $subject) {
                    return $subject->course?->name;
                })
                ->addColumn('actions', function (Subject $subject) {
                    $show = '<a href="' . route('admin.lessons.index', ['subject' => $subject->id]) . '" class="text-success">
                            <span class="iconify" data-icon="mdi:show-outline" data-width="20" data-height="20"></span></a>';

                    $edit = ' <a href = "javascript:void(0);" id="editForm" onclick="editForm(' . $subject->id . ',\'' . $subject->name . '\',\'' . $subject->description . '\',\'' . $subject->course_id . '\')"
                            class="text-secondary"><span class="iconify" data-icon="bx:edit" data-width="20" data-height="20"></span></a> ';
                    return $edit . $show;
                })
                ->rawColumns(['actions', 'image', 'name'])
                ->addIndexColumn()
                ->make(true);

        }

        return view('admin.subjects.index');
    }

    public function store(Request $request)
    {
        if ($request->subject_id != -1) {
            return $this->update($request, $request->subject_id);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['required', 'string', 'max:255', 'min:3'],
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => MainHelper::error_processor($validator)]);
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'course_id' => $request->course_id,
        ];

        Subject::create($data);

        return response()->json([], 200);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['required', 'string', 'max:255', 'min:3'],
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => MainHelper::error_processor($validator)]);
        }

        $subject = Subject::findOrFail($id);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'course_id' => $request->course_id,
        ];

        $subject->update($data);

        return response()->json([], 200);
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->back()->with('success', 'تم حذف الوحدة بنجاح');
    }

    public function get_subjects(Request $request)
    {
        $key = explode(' ', $request->q);
        $data = Subject::when($request->course, function ($q) use ($request) {
            $q->where('course_id', $request->course);
            })->when($request->q, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            });

        if ($request->ispPages == 1) {
            $data = $data->limit(100)->get();
        } else {
            $data = $data->get();
        }

        $filter_data = [];
        foreach ($data as $item) {
            $filter_data[] = [
                'id' => $item->id,
                'text' => $item->name ,
            ];
        }

        return response()->json($filter_data);
    }

}
