<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Member;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


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
            ->make(true);
    }
}
