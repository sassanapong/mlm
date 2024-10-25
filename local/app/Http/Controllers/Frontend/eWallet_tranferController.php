<?php

namespace App\Http\Controllers\Frontend;

use App\Customers;
use App\CustomersBank;
use App\eWallet;
use App\eWallet_tranfer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;
use PhpParser\Node\Expr\FuncCall;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use DB;

class eWallet_tranferController extends Controller
{

    public function index()
    {

        return view('frontend/eWallet-TranferHistory');
    }



    public function eWallet_TranferHistory_table(Request $rs)
    {
        $s_date = !empty($rs->s_date) ? date('Y-m-d', strtotime($rs->s_date)) : date('Y-01-01');
        $e_date = !empty($rs->e_date) ? date('Y-m-d', strtotime($rs->e_date)) : date('Y-12-t');
        $user_name = Auth::guard('c_user')->user()->user_name;

        $ewallet_tranfer = DB::table('ewallet_tranfer')
            ->where('customer_username', '=',$user_name)
            ->orderby('id','DESC');

        $sQuery = Datatables::of($ewallet_tranfer);
        return $sQuery

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

         ->editColumn('date_mark', function ($query) {

            if(empty($query->date_mark)|| $query->date_mark == '0000-00-00 00:00:00'){
                $time = '';
            }else{
                $time = date('Y/m/d H:i:s', strtotime($query->date_mark));
            }


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

}
