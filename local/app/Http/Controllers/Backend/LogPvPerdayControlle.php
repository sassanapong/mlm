<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;

use App\Exports\LogPvPerDayExport;
use Maatwebsite\Excel\Facades\Excel;

class LogPvPerdayControlle extends Controller
{


    public function index()
    {
        //   $data = AllsaleReportControlle::vl_2_3();
        //  dd($data);ssas
        return view('backend/LogPvPerday/index');
    }

    public function log_pv_per_day_ab_balance_all()
    {
        //   $data = AllsaleReportControlle::vl_2_3();
        //  dd($data);



        return view('backend/LogPvPerday/log_pv_per_day_ab_balance_all');
    }

    public function report_bonus_es()
    {
        //   $data = AllsaleReportControlle::vl_2_3();
        //  dd($data);

        return view('backend/LogPvPerday/report_bonus_es');
    }


    public function report_pv_per_day_ab_balance_bonus7()
    {
        //   $data = AllsaleReportControlle::vl_2_3();
        //  dd($data);


        $data = DB::table('report_pv_per_day_ab_balance_bonus7')

            ->selectRaw('SUM(bonus_full) AS el,date_action')
            ->where('status', '=', 'pending')
            // ->where('recive_user_name', '1169186')
            ->limit(100)
            // ->whereDate('date_action', '=', $action_date)
            ->groupBy('date_action')
            ->get();


        return view('backend/LogPvPerday/report_pv_per_day_ab_balance_bonus7', compact('data'));
    }

    public function report_pv_per_day_ab_balance()
    {
        //   $data = AllsaleReportControlle::vl_2_3();
        //  dd($data);


        $data = DB::table('report_pv_per_day_ab_balance')

            ->selectRaw('SUM(bonus_full) AS el,date_action')
            ->where('status', '=', 'pending')
            // ->where('recive_user_name', '1169186')
            ->limit(100)
            // ->whereDate('date_action', '=', $action_date)
            ->groupBy('date_action')
            ->get();

        return view('backend/LogPvPerday/report_pv_per_day_ab_balance', compact('data'));
    }



    public function report_pv_per_day_ab_balance_bonus9()
    {
        //   $data = AllsaleReportControlle::vl_2_3();
        //  dd($data);

        $data = DB::table('report_pv_per_day_ab_balance_bonus9')
            ->select(
                'recive_user_name',
                DB::raw('SUM(bonus_full) as el'),

                'date_action'
            )
            ->where('status', '=', 'pending')
            // ->where('recive_user_name', '1169186')
            ->limit('100')
            ->groupby('date_action')
            ->get();

        return view('backend/LogPvPerday/report_pv_per_day_ab_balance_bonus9', compact('data'));
    }



    public function log_pv_per_day_excel(Request $request)
    {
        $log_pv_per_day = DB::table('log_pv_per_day')
            ->whereDate('date_action', $request->e_date)
            ->get();


        $logArray[] = [
            'id',
            'user_name',
            'type_recive',
            'user_name_recive',
            'customer_id_fk',
            'customer_id_fk_recive',
            'pv_upline_total',
            'pv',
            'type',
            'year',
            'month',
            'day',
            'date_action',
            'created_at',
            'updated_at',
            'deleted_at'
        ];

        foreach ($log_pv_per_day as $log) {
            $logArray[] = (array) $log;
        }

        return Excel::download(new LogPvPerDayExport($logArray), 'log_pv_per_day.xlsx');
    }

    public function log_pv_per_day_all_excel(Request $request)
    {
        $log_pv_per_day_ab_balance_all = DB::table('log_pv_per_day_ab_balance_all')
            ->whereBetween('date_action', [$request->s_date, $request->e_date])
            ->get();

        $logArray[] = [
            'id',
            'user_name',
            'customer_id_fk',
            'name',
            'introduce_id',
            'qualification_id',
            'balance',
            'balance_type',
            'balance_up_old',
            'pv_today',
            'pv_a_new',
            'pv_b_new',
            'pv_a',
            'pv_b',
            'pv_a_old',
            'pv_b_old',
            'kang',
            'aoon',
            'note',
            'year',
            'month',
            'day',
            'date_action',
            'status',
            'created_at',
            'updated_at',
            'deleted_at'
        ];


        foreach ($log_pv_per_day_ab_balance_all as $log) {
            $logArray[] = (array) $log;
        }

        return Excel::download(new LogPvPerDayExport($logArray), 'log_pv_per_day_ab_balance_all.xlsx');
    }

