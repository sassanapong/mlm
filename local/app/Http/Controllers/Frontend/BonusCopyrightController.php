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

    public static function RunBonus_copyright1() //โบนัสเจ้าขอลิขสิท
    {
        // DB::table('report_bonus_active')
        //  ->update(['status_copyright' => 'panding']);
        //  die('success');

        $report_bonus_active =  DB::table('report_bonus_active') //รายชื่อคนที่มีรายการแจงโบนัสข้อ 6
            ->selectRaw('user_name_g,sum(bonus) as total_bonus')
            ->where('status', '=', 'success')
            ->where('status_copyright', '=', 'panding')
            ->groupby('user_name_g')
            ->limit('1000')
            ->get();
            //  dd($report_bonus_active);

            if(count($report_bonus_active)<=0){
                return 'success ทั้งหมดแล้ว';

            }
            $rs = array();
            $upline_arr = array();
        foreach ($report_bonus_active as $value) {


                    $introduce = DB::table('customers')->select(
                        'customers.introduce_id'
                    )
                    ->where('user_name', '=',$value->user_name_g)
                    ->first();


                    $user_name = @$introduce->introduce_id;

                    if($introduce and $introduce->introduce_id != 'AA'){
                        $i = 1;
                        $j = 1;
                        while ($i <= 6) { //ค้นหาด้านบน 6 ชั้น
                            $up = DB::table('customers')->select(
                                'customers.pv',
                                'customers.id',
                                'customers.name',
                                'customers.last_name',
                                'customers.user_name',
                                'customers.qualification_id',
                                'customers.expire_date',
                                'dataset_qualification.code',
                                'customers.introduce_id'
                            )
                            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                            ->where('user_name', '=',$user_name)
                            ->first();


                            if (empty($up)) {
                                $status = 'fail';
                                $rs = '';
                                $i = 6;
                                break;
                            }

                            if (empty($up->expire_date) || strtotime($up->expire_date) < strtotime(date('Ymd'))) {
                                $status = 'fail';
                                $user_name = $up->introduce_id;
                            }elseif(empty($up->name) || $up->name == ''){
                                $status = 'fail';
                                $user_name = $up->introduce_id;
                            }else{

                                if($up->code == '' || $up->code == null || $up->code == '-'){
                                    $qualification_id = 'MB';
                                }else{
                                    $qualification_id = $up->code;
                                }


                                $name = $up->name . ' ' . $up->last_name;
                                if($j== 1){
                                    $percen = 20;

                                    if($qualification_id == 'MB' ){
                                        $bonus_copyright = 0;

                                    }else{
                                        $bonus_copyright= $value->total_bonus * 20/100;


                                    }
                                }elseif($j== 2){
                                    $percen = 15;
                                    if($qualification_id == 'MB'||$qualification_id == 'MO' ){
                                        $bonus_copyright = 0;
                                    }else{
                                        $bonus_copyright= $value->total_bonus * 15/100;
                                    }

                                }elseif($j== 3){
                                    $percen = 5;
                                    if($qualification_id == 'MB'||$qualification_id == 'MO' ||$qualification_id == 'VIP' ){
                                        $bonus_copyright = 0;
                                    }else{
                                        $bonus_copyright= $value->total_bonus * 5/100;
                                    }

                                }elseif($j== 4){


                                    $percen = 4;
                                    if($qualification_id == 'MB'||$qualification_id == 'MO' ||$qualification_id == 'VIP' || $qualification_id == 'VVIP' ){
                                        $bonus_copyright = 0;
                                    }else{
                                        $bonus_copyright= $value->total_bonus * 4/100;
                                    }


                                }else{
                                    $percen = 3;
                                    if($qualification_id == 'MB'||$qualification_id == 'MO' ||$qualification_id == 'VIP' || $qualification_id == 'VVIP' ){
                                        $bonus_copyright = 0;
                                    }else{
                                        $bonus_copyright= $value->total_bonus * 3/100;
                                    }


                                }



                                $upline_arr[$value->user_name_g][] =['user_name'=>$up->user_name,'name'=>$name,'postion'=>$up->qualification_id,
                                'g'=>$j,'percen'=>$percen,'bonus'=>$bonus_copyright];
                                $i++;
                                $j++;
                                $status = 'success';
                            }
                        }
                        if($upline_arr){
                            $rs[] = ['user_name'=>$value->user_name_g,'bonus'=>$value->total_bonus,'sponser_all'=>$upline_arr[$value->user_name_g]];
                        }else{
                            $rs[] = ['user_name'=>$value->user_name_g,'bonus'=>$value->total_bonus,'sponser_all'=>null];

                        }



                    }else{

                        $rs[] = ['user_name'=>$value->user_name_g,'bonus'=>$value->total_bonus,'sponser_all'=>null];
                    }


                }

                try {
                    DB::BeginTransaction();
                    $date = date('Y-m-d');
                    foreach($rs as $value){
                        if($value['sponser_all']){
                            foreach($value['sponser_all'] as $sponser_all){

                                $dataPrepare = [
                                    'user_name_bonus_active' => $value['user_name'],
                                    'bonus' => $value['bonus'],
                                    'user_name_g' => $sponser_all['user_name'],
                                    'name_g' =>  $sponser_all['name'],
                                    'postion_g' => $sponser_all['postion'],
                                    'g' =>$sponser_all['g'],
                                    'percen_g' => $sponser_all['percen'],
                                    'bonus_g' => $sponser_all['bonus'],
                                    'date' => $date,
                                ];

                                DB::table('run_warning_copyright')
                                ->Insert([$dataPrepare]);

                                // DB::table('run_warning_copyright')
                                // ->updateOrInsert(
                                // ['user_name_bonus_active' => $value['user_name'],'user_name_g'=>$sponser_all['user_name'], 'date' => $date],
                                // $dataPrepare
                                // );

                            }
                            DB::table('report_bonus_active')
                            ->where('user_name_g', $value['user_name'])
                            ->update(['status_copyright' => 'process']);

                        }else{
                            DB::table('report_bonus_active')
                            ->where('user_name_g', $value['user_name'])
                            ->update(['status_copyright' => 'success']);
                        }

                    }
                    DB::commit();
                    return 'success';

                } catch (Exception $e) {
                DB::rollback();
                return 'fail';
            }

    }

    public static function RunBonus_copyright(){

        $report_bonus_active =  DB::table('run_warning_copyright') //รายชื่อคนที่มีรายการแจงโบนัสข้อ 6
            ->selectRaw('user_name_g,sum(bonus_g) as total_bonus,date')
            ->where('status', '=', 'panding')
            ->groupby('user_name_g','date')
            ->get();
            $i=0;
            foreach( $report_bonus_active as $value){
                $dataPrepare = [
                    'customer_user' => $value->user_name_g,
                    'total_bonus' =>  $value->total_bonus,
                    'date_active' => $value->date,
                ];
                // dd($dataPrepare);

            DB::table('report_bonus_copyright')
                    ->updateOrInsert(
                    ['customer_user' => $value->user_name_g,'date_active' => $value->date],
                    $dataPrepare
                    );
                    $i++;

            }
            $data = ['success','total'=>count($report_bonus_active),'process'=>$i];
            dd($data);
            return  $data ;

    }




}
