<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Member;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;


class MemberController extends Controller
{


    public function index(Request $request)
    {
        return view('backend/member/index');
    }

    public function get_data_member(Request $request)
    {

        $data = Member::select('*')->orderBy('id', 'DESC');

        if ($request->has('Where')) {
            foreach (request('Where') as $key => $val) {
                if ($val) {
                    if (strpos($val, ',')) {
                        $data->whereIn($key, explode(',', $val));
                    } else {
                        $data->where($key, $val);
                    }
                }
            }
        }
        if ($request->has('Like')) {
            foreach (request('Like') as $key => $val) {
                if ($val) {
                    $data->where($key, 'like', '%' . $val . '%');
                }
            }
        }


        return DataTables::of($data->get())
            ->setRowClass('intro-x py-4 h-20 zoom-in box')
            ->editColumn('credit', function ($query) {
                return  number_format($query->credit) . "" . " แต้ม";
            })
            ->make(true);
    }


    public function store_member(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required|unique:member',
                'password' => 'required',
                'name' => 'required',
                'last_name' => 'required',
                'phone' => 'required',

                'role' => 'required',
            ],
            [
                'username.required' => 'กรุณากรอกข้อมูล',
                'username.unique' => 'username นี้ถูกใช้งานแล้ว',
                'password.required' => 'username นี้ถูกใช้งานแล้ว',
                'name.required' => 'กรุณากรอกข้อมูล',
                'last_name.required' => 'กรุณากรอกข้อมูล',
                'phone.required' => 'กรุณากรอกข้อมูล',

                'role.required' => 'กรุณากรอกข้อมูล',
            ]
        );

        if (!$validator->fails()) {

            $dataprepare = [
                'username' => $request->username,
                'password' => md5($request->password),
                'name' => $request->name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'role' => $request->role,
                'status' => 1,
            ];

            $query = Member::create($dataprepare);

            if ($query) {
                return response()->json(['status' => 'success'], 200);
            }
        }
        return response()->json(['error' => $validator->errors()]);
    }


    public function data_edit_password(Request $request)
    {

        $id = $request->id;

        $query = Member::where('id', $id)->first();

        return response()->json($query);
    }
}