    public function report_pv_per_day_ab_balance_bonus7_excel(Request $request)
    {
        $report_pv_per_day_ab_balance_bonus7 = DB::table('report_pv_per_day_ab_balance_bonus7')
            ->select([
                'report_pv_per_day_ab_balance_bonus7.*',
                'customers.id_card',
                'customers_address_card.address',
                'customers_address_card.moo',
                'customers_address_card.soi',
                'customers_address_card.road',
                'address_districts.district_name as district',
                'address_provinces.province_name as province',
                'address_tambons.tambon_name as tambon',
                'customers_address_card.zipcode',
            ])
            ->whereDate('report_pv_per_day_ab_balance_bonus7.date_action', $request->e_date)
            ->leftJoin('customers', 'report_pv_per_day_ab_balance_bonus7.user_name', '=', 'customers.user_name')
            ->leftJoinSub(
                DB::table('customers_address_card')
                    ->select('user_name', 'address', 'moo', 'soi', 'road', 'district', 'province', 'tambon', 'zipcode')
                    ->groupBy('user_name'),
                'customers_address_card',
                'report_pv_per_day_ab_balance_bonus7.user_name',
                '=',
                'customers_address_card.user_name'
            )
            ->leftJoin('address_districts', 'address_districts.district_id', '=', 'customers_address_card.district')
            ->leftJoin('address_provinces', 'address_provinces.province_id', '=', 'customers_address_card.province')
            ->leftJoin('address_tambons', 'address_tambons.tambon_id', '=', 'customers_address_card.tambon')
            ->distinct()
            ->get();

        // กำหนด Header สำหรับไฟล์ Excel
        $logArray[] = [
            'ลำดับ',
            'วันที่',
            'รหัสสมาชิก',
            'เลขบัตรประชาชน',
            'ตำแหน่ง',
            'introduce_id',
            'upline_id',
            'type_upline',
            'jang_pv_fk',
            'jang_user_name',
            'jang_introduce_id',
            'jang_qualification',
            'jang_expire_date',
            'pv',
            'g',
            'percen',
            'tax_percen',
            'tax_total',
            'bonus_full',
            'bonus',
            'ที่อยู่',
            'status',
        ];

        // วนลูปข้อมูลและเพิ่มลงใน Array
        $index = 1;
        foreach ($report_pv_per_day_ab_balance_bonus7 as $log) {
            $address = trim(
                ($log->address ? $log->address . ' ' : '') .
                    ($log->moo ? 'ม.' . $log->moo . ' ' : '') .
                    ($log->soi ? 'ซอย.' . $log->soi . ' ' : '') .
                    ($log->road ? 'ถนน.' . $log->road . ' ' : '') .
                    ($log->tambon ? 'ต.' . $log->tambon . ' ' : '') .
                    ($log->province ? 'อ.' . $log->province . ' ' : '') .
                    ($log->district ? 'จ.' . $log->district . ' ' : '') .
                    ($log->zipcode ? $log->zipcode : '')
            );

            $logArray[] = [
                $index++,
                $log->date_action,
                $log->user_name,
                $log->id_card,
                $log->qualification,
                $log->introduce_id,
                $log->upline_id,
                $log->type_upline,
                $log->jang_pv_fk,
                $log->jang_user_name,
                $log->jang_introduce_id,
                $log->jang_qualification,
                $log->jang_expire_date,
                $log->pv,
                $log->g,
                $log->percen,
                $log->tax_percen,
                $log->tax_total,
                $log->bonus_full,
                $log->bonus,
                $address, // เพิ่มที่อยู่ที่จัดรูปแบบเรียบร้อยแล้ว
                $log->status,
            ];
        }

        return Excel::download(new LogPvPerDayExport($logArray), 'report_pv_per_day_ab_balance_bonus7.xlsx');
    }


