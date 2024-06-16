<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class ApiFunction6Controller extends Controller
{
    public function index($user_name)
    {


        $data = ApiFunction6Controller::tree_all($user_name);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $data,
            'message' => 'categories successfully',
        ], 200);
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
}
