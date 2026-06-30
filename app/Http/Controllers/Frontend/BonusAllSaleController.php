<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Auth;
use Illuminate\Support\Facades\Schema;

class BonusAllSaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }
    public function index()
    {
        $period = $this->currentPeriod();
        $previewRun = null;
        $previewDetails = collect();
        $previewSelf = null;
        $previewTotalPv = 0;
        $previewRate = 0;
        $previewNextRate = 3;
        $previewPvToNextRate = 1500;
        $previewUpdatedAt = null;
        $previewTableReady = Schema::hasTable('bonus_all_sale_preview_runs') && Schema::hasTable('bonus_all_sale_preview_details');

        $AllSaleTotal = DB::table('report_bonus_all_sale_permouth')
            ->where('customer_id_fk', Auth::guard('c_user')->user()->id)
            ->sum('bonus_total_not_tax');

        $customers =  DB::table('customers')
            ->select(
                'customers.id',
                'customers.user_name',
                'customers.pv_upgrad',
                'customers.status_customer',
                'customers.ewallet',
                'customers.ewallet_use',
                'customers.expire_date',
                'customers.expire_date_bonus',
                'dataset_qualification.business_qualifications as qualification_name',
                'dataset_qualification.bonus'
            )
            ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', Auth::guard('c_user')->user()->user_name)

            ->first();

        if ($previewTableReady) {
            $previewRun = DB::table('bonus_all_sale_preview_runs')
                ->where('year', $period['year'])
                ->where('month', $period['month'])
                ->where('route', $period['route'])
                ->first();

            $previewRows = DB::table('bonus_all_sale_preview_details')
                ->where('year', $period['year'])
                ->where('month', $period['month'])
                ->where('route', $period['route'])
                ->where('user_name', Auth::guard('c_user')->user()->user_name)
                ->orderByRaw("CASE WHEN g1_user_name = 'SELF' THEN 0 ELSE 1 END")
                ->orderBy('organization_pv', 'desc')
                ->get();

            $previewSelf = $previewRows->firstWhere('g1_user_name', 'SELF');
            $previewDetails = $previewRows->filter(function ($row) {
                return $row->g1_user_name !== 'SELF';
            })->values();

            $previewTotalPv = (float) $previewRows->sum('organization_pv');
            $previewRate = $this->rateForPv($previewTotalPv);
            $next = $this->nextRateForPv($previewTotalPv);
            $previewNextRate = $next['rate'];
            $previewPvToNextRate = $next['pv_to_next_rate'];
            $previewUpdatedAt = $previewRun && $previewRun->generated_at
                ? Carbon::parse($previewRun->generated_at)->format('d/m/Y H:i')
                : null;
        }

        return view('frontend/bonus-AllSale', compact(
            'AllSaleTotal',
            'customers',
            'period',
            'previewRun',
            'previewDetails',
            'previewSelf',
            'previewTotalPv',
            'previewRate',
            'previewNextRate',
            'previewPvToNextRate',
            'previewUpdatedAt',
            'previewTableReady'
        ));
    }

    protected function currentPeriod()
    {
        $today = Carbon::now();
        $day = (int) $today->format('d');
        $route = $day <= 15 ? 1 : 2;
        $startDate = $route === 1
            ? $today->copy()->startOfMonth()
            : $today->copy()->startOfMonth()->addDays(15);
        $endDate = $route === 1
            ? $today->copy()->startOfMonth()->addDays(14)
            : $today->copy()->endOfMonth();

        return [
            'year' => (int) $today->format('Y'),
            'month' => (int) $today->format('m'),
            'route' => $route,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
    }

    protected function rateForPv($pv)
    {
        if ($pv >= 100000) {
            return 21;
        } elseif ($pv >= 80000) {
            return 18;
        } elseif ($pv >= 50000) {
            return 15;
        } elseif ($pv >= 30000) {
            return 12;
        } elseif ($pv >= 20000) {
            return 9;
        } elseif ($pv >= 10000) {
            return 6;
        } elseif ($pv >= 1500) {
            return 3;
        }

        return 0;
    }

    protected function nextRateForPv($pv)
    {
        $steps = [
            1500 => 3,
            10000 => 6,
            20000 => 9,
            30000 => 12,
            50000 => 15,
            80000 => 18,
            100000 => 21,
        ];

        foreach ($steps as $targetPv => $rate) {
            if ($pv < $targetPv) {
                return [
                    'rate' => $rate,
                    'pv_to_next_rate' => $targetPv - $pv,
                ];
            }
        }

        return [
            'rate' => 21,
            'pv_to_next_rate' => 0,
        ];
    }
}