    public function report_pv_per_day_ab_balance_excel(Request $request)
    {
        $report_pv_per_day_ab_balance = DB::table('report_pv_per_day_ab_balance')
            ->select([
                'report_pv_per_day_ab_balance.*',
                'customers.id_card',
                'customers_address_card.address',
                'customers_address_card.moo',
                'customers_address_card.soi',
                'customers_address_card.road',
                'address_districts.district_name as district',
                'address_provinces.province_name as province',
                'address_tambons.tambon_name as tambon',
                'customers_address_card.zipcode',
            ])
            ->whereBetween('report_pv_per_day_ab_balance.date_action', [$request->s_date, $request->e_date])
            ->leftJoin('customers', 'report_pv_per_day_ab_balance.user_name', '=', 'customers.user_name')
            ->leftJoinSub(
                DB::table('customers_address_card')
                    ->select('user_name', 'address', 'moo', 'soi', 'road', 'district', 'province', 'tambon', 'zipcode')
                    ->groupBy('user_name'),
                'customers_address_card',
                'report_pv_per_day_ab_balance.user_name',
                '=',
                'customers_address_card.user_name'
            )
            ->leftJoin('address_districts', 'address_districts.district_id', '=', 'customers_address_card.district')
            ->leftJoin('address_provinces', 'address_provinces.province_id', '=', 'customers_address_card.province')
            ->leftJoin('address_tambons', 'address_tambons.tambon_id', '=', 'customers_address_card.tambon')
            ->distinct()
            ->get();

        // กำหนดคอลัมน์ใหม่ตามที่คุณระบุ
        $logArray[] = [
            'วันที่',
            'รหัสสมาชิก',
            'ชื่อสมาชิก',
            'เลขบัตรประชาชน',
            'ตำแหน่ง',
            'introduce_id',
            'bonus_limit',
            'expire_date',
            'balance',
            'balance_up_old',
            'kang',
            'aoon',
            'rate',
            'bonus_aoon',
            'bonus_kang',
            'kang_balance_up_old',
            'bonus_full',
            'bonus',
            'ที่อยู่',
            'status',
        ];

        // วนลูปเพื่อเพิ่มข้อมูลเข้าไปใน $logArray ตามคอลัมน์ใหม่
        foreach ($report_pv_per_day_ab_balance as $log) {

            $address = trim(
                ($log->address ? $log->address . ' ' : '') .
                    ($log->moo ? 'ม.' . $log->moo . ' ' : '') .
                    ($log->soi ? 'ซอย.' . $log->soi . ' ' : '') .
                    ($log->road ? 'ถนน.' . $log->road . ' ' : '') .
                    ($log->tambon ? 'ต.' . $log->tambon . ' ' : '') .
                    ($log->province ? 'อ.' . $log->province . ' ' : '') .
                    ($log->district ? 'จ.' . $log->district . ' ' : '') .
                    ($log->zipcode ? $log->zipcode : '')
            );


            $logArray[] = [
                $log->date_action,
                $log->user_name,
                $log->name,
                $log->id_card,
                $log->qualification_id,
                $log->introduce_id,
                $log->bonus_limit,
                $log->expire_date,
                $log->balance,
                $log->balance_up_old,
                $log->kang,
                $log->aoon,
                $log->rate,
                $log->bonus_aoon,
                $log->bonus_kang,
                $log->kang_balance_up_old,
                $log->bonus_full,
                $log->bonus,
                $address, // เพิ่มที่อยู่ที่จัดรูปแบบเรียบร้อยแล้ว
                $log->status,
            ];
        }

        // ส่งออกข้อมูลไปยังไฟล์ Excel
        return Excel::download(new LogPvPerDayExport($logArray), 'report_pv_per_day_ab_balance.xlsx');
    }

