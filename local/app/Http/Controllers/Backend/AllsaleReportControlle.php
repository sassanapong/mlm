<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;


class AllsaleReportControlle extends Controller
{


    public function index()
    {
        $y = '2022';
        $m = '12';

        // $customers = DB::table('customers')
        // ->wherein('customers.qualification_id',['XVVIP','SVVIP','MG','MR','ME','MD'])
        // ->get();
        // // dd($customers);
        // foreach($customers as $value){

        //     $lv_1_mb = AllsaleReportControlle::count_upline($value->user_name,['MB']);
        //     $lv_1_mo = AllsaleReportControlle::count_upline($value->user_name,['MO']);
        //     $lv_1_vip = AllsaleReportControlle::count_upline($value->user_name,['VIP']);
        //     $lv_1_vvip = AllsaleReportControlle::count_upline($value->user_name,['VVIP']);
        //     $lv_1_xvvip_up = AllsaleReportControlle::count_upline($value->user_name,['XVVIP','SVVIP','MG','MR','ME','MD']);

        //     $dataPrepare = [
        //         'user_name' => $value->user_name,
        //         'name' =>  $value->name.' '.$value->last_name,
        //         'id_card' =>  $value->id_card,
        //         'qualification' => $value->qualification_id,
        //         'active_date' => $value->expire_date,
        //         // 'bonus_total' => $value->date,
        //         // 'bonus_type_7' => $value->date,
        //         // 'bonus_type_9' => $value->date,
        //         // 'bonus_type_10' => $value->date,
        //         // 'bonus_type_11' => $value->date,
        //         'lv_1_mb' =>  $lv_1_mb,
        //         'lv_1_mo' =>  $lv_1_mo,
        //         'lv_1_vip' =>  $lv_1_vip,
        //         'lv_1_vvip' =>  $lv_1_vvip,
        //         'lv_1_xvvip_up' =>  $lv_1_xvvip_up,
        //         // 'lv_2_mb' => $value->date,
        //         // 'lv_2_mo' => $value->date,
        //         // 'lv_2_vip' => $value->date,
        //         // 'lv_2_vvip' => $value->date,
        //         // 'lv_2_xvvip_up' => $value->date,
        //         // 'lv_3_mb' => $value->date,
        //         // 'lv_3_mo' => $value->date,
        //         // 'lv_3_vip' => $value->date,
        //         // 'lv_3_vvip' => $value->date,
        //         // 'lv_3_xvvip_up' => $value->date,
        //         'year' => $y,
        //         'month' => $m,

        //     ];
        //     DB::table('report_bonus_all_sale')
        //     ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y,'month'=>$m],$dataPrepare);
        // }


        // $request['s_date'] = date('2022-12-01');
        // $request['e_date'] = date('2022-12-t');

        // $ewallet = DB::table('ewallet')
        // ->selectRaw('customers.id,customers.user_name,customers.name,sum(bonus_full) as bonus_full')
        // ->leftjoin('customers', 'ewallet.customer_username', '=', 'customers.user_name')
        // ->wherein('customers.qualification_id',['XVVIP','SVVIP','MG','MR','ME','MD'])
        // ->wherein('ewallet.type',['7','9','10','11'])
        // ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' = ''  THEN  date(ewallet.created_at) = '{$request['s_date']}' else 1 END"))
        // ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' != ''  THEN  date(ewallet.created_at) >= '{$request['s_date']}' and date(ewallet.created_at) <= '{$request['e_date']}'else 1 END"))
        // ->whereRaw(("case WHEN '{$request['s_date']}' = '' and '{$request['e_date']}' != ''  THEN  date(ewallet.created_at) = '{$request['e_date']}' else 1 END"))
        // ->groupby('ewallet.customer_username')
        // ->get();


        // foreach($ewallet as $value){
        //     $bonus_type_7 = DB::table('ewallet')
        //     ->selectRaw('sum(bonus_full) as bonus_type_7')
        //     ->where('ewallet.customer_username','=', $value->user_name)
        //     ->where('ewallet.type','=',7)
        //     ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' = ''  THEN  date(ewallet.created_at) = '{$request['s_date']}' else 1 END"))
        //     ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' != ''  THEN  date(ewallet.created_at) >= '{$request['s_date']}' and date(ewallet.created_at) <= '{$request['e_date']}'else 1 END"))
        //     ->whereRaw(("case WHEN '{$request['s_date']}' = '' and '{$request['e_date']}' != ''  THEN  date(ewallet.created_at) = '{$request['e_date']}' else 1 END"))
        //     ->groupby('ewallet.customer_username')
        //     ->first();

        //     $bonus_type_9 = DB::table('ewallet')
        //     ->selectRaw('sum(bonus_full) as bonus_type_9')
        //     ->where('ewallet.customer_username','=',$value->user_name)
        //     ->where('ewallet.type','=',9)
        //     ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' = ''  THEN  date(ewallet.created_at) = '{$request['s_date']}' else 1 END"))
        //     ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' != ''  THEN  date(ewallet.created_at) >= '{$request['s_date']}' and date(ewallet.created_at) <= '{$request['e_date']}'else 1 END"))
        //     ->whereRaw(("case WHEN '{$request['s_date']}' = '' and '{$request['e_date']}' != ''  THEN  date(ewallet.created_at) = '{$request['e_date']}' else 1 END"))
        //     ->groupby('ewallet.customer_username')
        //     ->first();
        //     $bonus_type_10 = DB::table('ewallet')
        //     ->selectRaw('sum(bonus_full) as bonus_type_10')
        //     ->where('ewallet.customer_username','=',$value->user_name)
        //     ->where('ewallet.type','=',10)
        //     ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' = ''  THEN  date(ewallet.created_at) = '{$request['s_date']}' else 1 END"))
        //     ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' != ''  THEN  date(ewallet.created_at) >= '{$request['s_date']}' and date(ewallet.created_at) <= '{$request['e_date']}'else 1 END"))
        //     ->whereRaw(("case WHEN '{$request['s_date']}' = '' and '{$request['e_date']}' != ''  THEN  date(ewallet.created_at) = '{$request['e_date']}' else 1 END"))
        //     ->groupby('ewallet.customer_username')
        //     ->first();
        //     $bonus_type_11 = DB::table('ewallet')
        //     ->selectRaw('sum(bonus_full) as bonus_type_11')
        //     ->where('ewallet.customer_username','=',$value->user_name)
        //     ->where('ewallet.type','=',11)
        //     ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' = ''  THEN  date(ewallet.created_at) = '{$request['s_date']}' else 1 END"))
        //     ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' != ''  THEN  date(ewallet.created_at) >= '{$request['s_date']}' and date(ewallet.created_at) <= '{$request['e_date']}'else 1 END"))
        //     ->whereRaw(("case WHEN '{$request['s_date']}' = '' and '{$request['e_date']}' != ''  THEN  date(ewallet.created_at) = '{$request['e_date']}' else 1 END"))
        //     ->groupby('ewallet.customer_username')
        //     ->first();
        //     $dataPrepare = [
        //         'bonus_total' => @$value->bonus_full,
        //         'bonus_type_7' => @$bonus_type_7->bonus_type_7,
        //         'bonus_type_9' => @$bonus_type_9->bonus_type_9,
        //         'bonus_type_10' => @$bonus_type_10->bonus_type_10,
        //         'bonus_type_11' => @$bonus_type_11->bonus_type_11,
        //         'year' => $y,
        //         'month' => $m,

        //     ];
        //     DB::table('report_bonus_all_sale')
        //     ->updateOrInsert(
        //         ['user_name' => $value->user_name, 'year' => $y,'month'=>$m],
        //         $dataPrepare
        //     );

        // }
        //  dd('success 2');

        return view('backend/AllSale_report/index');

    }

