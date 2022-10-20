<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use DB;
use Auth;
use Illuminate\Http\Request;


class TreeController extends Controller
{
    public function __construct()
    {
      $this->middleware('customer');
    }

    public function index(Request $request)
    {
        if($request->user_name){
            $user_name = $request->user_name;
        }else{
            $user_name = Auth::guard('c_user')->user()->user_name;
        }
        $data = TreeController::line_all($user_name);
        // dd($data);

        return view('frontend/tree',compact('data'));
    }

    public static function line_all($username,$type_upline=''){
		$data =array();


    if (!empty($username) and !empty($type_upline)){
      $i =1;
      $j = 2;

      for ($i; $i<$j; $i++) {

        $last_id = DB::table('customers')
        ->select('upline_id','user_name')
		->where('type_upline','=',$type_upline)
        ->where('upline_id','=',$username)
        ->first();

        if($last_id){
          $i = 1;
          $j = 5;
          $username	= $last_id->user_name;
        }else{
          $i = 1;
          $j = 0;
          $last_id = DB::table('customers')
          ->select('upline_id','user_name')
		      ->where('type_upline','=',$type_upline)
          ->where('user_name','=',$username)
          ->first();

          $last_id = $last_id->upline_id;

        }

      }

    }else{

      $last_id = $username;
    }

		if (empty($last_id)) {
			return null;
		}else{


			$lv1 = DB::table('customers')
			->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
			->where('user_name','=',$last_id)
			->first();


			$lv2_a = DB::table('customers')
			->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
			->where('upline_id','=',$lv1->user_name)
			->where('type_upline','=','A')
			->first();


			if($lv2_a){

				$lv3_a_a = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_a->user_name)
				->where('type_upline','=','A')
				->first();

				$lv3_a_b = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_a->user_name)
				->where('type_upline','=','B')
				->first();

				$lv3_a_c = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_a->user_name)
				->where('type_upline','=','C')
				->first();

                $lv3_a_d = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_a->user_name)
				->where('type_upline','=','D')
				->first();

                $lv3_a_e = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_a->user_name)
				->where('type_upline','=','E')
				->first();

			}else{
				$lv2_a = null;
				$lv3_a_a = null;
				$lv3_a_b = null;
				$lv3_a_c = null;
                $lv3_a_d = null;
                $lv3_a_e = null;
			}


			$lv2_b = DB::table('customers')
			->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
			->where('upline_id','=',$lv1->user_name)
			->where('type_upline','=','B')
			->first();

			if($lv2_b){

				$lv3_b_a = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_b->user_name)
				->where('type_upline','=','A')
				->first();


				$lv3_b_b = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_b->user_name)
				->where('type_upline','=','B')
				->first();

				$lv3_b_c = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_b->user_name)
				->where('type_upline','=','C')
				->first();
                $lv3_b_d = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_b->user_name)
				->where('type_upline','=','D')
				->first();
                $lv3_b_e = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_b->user_name)
				->where('type_upline','=','E')
				->first();

			}else{
				$lv2_b = null;
				$lv3_b_a = null;
				$lv3_b_b = null;
				$lv3_b_c = null;
                $lv3_b_d = null;
                $lv3_b_e = null;
			}

			$lv2_c = DB::table('customers')
			->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
			->where('upline_id','=',$lv1->user_name)
			->where('type_upline','=','C')
			->first();

			if($lv2_c){

				$lv3_c_a = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_c->user_name)
				->where('type_upline','=','A')
				->first();


				$lv3_c_b = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_c->user_name)
				->where('type_upline','=','B')
				->first();


				$lv3_c_c = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_c->user_name)
				->where('type_upline','=','C')
				->first();
                $lv3_c_d = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_c->user_name)
				->where('type_upline','=','D')
				->first();
                $lv3_c_e = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_c->user_name)
				->where('type_upline','=','E')
				->first();

			}else{
				$lv2_c = null;
				$lv3_c_a = null;
				$lv3_c_b = null;
				$lv3_c_c = null;
                $lv3_c_d = null;
                $lv3_c_e = null;
			}

            $lv2_d = DB::table('customers')
			->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
			->where('upline_id','=',$lv1->user_name)
			->where('type_upline','=','D')
			->first();

			if($lv2_d){

				$lv3_d_a = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_d->user_name)
				->where('type_upline','=','A')
				->first();


				$lv3_d_b = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_d->user_name)
				->where('type_upline','=','B')
				->first();


				$lv3_d_c = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_d->user_name)
				->where('type_upline','=','C')
				->first();
                $lv3_d_d = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_d->user_name)
				->where('type_upline','=','D')
				->first();
                $lv3_d_e = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_d->user_name)
				->where('type_upline','=','E')
				->first();

			}else{
				$lv2_d = null;
				$lv3_d_a = null;
				$lv3_d_b = null;
				$lv3_d_c = null;
                $lv3_d_d = null;
                $lv3_d_e = null;
			}

            $lv2_e = DB::table('customers')
			->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
			->where('upline_id','=',$lv1->user_name)
			->where('type_upline','=','D')
			->first();

			if($lv2_e){

				$lv3_e_a = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_e->user_name)
				->where('type_upline','=','A')
				->first();


				$lv3_e_b = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_e->user_name)
				->where('type_upline','=','B')
				->first();


				$lv3_e_c = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_e->user_name)
				->where('type_upline','=','C')
				->first();
                $lv3_e_d = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_e->user_name)
				->where('type_upline','=','D')
				->first();
                $lv3_e_e = DB::table('customers')
				->select('id','user_name','business_name','prefix_name','name','last_name','profile_img','upline_id','type_upline')
				->where('upline_id','=',$lv2_e->user_name)
				->where('type_upline','=','E')
				->first();

			}else{
				$lv2_e = null;
				$lv3_e_a = null;
				$lv3_e_b = null;
				$lv3_e_c = null;
                $lv3_e_d = null;
                $lv3_e_e = null;
			}

			$data = ['lv1'=>$lv1,
			'lv2_a'=>$lv2_a,'lv2_b'=>$lv2_b,'lv2_c'=>$lv2_c,'lv2_d'=>$lv2_d,'lv2_e'=>$lv2_e,
			'lv3_a_a'=>$lv3_a_a,'lv3_a_b'=>$lv3_a_b,'lv3_a_c'=>$lv3_a_c,'lv3_a_d'=>$lv3_a_d,'lv3_a_e'=>$lv3_a_e,
			'lv3_b_a'=>$lv3_b_a,'lv3_b_b'=>$lv3_b_b,'lv3_b_c'=>$lv3_b_c,'lv3_b_d'=>$lv3_b_d,'lv3_b_e'=>$lv3_b_e,
			'lv3_c_a'=>$lv3_c_a,'lv3_c_b'=>$lv3_c_b,'lv3_c_c'=>$lv3_c_c,'lv3_c_d'=>$lv3_c_d,'lv3_c_e'=>$lv3_c_e,
            'lv3_d_a'=>$lv3_d_a,'lv3_d_b'=>$lv3_d_b,'lv3_d_c'=>$lv3_d_c,'lv3_d_d'=>$lv3_d_d,'lv3_d_e'=>$lv3_d_e,
            'lv3_e_a'=>$lv3_e_a,'lv3_e_b'=>$lv3_e_b,'lv3_e_c'=>$lv3_e_c,'lv3_e_d'=>$lv3_e_d,'lv3_e_e'=>$lv3_e_e,
		];

		return $data;
	}

}


}
