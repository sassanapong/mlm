<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customers;
use App\Jang_pv;
use App\Report_bonus_active;
use App\Report_bonus_copyright;
use DB;
use DataTables;
use Auth;
use App\eWallet;
use Illuminate\Support\Arr;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class BonusCopyrightController extends Controller
{
    public $arr = array();

    public static function RunBonus_copyright() //โบนัสเจ้าขอลิขสิท
    {

        $report_bonus_active =  DB::table('report_bonus_active') //รายชื่อคนที่มีรายการแจงโบนัสข้อ 6
            ->selectRaw('user_name,sum(bonus) as total_bonus')
            ->where('status', '=', 'success')
            ->where('status_copyright', '=', 'panding')
            ->groupby('user_name')
            ->get();
        $arr = array();
        foreach ($report_bonus_active as $value) {
            $introduce =  DB::table('customers')
                ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
                // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                ->where('user_name', '=', $value->user_name)
                ->first();
            $arr[$value->user_name]['data'] =  $introduce;
            $arr[$value->user_name]['lv'] =  0;
            $introduce = self::tree($value->user_name)->flatten();
        }
        dd($arr);
    }

    public function tree($user_name)
    {
        $c = self::user_introduce($user_name);
        //self::formatTree($c);
        $this->formatTree($c);
        return $c;
    }

    public function formatTree($introduces, $num = 0, $i = 0)
    {
        $num += 1;
        foreach ($introduces as $introduce) {
            $introduce->lv = $num;
            $introduce->children = self::user_introduce($introduce->user_name);

            if ($introduce->children->isNotEmpty()) {
                //   $this->arr['pv_all'][] = $introduce->pv;
                self::formatTree($introduce->children, $num, $i);
            } else {
                //   $this->arr['pv_all'][] = $introduce->pv;
            }
        }
    }




    public static function user_introduce($user_name)
    {
        $data_user =  DB::table('customers')
            ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
            // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
            ->where('introduce_id', '=', $user_name)
            ->get();
        return $data_user;
    }
}
