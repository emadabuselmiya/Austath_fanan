<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivationCode;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class CodesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data_search = [
                'is_used' => $request->is_used,
            ];

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
            $totalRecords = ActivationCode::select('count(*) as allcount')->count();
            $totalRecordswithFilter = ActivationCode::select('count(*) as allcount')
                ->when(!is_null($data_search['is_used']), function ($query) use ($data_search) {
                    $query->where('is_used', $data_search['is_used']);
                })
                ->when($searchValue, function ($query, $searchValue) {
                    $query->where('id', 'like', '%' . $searchValue . '%')
                        ->orWhere('code', 'like', '%' . $searchValue . '%')
                        ->whereHas('user', function ($query) use ($searchValue) {
                            $query->orWhere('name', 'like', '%' . $searchValue . '%');
                        });
                })
                ->count();

            $records = ActivationCode::orderBy($columnName, $columnSortOrder)
                ->when(!is_null($data_search['is_used']), function ($query) use ($data_search) {
                    $query->where('is_used', $data_search['is_used']);
                })
                ->when($searchValue, function ($query, $searchValue) {
                    $query->where('id', 'like', '%' . $searchValue . '%')
                        ->orWhere('code', 'like', '%' . $searchValue . '%')
                        ->whereHas('user', function ($query) use ($searchValue) {
                            $query->orWhere('name', 'like', '%' . $searchValue . '%')
                                ->orWhere('email', 'like', '%' . $searchValue . '%');
                        });
                })
                ->select('*')
                ->skip($start)
                ->take($rowperpage)
                ->get();

            // Fetch records

            $data_arr = array();

            foreach ($records as $record) {

                $delete = '<a class="text-danger" onclick="deleteForm(' . $record->id . ')"><span class="iconify" data-icon="fluent:delete-20-filled" data-width="25" data-height="25"></span></a>';

                $data_arr[] = [
                    "id" => $record->id,
                    "code" => $record->code,
                    "is_used" => $record->is_used ? '<span class="badge bg-label-success font-regular">مستخدم</span>' : '<span class="badge bg-label-danger font-regular">غير مستخدم</span>',
                    "user_id" => $record->user?->name,
                    "actions" => $delete,
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

        return view('admin.codes.index');
    }

    public function destroy($id)
    {
        $admin = ActivationCode::findOrFail($id);
        $admin->delete();

        return redirect()->back()->with('success', 'تم حذف الكود بنجاح');
    }

    public function codes_export(Request $request)
    {
        ini_set('max_execution_time', '3600'); // 1 hour
        ini_set('memory_limit', '-1');

        $data = ActivationCode::where('is_used', 0)
            ->select('code')
            ->get()
            ->map(function ($item, $key) {
                return [
                    '#' => $key + 1,
                    'الكود' => $item->code,
                ];
            });

        return (new FastExcel($data))->download('Codes_' . now()->toDateString() . '.xlsx');
    }

}
