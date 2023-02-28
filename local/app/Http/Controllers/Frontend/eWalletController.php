<?php

namespace App\Http\Controllers\Frontend;

use App\Customers;
use App\CustomersBank;
use App\eWallet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;
use PhpParser\Node\Expr\FuncCall;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use DB;

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
        $data =  eWallet::where(function ($query) use ($request) {
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
            ->where('customers_id_fk', Auth::guard('c_user')->user()->id)
            // ->orwhere('customers_id_receive',$recive->id)
            ->OrderBy('id', 'DESC');
            // ->get();


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



            ->editColumn('customer_username', function ($query) {
                //$customers = Customers::select('user_name','name', 'last_name')->where('id', $query->customers_id_receive)->first();
                // if($customers){
                //     $test_customers = $customers['user_name'];
                // }else{
                //     $test_customers="-";
                // }

                if($query->type == 2){

                    if($query->customers_username_tranfer == '0534768'){
                        $customers = Customers::select('user_name','name', 'last_name')->where('user_name', $query->customers_username_tranfer)->first();
                        $name_user= $customers->name . ' ' . $customers->last_name ;
                    }else{
                        $name_user= $query->customers_username_tranfer;
                    }
                }else{


                    if( $query->customers_name_receive == '0534768'){
                        $customers = Customers::select('user_name','name', 'last_name')->where('user_name', $query->customers_name_receive)->first();
                        $name_user= $customers->name . ' ' . $customers->last_name ;
                    }else{

                        $name_user= $query->customer_username;
                    }

                }

                return $name_user;
            })


            // ดึงข้อมูล created_at
            ->editColumn('created_at', function ($query) {
                $time = date('Y/m/d H:i:s', strtotime($query->created_at));

                return $time;
            })
            // ดึงข้อมูล lot_expired_date วันหมดอายุ
            ->editColumn('amt', function ($query) {
                if($query->edit_amt>0){
                    $amt = number_format($query->edit_amt, 2);
                }else{
                    $amt = number_format($query->amt, 2);
                }
                $type = $query->type;
                if($query->customers_id_receive == Auth::guard('c_user')->user()->id){

                    if ( $type  == 3 || $type  == 4) {
                        $text_type = "-";
                    }else   {
                        $text_type = "";
                    }


                }else{

                    if ( $type  == 2 || $type  == 3 || $type  == 4) {
                        $text_type = "-";
                    }else   {
                        $text_type = "";
                    }


                }

                return $text_type.$amt;
            })
            ->editColumn('balance', function ($query) {
                // if($query->customers_id_receive == Auth::guard('c_user')->user()->id){
                //     $balance = number_format($query->balance_recive, 2) . " บาท";
                // }else{
                //     $balance = number_format($query->balance, 2) . " บาท";
                // }
                $balance = number_format($query->balance, 2);
                return $balance;
            })
            ->editColumn('edit_amt', function ($query) {
                $edit_amt = $query->edit_amt == 0 ? '' :  number_format($query->edit_amt, 2);
                return $edit_amt;
            })

            ->editColumn('customers_name_receive', function ($query) {
                // $customers = Customers::select('user_name','name', 'last_name')->where('id', $query->customers_id_receive)->first();

                if( $query->customers_name_receive == '0534768'){
                    $customers = Customers::select('user_name','name', 'last_name')->where('id', $query->customers_id_receive)->first();
                    return $customers->name . ' ' . $customers->last_name ;
                }else{
                    $test_customers = $query->customers_name_receive;

                return $test_customers;
                }


            })
            ->editColumn('note_orther', function ($query) {
                // $customers = Customers::select('user_name','name', 'last_name')->where('id', $query->customers_id_receive)->first();
                if($query->note_orther){
                    $html = $query->note_orther;
                }else{
                    $html = $query->type_note;
                }


                return $html;
            })

            // ->editColumn('customers_id_fk', function ($query) {
            //     $customers = Customers::select('user_name','name', 'last_name')->where('id', $query->customers_id_fk)->first();
            //     $test_customers = $customers['user_name'];
            //     return $test_customers;
            // })

            ->editColumn('type', function ($query) {
                $type = $query->type;
                $text_type = "";
                if($query->customers_id_receive == Auth::guard('c_user')->user()->id){
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
                    if ($type  == 6) {
                        $text_type = "บริหารทีมลูกค้าประจำ";
                    }
                    if ($type  == 7) {
                        $text_type = "สนับสนุนสินค้า";
                    }
                    if ($type  == 8) {
                        $text_type = "โบนัสบริหารทีม";
                    }
                    if ($type  == 9) {
                        $text_type = "โบนัสเจ้าของลิขสิทธิ์";
                    }

                    if ($type  == 10) {
                        $text_type = "โบนัสโบนัสขยายธุรกิจ";
                    }

                    if ($type  == 11) {
                        $text_type = "โบนัสสร้างทีม";
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
                    if ($type  == 6) {
                        $text_type = "บริหารทีมลูกค้าประจำ";
                    }
                    if ($type  == 7) {
                        $text_type = "สนับสนุนสินค้า";
                    }
                    if ($type  == 8) {
                        $text_type = "โบนัสบริหารทีม";
                    }
                    if ($type  == 9) {
                        $text_type = "โบนัสเจ้าของลิขสิทธิ์";
                    }

                    if ($type  == 10) {
                        $text_type = "โบนัสโบนัสขยายธุรกิจ";
                    }

                    if ($type  == 11) {
                        $text_type = "โบนัสสร้างทีม";
                    }



                }
                return $text_type;
            })
            ->editColumn('status', function ($query) {
                $status = $query->status;

                if ($status == 1) {
                    $status = "รออนุมัติ";
                    $status_bg = "warning";

                }
                if ($status == 2) {
                    $status = "อนุมัติ";
                    $status_bg = "success";

                }
                if ($status == 3) {
                    $status = "ไม่อนุมัติ";
                    $status_bg = "danger";
                }
                if ($status == 4) {
                    $status = "ยกเลิก";
                    $status_bg = "danger";
                }
                $html ='<span class="badge bg-'.$status_bg.'">'.$status.'</span>';

                return $html;
            })

            ->rawColumns(['transaction_code','status'])
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

            $count_eWallet = \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_wallet();

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

                try {
                    DB::BeginTransaction();
                    $query =  eWallet::create($dataPrepare);
                DB::commit();
                return response()->json(['status' => 'success'], 200);
                    } catch (Exception $e) {
                        DB::rollback();
                        return response()->json(['status' => 'fail','ms'=>'เกิดข้อผิดพลาดกรุณาทำรายการไหม่'], 200);
                    }
            }
            return response()->json(['status' => 'fail'], 200);

        }
        return response()->json(['error' => $validator->errors()]);
    }


    public function transfer(Request $request)
    {
        $customers_id_fk =  Auth::guard('c_user')->user()->id;
        // $count_eWallet = eWallet::get()->count() + 1;

        $transaction_code =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_wallet();

        $customer_receive = Customers::where('user_name',$request->customers_id_receive)->first();
        $old_balance_receive =  $customer_receive->ewallet;
        // dd($customer_receive);
        $customer_transfer = Customers::where('id',$customers_id_fk)->first();

        if (empty($customer_transfer->expire_date) || strtotime($customer_transfer->expire_date) < strtotime(date('Ymd'))) {
            $data = ['status'=>'fail','ms'=>'รหัสของคุณไม่มีการ Active ไม่สามารถแจงให้รหัสอื่นได้'];
            return response()->json(['status' => 'fail','ms'=>'รหัสของคุณไม่มีการ Active ไม่สามารถทำราการโอนได้'], 200);

        }

        $old_balance_user =  $customer_transfer->ewallet;

        if($customer_transfer->ewallet >= $request->amt){

            $customer_transfer->ewallet = $customer_transfer->ewallet-$request->amt;
            $customer_receive->ewallet = $customer_receive->ewallet+$request->amt;

            $dataPrepare = [//ผู้โอน
                'transaction_code' => $transaction_code,
                'customers_id_fk' => $customers_id_fk,
                'customer_username' => Auth::guard('c_user')->user()->user_name,
                'customers_id_receive' => $customer_receive->id,
                'customers_id_tranfer' => $customers_id_fk,
                'customers_username_tranfer' =>Auth::guard('c_user')->user()->user_name,
                'customers_name_receive' => $customer_receive->user_name,
                'old_balance'=> $old_balance_user,
                'balance'=>$customer_transfer->ewallet,
                // 'balance_recive'=>$customer_receive->ewallet,
                'note_orther'=>'โอนให้รหัส '.$customer_receive->user_name,
                'type_tranfer'=>'tranfer',
                'receive_date'=>date('Y-m-d'),
                'receive_time'=>date('H:i:s'),
                'amt' => $request->amt,
                'type' => 2,
                'status' => 2,
            ];
            $query =  eWallet::create($dataPrepare);

            $dataPrepare_receive = [//ผู้รับ
                'transaction_code' => $transaction_code,
                'customers_id_fk' =>  $customer_receive->id,
                'customer_username' => $customer_receive->user_name,
                'customers_id_tranfer' => $customers_id_fk,
                'customers_username_tranfer' =>Auth::guard('c_user')->user()->user_name,
                'customers_id_receive' => $customer_receive->id,
                'customers_name_receive' => $customer_receive->user_name,
                'old_balance'=>$old_balance_receive,
                'balance'=>$customer_receive->ewallet,
                'note_orther'=>'ได้รับยอดโอนจาก '.Auth::guard('c_user')->user()->user_name,

                // 'balance_recive'=>$customer_receive->ewallet,
                'type_tranfer'=>'receive',
                'receive_date'=>date('Y-m-d'),
                'receive_time'=>date('H:i:s'),
                'amt' => $request->amt,
                'type' => 2,
                'status' => 2,
            ];

            $query_receive =  eWallet::create($dataPrepare_receive);
            try {
                DB::BeginTransaction();
            $customer_transfer->save();
            $customer_receive->save();
            DB::commit();
            return response()->json(['status' => 'success'], 200);
                } catch (Exception $e) {
                    DB::rollback();
                    return response()->json(['status' => 'fail','ms'=>'เกิดข้อผิดพลาดกรุณาทำรายการไหม่'], 200);
                }
        }else{
            return response()->json(['status' => 'fail','ms'=>'eWallet ของท่านไม่เพียงพอ'], 200);
            // return redirect('home')->withError('eWallet ของท่านไม่เพียงพอ');
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

    public function check_customerbank(Request $request){
        $customer_bank = CustomersBank::where('customers_id',$request->id)->first();
        if(!empty($customer_bank)){
            $return = "true";
        }else{
            $return = "fail";
        }
         return $return;
    }



    public static function  checkcustomer_upline(Request $request)
    {
        //Request $request

        $rs_user_name_active = trim($request->user_name_active);
        $rs_user_use  = trim($request->user_use);

        $user =  DB::table('customers')
        ->select('customers.pv','customers.id','customers.name','customers.last_name','customers.user_name','customers.qualification_id','customers.expire_date',
        'dataset_qualification.pv_active','customers.introduce_id')
        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
        ->where('user_name','=', $rs_user_use)
        ->first();

        if (empty($user->expire_date) || strtotime($user->expire_date) < strtotime(date('Ymd'))) {
            $data = ['status'=>'fail','ms'=>'รหัสของคุณไม่มีการ Active ไม่สามารถแจงให้รหัสอื่นได้'];
            return $data;
        }

        // $rs_user_use  = '7492038';
        // $rs_user_name_active = '9519863';
        $rs = array();

        $user_name_active =  DB::table('customers')
        ->select('customers.pv','customers.id','customers.name','customers.last_name','customers.user_name','customers.qualification_id','customers.expire_date',
        'dataset_qualification.pv_active','customers.introduce_id')
        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
        ->where('user_name','=', $rs_user_name_active)
        ->first();
        //9519863



        if(!empty($user_name_active)){
            $name = $user_name_active->name.' '.$user_name_active->last_name;
            if (empty( $user_name_active->expire_date) || strtotime( $user_name_active->expire_date) < strtotime(date('Ymd'))) {
                if (empty( $user_name_active->expire_date)) {
                    $date_mt_active = 'Not Active';
                } else {
                    //$date_mt_active= date('d/m/Y',strtotime(Auth::guard('c_user')->user()->expire_date));
                    $date_mt_active = 'Not Active';
                }
                $status = 'danger';
            } else {
                $date_mt_active = 'Active ' . date('d/m/Y', strtotime(Auth::guard('c_user')->user()->expire_date));
                $status = 'success';
            }
            /////////////ไหม่ไม่ต้องตามสางาน
            $data = ['user_name'=>$user_name_active->user_name,'name'=>$name,'position'=>$user_name_active->qualification_id,'pv_active'=>$user_name_active->pv_active,'date_active'=>$date_mt_active,'ms'=>'Success'];
            return $data;

            ///////////////////
            // if($user_name_active->user_name == $rs_user_use || $user_name_active->introduce_id == $rs_user_use){

            //     $data = ['user_name'=>$user_name_active->user_name,'name'=>$name,'position'=>$user_name_active->qualification_id,'pv_active'=>$user_name_active->pv_active,'date_active'=>$date_mt_active,'ms'=>'Success'];
            //     return $data;
            // }else{
            //     $i=1;
            //     $user_name = $rs_user_use;

            //     while($i <= 10) {//ค้นหาด้านบน 5 ชั้น
            //         $up =  DB::table('customers')
            //         ->select('customers.name','customers.last_name','customers.user_name','customers.introduce_id')
            //         // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
            //         ->where('user_name','=',$user_name)
            //         ->first();

            //         if(empty($up)){
            //             $status = 'fail';

            //             //1240175
            //             @$user_name = $up->introduce_id;
            //             $rs ='';
            //             $i=10;
            //             break;
            //         }

            //         if($up->user_name ==  $rs_user_name_active || $up->introduce_id ==  $rs_user_name_active){
            //             $i=10;
            //             $rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'up','status'=>'success'];
            //             $status = 'success';
            //             break;
            //         }else{
            //             @$user_name = $up->introduce_id;
            //             // $rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'up','status'=>'fail'];
            //             $rs ='';
            //             $status = 'fail';
            //             if(!empty($up->name)){
            //                 $i++;
            //             }

            //         }
            //     }


            //     if($status == 'fail'){
            //         $j=1;
            //         $user_name = $user_name_active->introduce_id;
            //         while($j <= 10) {//ค้นหาด้านล่าง 5 ชั้น

            //             $up =  DB::table('customers')
            //             ->select('customers.name','customers.last_name','customers.user_name','customers.introduce_id')
            //             // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
            //             ->where('user_name','=',$user_name)
            //             ->first();
            //             // if($i==3){
            //             //     dd($up,$i);
            //             // }



            //             if(empty($up)){
            //                 $status = 'fail';
            //                 @$user_name = $up->introduce_id;
            //                 $rs ='';
            //                 //$rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'dow','status'=>'fail'];
            //                 $i=10;
            //                 break;
            //             }

            //             if($up->user_name == $rs_user_use || $up->introduce_id == $rs_user_use){
            //                 $i=10;
            //                 //$rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'dow','status'=>'success'];
            //                 $rs ='';
            //                 $status = 'success';
            //                 break;
            //             }else{
            //                 @$user_name = $up->introduce_id;
            //                 //$rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'dow','status'=>'fail'];
            //                 $rs ='';
            //                 $status = 'fail';
            //                 if(!empty($up->name)){
            //                     $j++;
            //                 }

            //             }
            //         }
            //     }
            //     // dd($rs);

            //     if( $status == 'fail'){
            //         $data = ['status'=>'fail','rs'=>$rs,'ms'=>'รหัสสมาชิกไม่อยู่ในสายงานแนะนำ'];
            //         return $data;
            //     }else{
            //         $data = ['status'=>'success','user_name'=>$user_name_active->user_name,'name'=>$name,'position'=>$user_name_active->qualification_id,'pv_active'=>$user_name_active->pv_active,'date_active'=>$date_mt_active,'rs'=>$rs,'ms'=>'Success'];
            //         return $data;
            //     }
            // }



            // for($i=1;$i<=5;$i++){
            //     if()

            // }
        }else{
            $data = ['status'=>'fail','ms'=>'รหัสสมาชิกไม่ถูกต้อง'];
            return $data;
        }

    }

    public static function  checkcustomer_upline_tranfer_pv(Request $request)
    {
        //Request $request

        $rs_user_name_active = trim($request->user_name_active);
        $rs_user_use  = trim($request->user_use);
        if($rs_user_name_active ==  $rs_user_use){
            $data = ['status'=>'fail','ms'=>'ไม่สามารถทำรายการโอน PV ให้ตัวเองได้'];
            return $data;
        }

        $user =  DB::table('customers')
        ->select('customers.pv','customers.id','customers.name','customers.last_name','customers.user_name','customers.qualification_id','customers.expire_date',
        'dataset_qualification.pv_active','customers.introduce_id')
        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
        ->where('user_name','=', $rs_user_use)
        ->first();

        if (empty($user->expire_date) || strtotime($user->expire_date) < strtotime(date('Ymd'))) {
            $data = ['status'=>'fail','ms'=>'รหัสของคุณไม่มีการ Active ไม่สามารถแจงให้รหัสอื่นได้'];
            return $data;
        }

        // $rs_user_use  = '7492038';
        // $rs_user_name_active = '9519863';
        $rs = array();

        $user_name_active =  DB::table('customers')
        ->select('customers.pv','customers.id','customers.name','customers.last_name','customers.user_name','customers.qualification_id','customers.expire_date',
        'dataset_qualification.pv_active','customers.introduce_id')
        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
        ->where('user_name','=', $rs_user_name_active)
        ->first();
        //9519863



        if(!empty($user_name_active)){
            $name = $user_name_active->name.' '.$user_name_active->last_name;
            if (empty( $user_name_active->expire_date) || strtotime( $user_name_active->expire_date) < strtotime(date('Ymd'))) {
                if (empty( $user_name_active->expire_date)) {
                    $date_mt_active = 'Not Active';
                } else {
                    //$date_mt_active= date('d/m/Y',strtotime(Auth::guard('c_user')->user()->expire_date));
                    $date_mt_active = 'Not Active';
                }
                $status = 'danger';
            } else {
                $date_mt_active = 'Active ' . date('d/m/Y', strtotime(Auth::guard('c_user')->user()->expire_date));
                $status = 'success';
            }
            //ไหม่ ไม่ต้องตามสายงาน
            $data = ['user_name'=>$user_name_active->user_name,'name'=>$name,'position'=>$user_name_active->qualification_id,'pv_active'=>$user_name_active->pv_active,'date_active'=>$date_mt_active,'ms'=>'Success'];
            return $data;


            // if($user_name_active->user_name == $rs_user_use || $user_name_active->introduce_id == $rs_user_use){

            //     $data = ['user_name'=>$user_name_active->user_name,'name'=>$name,'position'=>$user_name_active->qualification_id,'pv_active'=>$user_name_active->pv_active,'date_active'=>$date_mt_active,'ms'=>'Success'];
            //     return $data;
            // }else{
            //     $i=1;
            //     $user_name = $rs_user_use;

            //     while($i <= 10) {//ค้นหาด้านบน 5 ชั้น
            //         $up =  DB::table('customers')
            //         ->select('customers.name','customers.last_name','customers.user_name','customers.introduce_id')
            //         // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
            //         ->where('user_name','=',$user_name)
            //         ->first();

            //         if(empty($up)){
            //             $status = 'fail';

            //             //1240175
            //             @$user_name = $up->introduce_id;
            //             $rs ='';
            //             $i=10;
            //             break;
            //         }

            //         if($up->user_name ==  $rs_user_name_active || $up->introduce_id ==  $rs_user_name_active){
            //             $i=10;
            //             $rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'up','status'=>'success'];
            //             $status = 'success';
            //             break;
            //         }else{
            //             @$user_name = $up->introduce_id;
            //             // $rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'up','status'=>'fail'];
            //             $rs ='';
            //             $status = 'fail';
            //             if(!empty($up->name)){
            //                 $i++;
            //             }

            //         }
            //     }


            //     if($status == 'fail'){
            //         $j=1;
            //         $user_name = $user_name_active->introduce_id;
            //         while($j <= 10) {//ค้นหาด้านล่าง 5 ชั้น

            //             $up =  DB::table('customers')
            //             ->select('customers.name','customers.last_name','customers.user_name','customers.introduce_id')
            //             // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
            //             ->where('user_name','=',$user_name)
            //             ->first();
            //             // if($i==3){
            //             //     dd($up,$i);
            //             // }



            //             if(empty($up)){
            //                 $status = 'fail';
            //                 @$user_name = $up->introduce_id;
            //                 $rs ='';
            //                 //$rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'dow','status'=>'fail'];
            //                 $i=10;
            //                 break;
            //             }

            //             if($up->user_name == $rs_user_use || $up->introduce_id == $rs_user_use){
            //                 $i=10;
            //                 //$rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'dow','status'=>'success'];
            //                 $rs ='';
            //                 $status = 'success';
            //                 break;
            //             }else{
            //                 @$user_name = $up->introduce_id;
            //                 //$rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'dow','status'=>'fail'];
            //                 $rs ='';
            //                 $status = 'fail';
            //                 if(!empty($up->name)){
            //                     $j++;
            //                 }

            //             }
            //         }
            //     }
            //     // dd($rs);

            //     if( $status == 'fail'){
            //         $data = ['status'=>'fail','rs'=>$rs,'ms'=>'รหัสสมาชิกไม่อยู่ในสายงานแนะนำ'];
            //         return $data;
            //     }else{
            //         $data = ['status'=>'success','user_name'=>$user_name_active->user_name,'name'=>$name,'position'=>$user_name_active->qualification_id,'pv_active'=>$user_name_active->pv_active,'date_active'=>$date_mt_active,'rs'=>$rs,'ms'=>'Success'];
            //         return $data;
            //     }
            // }



            // for($i=1;$i<=5;$i++){
            //     if()

            // }
        }else{
            $data = ['status'=>'fail','ms'=>'รหัสสมาชิกไม่ถูกต้อง'];
            return $data;
        }

    }






    public function withdraw(Request $request)
    {
        $customers_id_fk =  Auth::guard('c_user')->user()->id;
        $customer_withdraw = Customers::where('id',$customers_id_fk)->first();
        if($customer_withdraw->ewallet < $request->amt){
            return redirect('home')->withError('ยอดทำรายการผิดกรุณาทำรายการไหม่อีกครั้ง');
        }

        if($customer_withdraw->ewallet_use < $request->amt){
            return redirect('home')->withError('ยอดทำรายการผิดกรุณาทำรายการไหม่อีกครั้ง');
        }

        if(empty($customer_withdraw->expire_date) || (strtotime($customer_withdraw->expire_date) < strtotime(date('Y-m-d'))) )
        {
         return redirect('home')->withError('วันที่รักษายอดไม่เพียงพอ');
        }else{
        $y = date('Y') + 543;
        $y = substr($y, -2);

        $customer_withdraw->ewallet = $customer_withdraw->ewallet-$request->amt;
        $customer_withdraw->ewallet_use = $customer_withdraw->ewallet_use-$request->amt;



        $transaction_code =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_bonus(2);


        $dataPrepare = [
            'transaction_code' => $transaction_code,
            'customers_id_fk' => $customers_id_fk,
            'customer_username'=>$customer_withdraw->user_name,
            'old_balance'=>$customer_withdraw->ewallet+$request->amt,
            'balance'=>$customer_withdraw->ewallet,
            'amt' => $request->amt,
            'type' => 3,
            'status' => 1,
        ];

        try {
            DB::BeginTransaction();
            $customer_withdraw->save();
            $query =  eWallet::create($dataPrepare);
            DB::commit();
            return redirect('home')->withSuccess('ทำรายการถอดสำเร็จ');
        }catch (\Exception $e) {
            DB::rollback();
            return redirect('home')->withSuccess('ทำรายการถอดไม่สำเร็จกรุณาทำรายการไหม่');
        }


        }



    }
}
