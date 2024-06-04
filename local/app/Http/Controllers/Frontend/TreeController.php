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

		if ($user_name == $data[0]['upline_id'] || $data[0]['upline_id'] == 'AA' || $data[0]['upline_id'] == '') {
			$upstap = null;
		} else {
			$upstap = $data[0]['upline_id'];
		}


		return view('frontend/tree')->with('myArray', json_encode($data, JSON_UNESCAPED_UNICODE))
			->with('upstap', $upstap);
	}

	public function index_post(Request $request)
	{

		if ($request->user_name) {

			$user_name = $request->user_name;
			$data = TreeController::tree_all($user_name);

			if ($user_name == $data[0]['upline_id'] || $data[0]['upline_id'] == 'AA' || $data[0]['upline_id'] == '') {
				$upstap = null;
			} else {
				$upstap = $data[0]['upline_id'];
			}


			return view('frontend/tree')->with('myArray', json_encode($data, JSON_UNESCAPED_UNICODE))
				->with('upstap', $upstap);
		} else {
			$user_name = Auth::guard('c_user')->user()->user_name;
			$data = TreeController::tree_all($user_name);
			if ($user_name == $data[0]['title2'] || $data[0]['title2'] == 'AA' || $data[0]['title2'] == '') {
				$upstap = null;
			} else {
				$upstap = $data[0]['title2'];
			}


			return view('frontend/tree')->with('myArray', json_encode($data, JSON_UNESCAPED_UNICODE))
				->with('upstap', $upstap);
		}
	}

	public function tree_all($user_name)
	{
		$data_array = [];

		$introduce_lv1 = DB::table('customers')
			->select('customers.*', 'dataset_qualification.business_qualifications')
			->leftjoin('dataset_qualification', 'dataset_qualification.id', '=', 'customers.qualification_id')
			->where('user_name', '=', $user_name)
			// ->where('status_customer', '=', '1')
			->first();




		if ($introduce_lv1->profile_img) {
			$img = asset('local/public/profile_customer/' . $introduce_lv1->profile_img);
		} else {
			$img = asset('frontend/images/profile_blank.png');
		}




		// Add the first level node

		$name_1 = mb_strlen($introduce_lv1->name) > 17 ? mb_substr($introduce_lv1->name, 0, 17) . '...' : $introduce_lv1->name;
		if (Auth::guard('c_user')->user()->user_name == $introduce_lv1->user_name || Auth::guard('c_user')->user()->user_name == $introduce_lv1->upline_id) {
			$type_upline = '';
			$upline_id = null;
		} else {
			$type_upline = $introduce_lv1->type_upline;
			$upline_id =  $introduce_lv1->upline_id;
		}

		if ($type_upline == "AA") {
			$type_upline = '';
		}

		if ($introduce_lv1->introduce_id == 'AA') {
			$introduce_id = '';
		} else {
			$introduce_id = $introduce_lv1->introduce_id;
		}
		$data_array[] = [
			'id' => $introduce_lv1->user_name,
			'title2' => $introduce_id,
			'name' => $name_1,
			'upline_id' => $upline_id,
			'title' => $introduce_lv1->user_name,
			'performance' => $introduce_lv1->business_qualifications,
			'status' => '',
			'type_upline' => $type_upline,
			'img' => $img
		];

		// Fetch and add the second level nodes

		$introduce_lv2 = DB::table('customers')
			->select('customers.*', 'dataset_qualification.business_qualifications')
			->leftjoin('dataset_qualification', 'dataset_qualification.id', '=', 'customers.qualification_id')
			->where('upline_id', '=', $user_name)
			// ->where('status_customer', '=', '1')
			->orderby('type_upline')
			->get();

		if (count($introduce_lv2) == 0) {
			$img = asset('frontend/images/profile_blank.png');
			$add_img = asset('frontend/images/plus.png');
			$data_array[] = [
				'id' => 'A' . $introduce_lv1->id,
				'user_name' => '',
				'pid' => $introduce_lv1->user_name, // Assuming 'pid' should be the same as the second level 'id'
				'department' => '',
				'name' => 'เพิ่มรหัส A',
				'type_upline' => 'A',
				'role' => '',
				'status' => 'add',
				'img' => $add_img
			];

			$data_array[] = [
				'id' => 'B' . $introduce_lv1->id,
				'user_name' => '',
				'pid' => $introduce_lv1->user_name, // Assuming 'pid' should be the same as the second level 'id'
				'department' => '',
				'name' => 'เพิ่มรหัส B',
				'type_upline' => 'B',
				'role' => '',
				'status' => 'add',
				'img' => $add_img
			];

			return $data_array;
		} else {
			foreach ($introduce_lv2 as $value) {
				if ($value->profile_img) {
					$img = asset('local/public/profile_customer/' . $value->profile_img);
				} else {
					$img = asset('frontend/images/profile_blank.png');
				}


				$add_img = asset('frontend/images/plus.png');


				if (count($introduce_lv2) == 1) {
					$name_2 = mb_strlen($value->name) > 17 ? mb_substr($value->name, 0, 17) . '...' : $value->name;
					if ($value->type_upline == 'A') {
						$data_array[] = [
							'id' => $value->user_name,
							'title2' => $value->introduce_id,
							'name' => $name_2,
							'pid' => $value->upline_id, // Assuming 'pid' should be the same as the second level 'id'
							'title' => $value->user_name,
							'upline_id' => $value->upline_id,
							'performance' => $value->business_qualifications,
							'type_upline' => $value->type_upline,
							'status' => '',
							'img' => $img
						];

						$data_array[] = [
							'id' => 'B' . $introduce_lv1->id,
							'user_name' => '',
							'pid' => $introduce_lv1->user_name, // Assuming 'pid' should be the same as the second level 'id'
							'department' => '',
							'name' => 'เพิ่มรหัส B',
							'type_upline' => 'B',
							'role' => '',
							'status' => 'add',
							'img' => $add_img
						];
					} else {
						$name_2 = mb_strlen($value->name) > 17 ? mb_substr($value->name, 0, 17) . '...' : $value->name;

						$data_array[] = [
							'id' => 'A' . $introduce_lv1->id,
							'user_name' => '',
							'pid' => $introduce_lv1->user_name, // Assuming 'pid' should be the same as the second level 'id'
							'department' => '',
							'name' => 'เพิ่มรหัส A',
							'type_upline' => 'A',
							'role' => '',
							'status' => 'add',
							'img' => $add_img
						];


						$data_array[] = [
							'id' => $value->user_name,
							'title2' => $value->introduce_id,
							'name' => $name_2,
							'pid' => $value->upline_id, // Assuming 'pid' should be the same as the second level 'id'
							'title' => $value->user_name,
							'upline_id' => $value->upline_id,
							'performance' => $value->business_qualifications,
							'type_upline' => $value->type_upline,
							'status' => '',
							'img' => $img
						];
					}
				} else {
					$name_2 = mb_strlen($value->name) > 17 ? mb_substr($value->name, 0, 17) . '...' : $value->name;
					$data_array[] = [
						'id' => $value->user_name,
						'title2' => $value->introduce_id,
						'name' => $name_2,
						'pid' => $value->upline_id, // Assuming 'pid' should be the same as the second level 'id'
						'title' => $value->user_name,
						'upline_id' => $value->upline_id,
						'performance' => $value->business_qualifications,
						'type_upline' => $value->type_upline,
						'status' => '',
						'img' => $img
					];
				}


				// Fetch and add the third level nodes
				$introduce_lv3 = DB::table('customers')
					->select('customers.*', 'dataset_qualification.business_qualifications')
					->leftjoin('dataset_qualification', 'dataset_qualification.id', '=', 'customers.qualification_id')
					->where('upline_id', '=', $value->user_name)
					->orderby('type_upline')
					// ->where('status_customer', '=', '1')
					->get();

				if (count($introduce_lv3) > 0) {
					foreach ($introduce_lv3 as $value3) {


						if ($value3->profile_img) {
							$img = asset('local/public/profile_customer/' . $value3->profile_img);
						} else {
							$img = asset('frontend/images/profile_blank.png');
						}

						if (count($introduce_lv3) == 1) {
							$add_img = asset('frontend/images/plus.png');
							if ($value3->type_upline == 'A') {


								$name_3 = mb_strlen($value3->name) > 17 ? mb_substr($value3->name, 0, 17) . '...' : $value3->name;
								$data_array[] = [
									'id' => $value3->user_name,
									'title2' => $value3->introduce_id,
									'name' => $name_3,
									'pid' => $value3->upline_id, // Assuming 'pid' should be the same as the second level 'id'
									'title' => $value3->user_name,
									'performance' => $value3->business_qualifications,
									'type_upline' => $value3->type_upline,
									'upline_id' => $value3->upline_id,
									'status' => '',
									'img' => $img
								];

								$data_array[] = [
									'id' => 'B' . $value->id,
									'user_name' => '',
									'pid' => $value->user_name, // Assuming 'pid' should be the same as the second level 'id'
									'department' => '',
									'name' => 'เพิ่มรหัส B',
									'type_upline' => 'B',
									'role' => '',
									'status' => 'add',
									'img' => $add_img
								];
							} else {


								$data_array[] = [
									'id' => 'A' . $value->id,
									'user_name' => '',
									'pid' => $value->user_name, // Assuming 'pid' should be the same as the second level 'id'
									'department' => '',
									'name' => 'เพิ่มรหัส A',
									'type_upline' => 'A',
									'role' => '',
									'status' => 'add',
									'img' => $add_img
								];
								$name_3 = mb_strlen($value3->name) > 17 ? mb_substr($value3->name, 0, 17) . '...' : $value3->name;
								$data_array[] = [
									'id' => $value3->user_name,
									'title2' => $value3->introduce_id,
									'name' => $name_3,
									'pid' => $value3->upline_id, // Assuming 'pid' should be the same as the second level 'id'
									'title' => $value3->user_name,
									'performance' => $value3->business_qualifications,
									'type_upline' => $value3->type_upline,
									'upline_id' => $value3->upline_id,
									'status' => '',
									'img' => $img
								];
							}
						} else {
							$name_3 = mb_strlen($value3->name) > 17 ? mb_substr($value3->name, 0, 17) . '...' : $value3->name;
							$data_array[] = [
								'id' => $value3->user_name,
								'title2' => $value3->introduce_id,
								'name' => $name_3,
								'pid' => $value3->upline_id, // Assuming 'pid' should be the same as the second level 'id'
								'title' => $value3->user_name,
								'performance' => $value3->business_qualifications,
								'type_upline' => $value3->type_upline,
								'upline_id' => $value3->upline_id,
								'status' => '',
								'img' => $img
							];
						}




						$introduce_lv4 = DB::table('customers')
							->select('customers.*', 'dataset_qualification.business_qualifications')
							->leftjoin('dataset_qualification', 'dataset_qualification.id', '=', 'customers.qualification_id')
							->where('upline_id', '=', $value3->user_name)
							->orderby('type_upline')
							// ->where('status_customer', '=', '1')
							->get();
						if (count($introduce_lv4) > 0) {
							if (count($introduce_lv4) == 1) {

								foreach ($introduce_lv4 as $value4) {
									if ($value4->profile_img) {
										$img = asset('local/public/profile_customer/' . $value4->profile_img);
									} else {
										$img = asset('frontend/images/profile_blank.png');
									}
									$add_img = asset('frontend/images/plus.png');

									if ($value4->type_upline == 'A') {
										$name_4 = mb_strlen($value4->name) > 17 ? mb_substr($value4->name, 0, 17) . '...' : $value4->name;
										$data_array[] = [
											'id' => $value4->user_name,
											'title2' => $value4->introduce_id,
											'name' => $name_4,
											'pid' => $value4->upline_id, // Assuming 'pid' should be the same as the second level 'id'
											'title' => $value4->user_name,
											'performance' => $value4->business_qualifications,
											'type_upline' => $value4->type_upline,
											'upline_id' => $value4->upline_id,
											'status' => '',
											'img' => $img
										];

										$data_array[] = [
											'id' => 'B' . $value3->id,
											'user_name' => '',
											'pid' => $value3->user_name, // Assuming 'pid' should be the same as the second level 'id'
											'department' => '',
											'name' => 'เพิ่มรหัส B',
											'type_upline' => 'B',
											'role' => '',
											'status' => 'add',
											'img' => $add_img
										];
									} else {
										$data_array[] = [
											'id' => 'A' . $value3->id,
											'user_name' => '',
											'pid' => $value3->user_name, // Assuming 'pid' should be the same as the second level 'id'
											'department' => '',
											'name' => 'เพิ่มรหัส A',
											'type_upline' => 'A',
											'role' => '',
											'status' => 'add',
											'img' => $add_img
										];

										$name_4 = mb_strlen($value4->name) > 17 ? mb_substr($value4->name, 0, 17) . '...' : $value4->name;
										$data_array[] = [
											'id' => $value4->user_name,
											'title2' => $value4->introduce_id,
											'name' => $name_4,
											'pid' => $value4->upline_id, // Assuming 'pid' should be the same as the second level 'id'
											'title' => $value4->user_name,
											'performance' => $value4->business_qualifications,
											'type_upline' => $value4->type_upline,
											'upline_id' => $value4->upline_id,
											'status' => '',
											'img' => $img
										];
									}
								}
							} else {
								foreach ($introduce_lv4 as $value4) {


									if ($value4->profile_img) {
										$img = asset('local/public/profile_customer/' . $value4->profile_img);
									} else {
										$img = asset('frontend/images/profile_blank.png');
									}

									$name_4 = mb_strlen($value4->name) > 17 ? mb_substr($value4->name, 0, 17) . '...' : $value4->name;
									$data_array[] = [
										'id' => $value4->user_name,
										'title2' => $value4->introduce_id,
										'name' => $name_4,
										'pid' => $value4->upline_id, // Assuming 'pid' should be the same as the second level 'id'
										'title' => $value4->user_name,
										'performance' => $value4->business_qualifications,
										'type_upline' => $value4->type_upline,
										'upline_id' => $value4->upline_id,
										'status' => '',
										'img' => $img
									];
								}
							}
						} else {
							$add_img = asset('frontend/images/plus.png');


							$data_array[] = [
								'id' => 'A' . $value3->id,
								'user_name' => '',
								'pid' => $value3->user_name, // Assuming 'pid' should be the same as the second level 'id'
								'department' => '',
								'name' => 'เพิ่มรหัส A',
								'type_upline' => 'A',
								'role' => '',
								'status' => 'add',
								'img' => $add_img
							];

							$data_array[] = [
								'id' => 'B' . $value3->id,
								'user_name' => '',
								'pid' => $value3->user_name, // Assuming 'pid' should be the same as the second level 'id'
								'department' => '',
								'name' => 'เพิ่มรหัส B',
								'type_upline' => 'B',
								'role' => '',
								'status' => 'add',
								'img' => $add_img
							];
						}
					}
				} else {
					$add_img = asset('frontend/images/plus.png');

					$data_array[] = [
						'id' => 'A' . $value->id,
						'user_name' => '',
						'pid' => $value->user_name, // Assuming 'pid' should be the same as the second level 'id'
						'department' => '',
						'name' => 'เพิ่มรหัส A',
						'type_upline' => 'A',
						'role' => '',
						'status' => 'add',
						'img' => $add_img
					];

					$data_array[] = [
						'id' => 'B' . $value->id,
						'user_name' => '',
						'pid' => $value->user_name, // Assuming 'pid' should be the same as the second level 'id'
						'department' => '',
						'name' => 'เพิ่มรหัส B',
						'type_upline' => 'B',
						'role' => '',
						'status' => 'add',
						'img' => $add_img
					];
				}
			}
		}

		// Return JSON response
		// return $data_array;
		return $data_array;
	}


	public function modal_tree(Request $request)
	{
		$user_name = $request->user_name;

		$data = DB::table('customers')
			->where('customers.user_name', '=', $user_name)
			->first();

		return view('frontend/modal/modal-tree', ['data' => $data]);
	}
	public static function line_all($user_name, $type_upline = '')
	{
		$data = array();


		if (!empty($user_name) and !empty($type_upline)) {
			$i = 1;
			$j = 2;

			for ($i; $i < $j; $i++) {

				$last_id = DB::table('customers')
					->select('upline_id', 'user_name')
					->where('type_upline', '=', $type_upline)
					->where('upline_id', '=', $user_name)
					->first();

				if ($last_id) {
					$i = 1;
					$j = 5;
					$user_name	= $last_id->user_name;
				} else {
					$i = 1;
					$j = 0;
					$last_id = DB::table('customers')
						->select('upline_id', 'user_name')
						->where('type_upline', '=', $type_upline)
						->where('user_name', '=', $user_name)
						->first();

					$last_id = $last_id->upline_id;
				}
			}
		} else {

			$last_id = $user_name;
		}

		if (empty($last_id)) {
			return null;
		} else {


			$lv1 = DB::table('customers')
				->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
				->where('user_name', '=', $last_id)
				->first();


			$lv2_a = DB::table('customers')
				->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
				->where('upline_id', '=', $lv1->user_name)
				->where('type_upline', '=', 'A')
				->first();


			if ($lv2_a) {

				$lv3_a_a = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_a->user_name)
					->where('type_upline', '=', 'A')
					->first();

				$lv4_a_b = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_a->user_name)
					->where('type_upline', '=', 'B')
					->first();



				$lv5_a_c = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_a->user_name)
					->where('type_upline', '=', 'C')
					->first();

				$lv6_a_d = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_a->user_name)
					->where('type_upline', '=', 'D')
					->first();

				$lv7_a_e = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_a->user_name)
					->where('type_upline', '=', 'E')
					->first();
			} else {
				$lv2_a = null;
				$lv3_a_a = null;
				$lv4_a_b = null;
				$lv5_a_c = null;
				$lv6_a_d = null;
				$lv7_a_e = null;
			}


			$lv2_b = DB::table('customers')
				->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
				->where('upline_id', '=', $lv1->user_name)
				->where('type_upline', '=', 'B')
				->first();

			if ($lv2_b) {

				$lv3_b_a = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_b->user_name)
					->where('type_upline', '=', 'A')
					->first();


				$lv4_b_b = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_b->user_name)
					->where('type_upline', '=', 'B')
					->first();

				$lv5_b_c = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_b->user_name)
					->where('type_upline', '=', 'C')
					->first();
				$lv6_b_d = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_b->user_name)
					->where('type_upline', '=', 'D')
					->first();
				$lv7_b_e = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_b->user_name)
					->where('type_upline', '=', 'E')
					->first();
			} else {
				$lv2_b = null;
				$lv3_b_a = null;
				$lv4_b_b = null;
				$lv5_b_c = null;
				$lv6_b_d = null;
				$lv7_b_e = null;
			}

			$lv2_c = DB::table('customers')
				->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
				->where('upline_id', '=', $lv1->user_name)
				->where('type_upline', '=', 'C')
				->first();

			if ($lv2_c) {

				$lv3_c_a = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_c->user_name)
					->where('type_upline', '=', 'A')
					->first();


				$lv4_c_b = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_c->user_name)
					->where('type_upline', '=', 'B')
					->first();


				$lv5_c_c = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_c->user_name)
					->where('type_upline', '=', 'C')
					->first();
				$lv6_c_d = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_c->user_name)
					->where('type_upline', '=', 'D')
					->first();
				$lv7_c_e = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_c->user_name)
					->where('type_upline', '=', 'E')
					->first();
			} else {
				$lv2_c = null;
				$lv3_c_a = null;
				$lv4_c_b = null;
				$lv5_c_c = null;
				$lv6_c_d = null;
				$lv7_c_e = null;
			}

			$lv2_d = DB::table('customers')
				->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
				->where('upline_id', '=', $lv1->user_name)
				->where('type_upline', '=', 'D')
				->first();

			if ($lv2_d) {

				$lv3_d_a = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_d->user_name)
					->where('type_upline', '=', 'A')
					->first();


				$lv4_d_b = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_d->user_name)
					->where('type_upline', '=', 'B')
					->first();


				$lv5_d_c = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_d->user_name)
					->where('type_upline', '=', 'C')
					->first();
				$lv6_d_d = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_d->user_name)
					->where('type_upline', '=', 'D')
					->first();
				$lv7_d_e = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_d->user_name)
					->where('type_upline', '=', 'E')
					->first();
			} else {
				$lv2_d = null;
				$lv3_d_a = null;
				$lv4_d_b = null;
				$lv5_d_c = null;
				$lv6_d_d = null;
				$lv7_d_e = null;
			}

			$lv2_e = DB::table('customers')
				->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
				->where('upline_id', '=', $lv1->user_name)
				->where('type_upline', '=', 'E')
				->first();

			if ($lv2_e) {

				$lv3_e_a = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_e->user_name)
					->where('type_upline', '=', 'A')
					->first();


				$lv4_e_b = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_e->user_name)
					->where('type_upline', '=', 'B')
					->first();


				$lv5_e_c = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_e->user_name)
					->where('type_upline', '=', 'C')
					->first();
				$lv6_e_d = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_e->user_name)
					->where('type_upline', '=', 'D')
					->first();
				$lv7_e_e = DB::table('customers')
					->select('id', 'user_name', 'business_name', 'prefix_name', 'name', 'last_name', 'profile_img', 'upline_id', 'type_upline')
					->where('upline_id', '=', $lv2_e->user_name)
					->where('type_upline', '=', 'E')
					->first();
			} else {
				$lv2_e = null;
				$lv3_e_a = null;
				$lv4_e_b = null;
				$lv5_e_c = null;
				$lv6_e_d = null;
				$lv7_e_e = null;
			}

			$data = [
				'lv1' => $lv1,
				'lv2_a' => $lv2_a, 'lv2_b' => $lv2_b, 'lv2_c' => $lv2_c, 'lv2_d' => $lv2_d, 'lv2_e' => $lv2_e,
				'lv3_a_a' => $lv3_a_a, 'lv4_a_b' => $lv4_a_b, 'lv5_a_c' => $lv5_a_c, 'lv6_a_d' => $lv6_a_d, 'lv7_a_e' => $lv7_a_e,
				'lv3_b_a' => $lv3_b_a, 'lv4_b_b' => $lv4_b_b, 'lv5_b_c' => $lv5_b_c, 'lv6_b_d' => $lv6_b_d, 'lv7_b_e' => $lv7_b_e,
				'lv3_c_a' => $lv3_c_a, 'lv4_c_b' => $lv4_c_b, 'lv5_c_c' => $lv5_c_c, 'lv6_c_d' => $lv6_c_d, 'lv7_c_e' => $lv7_c_e,
				'lv3_d_a' => $lv3_d_a, 'lv4_d_b' => $lv4_d_b, 'lv5_d_c' => $lv5_d_c, 'lv6_d_d' => $lv6_d_d, 'lv7_d_e' => $lv7_d_e,
				'lv3_e_a' => $lv3_e_a, 'lv4_e_b' => $lv4_e_b, 'lv5_e_c' => $lv5_e_c, 'lv6_e_d' => $lv6_e_d, 'lv7_e_e' => $lv7_e_e,
			];

			return $data;
		}
	}
}
