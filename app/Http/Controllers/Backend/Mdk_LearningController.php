<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mdk_lrn;
use App\External\Tool;
use DateTime;
use DB;

class Mdk_LearningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Lrn = Mdk_lrn::all();
        $data = array(
            'Lrn' => $Lrn
        );
        return view('backend/mdk_learning/index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $lrn = new Mdk_lrn;
        $lrn->title_lrn = $request->title_lrn;
        $path = public_path() . '/upload/lrn/image/';
        if (!empty($request->file('image_lrn'))) {
            if (isset($lrn->image_lrn)) {
                // unlink($path . $lrn->image_lrn);
            }
            $imgwidth = 1920;
            $filename = '_image_lrn';
            $request_file = $request->file('image_lrn');
            $name_pic = Tool::upload_picture($path, $imgwidth, $filename, $request_file);
            $lrn->image_lrn = $name_pic;
        }

        $lrn->detail_lrn = $request->detail_lrn;

        if (!empty($request->file('upload_video_lrn'))) {
            $image = $request->file('upload_video_lrn');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('/upload/lrn/video/'), $imageName);
            $lrn->upload_video_lrn = $imageName;
            $lrn->video_type_lrn = 1;
        } elseif (!empty($request->link_video_lrn)) {
            $lrn->upload_video_lrn = $request->link_video_lrn;
            $lrn->video_type_lrn = 2;
        }

        $lrn->start_date_lrn = date_format(new DateTime($request->start_date_lrn),"Y-m-d");
        $lrn->end_date_lrn = date_format(new DateTime($request->end_date_lrn),"Y-m-d");

        if (!empty($request->file('uploadfile_lrn'))) {
            $image = $request->file('uploadfile_lrn');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('/upload/lrn/video/'), $imageName);
            $lrn->uploadfile_lrn = $imageName;
        }

        $lrn->save();
        return redirect('admin/mdk_learning');
    }

    public function Pulldata(Request $request)
    {
        $id_mdk_lrn = $request->id;
        $sql_mdk_lrn = DB::table('mdk_learning')
            ->select(
                'mdk_learning.*',
            )->where('id', $id_mdk_lrn)
            ->first();

        return response()->json($sql_mdk_lrn, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        // dd($request->id);
        $lrn = Mdk_lrn::find($request->id);
        $lrn->title_lrn = $request->title_lrn_update;
        $path = public_path() . '/upload/lrn/image/';
        if (!empty($request->file('image_lrn_update'))) {
            if (isset($lrn->image_lrn_update)) {
                // unlink($path . $lrn->image_lrn);
            }
            $imgwidth = 1920;
            $filename = '_image_lrn';
            $request_file = $request->file('image_lrn_update');
            $name_pic = Tool::upload_picture($path, $imgwidth, $filename, $request_file);
            $lrn->image_lrn = $name_pic;
        }

        $lrn->detail_lrn = $request->detail_lrn_update;

        if (!empty($request->file('upload_video_lrn2'))) {
            $image = $request->file('upload_video_lrn2');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('/upload/lrn/video/'), $imageName);
            $lrn->upload_video_lrn = $imageName;
            $lrn->video_type_lrn = 1;
        } elseif (!empty($request->link_video_lrn2)) {
            $lrn->link_video_lrn = $request->link_video_lrn2;
            $lrn->video_type_lrn = 2;
        }

        $lrn->start_date_lrn = date_format(new DateTime($request->start_date_lrn2), "Y-m-d");
        $lrn->end_date_lrn = date_format(new DateTime($request->end_date_lrn2), "Y-m-d");

        if (!empty($request->file('uploadfile_lrn2'))) {
            $image = $request->file('uploadfile_lrn2');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('/upload/lrn/video/'), $imageName);
            $lrn->uploadfile_lrn = $imageName;
        }
        $lrn->update();
        return redirect('admin/mdk_learning');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lrn = Mdk_lrn::find($id);
        $lrn->delete();
        $path = public_path() . '/upload/lrn/image/';
        unlink($path . $lrn->image_lrn);
        return back();
    }
}
