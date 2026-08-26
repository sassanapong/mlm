<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BonusAllSalePreviewCronController extends Controller
{
    const SELF_G1 = 'SELF';

    protected $customerCache = [];

    public function run(Request $request)
    {
        // if (!$this->isAuthorized($request)) {
        //     return response()->json([
        //         'status' => 'forbidden',
        //         'message' => 'Invalid cron token.',
        //     ], 403);
        // }

        if (!Schema::hasTable('bonus_all_sale_preview_runs') || !Schema::hasTable('bonus_all_sale_preview_details')) {
            return response()->json([
                'status' => 'missing_tables',
                'message' => 'Please run migrations before running All Sale preview cron.',
            ], 500);
        }

        if (!Schema::hasColumn('bonus_all_sale_preview_details', 'introduce_id')) {
            return response()->json([
                'status' => 'missing_column',
                'message' => 'Please run migrations to add introduce_id before running All Sale preview cron.',
            ], 500);
        }

        $batchLimit = (int) $request->get('limit', 1000);
        $batchLimit = max(1, min($batchLimit, 1000));
        $period = $this->currentPeriod($request->get('date'));
        $now = Carbon::now();
        $runDate = $request->get('date') ? Carbon::parse($request->get('date')) : $now;
        $totalCount = $this->countTotalRows($period);

        $latestRun = DB::table('bonus_all_sale_preview_runs')
            ->orderBy('id', 'desc')
            ->first();
        $latestRunDate = $latestRun
            ? Carbon::parse($latestRun->generated_at ?: $latestRun->started_at ?: $latestRun->created_at)
            : null;
        $isSameRunDay = $latestRunDate && $latestRunDate->isSameDay($runDate);
        $isSamePeriod = $latestRun
            && (int) $latestRun->year === $period['year']
            && (int) $latestRun->month === $period['month']
            && (int) $latestRun->route === 1;

        if (
            $latestRun
            && $latestRun->status === 'success'
            && $isSamePeriod
            && $isSameRunDay
        ) {
            return response()->json([
                'status' => 'success',
                'message' => 'Preview for today is already complete.',
                'run_id' => $latestRun->id,
                'year' => $period['year'],
                'month' => $period['month'],
                'route' => 1,
                'processed_pv_rows' => $latestRun->processed_pv_count,
                'total_count' => $totalCount,
                'processed_count' => $latestRun->processed_count,
                'remaining_count' => 0,
                'estimated_batches' => 0,
                'batch_limit' => $latestRun->batch_limit,
                'last_processed_id' => $latestRun->last_processed_id,
                'generated_at' => $latestRun->generated_at,
            ]);
        }

        if (!$latestRun || !$isSamePeriod || !$isSameRunDay || $latestRun->status === 'fail') {
            DB::table('bonus_all_sale_preview_details')->delete();
            DB::table('bonus_all_sale_preview_runs')->delete();

            $runId = DB::table('bonus_all_sale_preview_runs')->insertGetId([
                'year' => $period['year'],
                'month' => $period['month'],
                'route' => 1,
                'start_date' => $period['start_date'],
                'end_date' => $period['end_date'],
                'status' => 'pending',
                'last_processed_id' => 0,
                'total_count' => $totalCount,
                'processed_count' => 0,
                'processed_pv_count' => 0,
                'remaining_count' => $totalCount,
                'estimated_batches' => $this->estimatedBatches($totalCount, $batchLimit),
                'batch_limit' => $batchLimit,
                'started_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $run = DB::table('bonus_all_sale_preview_runs')->where('id', $runId)->first();
        } else {
            $run = $latestRun;
            $runId = $run->id;

            DB::table('bonus_all_sale_preview_runs')
                ->where('id', $runId)
                ->update([
                    'status' => 'running',
                    'total_count' => $totalCount,
                    'remaining_count' => max(0, $totalCount - (int) $run->processed_count),
                    'estimated_batches' => $this->estimatedBatches(max(0, $totalCount - (int) $run->processed_count), $batchLimit),
                    'batch_limit' => $batchLimit,
                    'error_message' => null,
                    'updated_at' => $now,
                ]);
        }

        try {
            $rows = DB::table('jang_pv')
                ->select('id', 'to_customer_username', 'pv')
                ->where('id', '>', $run->last_processed_id)
                ->whereIn('type', [1, 2, 3, 4])
                ->whereDate('created_at', '>=', $period['start_date'])
                ->whereDate('created_at', '<=', $period['end_date'])
                ->orderBy('id')
                ->limit($batchLimit)
                ->get();

            $batchPvRows = 0;
            $lastProcessedId = (int) $run->last_processed_id;

            foreach ($rows as $row) {
                $lastProcessedId = $row->id;
                $pv = (float) $row->pv;

                if ($pv <= 0 || empty($row->to_customer_username)) {
                    continue;
                }

                $this->applyPvToPreview($runId, $period, $row->to_customer_username, $pv);
                $batchPvRows++;
            }

            $processedCount = (int) $run->processed_count + count($rows);
            $processedPvRows = (int) $run->processed_pv_count + $batchPvRows;
            $remainingCount = max(0, $totalCount - $processedCount);
            if ($rows->isEmpty()) {
                $remainingCount = 0;
            }

            DB::table('bonus_all_sale_preview_runs')
                ->where('id', $runId)
                ->update([
                    'last_processed_id' => $lastProcessedId,
                    'total_count' => $totalCount,
                    'processed_count' => $processedCount,
                    'processed_pv_count' => $processedPvRows,
                    'remaining_count' => $remainingCount,
                    'estimated_batches' => $this->estimatedBatches($remainingCount, $batchLimit),
                    'batch_limit' => $batchLimit,
                    'status' => 'pending',
                    'updated_at' => Carbon::now(),
                ]);

            if ($remainingCount > 0) {
                return response()->json([
                    'status' => 'pending',
                    'message' => 'Preview batch processed. Run again to continue.',
                    'run_id' => $runId,
                    'year' => $period['year'],
                    'month' => $period['month'],
                    'route' => 1,
                    'batch_rows' => count($rows),
                    'processed_pv_rows' => $processedPvRows,
                    'total_count' => $totalCount,
                    'processed_count' => $processedCount,
                    'remaining_count' => $remainingCount,
                    'estimated_batches' => $this->estimatedBatches($remainingCount, $batchLimit),
                    'batch_limit' => $batchLimit,
                    'last_processed_id' => $lastProcessedId,
                ]);
            }

            $this->markRunSuccess($runId, $period);

            return response()->json([
                'status' => 'success',
                'message' => 'Preview calculation completed and replaced old snapshot.',
                'run_id' => $runId,
                'year' => $period['year'],
                'month' => $period['month'],
                'route' => 1,
                'processed_pv_rows' => $processedPvRows,
                'total_count' => $totalCount,
                'processed_count' => $processedCount,
                'remaining_count' => 0,
                'estimated_batches' => 0,
                'batch_limit' => $batchLimit,
                'last_processed_id' => $lastProcessedId,
            ]);
        } catch (\Exception $e) {
            DB::table('bonus_all_sale_preview_runs')
                ->where('id', $runId)
                ->update([
                    'status' => 'fail',
                    'error_message' => $e->getMessage(),
                    'updated_at' => Carbon::now(),
                ]);

            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    protected function isAuthorized(Request $request)
    {
        $token = env('BONUS_ALL_SALE_PREVIEW_TOKEN', 'bonus-all-sale-preview');

        return hash_equals((string) $token, (string) $request->get('token'));
    }

    protected function currentPeriod($date = null)
    {
        $today = $date ? Carbon::parse($date) : Carbon::now();

        return [
            'year' => (int) $today->format('Y'),
            'month' => (int) $today->format('m'),
            'start_date' => $today->copy()->startOfMonth()->format('Y-m-d'),
            'end_date' => $today->copy()->endOfMonth()->format('Y-m-d'),
            'route' => 1,
        ];
    }

    protected function countTotalRows(array $period)
    {
        return (int) DB::table('jang_pv')
            ->whereIn('type', [1, 2, 3, 4])
            ->whereDate('created_at', '>=', $period['start_date'])
            ->whereDate('created_at', '<=', $period['end_date'])
            ->count();
    }

    protected function estimatedBatches($remainingCount, $batchLimit)
    {
        if ($remainingCount <= 0) {
            return 0;
        }

        return (int) ceil($remainingCount / max(1, $batchLimit));
    }

    protected function applyPvToPreview($runId, array $period, $sourceUserName, $pv)
    {
        $source = $this->getCustomer($sourceUserName);
        if (!$source) {
            return;
        }

        $this->incrementPreviewDetail($runId, $period, $source, self::SELF_G1, null, $pv, $pv);

        $current = $source;
        $branchUserName = $source->user_name;
        $guard = 0;

        while (!empty($current->introduce_id) && $current->introduce_id !== 'AA' && $guard < 100) {
            $parent = $this->getCustomer($current->introduce_id);
            if (!$parent) {
                break;
            }

            $branch = $this->getCustomer($branchUserName);
            $this->incrementPreviewDetail($runId, $period, $parent, $branchUserName, $branch, 0, $pv);

            $branchUserName = $parent->user_name;
            $current = $parent;
            $guard++;
        }
    }

    protected function incrementPreviewDetail($runId, array $period, $owner, $g1UserName, $g1Customer, $personalPv, $organizationPv)
    {
        $existing = DB::table('bonus_all_sale_preview_details')
            ->where('year', $period['year'])
            ->where('month', $period['month'])
            ->where('route', 1)
            ->where('user_name', $owner->user_name)
            ->where('g1_user_name', $g1UserName)
            ->first();

        $newPersonalPv = ($existing ? (float) $existing->personal_pv : 0) + $personalPv;
        $newOrganizationPv = ($existing ? (float) $existing->organization_pv : 0) + $organizationPv;
        $rate = $this->rateForPv($newOrganizationPv);
        $next = $this->nextRateForPv($newOrganizationPv);
        $now = Carbon::now();

        $data = [
            'run_id' => $runId,
            'introduce_id' => $owner->introduce_id,
            'g1_name' => $g1Customer ? trim($g1Customer->name . ' ' . $g1Customer->last_name) : null,
            'g1_qualification' => $g1Customer ? $g1Customer->qualification_id : null,
            'g1_expire_date_bonus' => $g1Customer ? $g1Customer->expire_date_bonus : null,
            'personal_pv' => $newPersonalPv,
            'organization_pv' => $newOrganizationPv,
            'all_sale_rate' => $rate,
            'next_rate' => $next['rate'],
            'pv_to_next_rate' => $next['pv_to_next_rate'],
            'generated_at' => $now,
            'updated_at' => $now,
        ];

        if ($existing) {
            DB::table('bonus_all_sale_preview_details')
                ->where('id', $existing->id)
                ->update($data);

            return;
        }

        DB::table('bonus_all_sale_preview_details')->insert(array_merge($data, [
            'year' => $period['year'],
            'month' => $period['month'],
            'route' => 1,
            'user_name' => $owner->user_name,
            'g1_user_name' => $g1UserName,
            'created_at' => $now,
        ]));
    }

    protected function getCustomer($userName)
    {
        if (isset($this->customerCache[$userName])) {
            return $this->customerCache[$userName];
        }

        $this->customerCache[$userName] = DB::table('customers')
            ->select('id', 'user_name', 'introduce_id', 'qualification_id', 'expire_date_bonus', 'name', 'last_name')
            ->where('user_name', $userName)
            ->first();

        return $this->customerCache[$userName];
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

    protected function markRunSuccess($runId, array $period)
    {
        $summaryRows = DB::table('bonus_all_sale_preview_details')
            ->select('user_name', DB::raw('SUM(organization_pv) as pv_total'))
            ->where('year', $period['year'])
            ->where('month', $period['month'])
            ->where('route', 1)
            ->groupBy('user_name')
            ->get();

        foreach ($summaryRows as $summary) {
            $rate = $this->rateForPv((float) $summary->pv_total);
            $next = $this->nextRateForPv((float) $summary->pv_total);

            DB::table('bonus_all_sale_preview_details')
                ->where('year', $period['year'])
                ->where('month', $period['month'])
                ->where('route', 1)
                ->where('user_name', $summary->user_name)
                ->update([
                    'all_sale_rate' => $rate,
                    'next_rate' => $next['rate'],
                    'pv_to_next_rate' => $next['pv_to_next_rate'],
                    'generated_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
        }

        DB::table('bonus_all_sale_preview_runs')
            ->where('id', $runId)
            ->update([
                'status' => 'success',
                'finished_at' => Carbon::now(),
                'generated_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
    }
}
