<?php

namespace App\Http\Controllers\Backend;

use App\AddressProvince;
use App\Branch;
use App\Http\Controllers\Controller;
use App\Member;
use App\Warehouse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Auth;

class BranchController extends Controller
{

    public function index(Request $request)
    {

        $province = AddressProvince::orderBy('province_name', 'ASC')->get();
        return view('backend.stock.branch.index')
            ->with('province', $province);
    }

    public function store_branch(Request $request)
    {


        $validator = Validator::make(
            $request->all(),
            [
                'b_code' => 'required|unique:branchs',
                'b_name' => 'required',
                'b_details' => 'required',
                'home_name' => 'required',
                'moo' => 'required',
                'soi' => 'required',
                'road' => 'required',
                'province' => 'required',
                'district' => 'required',
                'tambon' => 'required',
                'zipcode' => 'required',
                'tel' => 'required',
            ],
            [
                'b_code.required' => 'กรุณากรอกข้อมูล',
                'b_code.unique' => 'รหัสสาขาถูกใช้งานแล้ว',
                'b_name.required' => 'กรุณากรอกข้อมูล',
                'b_details.required' => 'กรุณากรอกข้อมูล',
                'home_name.required' => 'กรุณากรอกข้อมูล',
                'moo.required' => 'กรุณากรอกข้อมูล',
                'soi.required' => 'กรุณากรอกข้อมูล',
                'road.required' => 'กรุณากรอกข้อมูล',
                'province.required' => 'กรุณากรอกข้อมูล',
                'district.required' => 'กรุณากรอกข้อมูล',
                'district.required' => 'กรุณากรอกข้อมูล',
                'tambon.required' => 'กรุณากรอกข้อมูล',
                'zipcode.required' => 'กรุณากรอกข้อมูล',
                'tel.required' => 'กรุณากรอกข้อมูล',
            ]
        );

        if (!$validator->fails()) {



            $dataPrepare = [
                'b_code' => $request->b_code,
                'b_name' => $request->b_name,
                'b_details' => $request->b_details,
                'home_name' => $request->home_name,
                'moo' => $request->moo,
                'soi' => $request->soi,
                'road' => $request->road,
                'province' => $request->province,
                'district' => $request->district,
                'tambon' => $request->tambon,
                'zipcode' => $request->zipcode,
                'tel' => $request->tel,
                'status' => $request->status == null ? 99 : 1,
                'b_maker' =>   Auth::guard('member')->user()->id
            ];

            $query = Branch::create($dataPrepare);

            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['error' => $validator->errors()]);
    }


    public function get_data_branch(Request $request)
    {
        $data = Branch::where(function ($query) use ($request) {
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
            ->editColumn('b_maker', function ($query) {
                $member = Member::where('id', $query->b_maker)->select('name')->first();
                return   $member['name'];
            })
            ->addColumn('warehouse', function ($query) {
                $warehouse[] = Warehouse::select('w_code', 'w_name', 'status')->where('branch_id_fk', $query->id)

                    ->get();
                return $warehouse;
            })
            ->make(true);
    }


    public function get_data_info_branch(Request $request)
    {
        $id = $request->id;

        $query = Branch::where('id', $id)->first();

        return response()->json($query);
    }


    public function update_branch(Request $request)
    {



        $validator = Validator::make(
            $request->all(),
            [
                'b_code' => 'required|unique:branchs,b_code,' . $request->id,
                'b_name' => 'required',
                'b_details' => 'required',
                'home_name' => 'required',
                'moo' => 'required',
                'soi' => 'required',
                'road' => 'required',
                'province' => 'required',
                'district' => 'required',
                'tambon' => 'required',
                'zipcode' => 'required',
                'tel' => 'required',
            ],
            [
                'b_code.required' => 'กรุณากรอกข้อมูล',
                'b_code.unique' => 'รหัสคลังถูกใช้งานแล้ว',
                'b_name.required' => 'กรุณากรอกข้อมูล',
                'b_details.required' => 'กรุณากรอกข้อมูล',
                'home_name.required' => 'กรุณากรอกข้อมูล',
                'moo.required' => 'กรุณากรอกข้อมูล',
                'soi.required' => 'กรุณากรอกข้อมูล',
                'road.required' => 'กรุณากรอกข้อมูล',
                'province.required' => 'กรุณากรอกข้อมูล',
                'district.required' => 'กรุณากรอกข้อมูล',
                'district.required' => 'กรุณากรอกข้อมูล',
                'tambon.required' => 'กรุณากรอกข้อมูล',
                'zipcode.required' => 'กรุณากรอกข้อมูล',
                'tel.required' => 'กรุณากรอกข้อมูล',
            ]
        );

        if (!$validator->fails()) {

            $dataPrepare = [
                'b_code' => $request->b_code,
                'b_name' => $request->b_name,
                'b_details' => $request->b_details,
                'home_name' => $request->home_name,
                'moo' => $request->moo,
                'soi' => $request->soi,
                'road' => $request->road,
                'province' => $request->province,
                'district' => $request->district,
                'tambon' => $request->tambon,
                'zipcode' => $request->zipcode,
                'tel' => $request->tel,
                'status' => $request->status == null ? 99 : 1,
                'b_maker' =>   Auth::guard('member')->user()->id
            ];

            $query = Branch::where('id', $request->id)->update($dataPrepare);

            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['error' => $validator->errors()]);
    }
}
