<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\PromotionHelp;
use App\PromotionHelpDoc;
use App\PromotionHelpHeadDoc;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class PromotionHelpController extends Controller
{


    public function index(Request $request)
    {
        return view('backend/promotion_help/index');
    }


    public function get_promotion_help(Request $request)
    {
        $data = PromotionHelp::where(function ($query) use ($request) {
            if ($request->has('Where')) {
                foreach (request('Where') as $key => $val) {
                    if ($val) {
                        if (strpos($val, ',')) {
                            $query->whereIn($key, explode(',', $val));
                        } else {
                            $query->where($key, $val);
                        }
                    }
                }
            }
            if ($request->has('Like')) {

                foreach (request('Like') as $key => $val) {
                    if ($val) {
                        $query->where($key, 'like', '%' . $val . '%');
                    }
                }
            }
        })
            ->orderby('status', 'ASC')
            ->orderby('id', 'DESC')
            ->get();

        return DataTables::of($data)
            ->setRowClass('intro-x py-4 h-20 zoom-in box')
            ->editColumn('type_help', function ($data) {
                $thai_type_help = "";

                if ($data->type_help == 'birth') {
                    $thai_type_help = "คลอดบุตร";
                } else if ($data->type_help == 'hospital') {
                    $thai_type_help = "นอนโรงพยาบาล";
                } else if ($data->type_help == 'death') {
                    $thai_type_help = "เสียชีวิต";
                } else {
                    $thai_type_help = "เพลิงไหม้";
                }
                return $thai_type_help;
            })
            ->make(true);
    }


    public function get_data_promotion_help(Request $request)
    {
        $promotion_id =  $request->id;

        // รายละเอียด ของ โปรโมชั่นช่วยเพื่อน
        $promotion_help = PromotionHelp::where('id', $promotion_id)->first();

        // เอกสาร เพื่อนช่วยเ้พื่อน
        $promotion_help_doc = PromotionHelpDoc::where('promotion_help_id', $promotion_id)->get();

        // หัวข้อของเอกสาร
        $promotion_help_head_doc = PromotionHelpHeadDoc::select('id', 'help_head_doc', 'title')->where('promotion_help', $promotion_help->type_help)->get();


        $doc_help = [];
        foreach ($promotion_help_head_doc as $head_doc) {
            foreach ($promotion_help_doc as $help_doc) {
                if ($head_doc->help_head_doc == $help_doc->help_head_doc) {
                    $doc_help[$head_doc->title][] = [
                        'url' => $help_doc->url,
                        'file_name' => $help_doc->file_name,
                    ];
                }
            };
        };

        return response()->json(['promotion_help' => $promotion_help, 'doc_help' => $doc_help]);
    }


    public function action_data_promo_help(Request $request)
    {
        $dataPrepare = [
            'status' => $request->data['action']
        ];

        $query = PromotionHelp::where('id', $request->data['id'])->update($dataPrepare);

        if ($query) {
            return response()->json(['status' => 'success'], 200);
        }
    }
}
