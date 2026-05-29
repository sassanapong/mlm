<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Log;

class DeleteCustomerCancelFunctionController extends Controller
{

    public static function RebuildNormalCustomerLine()
    {
        dd('เริ่มปรับปรุงข้อมูลลูกค้าปกติที่มีปัญหาเรื่อง upline/introduce');
        // $rs =  DeleteCustomerCancelFunctionController::DeleteCancelCustomers();
        // dd($rs);

        // DB::table('customers')

        //     ->update([
        //         'status_check_runupline' => 'pending'
        //     ]);

        // DB::table('customers')
        //     ->where('upline_id', 'AA')
        //     ->update([
        //         'status_check_runupline' => 'success'
        //     ]);


        // dd('เริ่มปรับปรุงข้อมูลลูกค้าปกติที่มีปัญหาเรื่อง upline/introduce');
        $members = DB::table('customers')
            ->where('status_customer', 'normal')
            ->where('status_check_runupline', 'pending')
            ->orderBy('id')
            ->limit(3000)
            ->get();

        foreach ($members as $member) {
            DB::transaction(function () use ($member) {

                $newIntroduceId = self::findNormalIntroduce($member->introduce_id);

                $newUpline = self::findAvailableUpline(
                    $member->upline_id,
                    $member->type_upline,
                    $member->user_name
                );
                DB::table('customers')
                    ->where('id', $member->id)
                    ->update([
                        'introduce_id' => $newIntroduceId,
                        'upline_id' => $newUpline['upline_id'],
                        'type_upline' => $newUpline['type_upline'],
                        'status_check_runupline' => 'success',
                        'updated_at' => now(),
                    ]);
            });
        }

        return [
            'processed' => $members->count(),
            'pending' => DB::table('customers')
                ->where('status_customer', 'normal')
                ->where('status_check_runupline', 'pending')
                ->count(),
        ];
    }
    private static function findNormalIntroduce($introduceId)
    {
        if (!$introduceId || $introduceId == 'AA') {
            return $introduceId;
        }

        $current = $introduceId;
        $visited = [];

        while ($current && $current != 'AA') {
            if (in_array($current, $visited)) {
                return null;
            }

            $visited[] = $current;

            $customer = DB::table('customers')
                ->select('user_name', 'introduce_id', 'status_customer')
                ->where('user_name', $current)
                ->first();

            if (!$customer) {
                return null;
            }

            if ($customer->status_customer == 'normal') {
                return $customer->user_name;
            }

            $current = $customer->introduce_id;
        }

        return $current;
    }

    private static function findNormalUpline($uplineId, $typeUpline)
    {
        if (!$uplineId || $uplineId == 'AA') {
            return [
                'upline_id' => $uplineId,
                'type_upline' => $typeUpline,
            ];
        }

        $current = $uplineId;
        $currentType = $typeUpline;
        $visited = [];

        while ($current && $current != 'AA') {
            if (in_array($current, $visited)) {
                return [
                    'upline_id' => null,
                    'type_upline' => null,
                ];
            }

            $visited[] = $current;

            $customer = DB::table('customers')
                ->select('user_name', 'upline_id', 'type_upline', 'status_customer')
                ->where('user_name', $current)
                ->first();

            if (!$customer) {
                return [
                    'upline_id' => null,
                    'type_upline' => null,
                ];
            }

            if ($customer->status_customer == 'normal') {
                return [
                    'upline_id' => $customer->user_name,
                    'type_upline' => $currentType,
                ];
            }

            $current = $customer->upline_id;
            $currentType = $customer->type_upline;
        }

        return [
            'upline_id' => $current,
            'type_upline' => $currentType,
        ];
    }



    private static function findAvailableUpline($uplineId, $typeUpline, $selfUserName)
    {
        $normalUpline = self::findNormalUpline($uplineId, $typeUpline);

        if (!$normalUpline['upline_id']) {
            return [
                'upline_id' => null,
                'type_upline' => null,
            ];
        }

        // ถ้าช่องเดิมว่าง ใช้เลย
        if (!self::hasSlotConflict(
            $normalUpline['upline_id'],
            $normalUpline['type_upline'],
            $selfUserName
        )) {
            return $normalUpline;
        }

        // ถ้าช่องเดิมชน ให้หาช่องว่างใต้ upline คนนั้น
        return self::findFirstEmptySlot($normalUpline['upline_id']);
    }