    public function report_pv_per_day_ab_balance_bonus9_excel(Request $request)
    {
        $report_pv_per_day_ab_balance_bonus9 = DB::table('report_pv_per_day_ab_balance_bonus9')
            ->select([
                'report_pv_per_day_ab_balance_bonus9.*',
                'customers.id_card',
                'customers_address_card.address',
                'customers_address_card.moo',
                'customers_address_card.soi',
                'customers_address_card.road',
                'address_districts.district_name as district',
                'address_provinces.province_name as province',
                'address_tambons.tambon_name as tambon',
                'customers_address_card.zipcode',
            ])
            ->whereDate('report_pv_per_day_ab_balance_bonus9.date_action', $request->e_date)

            ->leftJoin('customers', 'report_pv_per_day_ab_balance_bonus9.user_name', '=', 'customers.user_name')
            ->leftJoinSub(
                DB::table('customers_address_card')
                    ->select('user_name', 'address', 'moo', 'soi', 'road', 'district', 'province', 'tambon', 'zipcode')
                    ->groupBy('user_name'),
                'customers_address_card',
                'report_pv_per_day_ab_balance_bonus9.user_name',
                '=',
                'customers_address_card.user_name'
            )
            ->leftJoin('address_districts', 'address_districts.district_id', '=', 'customers_address_card.district')
            ->leftJoin('address_provinces', 'address_provinces.province_id', '=', 'customers_address_card.province')
            ->leftJoin('address_tambons', 'address_tambons.tambon_id', '=', 'customers_address_card.tambon')
            ->distinct()
            ->get();

        // กำหนดคอลัมน์ใหม่ตามที่คุณระบุ
        $logArray[] = [
            'วันที่',
            'รหัสสมาชิก',
            'เลขบัตรประชาชน',
            'ตำแหน่ง',
            'rate',
            'recive_user_name',
            'recive_introduce_id',
            'recive_qualification',
            'recive_expire_date',
            'bonus_full_8',
            'g',
            'percen',
            'tax_percen',
            'tax_total',
            'bonus_full',
            'bonus',
            'ที่อยู่',
            'status',
        ];

        // วนลูปเพื่อเพิ่มข้อมูลเข้าไปใน $logArray ตามคอลัมน์ใหม่
        foreach ($report_pv_per_day_ab_balance_bonus9 as $log) {
            // สร้าง address จากข้อมูลที่เกี่ยวข้อง
            $address = trim(
                ($log->address ? $log->address . ' ' : '') .
                    ($log->moo ? 'ม.' . $log->moo . ' ' : '') .
                    ($log->soi ? 'ซอย.' . $log->soi . ' ' : '') .
                    ($log->road ? 'ถนน.' . $log->road . ' ' : '') .
                    ($log->tambon ? 'ต.' . $log->tambon . ' ' : '') .
                    ($log->province ? 'อ.' . $log->province . ' ' : '') .
                    ($log->district ? 'จ.' . $log->district . ' ' : '') .
                    ($log->zipcode ? $log->zipcode : '')
            );

            $logArray[] = [
                $log->date_action,
                $log->user_name,
                $log->id_card,
                $log->qualification,
                $log->rate,
                $log->recive_user_name,
                $log->recive_introduce_id,
                $log->recive_qualification,
                $log->recive_expire_date,
                $log->bonus_full_8,
                $log->g,
                $log->percen,
                $log->tax_percen,
                $log->tax_total,
                $log->bonus_full,
                $log->bonus,
                $address, // ที่อยู่ที่สร้างขึ้น
                $log->status,
            ];
        }

        // ส่งออกข้อมูลไปยังไฟล์ Excel
        return Excel::download(new LogPvPerDayExport($logArray), 'report_pv_per_day_ab_balance_bonus9.xlsx');
    }



    public function report_pv_per_day_ab_balance_bonus9_datable(Request $request)
    {


        $log_pv_per_day = DB::table('report_pv_per_day_ab_balance_bonus9')
            ->select([
                'report_pv_per_day_ab_balance_bonus9.*',
                'customers.id_card',
                'customers_address_card.address',
                'customers_address_card.moo',
                'customers_address_card.soi',
                'customers_address_card.road',
                'address_districts.district_name as district',
                'address_provinces.province_name as province',
                'address_tambons.tambon_name as tambon',
                'customers_address_card.zipcode',
            ])
            ->whereBetween('report_pv_per_day_ab_balance_bonus9.date_action', [$request->s_date, $request->e_date])

            ->when(!empty($request->user_name), function ($query) use ($request) {
                return $query->where('report_pv_per_day_ab_balance_bonus9.user_name', $request->user_name);
            })

            ->leftJoin('customers', 'report_pv_per_day_ab_balance_bonus9.user_name', '=', 'customers.user_name')

            ->leftJoinSub(
                DB::table('customers_address_card')
                    ->select('user_name', 'address', 'moo', 'soi', 'road', 'district', 'province', 'tambon', 'zipcode')
                    ->groupBy('user_name'),
                'customers_address_card',
                'report_pv_per_day_ab_balance_bonus9.user_name',
                '=',
                'customers_address_card.user_name'
            )

            ->leftJoin('address_districts', 'address_districts.district_id', '=', 'customers_address_card.district')
            ->leftJoin('address_provinces', 'address_provinces.province_id', '=', 'customers_address_card.province')
            ->leftJoin('address_tambons', 'address_tambons.tambon_id', '=', 'customers_address_card.tambon')

            ->distinct();


        $sQuery = Datatables::of($log_pv_per_day);

        return $sQuery
            ->addIndexColumn() // เพิ่มดัชนีแถวที่นี่
            ->addColumn('date_action', function ($row) {
                return date('d/m/Y', strtotime($row->date_action));
            })

            ->addColumn('address', function ($row) {
                if ($row->district) {
                    $address = $row->address . ' ม.' . $row->moo . ' ซอย.' . $row->soi . ' ถนน.' . $row->road . ' ต.' . $row->tambon . ' อ.' . $row->province . ' จ.' . $row->district . ' ' . $row->zipcode;
                } else {
                    $address = '';
                }
                return $address;
            })

            ->rawColumns(['date_action']) // เปลี่ยนจาก 'active_date' เป็น 'date_action'
            ->make(true);
    }


