<?php

namespace App\Http\Controllers\Backend;

use App\eWallet;
use App\Customers;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;
use PhpParser\Node\Expr\FuncCall;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class eWalletController extends Controller
{

    public function index()
    {
        return view('backend/ewallet/index');
    }


    public function get_ewallet(Request $request)
    {
        $data =  eWallet::select(
            'id',
            'transaction_code',
            'customers_id_fk',
            'file_ewllet',
            'amt',
            'customers_id_receive',
            'customers_name_receive',
            'type',
            'status',
            'created_at',
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
            ->OrderBy('created_at', 'DESC')
            ->get();


        return DataTables::of($data)
            ->setRowClass('intro-x py-4 h-24 zoom-in')

            // ดึงข้อมูล created_at
            ->editColumn('created_at', function ($query) {
                $time = date('d-m-Y H:i:s', strtotime($query->created_at));

                return $time;
            })
            // ดึงข้อมูล lot_expired_date วันหมดอายุ 
            ->editColumn('amt', function ($query) {
                $amt = number_format($query->amt) . " บาท";
                return $amt;
            })
            ->editColumn('type', function ($query) {
                $type = $query->type;
                $text_type = "";

                if ($type  == 1) {
                    $text_type = "ฝากเงิน";
                }
                if ($type  == 2) {
                    $text_type = "โอนเงิน";
                }
                if ($type  == 3) {
                    $text_type = "ถอนเงิน";
                }

                return $text_type;
            })

            ->make(true);
    }



    public function  get_info_ewallet(Request $request)
    {

        $data =  eWallet::select(
            'ewallet.id as ewallet_id',
            'transaction_code',
            'customers_id_fk',
            'ewallet.url',
            'ewallet.file_ewllet',
            'amt',
            'customers_id_receive',
            'customers_name_receive',
            'type',
            'status',
            'ewallet.created_at as ewallet_created_at',
            'customers.user_name',
            'customers.name',
            'customers_bank.bank_name',
            'customers_bank.bank_branch',
            'customers_bank.bank_no',
            'customers_bank.account_name',
        )
            ->join('customers', 'customers.id', 'ewallet.customers_id_fk')
            ->join('customers_bank', 'customers_bank.customers_id', 'customers.id')
            ->where('ewallet.id', $request->id)
            ->get();

        return response()->json($data);
    }



    public function approve_update_ewallet(Request $request)
    {


        $validator = Validator::make(
            $request->all(),
            [
                'date' => 'required',


            ],
            [
                'date.required' => 'กรุณากรอกข้อมูล',
            ]
        );
        if (!$validator->fails()) {

            $ewallet_id = $request->ewallet_id;
            $code_refer = $request->code_refer;


            $query = eWallet::where('code_refer', $code_refer)->first();

            $customers = Customers::where('id', $request->customers_id_fk)->first();


            $amt = $request->edit_amt == '' ? $request->amt : $request->amt;

            $query_ewallet = eWallet::where('id', $ewallet_id);
            if ($query == null) {



                $dataPrepare = [
                    'receive_date' => $request->date,
                    'receive_time' => $request->time,
                    'code_refer' => $request->code_refer,
                    'edit_amt' => $amt,
                    'status' => 2
                ];

                $query_ewallet->update($dataPrepare);

                // อัพเดท old_balance กับ  balance ของ table ewallet
                if ($query_ewallet) {

                    $dataPrepare_update = [
                        'old_balance' => $customers->ewallet,
                        'balance' =>  $customers->ewallet + $amt
                    ];



                    $query_ewallet->update($dataPrepare_update);


                    if ($query_ewallet) {

                        $dataPrepare_update_ewallet = [
                            'ewallet' =>  $customers->ewallet + $amt
                        ];
                        $customers->update($dataPrepare_update_ewallet);
                    }
                }
                return response()->json(['status' => 'success'], 200);
            } else {
                return response()->json(['status' => 'error']);
            }
        }
        return response()->json(['error' => $validator->errors()]);
    }
}