    private static function findFirstEmptySlot($startUserName)
    {
        $queue = [$startUserName];
        $visited = [];

        while (!empty($queue)) {
            $current = array_shift($queue);

            if (in_array($current, $visited)) {
                continue;
            }

            $visited[] = $current;

            $children = DB::table('customers')
                ->select('user_name', 'type_upline')
                ->where('status_customer', 'normal')
                ->where('upline_id', $current)
                ->whereIn('type_upline', ['A', 'B'])
                ->orderByRaw("FIELD(type_upline, 'A', 'B')")
                ->get();

            $usedTypes = $children->pluck('type_upline')->toArray();

            if (!in_array('A', $usedTypes)) {
                return [
                    'upline_id' => $current,
                    'type_upline' => 'A',
                ];
            }

            if (!in_array('B', $usedTypes)) {
                return [
                    'upline_id' => $current,
                    'type_upline' => 'B',
                ];
            }

            foreach ($children as $child) {
                $queue[] = $child->user_name;
            }
        }

        return [
            'upline_id' => null,
            'type_upline' => null,
        ];
    }

    private static function hasSlotConflict($uplineId, $typeUpline, $selfUserName)
    {
        return DB::table('customers')
            ->where('status_customer', 'normal')
            ->where('upline_id', $uplineId)
            ->where('type_upline', $typeUpline)
            ->where('user_name', '!=', $selfUserName)
            ->exists();
    }



    public static function DeleteCancelCustomers()
    {
        //ตรวจว่ามี A B ซ้ำกันไหม
        // $duplicates = DB::table('customers')
        //     ->select('upline_id', 'type_upline', DB::raw('COUNT(*) as total'))
        //     ->where('status_customer', 'normal')
        //     ->whereNotNull('upline_id')
        //     ->whereIn('type_upline', ['A', 'B'])
        //     ->groupBy('upline_id', 'type_upline')
        //     ->havingRaw('COUNT(*) > 1')
        //     ->get();

        // dd($duplicates);


        // $deleted = DB::table('customers')
        //     ->where('status_customer', 'cancel')
        //     ->count();
        // dd($deleted);
        // ดูรายละเอียดลูกค้าปกติที่มีปัญหาเรื่อง upline/introduce ก่อนลบ
        // $badIntroduceList = DB::table('customers as c')
        //     ->join('customers as p', 'c.introduce_id', '=', 'p.user_name')
        //     ->select(
        //         'c.id',
        //         'c.user_name',
        //         'c.introduce_id',
        //         'c.upline_id',
        //         'p.status_customer as introduce_status',
        //         'c.status_check_runupline'
        //     )
        //     ->where('c.status_customer', 'normal')
        //     ->where('p.status_customer', 'cancel')
        //     ->limit(20)
        //     ->get();

        // $badUplineList = DB::table('customers as c')
        //     ->join('customers as p', 'c.upline_id', '=', 'p.user_name')
        //     ->select(
        //         'c.id',
        //         'c.user_name',
        //         'c.upline_id',
        //         'c.type_upline',
        //         'p.status_customer as upline_status',
        //         'c.status_check_runupline'
        //     )
        //     ->where('c.status_customer', 'normal')
        //     ->where('p.status_customer', 'cancel')
        //     ->limit(20)
        //     ->get();

        // dd($badIntroduceList, $badUplineList);

        //ปรับคนที่เป็นปัญหาให้ประมวลผลไหม่
        // DB::table('customers as c')
        //     ->join('customers as p', 'c.introduce_id', '=', 'p.user_name')
        //     ->where('c.status_customer', 'normal')
        //     ->where('p.status_customer', 'cancel')
        //     ->update([
        //         'c.status_check_runupline' => 'pending',
        //     ]);

        // DB::table('customers as c')
        //     ->join('customers as p', 'c.upline_id', '=', 'p.user_name')
        //     ->where('c.status_customer', 'normal')
        //     ->where('p.status_customer', 'cancel')
        //     ->update([
        //         'c.status_check_runupline' => 'pending',
        //     ]);
        // dd('ปรับสถานะ pending ให้กับลูกค้าที่มีปัญหาเรื่อง upline/introduce เรียบร้อย');



        // $badIntroduce = DB::table('customers as c')
        //     ->join('customers as p', 'c.introduce_id', '=', 'p.user_name')
        //     ->where('c.status_customer', 'normal')
        //     ->where('p.status_customer', 'cancel')
        //     ->count();

        // $badUpline = DB::table('customers as c')
        //     ->join('customers as p', 'c.upline_id', '=', 'p.user_name')
        //     ->where('c.status_customer', 'normal')
        //     ->where('p.status_customer', 'cancel')
        //     ->count();

        // dd($badIntroduce, $badUpline);
        // 00 ค่อยเริ่มลบ


        // $deleted = DB::table('customers')
        //     ->where('status_customer', 'cancel')
        //     // ->limit(1000)
        //     ->delete();

        return [
            'deleted' => $deleted,
            'remaining' => DB::table('customers')
                ->where('status_customer', 'cancel')
                ->count(),
        ];
    }
}
