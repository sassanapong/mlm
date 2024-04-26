<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;


class EasyReportReportController extends Controller
{


    public function index()
    {
        // $s_date = date('2022-12-24');
        // $e_date = date('2023-01-06');
        // $user_name = '3266422';
        // $report_bonus_active = DB::table('db_orders')
        // ->selectRaw('customers.id,customers.user_name,customers.name,customers.last_name,customers.expire_date,qualification_id,sum(pv_total) as pv_total')
        // ->leftjoin('customers', 'db_orders.customers_user_name', '=', 'customers.user_name')
        // ->wheredate('customers.expire_date','>',now())
        // // ->leftjoin('log_up_vl', 'log_up_vl.introduce_id', '=', 'customers.user_name')
        // ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(db_orders.created_at) = '{$s_date}' else 1 END"))
        // ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(db_orders.created_at) >= '{$s_date}' and date(db_orders.created_at) <= '{$e_date}'else 1 END"))
        // ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(db_orders.created_at) = '{$e_date}' else 1 END"))
        // ->whereRaw(("case WHEN  '{$user_name}' != ''  THEN  customers.user_name = '{$user_name}' else 1 END"))
        // // ->orwhere('log_up_vl.new_lavel','=','XVVIP')
        // ->orderby('pv_total','DESC')
        // ->groupby('customers.user_name')
        // ->get(); 

        // $data =  EasyReportReportController::run_easy();
        // dd($data);
        return view('backend/Easy_report/index');
    }


    public function easy_report_datable(Request $request)
    {


        $business_location_id = 1;
        //sum(pv_total) as pv_total
        $report_bonus_active = DB::table('report_bonus_easy')

            ->where('year', '=', $request->s_date)
            ->where('month', '=', $request->e_date)
            // ->where('active_date','!=',null)
            ->where('route', '=', $request->route)
            ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  report_bonus_easy.user_name = '{$request->user_name}' else 1 END"));


        $sQuery = Datatables::of($report_bonus_active);
        return $sQuery
            ->addIndexColumn()


            ->addColumn('date_data', function ($row) {
                $date = $row->year . '-' . $row->month;
                return $date;
            })

            ->addColumn('pv_total', function ($row) {


                if ($row->pv_order) {
                    return  number_format($row->pv_order);
                } else {
                    return 0;
                }
            })

            ->addColumn('FastStart', function ($row) {



                if ($row->pv_faststart) {
                    return  number_format($row->pv_faststart);
                } else {
                    return 0;
                }


                // return $xvvip_new;
            })

            ->addColumn('pv_xvvip', function ($row) {
                if ($row->pv_xvvip) {
                    return  number_format($row->pv_xvvip);
                } else {
                    return 0;
                }
            })

            ->addColumn('pv_active', function ($row) {

                if ($row->pv_active) {
                    return  number_format($row->pv_active);
                } else {
                    return 0;
                }
            })

            ->addColumn('note', function ($row) {


                return $row->note;
            })



            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }

