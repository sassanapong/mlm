<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Reportissue;
use App\ReportissueDoc;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class IssueController extends Controller
{


    public function index(Request $request)
    {
        return view('backend/report_issue/index');
    }
    public function get_repost_issue(Request $request)
    {

        $data = Reportissue::where(function ($query) use ($request) {
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
            ->make(true);
    }


    public function get_data_info_issue(Request $request)
    {
        $id = $request->id;


        $query = Reportissue::where('report_issue.id', $id)->get()
            ->map(function ($item) {
                $item->doc_issue = ReportissueDoc::where('issue_id', $item->id)
                    ->get();
                return $item;
            });

        return ($query);
        return response()->json($query);
    }


    public function action_data_isseu(Request $request)
    {

        $dataPrepare = [
            'status' => $request->data['action']
        ];

        $query = Reportissue::where('id', $request->data['id'])->update($dataPrepare);

        if ($query) {
            return response()->json(['status' => 'success'], 200);
        }
    }
}
