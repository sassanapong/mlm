<?php

namespace App\Http\Controllers\Frontend\FC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\eWallet;

class RunPerDayPerMonth_ReCashBackController extends Controller
{
    public $arr = array();
    protected $s_date;
    protected $e_date;
    protected $y;
    protected $m;
    protected $route;
    protected $note;


    public function __construct()
    {
        // dd('closs');
        $this->s_date = '2026-01-01';
        $this->e_date = '2026-01-15';

        // $this->s_date = '2026-11-16';
        // $this->e_date = '2026-11-31';

        $this->y = '2026';
        $this->m = '01';
        $this->route = 1;
        // $this->route = 2;
        // แปลงเดือนและปี

        $thaiMonths = [
            '01' => 'มกราคม',
            '02' => 'กุมภาพันธ์',
            '03' => 'มีนาคม',
            '04' => 'เมษายน',
            '05' => 'พฤษภาคม',
            '06' => 'มิถุนายน',
            '07' => 'กรกฎาคม',
            '08' => 'สิงหาคม',
            '09' => 'กันยายน',
            '10' => 'ตุลาคม',
            '11' => 'พฤศจิกายน',
            '12' => 'ธันวาคม',
        ];

        $monthName = $thaiMonths[$this->m]; // แปลงเลขเดือนเป็นชื่อเดือนภาษาไทย
        $yearTh = (int) $this->y + 543;      // แปลง ค.ศ. เป็น พ.ศ.

        // ตั้งค่า note แบบไดนามิก
        $this->note = "โบนัส Re CashBack {$monthName} {$yearTh} รอบที่ {$this->route}";
    }
    public function Recashback_01()
    {
        $request['s_date'] = $this->s_date;
        $request['e_date'] = $this->e_date;
        $s_date = $this->s_date;
        $e_date = $this->e_date;

        $update_jang_pv = DB::table('jang_pv') // รายชื่อคนที่มีรายการแจงโบนัสข้อ
            ->where('status_runbonus', '=', 'success')
            ->wherein('type', [1, 2, 3, 4])
            ->update(['status_runbonus' => 'pending']);


        $delete_report_bonus_all_sale_permouth = DB::table('report_bonus_recashback')
            ->where('year', $this->y)
            ->where('month', $this->m)
            ->where('route', $this->route)
            ->delete();

        return 'success step 1';
    }

