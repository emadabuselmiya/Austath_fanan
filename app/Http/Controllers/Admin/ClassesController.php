<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MainHelper;
use App\Http\Controllers\Controller;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;

class ClassesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $classes = StudentClass::withCount(['courses'])->get();

            return datatables()->of($classes)
                ->addColumn('actions', function (StudentClass $class) {

                    $edit = ' <a href = "javascript:void(0);" id="editForm" onclick="editForm(' . $class->id . ',\'' . $class->name . '\')"
                            class="text-secondary"><span class="iconify" data-icon="bx:edit" data-width="20" data-height="20"></span></a> ';
                    return $edit;
                })
                ->rawColumns(['actions', 'image', 'name'])
                ->addIndexColumn()
                ->make(true);

        }

        return view('admin.classes.index');
    }

    public function store(Request $request)
    {
        if ($request->class_id != -1) {
            return $this->update($request, $request->class_id);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'min:3'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => MainHelper::error_processor($validator)]);
        }

        StudentClass::create(['name' => $request->name]);

        return response()->json([], 200);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'min:3'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => MainHelper::error_processor($validator)]);
        }

        $category = StudentClass::findOrFail($id);

        $category->update(['name' => $request->name]);

        return response()->json([], 200);
    }

    public function destroy($id)
    {
        $admin = StudentClass::findOrFail($id);
        $admin->delete();

        return redirect()->back()->with('success', 'تم حذف الفصل بنجاح');
    }
}
