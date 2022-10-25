<?php

namespace App\Http\Controllers\Backend;

use App\Customers;
use App\CustomersAddressCard;
use App\Http\Controllers\Controller;
use App\Models\CUser;
use Illuminate\Http\Request;
use Auth;
use Yajra\DataTables\Facades\DataTables;

class CustomerServiceController extends Controller
{


    public function index(Request $request)
    {
        return view('backend.customer_service.check_doc.index');
    }


    public function get_check_doc(Request $request)
    {

        $data = Customers::select(
            "id",
            'user_name',
            'name',
            'last_name',
            'prefix_name',
            'phone',
            'regis_doc1_status',
            'regis_doc4_status',
        )


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
            ->where('regis_doc1_status', '=', '3')
            ->orwhere('regis_doc4_status', '=', '3')
            ->get();


        return DataTables::of($data)
            ->setRowClass('intro-x py-4 h-24 zoom-in')

            // ดึงข้อมูล created_at
            ->editColumn('created_at', function ($query) {
                $time = date('d-m-Y H:i:s', strtotime($query->created_at));

                return $time;
            })

            // ดึงข้อมูล ชื่อ นามสกุล
            ->editColumn('name', function ($query) {
                $text = $query->prefix_name . "" . $query->name . " " . $query->last_name;
                return $text;
            })

            // Text color icon
            ->addColumn('text_color_doc_1', function ($query) {
                $text_color = '';
                if ($query->regis_doc1_status == 0) {
                    $text_color = 'text-success;';
                }
                if ($query->regis_doc1_status == 3) {
                    $text_color = 'text-warning';
                }
                if ($query->regis_doc1_status == 4) {
                    $text_color = 'text-danger';
                }
                return $text_color;
            })
            ->addColumn('text_color_doc_4', function ($query) {
                $text_color = '';

                if ($query->regis_doc4_status == 3) {
                    $text_color = 'text-warning';
                }
                if ($query->regis_doc4_status == 4) {
                    $text_color = 'text-danger';
                }
                return $text_color;
            })
            ->make(true);
    }

    public function admin_login_user($id)
    {

        $users = CUser::where('id', $id)->first();
        Auth::guard('c_user')->logout();
        Auth::guard('c_user')->login($users);

        return redirect('editprofile');
    }


    public function admin_get_info_card(Request $request)
    {
        $user_name = $request->user_name;
        $data = CustomersAddressCard::select(
            'customers_address_card.*',
            'district_name as district',
            'province_name as province',
            'tambon_name as tambon',
        )
            ->leftjoin('address_districts', 'address_districts.district_id', 'customers_address_card.district')
            ->leftjoin('address_provinces', 'address_provinces.province_id', 'customers_address_card.province')
            ->leftjoin('address_tambons', 'address_tambons.tambon_id', 'customers_address_card.tambon')
            ->where('user_name', $user_name)->first();

        return response()->json($data);
    }
}
