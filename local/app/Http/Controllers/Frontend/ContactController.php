<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\PromotionHelp;
use App\PromotionHelpDoc;
use App\PromotionHelpHeadDoc;
use App\Reportissue;
use App\ReportissueDoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }
    public function index()
    {

        return view('frontend/contact');
    }

    public function store_report_issue(Request $request)
    {

        $rule = [
            'username' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'info_issue' => 'required',
            'doc_issue.*.doc_issue' => 'required|mimes:jpg,png,jpeg,gif,svg',
        ];
        $message_err = [
            'username.required' => 'กรุณากรอกข้อมูล',
            'name.required' => 'กรุณากรอกข้อมูล',
            'last_name.required' => 'กรุณากรอกข้อมูล',
            'info_issue.required' => 'กรุณากรอกข้อมูล',
            'doc_issue.*.required' => 'กรุณาแนบไฟล์',
            'doc_issue.*.mimes' => 'รองรับไฟล์นามสกุล jpeg,jpg,png เท่านั้น',
        ];

        if ($request->cReport == "อื่นๆ") {
            $rule['text_other'] = 'required';
            $message_err['text_other.required'] = 'กรุณากรอกข้อมูล';
        }

        $validator = Validator::make(
            $request->all(),
            $rule,
            $message_err
        );

        if (!$validator->fails()) {

            if ($request->cReport == "อื่นๆ") {
                $text_other = $request->text_other;
            } else {
                $text_other = '';
            }

            $dataprepare = [
                'username' => $request->username,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'head_info' => $request->cReport,
                'info_issue' => $request->info_issue,
                'text_other' => $text_other,
                'status' => 1
            ];

            $query = Reportissue::create($dataprepare);
            //บันทึกรูปภาพสินค้า


            $files = $request->doc_issue;
            //เอาข้อมูลรุปภาพมา loop ออกมา
            foreach ($files as $key => $val) {
                $url = 'local/public/images/report_issue_doc/' . date('Ym');

                $fileName = $val;


                //เปลี่ยนชื่อไฟล์ให้เป็น วันที่ปัจจุบัน + $key เผื่อกันในกรณีที่มีการอัพโหลดรูปภาพมากกว่า 1 รูป
                $imageName = date("YmdHis") . $key . '.' . $fileName->extension();

                //ย้ายไฟล์ไปเก็บในเครื่อง
                $fileName->move($url, $imageName);

                $doc_issue = [
                    'issue_id' => $query->id,
                    'url' => $url,
                    'doc_name' => $imageName,
                ];
                $query_doc = ReportissueDoc::create($doc_issue);
            }


            return response()->json(['status' => 'success', 'data' => $dataprepare], 200);
        }
        return response()->json(['error' => $validator->errors()]);
    }


    public function store_promotion_help(Request $request)
    {

        $type = '';
        if ($request->cPromotion == "คลอดบุตร") {
            $type = "birth";
        }
        if ($request->cPromotion == "นอนโรงพยาบาล") {
            $type = "hospital";
        }
        if ($request->cPromotion == "เสียชีวิต") {
            $type = "death";
        }
        if ($request->cPromotion == "เพลิงไหม้") {
            $type = "fire";
        }

        $rule = [
            'username' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'exercise_date' => 'required',
            'info_promotion' => 'required',
        ];
        $message_err = [
            'username.required' => 'กรุณากรอกข้อมูล',
            'name.required' => 'กรุณากรอกข้อมูล',
            'last_name.required' => 'กรุณากรอกข้อมูล',
            'phone.required' => 'กรุณากรอกข้อมูล',
            'exercise_date.required' => 'กรุณากรอกข้อมูล',
            'info_promotion.required' => 'กรุณากรอกข้อมูล',

        ];

        $validator = Validator::make(
            $request->all(),
            $rule,
            $message_err
        );



        if (!$validator->fails()) {

            $dataPrepare = [
                'type_help' => $type,
                'username' => $request->username,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'role' => $request->role,
                'exercise_date' => $request->exercise_date,
                'info_promotion' => $request->info_promotion,
                'status' => 1
            ];

            $query = PromotionHelp::create($dataPrepare);



            if ($request->doc_promotion) {

                $data_doc = $request->doc_promotion;


                foreach ($data_doc as $type_file => $items) {

                    $url = 'local/public/images/promotion_help/' . $type . "/" . $type_file . "/" . date('Ym');
                    foreach ($items as $key => $val) {
                        $fileName = $val;

                        $imageName = date("YmdHis") . $key . '.' . $fileName->extension();

                        $fileName->move($url, $imageName);

                        $doc_promotion_help = [
                            'promotion_help_id' => $query->id,
                            'help_head_doc' => $type_file,
                            'url' => $url,
                            'file_name' => $imageName,

                        ];

                        $query_doc  = PromotionHelpDoc::create($doc_promotion_help);
                    }
                }
            }
            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['error' => $validator->errors()]);
    }
}
