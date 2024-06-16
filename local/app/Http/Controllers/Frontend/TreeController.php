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

	public function index($user_name = "")
	{
		if (empty($user_name)) {
			$user_name = Auth::guard('c_user')->user()->user_name;
		}

		$data = TreeController::tree_all($user_name);

		return view('frontend/tree')->with('myArray', json_encode($data, JSON_UNESCAPED_UNICODE))
			->with('data', $data);
	}

	public function index_post(Request $request)
	{

		if ($request->user_name) {

			$user_name = $request->user_name;
			$data = TreeController::tree_all($user_name);


			return view('frontend/tree')->with('myArray', json_encode($data, JSON_UNESCAPED_UNICODE))

				->with('data', $data);
		} else {
			$user_name = Auth::guard('c_user')->user()->user_name;
			$data = TreeController::tree_all($user_name);

			return view('frontend/tree')->with('myArray', json_encode($data, JSON_UNESCAPED_UNICODE))
				->with('upstap', $data);
		}
	}

	public static function tree_all($username = '')
	{
		$data = array();

		if (empty($username)) {
			return null;
		} else {

			$lv1 = DB::table('customers')
				->select('customers.*', 'dataset_qualification.business_qualifications')
				->leftjoin('dataset_qualification', 'dataset_qualification.business_qualifications', '=', 'customers.qualification_id')
				->where('customers.user_name', '=', $username)
				->first();

			$lv2_a = DB::table('customers')
				->select('customers.*', 'dataset_qualification.business_qualifications')
				->leftjoin('dataset_qualification', 'dataset_qualification.business_qualifications', '=', 'customers.qualification_id')
				->where('customers.upline_id', '=', $lv1->user_name)
				->where('customers.type_upline', '=', 'A')
				->first();


			if ($lv2_a) {

				$lv3_a_a = DB::table('customers')
					->select('customers.*', 'dataset_qualification.business_qualifications')
					->leftjoin('dataset_qualification', 'dataset_qualification.business_qualifications', '=', 'customers.qualification_id')
					->where('customers.upline_id', '=', $lv2_a->user_name)
					->where('customers.type_upline', '=', 'A')
					->first();

				$lv3_a_b = DB::table('customers')
					->select('customers.*', 'dataset_qualification.business_qualifications')
					->leftjoin('dataset_qualification', 'dataset_qualification.business_qualifications', '=', 'customers.qualification_id')
					->where('customers.upline_id', '=', $lv2_a->user_name)
					->where('customers.type_upline', '=', 'B')
					->first();
			} else {
				$lv2_a = null;
				$lv3_a_a = null;
				$lv3_a_b = null;
			}


			if ($lv3_a_a) {

				$lv4_a_a_a = DB::table('customers')
					->select('customers.*', 'dataset_qualification.business_qualifications')
					->leftjoin('dataset_qualification', 'dataset_qualification.business_qualifications', '=', 'customers.qualification_id')
					->where('customers.upline_id', '=', $lv3_a_a->user_name)
					->where('customers.type_upline', '=', 'A')
					->first();

				$lv4_a_a_b = DB::table('customers')
					->select('customers.*', 'dataset_qualification.business_qualifications')
					->leftjoin('dataset_qualification', 'dataset_qualification.business_qualifications', '=', 'customers.qualification_id')

					->where('customers.upline_id', '=', $lv3_a_a->user_name)
					->where('customers.type_upline', '=', 'B')
					->first();
			} else {
				$lv3_a_a = null;
				$lv4_a_a_a = null;
				$lv4_a_a_b = null;
			}

			if ($lv3_a_b) {

				$lv4_a_b_a = DB::table('customers')
					->select('customers.*', 'dataset_qualification.business_qualifications')
					->leftjoin('dataset_qualification', 'dataset_qualification.business_qualifications', '=', 'customers.qualification_id')

					->where('customers.upline_id', '=', $lv3_a_b->user_name)
					->where('customers.type_upline', '=', 'A')
					->first();

				$lv4_a_b_b = DB::table('customers')
					->select('customers.*', 'dataset_qualification.business_qualifications')
					->leftjoin('dataset_qualification', 'dataset_qualification.business_qualifications', '=', 'customers.qualification_id')

					->where('customers.upline_id', '=', $lv3_a_b->user_name)
					->where('customers.type_upline', '=', 'B')
					->first();
			} else {
				$lv3_a_b = null;
				$lv4_a_b_a = null;
				$lv4_a_b_b = null;
			}


			$lv2_b = DB::table('customers')
				->select('customers.*', 'dataset_qualification.business_qualifications')
				->leftjoin('dataset_qualification', 'dataset_qualification.business_qualifications', '=', 'customers.qualification_id')

				->where('customers.upline_id', '=', $lv1->user_name)
				->where('customers.type_upline', '=', 'B')
				->first();

			if ($lv2_b) {

				$lv3_b_a = DB::table('customers')
					->select('customers.*', 'dataset_qualification.business_qualifications')
					->leftjoin('dataset_qualification', 'dataset_qualification.business_qualifications', '=', 'customers.qualification_id')

					->where('customers.upline_id', '=', $lv2_b->user_name)
					->where('customers.type_upline', '=', 'A')
					->first();


				$lv3_b_b = DB::table('customers')
					->select('customers.*', 'dataset_qualification.business_qualifications')
					->leftjoin('dataset_qualification', 'dataset_qualification.business_qualifications', '=', 'customers.qualification_id')

					->where('customers.upline_id', '=', $lv2_b->user_name)
					->where('customers.type_upline', '=', 'B')
					->first();
			} else {
				$lv2_b = null;
				$lv3_b_a = null;
				$lv3_b_b = null;
			}
			//////////////////

			if ($lv3_b_a) {

				$lv4_b_a_a = DB::table('customers')
					->select('customers.*', 'dataset_qualification.business_qualifications')
					->leftjoin('dataset_qualification', 'dataset_qualification.business_qualifications', '=', 'customers.qualification_id')

					->where('customers.upline_id', '=', $lv3_b_a->user_name)
					->where('customers.type_upline', '=', 'A')
					->first();

				$lv4_b_a_b = DB::table('customers')
					->select('customers.*', 'dataset_qualification.business_qualifications')
					->leftjoin('dataset_qualification', 'dataset_qualification.business_qualifications', '=', 'customers.qualification_id')

					->where('customers.upline_id', '=', $lv3_b_a->user_name)
					->where('customers.type_upline', '=', 'B')
					->first();
			} else {
				$lv3_b_a = null;
				$lv4_b_a_a = null;
				$lv4_b_a_b = null;
			}

			if ($lv3_b_b) {

				$lv4_b_b_a = DB::table('customers')
					->select('customers.*', 'dataset_qualification.business_qualifications')
					->leftjoin('dataset_qualification', 'dataset_qualification.business_qualifications', '=', 'customers.qualification_id')

					->where('upline_id', '=', $lv3_b_b->user_name)
					->where('type_upline', '=', 'A')
					->first();

				$lv4_b_b_b = DB::table('customers')
					->select('customers.*', 'dataset_qualification.business_qualifications')
					->leftjoin('dataset_qualification', 'dataset_qualification.business_qualifications', '=', 'customers.qualification_id')
					->where('customers.upline_id', '=', $lv3_b_b->user_name)
					->where('customers.type_upline', '=', 'B')
					->first();
			} else {
				$lv3_b_b = null;
				$lv4_b_b_a = null;
				$lv4_b_b_b = null;
			}

			$data = [
				'lv1' => $lv1,
				'lv2_a' => $lv2_a, 'lv2_b' => $lv2_b,
				'lv3_a_a' => $lv3_a_a, 'lv3_a_b' => $lv3_a_b,
				'lv3_b_a' => $lv3_b_a, 'lv3_b_b' => $lv3_b_b,

				'lv4_a_a_a' => $lv4_a_a_a, 'lv4_a_a_b' => $lv4_a_a_b, 'lv4_a_b_a' => $lv4_a_b_a, 'lv4_a_b_b' => $lv4_a_b_b,
				'lv4_b_a_a' => $lv4_b_a_a, 'lv4_b_a_b' => $lv4_b_a_b, 'lv4_b_b_a' => $lv4_b_b_a, 'lv4_b_b_b' => $lv4_b_b_b
			];

			return $data;
		}
	}


	public function modal_tree(Request $request)
	{
		$user_name = $request->user_name;

		$data = DB::table('customers')
			->where('customers.user_name', '=', $user_name)
			->first();

		return view('frontend/modal/modal-tree', ['data' => $data]);
	}


	public function under_a(Request $request)
	{

		$username = $request->username;

		$las_a_id = TreeController::m_under_a($username);

		$data =  TreeController::tree_all($las_a_id);

		return view('frontend/tree', compact('data'));
	}

	public function under_b(Request $request)
	{
		$username = $request->username;

		$las_a_id = TreeController::m_under_b($username);

		$data =  TreeController::tree_all($las_a_id);

		return view('frontend/tree', compact('data'));
	}


	public static function m_under_a($username = '')
	{


		if (empty($username)) {

			return null;
		} else {
			$j = 2;

			for ($i = 1; $i < $j; $i++) {

				$last_id_a = DB::table('customers')
					->select('user_name', 'upline_id', 'qualification_id')
					->where('upline_id', '=', $username)
					->where('type_upline', '=', 'A')
					->first();

				if ($last_id_a) {
					$j = $j + $i;
					$username	= $last_id_a->user_name;
				} else {
					$j = 0;
					$last_id_a = DB::table('customers')
						->select('user_name', 'upline_id', 'qualification_id')
						->where('user_name', '=', $username)
						->where('type_upline', '=', 'A')
						->first();
					return $last_id_a->upline_id;
				}
			}
		}
	}

	public static function m_under_b($username = '')
	{

		if (empty($username)) {

			return null;
		} else {
			$j = 2;

			for ($i = 1; $i < $j; $i++) {

				$last_id_b = DB::table('customers')
					->select('user_name', 'upline_id', 'qualification_id')
					->where('upline_id', '=', $username)
					->where('type_upline', '=', 'B')
					->first();

				if ($last_id_b) {
					$j = $j + $i;
					$username	= $last_id_b->user_name;
				} else {
					$j = 0;

					$last_id_b = DB::table('customers')
						->select('user_name', 'upline_id', 'qualification_id')
						->where('user_name', '=', $username)
						->where('type_upline', '=', 'B')
						->first();
					return $last_id_b->upline_id;
				}
			}
		}
	}
	public static function check_under_a($username = '')
	{


		if (empty($username)) {

			return null;
		} else {
			$j = 2;

			for ($i = 1; $i < $j; $i++) {


				$last_id_a = DB::table('customers')
					->select('user_name', 'name', 'last_name', 'profile_img', 'upline_id', 'qualification_id')
					->where('upline_id', '=', $username)
					->where('type_upline', '=', 'A')
					->first();

				if ($last_id_a) {
					$j = $j + $i;
					$username	= $last_id_a->user_name;
				} else {
					$j = 0;



					$last_id_a = DB::table('customers')
						->select('user_name', 'name', 'last_name', 'profile_img', 'upline_id', 'qualification_id')
						->where('user_name', '=', $username)
						->first();


					return $last_id_a;
				}
			}
		}
	}

	public static function check_under_b($username = '')
	{

		if (empty($username)) {

			return null;
		} else {
			$j = 2;

			for ($i = 1; $i < $j; $i++) {

				$last_id_b = DB::table('customers')
					->select('user_name', 'name', 'last_name', 'profile_img', 'upline_id', 'qualification_id')
					->where('upline_id', '=', $username)
					->where('type_upline', '=', 'B')
					->first();

				if ($last_id_b) {
					$j = $j + $i;
					$username	= $last_id_b->user_name;
				} else {
					$j = 0;

					$last_id_b = DB::table('customers')
						->select('user_name', 'name', 'last_name', 'profile_img', 'upline_id', 'qualification_id')
						->where('user_name', '=', $username)
						->first();
					return $last_id_b;
				}
			}
		}
	}


	public function home_check_customer_id(Request $request)
	{

		$resule = TreeController::check_line($request->username);

		if ($resule['status'] == 'success') {
			$data = array('status' => 'success', 'username' => $resule['data']->user_name);
		} else {
			$data = array('status' => 'fail', 'data' => $resule);
		}
		//$data = ['status'=>'fail'];
		return $data;
	}

	public static function check_line($username)
	{

		$data_user = DB::table('customers')
			->select('user_name', 'name', 'last_name', 'profile_img', 'upline_id', 'qualification_id')
			->where('user_name', '=', $username)
			->first();

		if (!empty($data_user)) {

			$username = Auth::guard('c_user')->user()->user_name;
			$username_c = Auth::guard('c_user')->user()->user_name; //ของผู้เซิท


			if ($data_user->user_name == $username) {

				$resule = ['status' => 'success', 'message' => 'My Account', 'data' => $data_user];
				return $resule;
			}

			$username = $data_user->upline_id;
			$j = 2;
			for ($i = 1; $i <= $j; $i++) {
				if ($i == 1) {
					$data = DB::table('customers')
						->select('user_name', 'upline_id')
						->where('user_name', '=', $username)
						//->where('upline_id','=',$use_id)
						->first();
				}

				if ($data) {

					if ($data->user_name == $username_c || $data->upline_id == $username_c) {
						$resule = ['status' => 'success', 'message' => 'Under line', 'data' => $data_user];
						$j = 0;
					} elseif ($data->upline_id == 'AA') {

						$resule = ['status' => 'fail', 'message' => 'ไม่พบรหัสสมาชิกดังกล่าวหรือไม่ได้อยู่ในสายงานเดียวกัน'];
						$j = 0;
					} else {

						$data = DB::table('customers')
							->select('user_name', 'upline_id')
							->where('user_name', '=', $data->upline_id)
							->first();

						$j = $j + 1;
					}
				} else {
					$resule = ['status' => 'fail', 'message' => 'ไม่พบรหัสสมาชิกดังกล่าวหรือไม่ได้อยู่ในสายงานเดียวกัน'];
					$j = 0;
				}
			}
			return $resule;
		} else {
			$resule = ['status' => 'fail', 'message' => 'ไม่พบข้อมูลผู้ใช้งานรหัสนี้'];
			return $resule;
		}
	}


	public function search(Request $request)
	{


		$data =  TreeController::tree_all($request->home_search_id);

		return view('frontend/tree', compact('data'));
	}
}
