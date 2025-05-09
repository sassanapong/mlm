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
        //   $data = AllsaleReportControlle::vl_2_3();
        //  dd($data);
        return view('backend/AllSale_report/index');

    } 

    public function run_report_allsale()
    {
        $y = '2024';
        $m = '02';
        $e_date = date('2024-02-31');

        $report_bonus3_delete =  DB::table('report_bonus_all_sale')
        ->where('year',$y)
        ->where('month',$m)
        ->delete(); 
  
        $customers = DB::table('customers')
        // ->wherein('customers.qualification_id',['XVVIP','SVVIP','MG','MR','ME','MD'])
        // ->wheredate('customers.expire_date','>=',$e_date)
 
         ->wherein('customers.user_name',['0767667',
                '3199015',
                '1169186',
                '1160717',
                '4310586',
                '7492038',
                '1258155',
                '1220055',
                '1161618',
                '6088918',
                '3158469',
                '1833814',
                '0509339',
                '7302369',
                '0915303',
                '6880888',
                '1299934',
                '1242373',
                '1242848'])
            ->get();
 
 
        foreach($customers as $value){
         

            $lv_1_mb = AllsaleReportControlle::count_upline($value->user_name,['MB'],$e_date);
            $lv_1_mo = AllsaleReportControlle::count_upline($value->user_name,['MO'],$e_date);
            $lv_1_vip = AllsaleReportControlle::count_upline($value->user_name,['VIP'],$e_date);
            $lv_1_vvip = AllsaleReportControlle::count_upline($value->user_name,['VVIP'],$e_date);
            $lv_1_xvvip_up = AllsaleReportControlle::count_upline($value->user_name,['XVVIP','SVVIP','MG','MR','ME','MD'],$e_date);

            $dataPrepare = [
                'user_name' => $value->user_name,
                'name' =>  $value->name.' '.$value->last_name,
                'id_card' =>  $value->id_card,
                'qualification' => $value->qualification_id,
                'active_date' => $value->expire_date,
                // 'bonus_total' => $value->date,
                // 'bonus_type_7' => $value->date,
                // 'bonus_type_9' => $value->date,
                // 'bonus_type_10' => $value->date,
                // 'bonus_type_11' => $value->date,
                'lv_1_mb' =>  $lv_1_mb,
                'lv_1_mo' =>  $lv_1_mo,
                'lv_1_vip' =>  $lv_1_vip,
                'lv_1_vvip' =>  $lv_1_vvip, 
                'lv_1_xvvip_up' =>  $lv_1_xvvip_up,
                // 'lv_2_mb' => $value->date,
                // 'lv_2_mo' => $value->date,
                // 'lv_2_vip' => $value->date, 
                // 'lv_2_vvip' => $value->date,
                // 'lv_2_xvvip_up' => $value->date,
                // 'lv_3_mb' => $value->date,
                // 'lv_3_mo' => $value->date,
                // 'lv_3_vip' => $value->date,
                // 'lv_3_vvip' => $value->date,
                // 'lv_3_xvvip_up' => $value->date,
                'year' => $y,
                'month' => $m,
 
            ];
            DB::table('report_bonus_all_sale')
            ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y,'month'=>$m],$dataPrepare);
        }

        dd('success 1'); 

        // $request['s_date'] = date('2023-03-01');
        // $request['e_date'] = date('2023-03-31');

        // $ewallet = DB::table('ewallet')
        // ->selectRaw('customers.id,customers.user_name,customers.name,sum(bonus_full) as bonus_full')
        // ->leftjoin('customers', 'ewallet.customer_username', '=', 'customers.user_name')
        // ->wherein('customers.qualification_id',['XVVIP','SVVIP','MG','MR','ME','MD'])
        // ->wherein('ewallet.type',['7','9','10','11'])
        // ->wheredate('customers.expire_date','>=',$e_date)
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
            // $dataPrepare = [
            //     'bonus_total' => @$value->bonus_full,
            //     'bonus_type_7' => @$bonus_type_7->bonus_type_7,
            //     'bonus_type_9' => @$bonus_type_9->bonus_type_9,
            //     'bonus_type_10' => @$bonus_type_10->bonus_type_10,
            //     'bonus_type_11' => @$bonus_type_11->bonus_type_11,
            //     'year' => $y,
            //     'month' => $m,

            // ];
            // DB::table('report_bonus_all_sale')
            // ->updateOrInsert(
            //     ['user_name' => $value->user_name, 'year' => $y,'month'=>$m],
            //     $dataPrepare
            // );

        //}
         dd('success 2');

    }
    public function vl_2_3(){
        $e_date = date('2024-02-31');
        $report_bonus_all_sale = DB::table('report_bonus_all_sale')
        ->where('lv_2_mb','=',null)
        // ->limit(50)
        ->get();

        $array_lv_1 = array();
        $array_lv_2 = array(); 
        $y = '2024';
        $m = '02';
         $i = 0; 
        foreach($report_bonus_all_sale as $value){
            $i++;
            $customers = DB::table('customers')
           ->select('user_name')
           ->where('customers.introduce_id','=',$value->user_name)
           ->get();
           foreach($customers as $vl_1){
            $array_lv_1[] = $vl_1->user_name;
           }

           if(@$array_lv_1){
            $customers_lv2_mb = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_1)
            ->where('qualification_id','=','MB')
            ->wheredate('customers.expire_date', '>=', $e_date)
            ->count();

            $customers_lv2_mo = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_1)
            ->where('qualification_id','=','MO')
            ->wheredate('customers.expire_date', '>=', $e_date)
            ->count();

            $customers_lv2_vip = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_1)
            ->where('qualification_id','=','VIP')
            ->wheredate('customers.expire_date', '>=', $e_date)
            ->count();

            $customers_lv2_vvip = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_1)
            ->where('qualification_id','=','VVIP')
            ->wheredate('customers.expire_date', '>=', $e_date)
            ->count();

            $customers_lv2_xvvipup = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_1)
            ->wherein('customers.qualification_id',['XVVIP','SVVIP','MG','MR','ME','MD'])
            ->wheredate('customers.expire_date', '>=', $e_date)
            ->count();


            $dataPrepare = [
                'user_name' => $value->user_name,
                'lv_2_mb' => $customers_lv2_mb,
                'lv_2_mo' => $customers_lv2_mo,
                'lv_2_vip' => $customers_lv2_vip,
                'lv_2_vvip' => $customers_lv2_vvip,
                'lv_2_xvvip_up' => $customers_lv2_xvvipup,

                'year' => $y,
                'month' => $m,
            ];

            DB::table('report_bonus_all_sale')
            ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y,'month'=>$m],$dataPrepare);

            $customers_vl2 = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_1)
            ->get();


            foreach($customers_vl2 as $vl_2){
             $array_lv_2[] = $vl_2->user_name;
            }


            if(@$array_lv_2){

            $customers_lv3_mb = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_2)
            ->where('qualification_id','=','MB')
            ->wheredate('customers.expire_date', '>=', $e_date)
            ->count();



            $customers_lv3_mo = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_2)
            ->where('qualification_id','=','MO')
            ->wheredate('customers.expire_date', '>=', $e_date)
            ->count();

            $customers_lv3_vip = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_2)
            ->where('qualification_id','=','VIP')
            ->wheredate('customers.expire_date', '>=', $e_date)
            ->count();

            $customers_lv3_vvip = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_2)
            ->where('qualification_id','=','VVIP')
            ->wheredate('customers.expire_date', '>=', $e_date)
            ->count();

            $customers_lv3_xvvipup = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_2)
            ->wherein('customers.qualification_id',['XVVIP','SVVIP','MG','MR','ME','MD'])
            ->wheredate('customers.expire_date', '>=', $e_date)
            ->count();

            $dataPrepare = [
                'user_name' => $value->user_name,

                'lv_3_mb' =>  $customers_lv3_mb,
                'lv_3_mo' =>  $customers_lv3_mo,
                'lv_3_vip' =>  $customers_lv3_vip,
                'lv_3_vvip' =>  $customers_lv3_vvip,
                'lv_3_xvvip_up' =>  $customers_lv3_xvvipup,


                'year' => $y,
                'month' => $m,
            ];

            DB::table('report_bonus_all_sale')
            ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y,'month'=>$m],$dataPrepare);



            $customers_vl3 = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_2)
            ->get();


            foreach($customers_vl3 as $vl_3){
             $array_lv_3[] = $vl_3->user_name;
            }

            if(@$array_lv_3){

                $customers_lv4_mb = DB::table('customers')
                ->select('user_name')
                ->wherein('customers.introduce_id',$array_lv_3)
                ->where('qualification_id','=','MB')
                ->wheredate('customers.expire_date', '>=', $e_date)
                ->count();


                $customers_lv4_mo = DB::table('customers')
                ->select('user_name')
                ->wherein('customers.introduce_id',$array_lv_3)
                ->where('qualification_id','=','MO')
                ->wheredate('customers.expire_date', '>=', $e_date)
                ->count();

                $customers_lv4_vip = DB::table('customers')
                ->select('user_name')
                ->wherein('customers.introduce_id',$array_lv_3)
                ->where('qualification_id','=','VIP')
                ->wheredate('customers.expire_date', '>=', $e_date)
                ->count();

                $customers_lv4_vvip = DB::table('customers')
                ->select('user_name')
                ->wherein('customers.introduce_id',$array_lv_3)
                ->where('qualification_id','=','VVIP')
                ->wheredate('customers.expire_date', '>=', $e_date)
                ->count();

                $customers_lv4_xvvipup = DB::table('customers')
                ->select('user_name')
                ->wherein('customers.introduce_id',$array_lv_3)
                ->wherein('customers.qualification_id',['XVVIP','SVVIP','MG','MR','ME','MD'])
                ->wheredate('customers.expire_date', '>=', $e_date)
                ->count();

                $dataPrepare = [
                    'user_name' => $value->user_name,
                    'lv_4_mb' =>  $customers_lv4_mb,
                    'lv_4_mo' =>  $customers_lv4_mo,
                    'lv_4_vip' =>  $customers_lv4_vip,
                    'lv_4_vvip' =>  $customers_lv4_vvip,
                    'lv_4_xvvip_up' =>  $customers_lv4_xvvipup,
                    'year' => $y,
                    'month' => $m,
                ];

                DB::table('report_bonus_all_sale')
                ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y,'month'=>$m],$dataPrepare);



                $customers_vl4 = DB::table('customers')
                ->select('user_name')
                ->wherein('customers.introduce_id',$array_lv_3)
                ->get();


                foreach($customers_vl4 as $vl_4){
                 $array_lv_4[] = $vl_4->user_name;
                }

                if(@$array_lv_4){

                    $customers_lv5_mb = DB::table('customers')
                    ->select('user_name')
                    ->wherein('customers.introduce_id',$array_lv_4)
                    ->where('qualification_id','=','MB')
                    ->wheredate('customers.expire_date', '>=', $e_date)
                    ->count();

                    $customers_lv5_mo = DB::table('customers')
                    ->select('user_name')
                    ->wherein('customers.introduce_id',$array_lv_4)
                    ->where('qualification_id','=','MO')
                    ->wheredate('customers.expire_date', '>=', $e_date)
                    ->count();

                    $customers_lv5_vip = DB::table('customers')
                    ->select('user_name')
                    ->wherein('customers.introduce_id',$array_lv_4)
                    ->where('qualification_id','=','VIP')
                    ->wheredate('customers.expire_date', '>=', $e_date)
                    ->count();

                    $customers_lv5_vvip = DB::table('customers')
                    ->select('user_name')
                    ->wherein('customers.introduce_id',$array_lv_4)
                    ->where('qualification_id','=','VVIP')
                    ->wheredate('customers.expire_date', '>=', $e_date)
                    ->count();

                    $customers_lv5_xvvipup = DB::table('customers')
                    ->select('user_name')
                    ->wherein('customers.introduce_id',$array_lv_4)
                    ->wherein('customers.qualification_id',['XVVIP','SVVIP','MG','MR','ME','MD'])
                    ->wheredate('customers.expire_date', '>=', $e_date)
                    ->count();

                    $dataPrepare = [
                        'user_name' => $value->user_name,
                        'lv_5_mb' =>  $customers_lv5_mb,
                        'lv_5_mo' =>  $customers_lv5_mo,
                        'lv_5_vip' =>  $customers_lv5_vip,
                        'lv_5_vvip' =>  $customers_lv5_vvip,
                        'lv_5_xvvip_up' =>  $customers_lv5_xvvipup,
                        'year' => $y,
                        'month' => $m,
                    ];

                    DB::table('report_bonus_all_sale')
                    ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y,'month'=>$m],$dataPrepare);
                    unset($array_lv_1);
                    unset($array_lv_2);
                    unset($array_lv_3);
                    unset($array_lv_4);
                    unset($array_lv_5);
                }else{
                    $dataPrepare = [
                        'user_name' => $value->user_name,
                        'lv_5_mb' =>  0,
                        'lv_5_mo' =>  0,
                        'lv_5_vip' =>  0,
                        'lv_5_vvip' =>  0,
                        'lv_5_xvvip_up' =>  0,
                        'year' => $y,
                        'month' => $m,
                    ];

                    DB::table('report_bonus_all_sale')
                    ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y,'month'=>$m],$dataPrepare);
                    unset($array_lv_1);
                    unset($array_lv_2);
                    unset($array_lv_3);
                    unset($array_lv_4);
                    unset($array_lv_5);


                }



            }else{
                $dataPrepare = [
                    'user_name' => $value->user_name,
                    'lv_4_mb' =>  0,
                    'lv_4_mo' =>  0,
                    'lv_4_vip' => 0,
                    'lv_4_vvip' => 0,
                    'lv_4_xvvip_up' =>0,
                    'lv_5_mb' =>  0,
                    'lv_5_mo' =>  0,
                    'lv_5_vip' => 0,
                    'lv_5_vvip' => 0,
                    'lv_5_xvvip_up' =>0,
                    'year' => $y,
                    'month' => $m,
                ];

                DB::table('report_bonus_all_sale')
                ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y,'month'=>$m],$dataPrepare);
                unset($array_lv_1);
                unset($array_lv_2);
                unset($array_lv_3);
                unset($array_lv_4);
                unset($array_lv_5);

            }



            }else{
                $customers_lv3_mb = 0;
                 $customers_lv3_mo = 0;
                 $customers_lv3_vip = 0;
                 $customers_lv3_vvip = 0;
                $customers_lv3_xvvipup = 0;

                $dataPrepare = [
                    'user_name' => $value->user_name,
                    'lv_3_mb' =>  $customers_lv3_mb,
                    'lv_3_mo' =>  $customers_lv3_mo,
                    'lv_3_vip' =>  $customers_lv3_vip,
                    'lv_3_vvip' =>  $customers_lv3_vvip,
                    'lv_3_xvvip_up' =>  $customers_lv3_xvvipup,
                    'lv_4_mb' =>  0,
                    'lv_4_mo' =>  0,
                    'lv_4_vip' => 0,
                    'lv_4_vvip' => 0,
                    'lv_4_xvvip_up' =>0,
                    'lv_5_mb' =>  0,
                    'lv_5_mo' =>  0,
                    'lv_5_vip' => 0,
                    'lv_5_vvip' => 0,
                    'lv_5_xvvip_up' =>0,


                    'year' => $y,
                    'month' => $m,
                ];

                DB::table('report_bonus_all_sale')
                ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y,'month'=>$m],$dataPrepare);
                unset($array_lv_1);
                unset($array_lv_2);
                unset($array_lv_3);
                unset($array_lv_4);
                unset($array_lv_5);

            }



           }else{
            $dataPrepare = [
                'user_name' => $value->user_name,
                'lv_2_mb' => 0,
                'lv_2_mo' => 0,
                'lv_2_vip' => 0,
                'lv_2_vvip' => 0,
                'lv_2_xvvip_up' => 0,
                'lv_3_mb' =>  0,
                'lv_3_mo' =>  0,
                'lv_3_vip' =>  0,
                'lv_3_vvip' =>  0,
                'lv_3_xvvip_up' => 0,
                'lv_4_mb' =>  0,
                'lv_4_mo' =>  0,
                'lv_4_vip' =>  0,
                'lv_4_vvip' =>  0,
                'lv_4_xvvip_up' => 0,
                'lv_5_mb' =>  0,
                'lv_5_mo' =>  0,
                'lv_5_vip' =>  0,
                'lv_5_vvip' =>  0,
                'lv_5_xvvip_up' => 0,
                'year' => $y,
                'month' => $m,

            ];
            DB::table('report_bonus_all_sale')
            ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y,'month'=>$m],$dataPrepare);
            unset($array_lv_1);
            unset($array_lv_2);
            unset($array_lv_3);
            unset($array_lv_4);
            unset($array_lv_5);
           }
 
        }
        dd('success');

    }

    public static function count_upline($user_name,$position, $e_date)
    {
        $count = DB::table('customers')
        ->wherein('customers.qualification_id',$position)
        ->wheredate('customers.expire_date', '>=', $e_date)
        ->where('customers.introduce_id',$user_name)
        ->count();
        return $count;
    }

    public function allsale_report_datable(Request $request)
    {

        $report_bonus_all_sale = DB::table('report_bonus_all_sale')
        ->where('year','=',$request->s_date)
        ->where('month','=',$request->e_date)
        // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
        // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
        // ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  report_bonus_all_sale.user_name = '{$request->user_name}' else 1 END"))
        ->orderby('bonus_total','DESC');


        $sQuery = Datatables::of($report_bonus_all_sale);
        return $sQuery
        ->addIndexColumn()
        ->addColumn('date', function ($row) {

                return $row->year.'/'.$row->month;

        })
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
                // if(empty($row->active_date) || (strtotime($row->active_date) < strtotime(date('Ymd')))){

                //     $date_tv_active= date('d/m/Y',strtotime($row->active_date));
                //     $resule ='<span class="text-danger">Not Active</span>';
                //     return $resule;
                // }else{
                //     $date_tv_active= date('d/m/Y',strtotime($row->active_date));
                //     $resule ='<span class="text-success">Active</span>';
                //     return $resule;

                // }
                 return  $date_tv_active= date('d/m/Y',strtotime($row->active_date));
            })




            ->rawColumns(['active_date'])

            ->make(true);
    }
}
