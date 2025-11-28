<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MainHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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
            $totalRecords = Admin::select('count(*) as allcount')->count();
            $totalRecordswithFilter = Admin::select('count(*) as allcount')
                ->when($searchValue, function ($query, $searchValue) {
                    $query->where('id', 'like', '%' . $searchValue . '%')
                        ->orWhere('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('phone', 'like', '%' . $searchValue . '%')
                        ->orWhere('email', 'like', '%' . $searchValue . '%');
                })
                ->count();

            $records = Admin::orderBy($columnName, $columnSortOrder)
                ->when($searchValue, function ($query, $searchValue) {
                    $query->where('id', 'like', '%' . $searchValue . '%')
                        ->orWhere('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('phone', 'like', '%' . $searchValue . '%')
                        ->orWhere('email', 'like', '%' . $searchValue . '%');
                })
                ->select('*')
                ->skip($start)
                ->take($rowperpage)
                ->get();

            // Fetch records

            $data_arr = array();

            foreach ($records as $record) {
                $delete = '';
                $edit = '';

                    if (auth('admin')->id() != $record->id) {
                        $delete = '<a class="text-danger" onclick="deleteForm(' . $record->id . ')"><span class="iconify" data-icon="fluent:delete-20-filled" data-width="25" data-height="25"></span></a>';
                    }

                    $edit = ' <a href = "javascript:void(0);" id="editForm"
                            onclick="editForm(' . $record->id . ',\'' . $record->name . '\',\'' . $record->email . '\', \'' . $record->phone . '\', \'' . $record->job_title . '\', \'' . $record->role_id . '\')"
                            class="text-secondary"><span class="iconify" data-icon="bx:edit" data-width="25" data-height="25"></span></a> ';

                $status = '<label class="switch">
                                            <input type="checkbox" class="switch-input is-valid" name="install_in_app"
                                            onclick="location.href=\'' . route('admin.users.status', [$record->id, 'status' => 1]) . '\'">
                                            <span class="switch-toggle-slider">
                                          <span class="switch-on"></span>
                                          <span class="switch-off"></span>
                                        </span>
                                            <span class="switch-label"></span>
                                        </label>';

                if ($record->status) {
                    $status = '<label class="switch">
                                            <input type="checkbox" class="switch-input is-valid" name="install_in_app" checked
                                onclick="location.href=\'' . route('admin.users.status', [$record->id, 'status' => 0]) . '\'">
                                            <span class="switch-toggle-slider">
                                          <span class="switch-on"></span>
                                          <span class="switch-off"></span>
                                        </span>
                                            <span class="switch-label"></span>
                                        </label>';
                }


                $data_arr[] = [
                    "id" => $record->id,
                    "name" => $record->name . "<br>" . $record->job_title,
                    "email" => $record->email,
                    "phone" => $record->phone,
                    "job_title" => $record->job_title,
                    "role" => $record->role ? $record->role->name : 'Super Admin',
                    "status" => $status,
                    "actions" => $delete . $edit,
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

        return view('admin.users.index');
    }

    public function store(Request $request)
    {
        if ($request->user_id != -1) {
            return $this->update($request, $request->user_id);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'min:3', 'max:255', 'unique:admins,email'],
            'phone' => ['nullable', 'string', 'min:3', 'max:255', 'unique:admins,phone'],
            'password' => ['required', 'string', 'min:3', 'max:255', 'confirmed'],
            'job_title' => ['nullable', 'string', 'min:3', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => MainHelper::error_processor($validator)]);
        }
        $user = new Admin();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->job_title = $request->job_title;
        $user->password = Hash::make($request->password);
        $user->status = 1;
        $user->save();

        return response()->json([], 200);
    }

    public function update(Request $request, $id)
    {
        $user = Admin::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'min:3', 'max:255', "unique:admins,email,$user->id"],
            'phone' => ['nullable', 'string', 'min:10', 'max:255', "unique:admins,phone,$user->id"],
            'job_title' => ['nullable', 'string', 'min:3', 'max:255'],
            'password' => ['nullable', 'string', 'min:3', 'max:255', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => MainHelper::error_processor($validator)]);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->job_title = $request->job_title;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([], 200);
    }

    public function status(Request $request)
    {
        $user = Admin::findOrFail($request->id);
        $user->status = $request->status;
        $user->save();

        return redirect()->back()->with('success', translate('The User Status was updated successfully'));
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);

        $admin->delete();

        return redirect()->back()->with('success', translate('The User was Deleted successfully'));
    }
}
