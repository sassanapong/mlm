<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use App\Exports\Bonus_active_reportExport;
use Maatwebsite\Excel\Facades\Excel;

class BonusActiveReportController extends Controller
{


    public function index()
    {

        return view('backend/Active_report/index');
    }

    // public function bonus_active_report_datable(Request $request)
    // {
    //     dd('sss');


    //     $report_bonus_active = DB::table('report_bonus_active')
    //     ->select('report_bonus_active.*')
    //     // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(created_at) = '{$request->s_date}' else 1 END"))
    //     // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(created_at) >= '{$request->s_date}' and date(created_at) <= '{$request->e_date}'else 1 END"))
    //     // ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(created_at) = '{$request->e_date}' else 1 END"))
    //     // ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  user_name = '{$request->user_name}' else 1 END"))
    //     ->whereRaw(("case WHEN  '{$request->code}' != ''  THEN  code = '{$request->code}' else 1 END"))
    //     ->limit(10);



    //     $sQuery = Datatables::of($report_bonus_active);
    //     return $sQuery

    //         // ->setRowClass('intro-x py-4 h-24 zoom-in')
    //         ->addColumn('created_at', function ($row) {
    //             return date('Y/m/d H:i:s', strtotime($row->created_at));
    //         })

    //         ->addColumn('code', function ($row) {
    //             return $row->code;
    //         })

    //         ->addColumn('username_action', function ($row) {
    //             return $row->name.' ('.$row->user_name.')';
    //         })


    //         ->addColumn('active', function ($row) {
    //             return $row->customer_name_active.' ('.$row->customer_user_active.')';
    //         })

    //         ->addColumn('user_recive_bonus', function ($row) {
    //             return $row->name_g.' ('.$row->user_name_g.')';
    //         })



    //         // ->addColumn('created_at', function ($row) {
    //         //     if($row->date_active){
    //         //         return date('Y/m/d', strtotime($row->date_active));
    //         //     }else{
    //         //         return '';
    //         //     }

    //         // })


    //         // ->addColumn('note_orther', function ($row) {
    //         //     return  $row->note_orther;
    //         // })


    //         //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

    //         ->make(true);
    // }

    public function bonus_active_report_datable(Request $request)
    {
        $business_location_id = 1;
        $report_bonus_active = DB::table('report_bonus_active')
            ->select('report_bonus_active.*')
            ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(created_at) = '{$request->s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(created_at) >= '{$request->s_date}' and date(created_at) <= '{$request->e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(created_at) = '{$request->e_date}' else 1 END"))
            ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  user_name = '{$request->user_name}' else 1 END"))
            ->whereRaw(("case WHEN  '{$request->code}' != ''  THEN  code = '{$request->code}' else 1 END"))
            ->whereRaw(("case WHEN  '{$request->user_name_active}' != ''  THEN  customer_user_active = '{$request->user_name_active}' else 1 END"));

        $sQuery = Datatables::of($report_bonus_active);
        return $sQuery

            ->addColumn('created_at', function ($row) {
                return date('Y/m/d H:i:s', strtotime($row->created_at));
            })

            ->addColumn('code', function ($row) {
                return $row->code;
            })

            ->addColumn('qualification', function ($row) {

                $dataset_qualification = DB::table('dataset_qualification')
                    ->where('code', $row->position)
                    ->first();

                if ($dataset_qualification) {
                    return $dataset_qualification->business_qualifications;
                } else {
                    return '-';
                }
            })

            ->addColumn('username_action', function ($row) {
                return $row->name . ' (' . $row->user_name . ')';
            })


            ->addColumn('active', function ($row) {
                return $row->customer_name_active . ' (' . $row->customer_user_active . ')';
            })

            ->addColumn('user_recive_bonus', function ($row) {
                return $row->name_g . ' (' . $row->user_name_g . ')';
            })



            // ->addColumn('created_at', function ($row) {
            //     if($row->date_active){
            //         return date('Y/m/d', strtotime($row->date_active));
            //     }else{
            //         return '';
            //     }

            // })


            // ->addColumn('note_orther', function ($row) {
            //     return  $row->note_orther;
            // })



            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }

    public function bonus_active_report_excel(Request $request)
    {
        // ดึงข้อมูลจากฐานข้อมูล 
        $report_bonus_active = DB::table('report_bonus_active')
            ->select('report_bonus_active.*', 'dataset_qualification.business_qualifications')
            ->whereDate('created_at', $request->e_date)
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'report_bonus_active.qualification')
            ->get();


        // สร้าง Array ของข้อมูลที่ตรงกับคอลัมน์ที่ต้องการใน Excel
        $logArray = [];

        foreach ($report_bonus_active as $log) {

            $logArray[] = [
                'created_at' => date('Y/m/d H:i:s', strtotime($log->created_at)),
                'code' => $log->code,
                'username_action' => $log->name . ' (' . $log->user_name . ')',
                'active' => $log->customer_name_active . ' (' . $log->customer_user_active . ')',
                'user_recive_bonus' => $log->name_g . ' (' . $log->user_name_g . ')',
                'qualification' => $log->business_qualifications,
                'g' => $log->g,
                'pv' => $log->pv,
                'bonus_full' => $log->bonus_full,
                'percen' => $log->percen,
                'tax_total' => $log->tax_total,
                'bonus' => $log->bonus,
            ];
        }

        // ส่งออกข้อมูลไปยัง Excel
        return Excel::download(new Bonus_active_reportExport($logArray), 'Bonus_active_report(' . $request->e_date . ').xlsx');
    }
}
