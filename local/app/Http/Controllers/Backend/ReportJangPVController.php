<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;


class ReportJangPVController extends Controller
{


    public function index()
    {

        return view('backend/JangPv_report/index');
    }

    public function jangpv_report_datable(Request $request)
    {
        $business_location_id = 1;
        $jang_pv = DB::table('jang_pv')
            ->select('jang_pv.*', 'jang_type.type as type_name')
            ->leftjoin('jang_type', 'jang_pv.type', '=', 'jang_type.id')
            ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(jang_pv.created_at) = '{$request->s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(jang_pv.created_at) >= '{$request->s_date}' and date(jang_pv.created_at) <= '{$request->e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(jang_pv.created_at) = '{$request->e_date}' else 1 END"))
            ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  jang_pv.customer_username = '{$request->user_name}' else 1 END"))
            ->whereRaw(("case WHEN  '{$request->code}' != ''  THEN  jang_pv.code = '{$request->code}' else 1 END"))
            ->whereRaw(("case WHEN  '{$request->type}' != ''  THEN  jang_pv.type = '{$request->type}' else 1 END"));

        $sQuery = Datatables::of($jang_pv);
        return $sQuery


            ->addColumn('created_at', function ($row) {
                return date('Y/m/d H:i:s', strtotime($row->created_at));
            })

            ->addColumn('code', function ($row) {
                return $row->code;
            })

            ->addColumn('customer_username', function ($row) {
                $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->customer_username);
                if ($upline) {
                    $html = @$upline->name . ' ' . @$upline->last_name . ' (' . $upline->user_name . ')';
                } else {
                    $html = '-';
                }

                return $html;
            })

            ->addColumn('to_customer_username', function ($row) {
                $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->to_customer_username);
                if ($upline) {
                    $html = @$upline->name . ' ' . @$upline->last_name . ' (' . $upline->user_name . ')';
                } else {
                    $html = '-';
                }

                return $html;
            })

            ->addColumn('sponser_1', function ($row) {
                $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->to_customer_username);

                if ($upline) {

                    $introduce_id_1 = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($upline->introduce_id);
                    if ($introduce_id_1) {
                        $html = @$introduce_id_1->name . ' ' . @$introduce_id_1->last_name . ' (' . $introduce_id_1->user_name . ')';
                    } else {
                        $html = '-';
                    }
                } else {
                    $html = '-';
                }

                return $html;
            })


            ->addColumn('sponser_2', function ($row) {
                $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->to_customer_username);

                if ($upline) {

                    $introduce_id_1 = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($upline->introduce_id);
                    if ($introduce_id_1) {
                        $introduce_id_2 = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($introduce_id_1->introduce_id);
                        if ($introduce_id_2) {
                            $html = @$introduce_id_2->name . ' ' . @$introduce_id_2->last_name . ' (' . $introduce_id_2->user_name . ')';
                        } else {
                            $html = '-';
                        }
                    } else {
                        $html = '-';
                    }
                } else {
                    $html = '-';
                }

                return $html;
            })


            ->addColumn('sponser_3', function ($row) {
                $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->to_customer_username);

                if ($upline) {
                    $introduce_id_1 = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($upline->introduce_id);
                    if ($introduce_id_1) {
                        $introduce_id_2 = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($introduce_id_1->introduce_id);
                        if ($introduce_id_2) {
                            $introduce_id_3 = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($introduce_id_2->introduce_id);
                            if ($introduce_id_3) {
                                $html = @$introduce_id_3->name . ' ' . @$introduce_id_3->last_name . ' (' . $introduce_id_3->user_name . ')';
                            } else {
                                $html = '-';
                            }
                        } else {
                            $html = '-';
                        }
                    } else {
                        $html = '-';
                    }
                } else {
                    $html = '-';
                }

                return $html;
            })





            ->addColumn('date_active', function ($row) {
                if ($row->date_active) {
                    return date('Y/m/d', strtotime($row->date_active));
                } else {
                    return '';
                }
            })

            ->addColumn('position', function ($row) {
                $dataset_qualification = DB::table('dataset_qualification')
                    ->where('code', $row->position)
                    ->first();

                if ($dataset_qualification) {
                    return $dataset_qualification->business_qualifications;
                } else {
                    return '-';
                }
            })



            ->addColumn('pv', function ($row) {
                return number_format($row->pv, 2);
            })

            ->addColumn('note_orther', function ($row) {
                return  $row->note_orther;
            })


            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }
}
