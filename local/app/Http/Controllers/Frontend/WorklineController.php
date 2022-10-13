<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class WorklineController extends Controller
{
    public function index()
    {
        $id = auth('c_user')->id();

        $introduce = self::tree($id)->flatten();

        dd($introduce);
        return view('frontend/workline');
    }

    public function tree($id)
    {
        $c = self::user_introduce($id,null);
        $this->formatTree($c);
        return $c;
    }

    public static function user_introduce($id,$user_name)
    {

      $introduce = DB::table('customers')
        ->select(
          'customers.id',
          'customers.upline_id',
          'customers.type_upline',
          'customers.user_name',
          'customers.introduce_id',
          'customers.name',
          'customers.last_name',
          'customers.qualification_id',
          'customers.remain_date_num',
        )
        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
        ->whereNotNull('customers.name');
      // ->whereBetween('customers.regis_date_doc', [$from, $to])
      // ->orderbyraw('(customers.id = ' . $id . ') DESC');

      if (isset($id)) {
        $introduce->where('customers.id', $id);
      }

      if (isset($user_name)) {
        $introduce->where('customers.introduce_id', '=', $user_name);
        // ->where('customers.pv', '>', 0);
      }

      // if($type == 'pv') {
      //   $introduce->where('customers.pv', '>', 0);
      // }

      return $introduce->get();
    }


    public function formatTree($introduces,$num = 0,$i=0)
    {
      $num += 1;
      if($num>4){
        exit;
      }
      foreach ($introduces as $introduce) {
        $introduce->lv = $num;
        $introduce->children = self::user_introduce(null, $introduce->user_name);

        // if ($introduce->children->isNotEmpty()) {
        // //   $this->arr['pv_all'][] = $introduce->pv;
        //   self::formatTree($introduce->children, $num,$i);
        // } else {
        // //   $this->arr['pv_all'][] = $introduce->pv;
        // }
      }
    }

}
