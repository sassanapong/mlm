<?php

namespace App\Http\Controllers\Frontend;

use App\Customers;
use App\eWallet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;
use PhpParser\Node\Expr\FuncCall;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class eWalletController extends Controller
{

    public function eWallet_history()

    {
        return view('frontend/eWallet-history');
    }



    public function front_end_get_ewallet(Request $request)
    {
        $customer = Auth::guard('c_user')->user()->id;
        $recive = Customers::where('id',$customer)->first();
        $data =  eWallet::select(
            'id',
            'transaction_code',
            'customers_id_fk',
            'file_ewllet',
            'amt',
            'edit_amt',
            'customers_id_receive',
            'customers_name_receive',
            'type',

            'status',
            'type_note',
            'note_orther',
            'created_at',
            'balance',
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
            ->OrderBy('id', 'DESC')
            ->where('customers_id_fk', Auth::guard('c_user')->user()->id)
            ->orwhere('customers_id_receive',$recive->user_name)
            ->get();


        return DataTables::of($data)
            // ->setRowClass('intro-x py-4 h-24 zoom-in')



            ->editColumn('transaction_code', function ($query) {
               if($query->type == 4){
                $data = '<a href="' . route('order_detail', ['code_order' => $query->transaction_code]) . '" class="btn btn-sm btn-outline-primary">' . $query->transaction_code . '</a>';
                 return $data;

               }else{
                return $query->transaction_code;

               }

            })
            // ดึงข้อมูล created_at
            ->editColumn('created_at', function ($query) {
                $time = date('d/m/Y H:i:s', strtotime($query->created_at));

                return $time;
            })
            // ดึงข้อมูล lot_expired_date วันหมดอายุ
            ->editColumn('amt', function ($query) {
                $amt = number_format($query->amt, 2) . " บาท";
                return $amt;
            })
            ->editColumn('balance', function ($query) {
                if($query->customers_id_receive == Auth::guard('c_user')->user()->user_name){
                    $balance = number_format(Auth::guard('c_user')->user()->ewallet, 2) . " บาท";
                }else{
                    $balance = number_format($query->balance, 2) . " บาท";
                }

                return $balance;
            })
            ->editColumn('edit_amt', function ($query) {
                $edit_amt = $query->edit_amt == 0 ? '' :  number_format($query->edit_amt, 2) . " บาท";
                return $edit_amt;
            })

            ->editColumn('customers_name_receive', function ($query) {
                $customers = Customers::select('user_name','name', 'last_name')->where('user_name', $query->customers_id_receive)->first();
                $test_customers = $customers['name'] . " " . $customers['last_name'].' ('.$customers['user_name'].')' ;
                return $test_customers;
            })
            ->editColumn('customers_id_fk', function ($query) {
                $customers = Customers::select('user_name','name', 'last_name')->where('id', $query->customers_id_fk)->first();
                $test_customers = $customers['name'] . " " . $customers['last_name'].' ('.$customers['user_name'].')' ;
                return $test_customers;
            })

            ->editColumn('type', function ($query) {
                $type = $query->type;
                $text_type = "";
                if($query->customers_id_receive == Auth::guard('c_user')->user()->user_name){
                    if ($type  == 1) {
                        $text_type = "ฝากเงิน";
                    }
                    if ($type  == 2) {
                        $text_type = "รับเงิน";
                    }
                    if ($type  == 3) {
                        $text_type = "ถอนเงิน";
                    }
                    if ($type  == 4) {
                        $text_type = "ซื้อสินค้า";
                    }
                    if ($type  == 5) {
                        $text_type = "แจงลูกค้าประจำ";
                    }

                }else{

                    if ($type  == 1) {
                        $text_type = "ฝากเงิน";
                    }
                    if ($type  == 2) {
                        $text_type = "โอนเงิน";
                    }
                    if ($type  == 3) {
                        $text_type = "ถอนเงิน";
                    }
                    if ($type  == 4) {
                        $text_type = "ซื้อสินค้า";
                    }
                    if ($type  == 5) {
                        $text_type = "แจงลูกค้าประจำ";
                    }


                }
                return $text_type;
            })
            ->editColumn('status', function ($query) {
                $status = $query->status;

                if ($status == 1) {
                    $status = "รออนุมัติ";
                    $status_bg = "text-warning";

                }
                if ($status == 2) {
                    $status = "อนุมัติ";
                    $status_bg = "text-success";

                }
                if ($status == 3) {
                    $status = "ไม่อนุมัติ";
                    $status_bg = "text-danger";
                }

                return $status;
            })

            ->rawColumns(['transaction_code'])
            ->make(true);
    }



    public function deposit(Request $request)
    {


        $rule = [
            'amt' => 'required|numeric',
            'upload' => 'required',

        ];

        $message_err = [
            'amt.required' => 'กรุณากรอกข้อมูล',
            'amt.numeric' => 'กรุณากรอกเป็นตัวเลขเท่านั้น',
            'upload.required' => 'กรุณาแนบสลิป การโอนเงิน',
        ];


        $validator = Validator::make(
            $request->all(),
            $rule,
            $message_err
        );
        if (!$validator->fails()) {

            $customers_id_fk =  Auth::guard('c_user')->user()->id;
            $customers = Customers::where('id', $customers_id_fk)->first();

            $y = date('Y') + 543;
            $y = substr($y, -2);
            $count_eWallet =  IdGenerator::generate([
                'table' => 'ewallet',
                'field' => 'transaction_code',
                'length' => 15,
                'prefix' => 'EW' . $y . '' . date("m") . '-',
                'reset_on_prefix_change' => true
            ]);


            if ($request->upload) {
                $url = 'local/public/images/eWllet/deposit/' . date('Ym');
                $imageName = $request->upload->extension();
                $filenametostore =  date("YmdHis")  . $customers_id_fk . "." . $imageName;
                $request->upload->move($url,  $filenametostore);


                $dataPrepare = [
                    'transaction_code' => $count_eWallet,

                    'customers_id_fk' => $customers_id_fk,
                    'customer_username' => $customers->user_name,
                    'url' => $url,
                    'file_ewllet' => $filenametostore,
                    'amt' => $request->amt,
                    'type' => 1,
                    'status' => 1,
                ];

                $query =  eWallet::create($dataPrepare);
            }


            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['error' => $validator->errors()]);
    }


    public function transfer(Request $request)
    {
        $customers_id_fk =  Auth::guard('c_user')->user()->id;
        $count_eWallet = eWallet::get()->count() + 1;

        $y = date('Y') + 543;
        $y = substr($y, -2);
        $transaction_code = IdGenerator::generate([
            'table' => 'ewallet',
            'field' => 'transaction_code',
            'length' => 15,
            'prefix' => 'EW' . $y . '' . date("m") . '-',
            'reset_on_prefix_change' => true
        ]);

        $customer_receive = Customers::where('user_name',$request->customers_id_receive)->first();
        $customer_transfer = Customers::where('id',$customers_id_fk)->first();
        if($customer_transfer->ewallet >= $request->amt){
            $customer_receive->ewallet = $customer_receive->ewallet+$request->amt;
            $customer_transfer->ewallet = $customer_transfer->ewallet-$request->amt;

            $dataPrepare = [
                'transaction_code' => $transaction_code,
                'customers_id_fk' => $customers_id_fk,
                'customer_username' => Auth::guard('c_user')->user()->user_name,
                'customers_id_receive' => $request->customers_id_receive,
                'customers_name_receive' => $request->customers_name_receive,
                'old_balance'=>$customer_transfer->ewallet+$request->amt,
                'balance'=>$customer_transfer->ewallet,
                'receive_date'=>date('Y-m-d'),
                'receive_time'=>date('H:i:s'),
                'amt' => $request->amt,
                'type' => 2,
                'status' => 2,
            ];
            $query =  eWallet::create($dataPrepare);

            // $transaction_code2 = IdGenerator::generate([
            //     'table' => 'ewallet',
            //     'field' => 'transaction_code',
            //     'length' => 15,
            //     'prefix' => 'EW' . $y . '' . date("m") . '-',
            //     'reset_on_prefix_change' => true
            // ]);

            $customer_transfer->save();
            $customer_receive->save();
            return response()->json(['status' => 'success'], 200);
        }else{
            return redirect('home')->withError('eWallet ของท่านไม่เพียงพอ');
        }
    }
    public function checkcustomer(Request $request)
    {
        $customer = Customers::where('user_name',$request->id)->first();
        if(!empty($customer)){
            $return = $customer;
        }else{
            $return = "fail";
        }
         return $return;
    }




    public function withdraw(Request $request)
    {
        $customers_id_fk =  Auth::guard('c_user')->user()->id;
        $customer_withdraw = Customers::where('id',$customers_id_fk)->first();
        if(empty($customer_withdraw->expire_date) || (strtotime($customer_withdraw->expire_date) < strtotime(date('Y-m-d'))) )
        {
         return redirect('home')->withError('วันที่รักษายอดไม่เพียงพอ');
        }else{
        $y = date('Y') + 543;
        $y = substr($y, -2);

        $customer_withdraw->ewallet = $customer_withdraw->ewallet-$request->amt;
        $customer_withdraw->wallet_use = $customer_withdraw->wallet_use-$request->amt;
        $customer_withdraw->save();

        $count_eWallet = eWallet::get()->count() + 1;
        $transaction_code = IdGenerator::generate([
            'table' => 'ewallet',
            'field' => 'transaction_code',
            'length' => 15,
            'prefix' => 'EW' . $y . '' . date("m") . '-',
            'reset_on_prefix_change' => true
        ]);

        $dataPrepare = [
            'transaction_code' => $transaction_code,
            'customers_id_fk' => $customers_id_fk,
            'customer_username'=>$customer_withdraw->name,
            'old_balance'=>$customer_withdraw->ewallet+$request->amt,
            'balance'=>$customer_withdraw->ewallet,
            'amt' => $request->amt,
            'type' => 3,
            'status' => 1,
        ];
            $query =  eWallet::create($dataPrepare);
            return back();
        }



    }
}
