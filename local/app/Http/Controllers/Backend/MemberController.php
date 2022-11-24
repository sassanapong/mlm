<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Member;
use App\Reportissue;
use App\ReportissueDoc;
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

        $data = Member::where(function ($query) use ($request) {
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
                'password' => 'required|min:6',
                'name' => 'required',
                'last_name' => 'required',
                'phone' => 'required',

                'role' => 'required',
            ],
            [
                'username.required' => 'กรุณากรอกข้อมูล',
                'username.unique' => 'username นี้ถูกใช้งานแล้ว',
                'password.required' => 'กรุณากรอกข้อมูล',
                'password.min' => 'อย่างน้อย 6 ตัวอักษร',
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

    public function change_password_member(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required|min:6',
                'password_new' => 'required|min:6',
                'password_new_comfirm' => 'required|min:6',
            ],
            [
                'password.required' => 'กรุณากรอกรหัสผ่านเดิม',
                'password.min' => 'อย่างน้อย 6 ตัวอักษร',
                'password_new.required' => 'กรุณากรอกรหัสผ่านใหม่',
                'password_new.min' => 'อย่างน้อย 6 ตัวอักษร',
                'password_new_comfirm.required' => 'กรุณากรอกยืนยันรหัสผ่านใหม่ ',
                'password_new_comfirm.min' => 'อย่างน้อย 6 ตัวอักษร',
            ]
        );

        if (!$validator->fails()) {


            $password = md5($request->password);
            $password_new = md5($request->password_new);
            $password_new_comfirm = md5($request->password_new_comfirm);

            $id = $request->id;

            $Member = Member::where('id', $id)->first();

            // Check รหัสผ่านเดิมที่กรอกมาตรงกันของเดิมหรือไม่
            if (($password == $Member->password)) {

                // Check รหัสผ่านใหม่ ต้องตรงกันทั้ง 2 อัน
                if ($password_new == $password_new_comfirm) {
                    $Member->password = md5($request->password_new);
                    $Member->save();
                    return redirect('logout');
                }
                return response()->json(['error' => ['password_new_comfirm' => 'รหัสผ่านใหม่ไม่ตรงกัน']]);
            } else {
                return response()->json(['error' => ['password' => 'รหัสผ่านเดิมไม่ถูกต้อง']]);
            }
        }
        return response()->json(['error' => $validator->errors()]);
    }
    public function delete_member(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $Member = Member::where('id', $id)->first();
        $Member->update(['status' => $status]);
    }
}
