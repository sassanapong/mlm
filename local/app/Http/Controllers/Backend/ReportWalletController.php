<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;


class ReportWalletController extends Controller
{


    public function index()
    {

        // $ewallet = DB::table('ewallet')
        // ->limit(10)
        // ->get();

        // dd($ewallet);

        return view('backend/wallet_report/index');

    }

    public function wallet_report_datable(Request $request)
    {
        $business_location_id = 1;


        $ewallet = DB::table('ewallet')
        ->select('ewallet.*','customers.id_card','customers_address_card.address','customers_address_card.moo'
        ,'customers_address_card.soi','customers_address_card.road','district_name as district','province_name as province',
        'tambon_name as tambon','customers_address_card.zipcode','customers.name as c_name','customers.last_name','customers.qualification_id')

        ->leftjoin('customers', 'ewallet.customer_username', '=', 'customers.user_name')
        ->leftjoin('customers_address_card', 'ewallet.customer_username', '=', 'customers_address_card.user_name')
        ->leftjoin('address_districts', 'address_districts.district_id', 'customers_address_card.district')
        ->leftjoin('address_provinces', 'address_provinces.province_id', 'customers_address_card.province')
        ->leftjoin('address_tambons', 'address_tambons.tambon_id', 'customers_address_card.tambon')
        ->where('ewallet.type','=',1)
        ->where('ewallet.status','=',2)
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(ewallet.date_mark) = '{$request->s_date}' else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(ewallet.date_mark) >= '{$request->s_date}' and date(ewallet.date_mark) <= '{$request->e_date}'else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(ewallet.date_mark) = '{$request->e_date}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  ewallet.customer_username = '{$request->user_name}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->code_order}' != ''  THEN  ewallet.transaction_code = '{$request->code_order}' else 1 END"));


        $sQuery = Datatables::of($ewallet);
        return $sQuery

            ->setRowClass('intro-x py-4 h-24 zoom-in')
            ->addColumn('created_at', function ($row) {
                return date('Y/m/d H:i:s', strtotime($row->created_at));
            })

            ->addColumn('approve_date', function ($row) {
                return date('Y/m/d H:i:s', strtotime($row->date_mark));
            })

            ->addColumn('bill_date', function ($row) {

                return date('Y/m/d', strtotime($row->receive_date)).' '.$row->receive_date;
            })


            ->addColumn('name', function ($row) {

                return $row->c_name.' '.$row->last_name;
            })



            ->addColumn('amt', function ($row) {
                if($row->edit_amt>0){
                    $amt = number_format($row->edit_amt,2);
                }else{
                    $amt = number_format($row->amt,2);
                }


                return $amt;
            })

            // ->addColumn('type', function ($row) {
            //     $type = $row->type;
            //     if ($type  == 1) {
            //         $text_type = "ฝากเงิน";
            //     }
            //     if ($type  == 2) {
            //         $text_type = "โอนเงิน";
            //     }
            //     if ($type  == 3) {
            //         $text_type = "ถอนเงิน";
            //     }
            //     if ($type  == 4) {
            //         $text_type = "ซื้อสินค้า";
            //     }
            //     if ($type  == 5) {
            //         $text_type = "แจงลูกค้าประจำ";
            //     }
            //     if ($type  == 6) {
            //         $text_type = "บริหารทีมลูกค้าประจำ";
            //     }
            //     if ($type  == 7) {
            //         $text_type = "สนับสนุนสินค้า";
            //     }
            //     if ($type  == 8) {
            //         $text_type = "โบนัสบริหารทีม";
            //     }
            //     if ($type  == 9) {
            //         $text_type = "โบนัสเจ้าของลิขสิทธิ์";
            //     }

            //     if ($type  == 10) {
            //         $text_type = "โบนัสโบนัสขยายธุรกิจ";
            //     }

            //     if ($type  == 11) {
            //         $text_type = "โบนัสสร้างทีม";
            //     }
            //     return $text_type;
            // })






            ->addColumn('address', function ($row) {
                if($row->district){
                    $address = $row->address.' ม.'.$row->moo.' ซอย.'.$row->soi.' ถนน.'.$row->road.' ต.'.$row->tambon.' อ.'.$row->province.' จ.'.$row->district.' '.$row->zipcode;
                }else{
                    $address = '';
                }
                return $address;
            })




            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }
}