    public function run_easy()
    {

        $y = '2024';
        $m = '04';
        $route = '1';
        $s_date = date('2024-03-06');
        $e_date = date('2024-04-05');

        //check
        // $db_orders =  DB::table('db_orders') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //     ->selectRaw('db_orders.customers_user_name,code_order,count(code_order) as count_code')
        //     ->leftjoin('customers', 'db_orders.customers_user_name', '=', 'customers.user_name')
        //     ->wheredate('customers.expire_date', '>=', $e_date)
        //     ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(db_orders.created_at) = '{$s_date}' else 1 END"))
        //     ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(db_orders.created_at) >= '{$s_date}' and date(db_orders.created_at) <= '{$e_date}'else 1 END"))
        //     ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(db_orders.created_at) = '{$e_date}' else 1 END"))
        //     ->havingRaw('count(count_code) > 1 ')
        //     ->groupby('db_orders.code_order')
        //     ->get();
        // dd($db_orders);



        // $pv_total =  DB::table('db_orders') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //     ->selectRaw('db_orders.customers_user_name,sum(db_orders.pv_total) as pv_total,customers.name,customers.last_name,customers.expire_date,customers.qualification_id')
        //     ->leftjoin('customers', 'db_orders.customers_user_name', '=', 'customers.user_name')
        //     ->wheredate('customers.expire_date', '>=', $e_date)
        //     ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(db_orders.created_at) = '{$s_date}' else 1 END"))
        //     ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(db_orders.created_at) >= '{$s_date}' and date(db_orders.created_at) <= '{$e_date}'else 1 END"))
        //     ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(db_orders.created_at) = '{$e_date}' else 1 END"))
        //     ->groupby('db_orders.customers_user_name')
        //     ->get();
        // foreach ($pv_total as $value) {
        //     $dataPrepare = [
        //         'user_name' => $value->customers_user_name,
        //         'name' =>  $value->name . ' ' . $value->last_name,
        //         'pv_order' => $value->pv_total,
        //         'qualification' => $value->qualification_id,
        //         'active_date' => $value->expire_date,
        //         'year' => $y,
        //         'month' => $m,
        //         'route' => $route,
        //         'note' => 'รอบที่ 1 วันที่ 06/03/2024 ถึงวันที่ 05/04/2024',

        //     ];
        //     DB::table('report_bonus_easy')
        //         ->updateOrInsert(['user_name' => $value->customers_user_name, 'year' => $y, 'month' => $m, 'route' => $route], $dataPrepare);
        // }
        // dd('success1');


        // $pv_faststart =  DB::table('report_bonus_register') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //     ->selectRaw('report_bonus_register.regis_user_introduce_id,sum(report_bonus_register.pv) as pv_total,customers.name,customers.last_name,customers.expire_date,customers.qualification_id')
        //     ->leftjoin('customers', 'report_bonus_register.regis_user_introduce_id', '=', 'customers.user_name')
        //     ->where('g', '=', '1')
        //     ->wheredate('customers.expire_date', '>=', $e_date)
        //     ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(report_bonus_register.created_at) = '{$s_date}' else 1 END"))
        //     ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(report_bonus_register.created_at) >= '{$s_date}' and date(report_bonus_register.created_at) <= '{$e_date}'else 1 END"))
        //     ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(report_bonus_register.created_at) = '{$e_date}' else 1 END"))
        //     ->groupby('report_bonus_register.regis_user_introduce_id')
        //     ->get();

        // foreach ($pv_faststart as $value) {
        //     $dataPrepare = [
        //         'user_name' => $value->regis_user_introduce_id,
        //         'name' =>  $value->name . ' ' . $value->last_name,
        //         'pv_faststart' => $value->pv_total,
        //         'qualification' => $value->qualification_id,
        //         'active_date' => $value->expire_date,
        //         'year' => $y,
        //         'month' => $m,
        //         'note' => 'รอบที่ 1 วันที่ 06/03/2024 ถึงวันที่ 05/04/2024',
        //         'route' => $route,

        //     ];
        //     DB::table('report_bonus_easy')
        //         ->updateOrInsert(['user_name' => $value->regis_user_introduce_id, 'year' => $y, 'month' => $m, 'route' => $route], $dataPrepare);
        // }
        // dd('success2');

        // $pv_xvvip =  DB::table('report_bonus_register_xvvip') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //     ->selectRaw('report_bonus_register_xvvip.introduce_id,sum(pv_vvip_1) as pv_1,sum(pv_vvip_2) as pv_2,customers.name,customers.last_name,customers.expire_date,customers.qualification_id')
        //     ->leftjoin('customers', 'report_bonus_register_xvvip.introduce_id', '=', 'customers.user_name')
        //     // ->where('regis_user_introduce_id','=',$row->user_name)
        //     ->wheredate('customers.expire_date', '>=', $e_date)
        //     ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(report_bonus_register_xvvip.created_at) = '{$s_date}' else 1 END"))
        //     ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(report_bonus_register_xvvip.created_at) >= '{$s_date}' and date(report_bonus_register_xvvip.created_at) <= '{$e_date}'else 1 END"))
        //     ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(report_bonus_register_xvvip.created_at) = '{$e_date}' else 1 END"))
        //     ->groupby('introduce_id')
        //     ->get();

        // foreach ($pv_xvvip as $value) {
        //     $pv = $value->pv_1 + $value->pv_2;
        //     $dataPrepare = [
        //         'user_name' => $value->introduce_id,
        //         'name' =>  $value->name . ' ' . $value->last_name,
        //         'pv_xvvip' => $pv,
        //         'qualification' => $value->qualification_id,
        //         'active_date' => $value->expire_date,
        //         'year' => $y,
        //         'month' => $m,
        //         'note' => 'รอบที่ 1 วันที่ 06/03/2024 ถึงวันที่ 05/04/2024',
        //         'route' => $route,

        //     ];
        //     DB::table('report_bonus_easy')
        //         ->updateOrInsert(['user_name' => $value->introduce_id, 'year' => $y, 'month' => $m, 'route' => $route], $dataPrepare);
        // }
        // dd('success3');

        // $pv_active =  DB::table('report_bonus_active') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //     ->selectRaw('report_bonus_active.introduce_id,sum(report_bonus_active.pv) as pv_total,customers.name,customers.last_name,customers.expire_date,customers.qualification_id')
        //     ->leftjoin('customers', 'report_bonus_active.introduce_id', '=', 'customers.user_name')
        //     ->where('g', '=', '1')
        //     ->wheredate('customers.expire_date', '>=', $e_date)
        //     ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(report_bonus_active.created_at) = '{$s_date}' else 1 END"))
        //     ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(report_bonus_active.created_at) >= '{$s_date}' and date(report_bonus_active.created_at) <= '{$e_date}'else 1 END"))
        //     ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(report_bonus_active.created_at) = '{$e_date}' else 1 END"))
        //     ->groupby('introduce_id')
        //     ->get();

        // //dd($pv_active);

        // foreach ($pv_active as $value) {
        //     if ($value->introduce_id) {

        //         $dataPrepare = [
        //             'user_name' => $value->introduce_id,
        //             'name' =>  $value->name . ' ' . $value->last_name,
        //             'pv_active' => $value->pv_total,
        //             'qualification' => $value->qualification_id,
        //             'active_date' => $value->expire_date,
        //             'year' => $y,
        //             'month' => $m, 
        //             'note' => 'รอบที่ 1 วันที่ 06/03/2024 ถึงวันที่ 05/04/2024',
        //             'route' => $route,

        //         ];
        //         DB::table('report_bonus_easy')
        //             ->updateOrInsert(['user_name' => $value->introduce_id, 'year' => $y, 'month' => $m, 'route' => $route], $dataPrepare);
        //     }
        // }
        // dd('success4');
        // // dd('dd');


        //             'month' => $m,
        $report_bonus_easy = DB::table('report_bonus_easy')
            ->select('report_bonus_easy.user_name', 'customers.id_card', 'address')
            ->leftjoin('customers', 'customers.user_name', '=', 'report_bonus_easy.user_name')
            ->where('report_bonus_easy.address', '=', null)
            ->where('report_bonus_easy.year', $y)
            ->where('report_bonus_easy.month', $m)
            // ->where('report_bonus_easy.note', '=', 'รอบที่ 1 วันที่ 06/11/2023 ถึงวันที่ 05/12/2023')
            ->get();

        foreach ($report_bonus_easy as $value) {

            $address = DB::table('customers_address_delivery')
                ->select('customers_address_delivery.*', 'address_provinces.province_id', 'address_provinces.province_name', 'address_tambons.tambon_name', 'address_tambons.tambon_id', 'address_districts.district_id', 'address_districts.district_name')
                ->leftjoin('address_provinces', 'address_provinces.province_id', '=', 'customers_address_delivery.province')
                ->leftjoin('address_districts', 'address_districts.district_id', '=', 'customers_address_delivery.district')
                ->leftjoin('address_tambons', 'address_tambons.tambon_id', '=', 'customers_address_delivery.tambon')
                ->where('user_name', '=', $value->user_name)
                ->first();
            if ($address) {
                if (@$address->phone) {
                    $tel = ' เบอร์โทรศัพท์ ' . $address->phone;
                } else {
                    $tel = null;
                }
                $data = $address->address . 'หมู่ที่.' . $address->moo . ' ซอย.' . $address->soi . ' ถนน.' . $address->road . ' ตำบล.' . $address->tambon_name . ' อำเภอ.' . $address->district_name . ' จังหวัด.' . $address->province_name . ' ' . $address->zipcode . ' ' . $tel;
            } else {
                $data = '-';
            }


            $dataPrepare = [
                'user_name' => $value->user_name,
                'id_card' => $value->id_card,
                'address' => $data,
                'year' => $y,
                'month' => $m,
                'route' => $route,
            ];
            DB::table('report_bonus_easy')
                ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y, 'month' => $m, 'route' => $route], $dataPrepare);
        }
        dd('success 5 ');
    }
    ////////////////////////////////////////////////// new ///////////////
    public function index_new()
    {

        // $data =  EasyReportReportController::run_easy_new();
        // dd($data);
        return view('backend/Easy_report/index_new');
    }


    public function easy_report_datable_new(Request $request)
    {


        $business_location_id = 1;
        //sum(pv_total) as pv_total
        $report_bonus_active = DB::table('report_bonus_easy_new')

            ->where('year', '=', $request->s_date)
            ->where('month', '=', $request->e_date)
            // ->where('active_date','!=',null)
            ->where('route', '=', $request->route)
            ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  report_bonus_easy_new.user_name = '{$request->user_name}' else 1 END"));
        // ->count();
        // dd($report_bonus_active );


        $sQuery = Datatables::of($report_bonus_active);
        return $sQuery
            ->addIndexColumn()


            ->addColumn('date_data', function ($row) {
                $date = $row->year . '-' . $row->month;
                return $date;
            })

            ->addColumn('active_date', function ($row) {

                return  $date_tv_active = date('d/m/Y', strtotime($row->active_date));
            })

            ->addColumn('bonus_type_7', function ($row) {
                if ($row->bonus_type_7) {
                    return number_format($row->bonus_type_7, 2);
                } else {
                    return 0;
                }
            })



            ->addColumn('note', function ($row) {


                return $row->note;
            })



            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }

    public function run_easy_new()
    {

        $y = '2024';
        $m = '04';
        $route = '2';
        // $s_date = date('2023-04-21');
        // $e_date = date('2023-05-20');

        $s_date = date('2024-03-21');
        $e_date = date('2024-04-20');
        $note = 'รอบที่ 2 วันที่ 21 มีนาคม 2024 ถึงวันที่ 20 เมษายน 2024 (Pv ปกติ)';


        // $report_bonus_easy_new_adress = DB::table('customers')
        //     ->select('user_name', 'id_card', 'name', 'last_name', 'qualification_id', 'expire_date')
        //     ->wheredate('customers.expire_date', '>=', $e_date)
        //     ->where('status_customer', '!=', 'cancel')
        //     // ->where('user_name','=','1251430')
        //     ->where('status_runbonus_check_all', '=', 'pending')
        //     ->limit(2000)
        //     ->get();
        // dd($report_bonus_easy_new_adress);


        // $update =  DB::table('customers')

        //     ->where('status_runbonus_check_all', '=', 'success')
        //     ->update(['status_runbonus_check_all' => 'pending']);
        // dd($update);


        // foreach ($report_bonus_easy_new_adress as $value) {

        //     $address = DB::table('customers_address_delivery')
        //         ->select('customers_address_delivery.*', 'address_provinces.province_id', 'address_provinces.province_name', 'address_tambons.tambon_name', 'address_tambons.tambon_id', 'address_districts.district_id', 'address_districts.district_name')
        //         ->leftjoin('address_provinces', 'address_provinces.province_id', '=', 'customers_address_delivery.province')
        //         ->leftjoin('address_districts', 'address_districts.district_id', '=', 'customers_address_delivery.district')
        //         ->leftjoin('address_tambons', 'address_tambons.tambon_id', '=', 'customers_address_delivery.tambon')
        //         ->where('user_name', '=', $value->user_name)
        //         ->first();
        //     if ($address) {
        //         if (@$address->phone) {
        //             $tel = ' เบอร์โทรศัพท์ ' . $address->phone;
        //         } else {
        //             $tel = null;
        //         }
        //         $data = $address->address . 'หมู่ที่.' . $address->moo . ' ซอย.' . $address->soi . ' ถนน.' . $address->road . ' ตำบล.' . $address->tambon_name . ' อำเภอ.' . $address->district . ' จังหวัด.' . $address->province_name . ' ' . $address->zipcode . ' ' . $tel;
        //     } else {
        //         $data = null;
        //     }

        //     $dataPrepare = [
        //         'user_name' => $value->user_name,
        //         'id_card' => $value->id_card,
        //         'name' =>  $value->name . ' ' . $value->last_name,
        //         'qualification' => $value->qualification_id,
        //         'active_date' => $value->expire_date,
        //         'address' => $data,
        //         'year' => $y,
        //         'month' => $m,
        //         'route' => $route,
        //     ];
        //     DB::table('report_bonus_easy_new')
        //         ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y, 'month' => $m, 'route' => $route], $dataPrepare);
        //     DB::table('customers')
        //         ->where('user_name', '=', $value->user_name)
        //         ->update(['status_runbonus_check_all' => 'success']);
        // }
        // $report_bonus_easy_new_adress = DB::table('customers')
        //     ->select('user_name', 'id_card', 'name', 'last_name', 'qualification_id', 'expire_date')
        //     ->wheredate('customers.expire_date', '>=', $e_date)
        //     ->where('status_customer', '!=', 'cancel')
        //     ->where('status_runbonus_check_all', '=', 'pending')
        //     ->count();

        // dd($report_bonus_easy_new_adress, 'success 1');


        // $check_ewallet_type_7 =  DB::table('ewallet') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //     ->selectRaw('customer_username,transaction_code,count(transaction_code) as count_code')
        //     ->havingRaw('count(count_code) > 1 ')
        //     // ->wheredate('date_active', '=', $date)
        //     ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(ewallet.created_at) = '{$s_date}' else 1 END"))
        //     ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(ewallet.created_at) >= '{$s_date}' and date(ewallet.created_at) <= '{$e_date}'else 1 END"))
        //     ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(ewallet.created_at) = '{$e_date}' else 1 END"))
        //     ->where('ewallet.type', '=', 7)
        //     ->groupby('transaction_code')
        //     ->get();
        // dd($check_ewallet_type_7);

        // if ($check_ewallet_type_7) {
        //     foreach ($check_ewallet_type_7 as $value) {

        //         $check =  DB::table('ewallet') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //             ->selectRaw('customer_username,transaction_code,count(transaction_code) as count_code')
        //             ->havingRaw('count(count_code) > 1 ')
        //             ->where('transaction_code', '=', $value->transaction_code)
        //             ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(ewallet.created_at) = '{$s_date}' else 1 END"))
        //             ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(ewallet.created_at) >= '{$s_date}' and date(ewallet.created_at) <= '{$e_date}'else 1 END"))
        //             ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(ewallet.created_at) = '{$e_date}' else 1 END"))
        //             ->where('ewallet.type', '=', 7)
        //             ->groupby('transaction_code')
        //             ->first();



        //         if ($check->count_code >= 2) {

        //             $count_all =  DB::table('ewallet') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //                 ->where('transaction_code', '=', $value->transaction_code)
        //                 ->count();


        //             $g = $count_all / $value->count_code;

        //             $limit =  DB::table('ewallet') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //                 ->selectRaw('id,transaction_code')
        //                 ->where('transaction_code', '=', $value->transaction_code)
        //                 ->orderby('id')
        //                 ->limit($g)
        //                 ->get();

        //             foreach ($limit as $value_limit) {
        //                 $deleted = DB::table('ewallet')
        //                     ->where('transaction_code', '=', $value->transaction_code)
        //                     ->where('id', '=', $value_limit->id)->delete();
        //             }
        //         }
        //     }
        // }

        // dd('success 2'); 

        // $bonus_type_7 = DB::table('report_bonus_easy_new')
        //     ->where('year', '=', $y)
        //     ->where('month', '=', $m)
        //     ->where('route', '=', $route)
        //     // ->where('user_name', '=',1251430)
        //     ->whereNull('bonus_type_7')
        //     ->limit(2000)
        //     ->get();


        // foreach ($bonus_type_7 as $value) {

        //     $bonus = DB::table('ewallet') //ช่วยเพื่อน
        //         ->selectRaw('customers.id_card,customers.name,customers.last_name,customers.expire_date,customers.qualification_id,ewallet.customer_username,sum(ewallet.bonus_full) as bonus_type_7')
        //         ->leftjoin('customers', 'ewallet.customer_username', '=', 'customers.user_name')
        //         ->where('ewallet.type', '=', 7)
        //         ->wheredate('customers.expire_date', '>=', $e_date)
        //         ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(ewallet.created_at) = '{$s_date}' else 1 END"))
        //         ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(ewallet.created_at) >= '{$s_date}' and date(ewallet.created_at) <= '{$e_date}'else 1 END"))
        //         ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(ewallet.created_at) = '{$e_date}' else 1 END"))
        //         ->where('ewallet.customer_username', '=', $value->user_name)
        //         ->groupby('ewallet.customer_username')
        //         ->first();

        //     $lv_1_mb =  EasyReportReportController::count_upline($value->user_name, ['MB'], $e_date);
        //     $lv_1_mo =  EasyReportReportController::count_upline($value->user_name, ['MO'], $e_date);
        //     $lv_1_vip =  EasyReportReportController::count_upline($value->user_name, ['VIP'], $e_date);
        //     $lv_1_vvip =  EasyReportReportController::count_upline($value->user_name, ['VVIP'], $e_date);
        //     $lv_1_xvvip_up = EasyReportReportController::count_upline($value->user_name, ['XVVIP', 'SVVIP', 'MG', 'MR', 'ME', 'MD'], $e_date);

        //     if ($bonus) {
        //         $bonus_7 = $bonus->bonus_type_7;
        //     } else {
        //         $bonus_7 = 0;
        //     }

        //     $dataPrepare = [
        //         'user_name' => $value->user_name,
        //         'bonus_type_7' => $bonus_7,
        //         'lv_1_mb' =>  $lv_1_mb,
        //         'lv_1_mo' =>  $lv_1_mo,
        //         'lv_1_vip' =>  $lv_1_vip,
        //         'lv_1_vvip' =>  $lv_1_vvip,
        //         'lv_1_xvvip_up' =>  $lv_1_xvvip_up,

        //         // 'lv_1_mb_bonus' =>  $lv_1_mb * 20,
        //         // 'lv_1_mo_bonus' =>  $lv_1_mo * 20,
        //         // 'lv_1_vip_bonus' =>  $lv_1_vip * 20,
        //         // 'lv_1_vvip_bonus' =>  $lv_1_vvip * 20,
        //         // 'lv_1_xvvip_up_bonus' =>  $lv_1_xvvip_up * 20,

        //         //------------------------------------------------------
        //         'lv_1_mb_bonus' =>  $lv_1_mb * 20,
        //         'lv_1_mo_bonus' =>  $lv_1_mo * 40,
        //         'lv_1_vip_bonus' =>  $lv_1_vip * 60,
        //         'lv_1_vvip_bonus' =>  $lv_1_vvip * 80,
        //         'lv_1_xvvip_up_bonus' =>  $lv_1_xvvip_up * 100,
        //         //------------------------------------------------------

        //         'year' => $y,
        //         'month' => $m,
        //         'route' => $route,
        //         'note' => $note,

        //     ];

        //     // dd($dataPrepare);

        //     // dd($bonus_type_7);
        //     DB::table('report_bonus_easy_new')
        //         ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y, 'month' => $m, 'route' => $route], $dataPrepare);
        // }
        // // 1 // 
        // $bonus_type_count = DB::table('report_bonus_easy_new')
        //     ->where('year', '=', $y)
        //     ->where('month', '=', $m)
        //     ->where('route', '=', $route)
        //     // ->where('user_name', '=',1251430)
        //     ->whereNull('bonus_type_7')
        //     //->limit(2000) 
        //     ->count();

        // dd($bonus_type_count, 'success 3');


        // $report_bonus_easy_new = DB::table('report_bonus_easy_new')
        //     ->where('year', '=', $y)
        //     ->where('month', '=', $m)
        //     ->where('route', '=', $route)
        //     // ->whereNull('lv_2_mb_bonus')
        //     // ->limit(2000)
        //     ->get();

        // // dd($report_bonus_easy_new);

        // $array_lv_1 = array();
        // $array_lv_2 = array();

        // $i = 0;
        // foreach ($report_bonus_easy_new as $value) {
        //     $i++;
        //     $customers = DB::table('customers')
        //         ->select('user_name')
        //         ->where('customers.introduce_id', '=', $value->user_name)
        //         //    ->wheredate('customers.expire_date','>=',$e_date)
        //         ->get();
        //     foreach ($customers as $vl_1) {
        //         $array_lv_1[] = $vl_1->user_name;
        //     }

        //     if (@$array_lv_1) {
        //         $customers_lv2_mb = DB::table('customers')
        //             ->select('user_name')
        //             ->wherein('customers.introduce_id', $array_lv_1)
        //             ->wheredate('customers.expire_date', '>=', $e_date)
        //             ->where('qualification_id', '=', 'MB')
        //             ->count();

        //         $customers_lv2_mo = DB::table('customers')
        //             ->select('user_name')
        //             ->wherein('customers.introduce_id', $array_lv_1)
        //             ->wheredate('customers.expire_date', '>=', $e_date)
        //             ->where('qualification_id', '=', 'MO')
        //             ->count();

        //         $customers_lv2_vip = DB::table('customers')
        //             ->select('user_name')
        //             ->wherein('customers.introduce_id', $array_lv_1)
        //             ->wheredate('customers.expire_date', '>=', $e_date)
        //             ->where('qualification_id', '=', 'VIP')
        //             ->count();

        //         $customers_lv2_vvip = DB::table('customers')
        //             ->select('user_name')
        //             ->wherein('customers.introduce_id', $array_lv_1)
        //             ->wheredate('customers.expire_date', '>=', $e_date)
        //             ->where('qualification_id', '=', 'VVIP')
        //             ->count();

        //         $customers_lv2_xvvipup = DB::table('customers')
        //             ->select('user_name')
        //             ->wherein('customers.introduce_id', $array_lv_1)
        //             ->wheredate('customers.expire_date', '>=', $e_date)
        //             ->wherein('customers.qualification_id', ['XVVIP', 'SVVIP', 'MG', 'MR', 'ME', 'MD'])
        //             ->count();

        //         $customers_vl2 = DB::table('customers')
        //             ->select('user_name')
        //             // ->wheredate('customers.expire_date','>=',$e_date)
        //             ->wherein('customers.introduce_id', $array_lv_1)
        //             ->get();


        //         foreach ($customers_vl2 as $vl_2) {
        //             $array_lv_2[] = $vl_2->user_name;
        //         }


        //         if (@$array_lv_2) {

        //             $customers_lv3_mb = DB::table('customers')
        //                 ->select('user_name')
        //                 ->wherein('customers.introduce_id', $array_lv_2)
        //                 ->wheredate('customers.expire_date', '>=', $e_date)
        //                 ->where('qualification_id', '=', 'MB')
        //                 ->count();



        //             $customers_lv3_mo = DB::table('customers')
        //                 ->select('user_name')
        //                 ->wherein('customers.introduce_id', $array_lv_2)
        //                 ->wheredate('customers.expire_date', '>=', $e_date)
        //                 ->where('qualification_id', '=', 'MO')
        //                 ->count();

        //             $customers_lv3_vip = DB::table('customers')
        //                 ->select('user_name')
        //                 ->wherein('customers.introduce_id', $array_lv_2)
        //                 ->wheredate('customers.expire_date', '>=', $e_date)
        //                 ->where('qualification_id', '=', 'VIP')
        //                 ->count();

        //             $customers_lv3_vvip = DB::table('customers')
        //                 ->select('user_name')
        //                 ->wherein('customers.introduce_id', $array_lv_2)
        //                 ->wheredate('customers.expire_date', '>=', $e_date)
        //                 ->where('qualification_id', '=', 'VVIP')
        //                 ->count();

        //             $customers_lv3_xvvipup = DB::table('customers')
        //                 ->select('user_name')
        //                 ->wherein('customers.introduce_id', $array_lv_2)
        //                 ->wheredate('customers.expire_date', '>=', $e_date)
        //                 ->wherein('customers.qualification_id', ['XVVIP', 'SVVIP', 'MG', 'MR', 'ME', 'MD'])
        //                 ->count();
        //         } else {
        //             $customers_lv3_mb = 0;
        //             $customers_lv3_mo = 0;
        //             $customers_lv3_vip = 0;
        //             $customers_lv3_vvip = 0;
        //             $customers_lv3_xvvipup = 0;
        //         }


        //         $dataPrepare = [
        //             'user_name' => $value->user_name,
        //             'lv_2_mb' => $customers_lv2_mb,
        //             'lv_2_mo' => $customers_lv2_mo,
        //             'lv_2_vip' => $customers_lv2_vip,
        //             'lv_2_vvip' => $customers_lv2_vvip,
        //             'lv_2_xvvip_up' => $customers_lv2_xvvipup,
        //             'lv_3_mb' =>  $customers_lv3_mb,
        //             'lv_3_mo' =>  $customers_lv3_mo,
        //             'lv_3_vip' =>  $customers_lv3_vip,
        //             'lv_3_vvip' =>  $customers_lv3_vvip,
        //             'lv_3_xvvip_up' =>  $customers_lv3_xvvipup,


        //             // 'lv_2_mb_bonus' => $customers_lv2_mb * 20,
        //             // 'lv_2_mo_bonus' => $customers_lv2_mo * 20,
        //             // 'lv_2_vip_bonus' => $customers_lv2_vip * 20,
        //             // 'lv_2_vvip_bonus' => $customers_lv2_vvip * 20,
        //             // 'lv_2_xvvip_up_bonus' => $customers_lv2_xvvipup * 20,
        //             // 'lv_3_mb_bonus' =>  $customers_lv3_mb * 20,
        //             // 'lv_3_mo_bonus' =>  $customers_lv3_mo * 20,
        //             // 'lv_3_vip_bonus' =>  $customers_lv3_vip * 20,
        //             // 'lv_3_vvip_bonus' =>  $customers_lv3_vvip * 20,
        //             // 'lv_3_xvvip_up_bonus' =>  $customers_lv3_xvvipup * 20,

        //             // -----------------------------------
        //             'lv_2_mb_bonus' => $customers_lv2_mb * 20,
        //             'lv_2_mo_bonus' => $customers_lv2_mo * 40,
        //             'lv_2_vip_bonus' => $customers_lv2_vip * 60,
        //             'lv_2_vvip_bonus' => $customers_lv2_vvip * 80,
        //             'lv_2_xvvip_up_bonus' => $customers_lv2_xvvipup * 100,
        //             'lv_3_mb_bonus' =>  $customers_lv3_mb * 20,
        //             'lv_3_mo_bonus' =>  $customers_lv3_mo * 40,
        //             'lv_3_vip_bonus' =>  $customers_lv3_vip * 60,
        //             'lv_3_vvip_bonus' =>  $customers_lv3_vvip * 80,
        //             'lv_3_xvvip_up_bonus' =>  $customers_lv3_xvvipup * 100,
        //             // --------------------------------

        //             'year' => $y,
        //             'month' => $m,
        //             'route' => $route,
        //         ];

        //         DB::table('report_bonus_easy_new')
        //             ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y, 'month' => $m, 'route' => $route], $dataPrepare);
        //         unset($array_lv_1);
        //         unset($array_lv_2);
        //     } else {
        //         $dataPrepare = [
        //             'user_name' => $value->user_name,
        //             'lv_2_mb' => 0,
        //             'lv_2_mo' => 0,
        //             'lv_2_vip' => 0,
        //             'lv_2_vvip' => 0,
        //             'lv_2_xvvip_up' => 0,
        //             'lv_3_mb' =>  0,
        //             'lv_3_mo' =>  0,
        //             'lv_3_vip' =>  0,
        //             'lv_3_vvip' =>  0,
        //             'lv_3_xvvip_up' => 0,

        //             'lv_2_mb_bonus' => 0,
        //             'lv_2_mo_bonus' => 0,
        //             'lv_2_vip_bonus' => 0,
        //             'lv_2_vvip_bonus' => 0,
        //             'lv_2_xvvip_up_bonus' => 0,
        //             'lv_3_mb_bonus' =>  0,
        //             'lv_3_mo_bonus' => 0,
        //             'lv_3_vip_bonus' => 0,
        //             'lv_3_vvip_bonus' => 0,
        //             'lv_3_xvvip_up_bonus' => 0,
        //             'year' => $y,
        //             'month' => $m,
        //             'route' => $route,

        //         ];
        //         DB::table('report_bonus_easy_new')
        //             ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y, 'month' => $m, 'route' => $route], $dataPrepare);
        //         unset($array_lv_1);
        //         unset($array_lv_2);
        //     }
        // }

        // $report_bonus_easy_count = DB::table('report_bonus_easy_new')
        //     ->where('year', '=', $y)
        //     ->where('month', '=', $m)
        //     ->where('route', '=', $route)
        //     ->whereNull('lv_2_mb_bonus')
        //     // ->limit(2000)
        //     ->count();

        // dd($report_bonus_easy_count, 'success 4');

        ////////////////////////////////////// ไม่ใช้ /////////////////////////////////////////////////////////
        // $report_bonus_easy_new = DB::table('report_bonus_easy_new')
        //     ->where('year', '=', $y)
        //     ->where('month', '=', $m)
        //     ->where('route', '=', $route)
        //     ->whereNull('lv_1_mb')
        //     ->limit(1000)
        //     ->get();

        // dd($report_bonus_easy_new);


        // foreach ($report_bonus_easy_new as $value) {

        //     $lv_1_mb =  EasyReportReportController::count_upline($value->user_name, ['MB'], $e_date);
        //     $lv_1_mo =  EasyReportReportController::count_upline($value->user_name, ['MO'], $e_date);
        //     $lv_1_vip =  EasyReportReportController::count_upline($value->user_name, ['VIP'], $e_date);
        //     $lv_1_vvip =  EasyReportReportController::count_upline($value->user_name, ['VVIP'], $e_date);
        //     $lv_1_xvvip_up = EasyReportReportController::count_upline($value->user_name, ['XVVIP', 'SVVIP', 'MG', 'MR', 'ME', 'MD'], $e_date);

        //     $dataPrepare = [
        //         'user_name' => $value->user_name,
        //         'lv_1_mb' =>  $lv_1_mb,
        //         'lv_1_mo' =>  $lv_1_mo,
        //         'lv_1_vip' =>  $lv_1_vip,
        //         'lv_1_vvip' =>  $lv_1_vvip,
        //         'lv_1_xvvip_up' =>  $lv_1_xvvip_up,
        //         'year' => $y,
        //         'month' => $m,
        //         'route' => $route,
        //         'note' => $note,

        //     ];


        //     DB::table('report_bonus_easy_new')
        //         ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y, 'month' => $m, 'route' => $route], $dataPrepare);
        // }

        dd('success');
    }


    public static function count_upline($user_name, $position, $e_date)
    {
        $count = DB::table('customers')
            ->wherein('customers.qualification_id', $position)
            ->wheredate('customers.expire_date', '>=', $e_date)
            ->where('customers.introduce_id', $user_name)
            ->count();
        return $count;
    }
}
