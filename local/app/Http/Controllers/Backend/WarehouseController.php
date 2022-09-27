<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Branch;
use App\Member;
use App\Warehouse;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Auth;

class WarehouseController extends Controller
{


    public function index($id)

    {


        $branch = Branch::select('id', 'b_code', 'b_name')->where('id', $id)->get();
        return view('backend/stock/warehouse/index')
            ->with('branch', $branch);
    }

    public function get_data_warehouse(Request $request)
    {

        $data = Warehouse::where('branch_id_fk', $request->branch_id_fk)
            ->where(function ($query) use ($request) {
                if ($request->has('Where')) {
                    foreach (request('Where') as $key => $val) {
                        if ($val) {
                            if (strpos($val, ',')) {
                                $query->whereIn($key, explode(',', $val));
                            } else {
                                $query->where($key, $val);
                            }
                        }
                    }
                }
                if ($request->has('Like')) {
                    foreach (request('Like') as $key => $val) {
                        if ($val) {
                            $query->where($key, 'like', '%' . $val . '%');
                        }
                    }
                }
            })
            ->get();


        return DataTables::of($data)
            ->setRowClass('intro-x py-4 h-20 zoom-in box')


            ->editColumn('updated_at', function ($query) {
                $time =  date('d-m-Y H:i:s', strtotime($query->updated_at));
                return   $time;
            })
            ->editColumn('w_maker', function ($query) {
                $member = Member::where('id', $query->w_maker)->select('name')->first();
                return   $member['name'];
            })
            ->make(true);
    }


    public function store_warehoues(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'w_code' => 'required|unique:warehouse',
                'w_name' => 'required',
                'w_details' => 'required',

            ],
            [
                'w_code.required' => 'กรุณากรอกข้อมูล',
                'w_code.unique' => 'รหัสคลังถูกใช้งานแล้ว',
                'w_name.required' => 'กรุณากรอกข้อมูล',
                'w_details.required' => 'กรุณากรอกข้อมูล',

            ]
        );

        if (!$validator->fails()) {

            $dataPrepare = [
                'branch_id_fk' => $request->branch_id_fk,
                'w_code' => $request->w_code,
                'w_name' => $request->w_name,
                'w_details' => $request->w_details,
                'status' => $request->status == null ? 99 : 1,
                'w_maker' =>   Auth::guard('member')->user()->id
            ];



            $query = Warehouse::create($dataPrepare);

            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['error' => $validator->errors()]);
    }

    public function get_data_info_warehouse(Request $request)
    {
        $warehouse = Warehouse::where('id', $request->id)->first();
        return response()->json($warehouse);
    }



    public function update_warehouse(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [

                'w_name' => 'required',
                'w_details' => 'required',

            ],
            [
                'w_name.required' => 'กรุณากรอกข้อมูล',
                'w_details.required' => 'กรุณากรอกข้อมูล',

            ]
        );
        if (!$validator->fails()) {

            $dataPrepare = [
                'branch_id_fk' => $request->branch_id_fk,
                'w_code' => $request->w_code,
                'w_name' => $request->w_name,
                'w_details' => $request->w_details,
                'status' => $request->status == null ? 99 : 1,
                'w_maker' =>   Auth::guard('member')->user()->id
            ];



            $query = Warehouse::where('id', $request->id)->update($dataPrepare);

            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['error' => $validator->errors()]);
    }
}