    public function Recashback_02()
    {

        // dd('closs');
        $request['s_date'] = $this->s_date;
        $request['e_date'] = $this->e_date;
        $y = $this->y;
        $m =  $this->m;
        $route = $this->route;
        $note = $this->note;


        $jang_pv = DB::table('jang_pv') // รายชื่อคนที่มีรายการแจงโบนัสข้อ
            ->selectRaw('jang_pv.customer_username as customers_user_name,customers.qualification_id,
             dataset_qualification.business_qualifications,
            customers.expire_date,customers.expire_date_bonus,
            customers.name,customers.last_name,sum(jang_pv.pv) as pv_type_1234')
            ->leftJoin('customers', 'jang_pv.customer_username', '=', 'customers.user_name')
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('jang_pv.status_runbonus', '=', 'pending')
            ->where('customers.qualification_id', '!=', 'MC')

            ->where(function ($q) {
                $q->where('customers.expire_date', '>', $this->e_date)
                    ->orWhere('customers.expire_date_bonus', '>', $this->e_date);
            })

            ->wherein('type', [1, 2, 3, 4])
            ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' != ''  THEN  date(jang_pv.created_at) >= '{$request['s_date']}' and date(jang_pv.created_at) <= '{$request['e_date']}'else 1 END"))
            ->groupby('jang_pv.customer_username')
            ->orderby('customers.id', 'DESC')
            ->get();


        $i = 0;
        foreach ($jang_pv as $value) {

            if ($value->pv_type_1234 >= 100) {
                $rat = 100;
            } elseif ($value->pv_type_1234 >= 80) {
                $rat = 80;
            } elseif ($value->pv_type_1234 >= 60) {
                $rat = 60;
            } else {
                $rat = 0;
            }

            if ($rat > 0) {
                $i++;

                $bonus_full = $value->pv_type_1234 * ($rat / 100);
                $bonus_total_in_tax =  $bonus_full * (3 / 100);
                $bonus = $bonus_full - $bonus_total_in_tax;
                $dataPrepare = [
                    'user_name' => $value->customers_user_name,
                    'name' =>  $value->name . ' ' . $value->last_name,
                    'qualification' => $value->business_qualifications,
                    'percen' => $rat,
                    'pv' => $value->pv_type_1234,
                    'tax_percen' => 3,
                    'bonus_full' => $bonus_full,
                    'tax_total' => $bonus_total_in_tax,
                    'bonus' =>  $bonus,
                    'status' => 'pending',
                    'year' => $y,
                    'month' => $m,
                    'route' => $route,
                    'note' => $note,
                    'expire_date' => $value->expire_date,
                    'expire_date_bonus' => $value->expire_date_bonus
                ];



                $report_bonus_all_sale_permouth =  DB::table('report_bonus_recashback')
                    ->updateOrInsert(['user_name' => $value->customers_user_name, 'year' => $y, 'month' => $m, 'route' => $route], $dataPrepare);
            }
        }

        return 'success step 2 :' . $i;
    }


    public function Recashback_03()

    {


        $request['s_date'] = $this->s_date;
        $request['e_date'] = $this->e_date;
        $y = $this->y;
        $m =  $this->m;
        $route = $this->route;

        $c = DB::table('report_bonus_recashback')
            ->select('id', 'user_name', 'bonus_full', 'bonus as el', 'tax_total', 'note')
            ->where('status', '=', 'pending')
            ->where('year', '=', $y)
            ->where('month', '=', $m)
            ->where('route', '=', $route)
            ->limit(50)
            // ->where('note','=','Easy โปรโมชั่น รอบ 21ธ.ค.65 - 5 ม.ค.66')
            ->get();


        // dd('ddd');
        $i = 0;
        try {
            DB::BeginTransaction();

            // foreach ($c as $value) {
            //     $customers = DB::table('customers')
            //         ->select('id', 'user_name', 'ewallet','ewallet_use')
            //         ->where('user_name', $value->user_name)
            //         ->first();
            //         if(empty($customers)){
            //             dd($value->user_name,'Not Success');
            //         }
            // }

            foreach ($c as $value) {
                $customers = DB::table('customers')
                    ->select('id', 'user_name', 'ewallet', 'ewallet_use')
                    ->where('user_name', $value->user_name)
                    ->first();
                // if(empty($customers)){
                //     dd($value->user_name);
                // }


                if (empty($customers->ewallet)) {
                    $ewallet = 0;
                } else {
                    $ewallet = $customers->ewallet;
                }

                if (empty($customers->ewallet_use)) {
                    $ewallet_use = 0;
                } else {
                    $ewallet_use = $customers->ewallet_use;
                }

                $ew_total = $ewallet  + $value->el;
                $ew_use = $ewallet_use + $value->el;
                DB::table('customers')
                    ->where('user_name', $value->user_name)
                    ->update(['ewallet' => $ew_total, 'ewallet_use' => $ew_use]);


                $count_eWallet =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_wallet();


                $dataPrepare = [
                    'transaction_code' => $count_eWallet,
                    'customers_id_fk' => $customers->id,
                    'customer_username' => $value->user_name,
                    'tax_total' => $value->tax_total,
                    'bonus_full' => $value->bonus_full,
                    'amt' => $value->el,
                    'old_balance' => $customers->ewallet,
                    'balance' => $ew_total,
                    'note_orther' => $value->note,
                    'receive_date' => now(),
                    'receive_time' => now(),
                    'type' => 1,
                    'status' => 2,
                ];

                $query =  eWallet::create($dataPrepare);
                DB::table('report_bonus_recashback')
                    ->where('id', $value->id)
                    ->update(['status' => 'success']);

                $i++;
            }

            $c_report_bonus_recashback = DB::table('report_bonus_recashback')
                ->select('id', 'user_name', 'bonus_full', 'bonus as el', 'tax_total', 'note')
                ->where('status', '=', 'pending')
                ->where('year', '=', $y)
                ->where('month', '=', $m)
                ->where('route', '=', $route)
                ->count();

            DB::commit();

            return 'success step 3  Pending: ' . $c_report_bonus_recashback;
        } catch (Exception $e) {
            DB::rollback();
            return 'fail';
        }
    }
}
