<?php

namespace App\Http\Controllers\Backend;

use App\AddressProvince;
use App\Branch;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
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
                'b_code' => 'required',
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
                'status' => $request->status == null ? 0 : 1,
                'b_maker' =>   Auth::guard('member')->user()->name
            ];

            $query = Branch::create($dataPrepare);

            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['error' => $validator->errors()]);
    }
}
