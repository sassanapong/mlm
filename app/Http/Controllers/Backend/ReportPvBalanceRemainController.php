<?php

namespace App\Http\Controllers\Backend;

use App\Exports\LogPvPerDayExport;
use App\Http\Controllers\Controller;
use DataTables;
use DB;
use Excel;
use Illuminate\Http\Request;

// รายงาน คะแนน Balance คงเหลือ (ล่าสุดรายคนจาก log_pv_per_day_ab_balance_all)
class ReportPvBalanceRemainController extends Controller
{
    public function index()
    {
        return view('backend/ReportPvBalanceRemain/index');
    }

    // แถวล่าสุดของสมาชิกแต่ละคน
    protected function latestQuery()
    {
        $latest = DB::table('log_pv_per_day_ab_balance_all as t')
            ->select('t.*', DB::raw('ROW_NUMBER() OVER (PARTITION BY t.user_name ORDER BY t.created_at DESC, t.id DESC) AS rn'));

        return DB::query()
            ->fromSub($latest, 'x')
            ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'x.qualification_id')
            ->where('x.rn', 1)
            ->select('x.*', 'dataset_qualification.business_qualifications');
    }

    protected function applyFilter($query, Request $request)
    {
        if ($request->user_name) {
            $query->where('x.user_name', $request->user_name);
        }
        return $query;
    }

    public function datable(Request $request)
    {
        $query = $this->applyFilter($this->latestQuery(), $request);

        return Datatables::of($query)
            ->addIndexColumn()
            ->editColumn('date_action', function ($row) {
                return $row->date_action ? date('d/m/Y', strtotime($row->date_action)) : '';
            })
            ->addColumn('detail', function ($row) {
                $url = url('admin/report_pv_balance_remain/' . $row->user_name);
                return '<a href="' . e($url) . '" class="btn btn-sm btn-primary">ประวัติ</a>';
            })
            ->rawColumns(['detail'])
            ->make(true);
    }

    public function excel(Request $request)
    {
        $rows = $this->applyFilter($this->latestQuery(), $request)->orderBy('x.user_name')->get();

        $logArray[] = [
            'รหัสสมาชิก', 'ชื่อ', 'ตำแหน่ง', 'balance', 'balance_type',
            'pv_a', 'pv_b', 'kang', 'aoon', 'วันที่ล่าสุด',
        ];
        foreach ($rows as $r) {
            $logArray[] = [
                $r->user_name, $r->name, $r->business_qualifications, $r->balance, $r->balance_type,
                $r->pv_a, $r->pv_b, $r->kang, $r->aoon, $r->date_action,
            ];
        }

        return Excel::download(new LogPvPerDayExport($logArray), 'report_pv_balance_remain-' . date('d-m-Y') . '.xlsx');
    }

    // ประวัติรายคน
    public function detail($user_name)
    {
        $customer = DB::table('customers')->where('user_name', $user_name)->first();
        $latest = $this->latestQuery()->where('x.user_name', $user_name)->first();
        abort_if(!$customer && !$latest, 404);

        return view('backend/ReportPvBalanceRemain/detail', compact('user_name', 'customer', 'latest'));
    }

    public function detail_datable(Request $request, $user_name)
    {
        $query = DB::table('log_pv_per_day_ab_balance_all')
            ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'log_pv_per_day_ab_balance_all.qualification_id')
            ->select('log_pv_per_day_ab_balance_all.*', 'dataset_qualification.business_qualifications')
            ->where('log_pv_per_day_ab_balance_all.user_name', $user_name);

        return Datatables::of($query)
            ->addIndexColumn()
            ->editColumn('date_action', function ($row) {
                return $row->date_action ? date('d/m/Y', strtotime($row->date_action)) : '';
            })
            ->make(true);
    }
}
