<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MainHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Sale;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sales = Sale::get();

            return datatables()->of($sales)
                ->editColumn('image', function (Sale $sale) {
                    return '<img src="' . $sale->img_url . '" alt="sales" height="50" width="50">';
                })
                ->addColumn('actions', function (Sale $sale) {

                    $delete = '<a class="text-danger" onclick="deleteForm(' . $sale->id . ')"><span class="iconify" data-icon="fluent:delete-20-filled" data-width="25" data-height="25"></span></a>';

                    $edit = ' <a href = "javascript:void(0);" id="editForm" onclick="editForm(' . $sale->id . ',\'' . $sale->description . '\',\'' . $sale->img_url . '\')"
                            class="text-secondary"><span class="iconify" data-icon="bx:edit" data-width="20" data-height="20"></span></a> ';
                    return $edit . $delete;
                })
                ->rawColumns(['actions', 'image', 'name'])
                ->addIndexColumn()
                ->make(true);

        }

        return view('admin.sales.index');
    }

    public function store(Request $request)
    {
        if ($request->sales_id != -1) {
            return $this->update($request, $request->sales_id);
        }

        $validator = Validator::make($request->all(), [
            'description' => ['required', 'string', 'max:255', 'min:3'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => MainHelper::error_processor($validator)]);
        }

        $data = [
            'description' => $request->description,
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['img'] = $request->file('image')->store('uploads/sales/', 'public');
        }

        Sale::create($data);


        return response()->json([], 200);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'description' => ['required', 'string', 'max:255', 'min:3'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => MainHelper::error_processor($validator)]);
        }

        $sale = Sale::findOrFail($id);

        $data = [
            'description' => $request->description,
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($sale->img && Storage::disk('public')->exists($sale->img)) {
                Storage::disk('public')->delete($sale->img);
            }
            $data['img'] = $request->file('image')->store('uploads/sales/', 'public');
        }

        $sale->update($data);

        return response()->json([], 200);
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return redirect()->back()->with('success', 'تم حذف العرض بنجاح');
    }
}