    public function bonus_es_datable(Request $request)
    {
        $report_bonus_2024_easy = DB::table('report_bonus_2024_easy')
            ->whereBetween('date_action', [$request->s_date, $request->e_date])
            ->whereRaw("case WHEN '{$request->user_name}' != '' THEN report_bonus_2024_easy.user_name = '{$request->user_name}' else 1 END");

        $sQuery = Datatables::of($report_bonus_2024_easy);

        return $sQuery
            ->addIndexColumn() // เพิ่มดัชนีแถวที่นี่
            ->addColumn('date_action', function ($row) {
                return date('d/m/Y', strtotime($row->date_action));
            })
            ->rawColumns(['date_action']) // เปลี่ยนจาก 'active_date' เป็น 'date_action'
            ->make(true);
    }

    public function  bonus_es_excel(Request $request)
    {
        $report_bonus_2024_easy = DB::table('report_bonus_2024_easy')
            ->whereBetween('date_action', [$request->s_date, $request->e_date])
            ->get();

        $logArray[] = [
            'id',
            'user_name',
            'qualification',
            'expire_date',
            'code_order',
            'buy_user_name',
            'buy_qualification',
            'pv',
            'percen',
            'tax_percen',
            'tax_total',
            'bonus_full',
            'bonus',
            'remark_transfer',
            'date_action',
            'status',
            'created_at',
            'updated_at',
            'deleted_at'
        ];

        foreach ($report_bonus_2024_easy as $log) {
            $logArray[] = (array) $log;
        }

        return Excel::download(new LogPvPerDayExport($logArray), 'report_bonus_easy.xlsx');
    }

    public function report_pv_per_day_ab_balance_datable(Request $request)
    {

        $log_pv_per_day = DB::table('report_pv_per_day_ab_balance')
            ->select([
                'report_pv_per_day_ab_balance.*',
                'customers.id_card',
                'customers_address_card.address',
                'customers_address_card.moo',
                'customers_address_card.soi',
                'customers_address_card.road',
                'address_districts.district_name as district',
                'address_provinces.province_name as province',
                'address_tambons.tambon_name as tambon',
                'customers_address_card.zipcode',
            ])
            ->whereBetween('report_pv_per_day_ab_balance.date_action', [$request->s_date, $request->e_date])
            ->whereRaw("case WHEN '{$request->user_name}' != '' THEN report_pv_per_day_ab_balance.user_name = '{$request->user_name}' else 1 END")
            ->leftJoin('customers', 'report_pv_per_day_ab_balance.user_name', '=', 'customers.user_name')
            ->leftJoinSub(
                DB::table('customers_address_card')
                    ->select('user_name', 'address', 'moo', 'soi', 'road', 'district', 'province', 'tambon', 'zipcode')
                    ->groupBy('user_name'),
                'customers_address_card',
                'report_pv_per_day_ab_balance.user_name',
                '=',
                'customers_address_card.user_name'
            )
            ->leftJoin('address_districts', 'address_districts.district_id', '=', 'customers_address_card.district')
            ->leftJoin('address_provinces', 'address_provinces.province_id', '=', 'customers_address_card.province')
            ->leftJoin('address_tambons', 'address_tambons.tambon_id', '=', 'customers_address_card.tambon')
            ->distinct();


        $sQuery = Datatables::of($log_pv_per_day);

        return $sQuery
            ->addIndexColumn() // เพิ่มดัชนีแถวที่นี่
            ->addColumn('date_action', function ($row) {
                return date('d/m/Y', strtotime($row->date_action));
            })
            ->addColumn('address', function ($row) {
                if ($row->district) {
                    $address = $row->address . ' ม.' . $row->moo . ' ซอย.' . $row->soi . ' ถนน.' . $row->road . ' ต.' . $row->tambon . ' อ.' . $row->province . ' จ.' . $row->district . ' ' . $row->zipcode;
                } else {
                    $address = '';
                }
                return $address;
            })

            ->rawColumns(['date_action']) // เปลี่ยนจาก 'active_date' เป็น 'date_action'
            ->make(true);
    }

