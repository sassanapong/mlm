<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\StarReCashController;
use Illuminate\Http\Request;
use DB;
use DataTables;

/**
 * รายงานโบนัส STAR ReCash (หลังบ้าน)
 * เห็นทุกรายการของทุกคน รวมรายการ notfound ที่ส่วนต่างไม่มีผู้รับ
 */
class ReportStarReCashController extends Controller
{
    public function index()
    {
        return view('backend/Report_star_recash/index');
    }

    public function report_star_recash_datable(Request $request)
    {
        $report = DB::table('report_bonus_star_recash')
            ->when($request->s_date && !$request->e_date, function ($q) use ($request) {
                return $q->whereDate('created_at', $request->s_date);
            })
            ->when(!$request->s_date && $request->e_date, function ($q) use ($request) {
                return $q->whereDate('created_at', $request->e_date);
            })
            ->when($request->s_date && $request->e_date, function ($q) use ($request) {
                return $q->whereDate('created_at', '>=', $request->s_date)
                    ->whereDate('created_at', '<=', $request->e_date);
            })
            ->when($request->user_name_g, function ($q) use ($request) {
                return $q->where('user_name_g', $request->user_name_g);
            })
            ->when($request->regis_user_name, function ($q) use ($request) {
                return $q->where('regis_user_name', $request->regis_user_name);
            })
            ->when($request->type, function ($q) use ($request) {
                return $q->where('type', $request->type);
            })
            ->when($request->status, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->orderByDesc('id');

        $sQuery = Datatables::of($report);

        return $sQuery
            ->addColumn('created_at', function ($row) {
                return date('Y/m/d H:i:s', strtotime($row->created_at));
            })

            ->addColumn('type', function ($row) {
                return StarReCashController::type_label($row->type);
            })

            ->addColumn('g1_qualification', function ($row) {
                return self::qualification_name($row->g1_qualification);
            })

            ->addColumn('qualification', function ($row) {
                return $row->qualification_name ?: self::qualification_name($row->qualification);
            })

            ->addColumn('g1_percen', function ($row) {
                return number_format($row->g1_percen, 0) . '%';
            })

            ->addColumn('percen', function ($row) {
                return number_format($row->percen, 0) . '%';
            })

            ->addColumn('user_name_g', function ($row) {
                return $row->user_name_g ?: '-';
            })

            ->addColumn('name_g', function ($row) {
                return $row->name_g ?: '-';
            })

            ->addColumn('expire_date_bonus', function ($row) {
                if (empty($row->expire_date_bonus) || $row->expire_date_bonus == '0000-00-00') {
                    return '-';
                }

                return date('Y/m/d', strtotime($row->expire_date_bonus));
            })

            ->addColumn('level_up', function ($row) {
                return $row->level_up ?: '-';
            })

            ->addColumn('status', function ($row) {
                if ($row->status == 'success') {
                    return 'จ่ายแล้ว';
                }

                if ($row->status == 'notfound') {
                    return 'ไม่มีผู้รับ';
                }

                if ($row->status == 'cancel') {
                    return 'ยกเลิก';
                }

                return 'รอจ่าย';
            })

            ->make(true);
    }

    private static function qualification_name($code)
    {
        if (empty($code)) {
            return '-';
        }

        $dataset_qualification = DB::table('dataset_qualification')
            ->where('code', $code)
            ->first();

        if ($dataset_qualification) {
            return $dataset_qualification->business_qualifications;
        }

        return $code;
    }
}
