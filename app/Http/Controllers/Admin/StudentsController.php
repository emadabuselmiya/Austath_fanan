<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

                $data_arr[] = [
                    "id" => $record->id,
                    "name" => $record->name,
                    "email" => $record->email,
                    "class_id" => $record->class?->name,
                    "activeCourses" => $record->activeCourses()->count(),
                    "actions" => $show,
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
}