    public function count_upline($user_name,$position)
    {
        $count = DB::table('customers')
        ->wherein('customers.qualification_id',$position)
        ->where('customers.introduce_id',$user_name)
        ->count();
        return $count;
    }

    public function allsale_report_datable(Request $request)
    {

        $report_bonus_all_sale = DB::table('report_bonus_all_sale')

        // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
        // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
        // ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  report_bonus_all_sale.user_name = '{$request->user_name}' else 1 END"));


        $sQuery = Datatables::of($report_bonus_all_sale);
        return $sQuery
        ->addIndexColumn()

            ->addColumn('bonus_total', function ($row) {
                if($row->bonus_total){
                    return number_format($row->bonus_total,2);
                }else{
                    return 0;
                }
            })
            ->addColumn('bonus_type_7', function ($row) {
                if($row->bonus_type_7){
                    return number_format($row->bonus_type_7,2);
                }else{
                    return 0;
                }
            })
            ->addColumn('bonus_type_9', function ($row) {
                if($row->bonus_type_9){
                    return number_format($row->bonus_type_9,2);
                }else{
                    return 0;
                }
            })

            ->addColumn('bonus_type_10', function ($row) {
                if($row->bonus_type_10){
                    return number_format($row->bonus_type_10,2);
                }else{
                    return 0;
                }
            })

            ->addColumn('bonus_type_11', function ($row) {
                if($row->bonus_type_11){
                    return number_format($row->bonus_type_11,2);
                }else{
                    return 0;
                }
            })

              ->addColumn('active_date', function ($row) {
                if(empty($row->active_date) || (strtotime($row->active_date) < strtotime(date('Ymd')))){

                    $date_tv_active= date('d/m/Y',strtotime($row->active_date));
                    $resule ='<span class="text-danger">Not Active</span>';
                    return $resule;
                }else{
                    $date_tv_active= date('d/m/Y',strtotime($row->active_date));
                    $resule ='<span class="text-success">Active</span>';
                    return $resule;

                }
            })




            ->rawColumns(['active_date'])

            ->make(true);
    }
}