    public function report_pv_per_day_ab_balance_bonus7_datable(Request $request)
    {
        $log_pv_per_day = DB::table('report_pv_per_day_ab_balance_bonus7')
            ->select([
                'report_pv_per_day_ab_balance_bonus7.*',
                'customers.id_card',
                'customers_address_card.address',
                'customers_address_card.moo',
                'customers_address_card.soi',
                'customers_address_card.road',
                'address_districts.district_name as district',
                'address_provinces.province_name as province',
                'address_tambons.tambon_name as tambon',
                'customers_address_card.zipcode',
            ])
            ->whereBetween('report_pv_per_day_ab_balance_bonus7.date_action', [$request->s_date, $request->e_date])

            ->when(!empty($request->user_name), function ($query) use ($request) {
                return $query->where('report_pv_per_day_ab_balance_bonus7.user_name', $request->user_name);
            })

            ->leftJoin('customers', 'report_pv_per_day_ab_balance_bonus7.user_name', '=', 'customers.user_name')

            ->leftJoinSub(
                DB::table('customers_address_card')
                    ->select('user_name', 'address', 'moo', 'soi', 'road', 'district', 'province', 'tambon', 'zipcode')
                    ->groupBy('user_name'),
                'customers_address_card',
                'report_pv_per_day_ab_balance_bonus7.user_name',
                '=',
                'customers_address_card.user_name'
            )

            ->leftJoin('address_districts', 'address_districts.district_id', '=', 'customers_address_card.district')
            ->leftJoin('address_provinces', 'address_provinces.province_id', '=', 'customers_address_card.province')
            ->leftJoin('address_tambons', 'address_tambons.tambon_id', '=', 'customers_address_card.tambon')

            ->distinct();

        $sQuery = Datatables::of($log_pv_per_day);

        return $sQuery
            ->addIndexColumn() // เพิ่มดัชนีแถวที่นี่
            ->addColumn('date_action', function ($row) {
                return date('d/m/Y', strtotime($row->date_action));
            })

            ->addColumn('address', function ($row) {
                if ($row->district) {
                    $address = $row->address . ' ม.' . $row->moo . ' ซอย.' . $row->soi . ' ถนน.' . $row->road . ' ต.' . $row->tambon . ' อ.' . $row->province . ' จ.' . $row->district . ' ' . $row->zipcode;
                } else {
                    $address = '';
                }
                return $address;
            })

            ->rawColumns(['date_action']) // เปลี่ยนจาก 'active_date' เป็น 'date_action'
            ->make(true);
    }

    public function log_pv_per_day_datable(Request $request)
    {
        $log_pv_per_day = DB::table('log_pv_per_day')
            ->whereBetween('date_action', [$request->s_date, $request->e_date])
            ->whereRaw("case WHEN '{$request->user_name}' != '' THEN log_pv_per_day.user_name = '{$request->user_name}' else 1 END");



        $sQuery = Datatables::of($log_pv_per_day);

        return $sQuery
            ->addIndexColumn() // เพิ่มดัชนีแถวที่นี่
            ->addColumn('date_action', function ($row) {
                return date('d/m/Y', strtotime($row->date_action));
            })
            ->rawColumns(['date_action']) // เปลี่ยนจาก 'active_date' เป็น 'date_action'
            ->make(true);
    }

    public function log_pv_per_day_ab_balance_all_datable(Request $request)
    {
        $log_pv_per_day = DB::table('log_pv_per_day_ab_balance_all')
            ->whereBetween('date_action', [$request->s_date, $request->e_date])
            ->whereRaw("case WHEN '{$request->user_name}' != '' THEN log_pv_per_day_ab_balance_all.user_name = '{$request->user_name}' else 1 END")
            ->orderby('id');
        $sQuery = Datatables::of($log_pv_per_day);
        return $sQuery
            ->addIndexColumn() // เพิ่มดัชนีแถวที่นี่
            ->addColumn('date_action', function ($row) {
                return date('d/m/Y', strtotime($row->date_action));
            })
            ->rawColumns(['date_action']) // เปลี่ยนจาก 'active_date' เป็น 'date_action'
            ->make(true);
    }
}
