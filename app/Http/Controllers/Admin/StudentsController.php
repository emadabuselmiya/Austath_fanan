<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\StudentClass;
use App\Models\StudentCourseActivation;
use App\Models\User;
use Illuminate\Http\Request;

class StudentsController extends Controller
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

            // Total records
            $totalRecords = User::select('count(*) as allcount')->count();
            $totalRecordswithFilter = User::select('count(*) as allcount')
                ->when($searchValue, function ($query, $searchValue) {
                    $query->where('id', 'like', '%' . $searchValue . '%')
                        ->orWhere('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('email', 'like', '%' . $searchValue . '%');
                })
                ->count();

            $records = User::orderBy($columnName, $columnSortOrder)
                ->when($searchValue, function ($query, $searchValue) {
                    $query->where('id', 'like', '%' . $searchValue . '%')
                        ->orWhere('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('email', 'like', '%' . $searchValue . '%');
                })
                ->select('*')
                ->skip($start)
                ->take($rowperpage)
                ->get();

            // Fetch records

            $data_arr = array();

            foreach ($records as $record) {

                $show = '<a class="text-success" href="javascript:void(0);" onclick="quick_view(' . $record->id . ')">
                           <span class="iconify" data-icon="ic:baseline-remove-red-eye" data-width="20" data-height="20"></span></a>';

                $delete = '<a class="text-danger" onclick="deleteForm(' . $record->id . ')"><span class="iconify" data-icon="fluent:delete-20-filled" data-width="25" data-height="25"></span></a>';

                $classes = StudentClass::get();
                $class_id = '<select name="class_id" id="class_id" class="form-control select2" onchange="updateClass(' . $record->id . ',this.value)">';
                $class_id .= '<option value="" ></option>';
                foreach ($classes as $class) {
                    $class_id .= '<option value="' . $class->id . '" ' . ($record->class_id == $class->id ? 'selected' : '') . '>' . $class->name . '</option>';

                }
                $class_id .= '</select>';

                $data_arr[] = [
                    "id" => $record->id,
                    "name" => $record->name . $record->verified == 2 ? '(محذوف)' : '',
                    "email" => $record->email,
                    "class_id" => $class_id,
                    "activeCourses" => $record->activeCourses()->count(),
                    "actions" => $show . $delete,
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

        return view('admin.students.index');
    }

    public function show($id)
    {
        $student = User::findOrFail($id);

        return response()->json([
            'success' => 1,
            'view' => view('admin.students._quick-view', compact('student'))->render(),
        ]);
    }

    public function editCode($id)
    {
        $active = StudentCourseActivation::findOrFail($id);

        return response()->json([
            'success' => 1,
            'view' => view('admin.students._update-code', compact('active'))->render(),
        ]);
    }

    public function updateCode(Request $request)
    {
        $active = StudentCourseActivation::findOrFail($request->active_id);
        $active->update([
            'course_id' => $request->course_id,
        ]);

        return response()->json([], 200);
    }

    public function toggle_settings_status(User $user, Request $request)
    {

        $user[$request->menu] = $request->status;

        $user->save();

        return redirect()->back()->with('success', translate('تم تعديل الحالة بنجاح'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->update(['verified' => 2]);

        return redirect()->back()->with('success', translate('تم حذف الطالب بنجاح'));
    }
}
