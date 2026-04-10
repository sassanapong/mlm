<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mdk_ct;
use App\External\Tool;
use DateTime;
use DB;

class Mdk_CtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Ct = Mdk_ct::all();
        $data = array(
            'Ct' => $Ct
        );
        return view('backend/mdk_ct/index', $data);
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
        $ct = new Mdk_ct;
        $ct->title_ct = $request->title_ct;
        $path = public_path() . '/upload/ct/image/';
        if (!empty($request->file('image_ct'))) {
            if (isset($ct->image_ct)) {
                // unlink($path . $ct->image_ct);
            }
            $imgwidth = 1920;
            $filename = '_image_ct';
            $request_file = $request->file('image_ct');
            $name_pic = Tool::upload_picture($path, $imgwidth, $filename, $request_file);
            $ct->image_ct = $name_pic;
        }

        $ct->detail_ct = $request->detail_ct;
        $ct->detail_ct_all = $request->detail_ct_all;

        if (!empty($request->file('upload_video_ct'))) {
            $image = $request->file('upload_video_ct');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('/upload/ct/video/'), $imageName);
            $ct->upload_video_ct = $imageName;
            $ct->video_type_ct = 1;
        } elseif (!empty($request->link_video_ct)) {
            $ct->upload_video_ct = $request->link_video_ct;
            $ct->video_type_ct = 2;
        }

        $ct->start_date_ct = date_format(new DateTime($request->start_date_ct),"Y-m-d");
        $ct->end_date_ct = date_format(new DateTime($request->end_date_ct),"Y-m-d");

        if (!empty($request->file('uploadfile_ct'))) {
            $image = $request->file('uploadfile_ct');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('/upload/ct/video/'), $imageName);
            $ct->uploadfile_ct = $imageName;
        }

        $ct->save();
        return redirect('admin/mdk_ct');
    }

    public function Pulldata(Request $request)
    {
        $id_mdk_ct = $request->id;
        $sql_mdk_ct = DB::table('mdk_ct')
            ->select(
                'mdk_ct.*',
            )->where('id', $id_mdk_ct)
            ->first();

        return response()->json($sql_mdk_ct, 200);
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
        $ct = Mdk_ct::find($request->id);
        $ct->title_ct = $request->title_ct_update;
        $path = public_path() . '/upload/ct/image/';
        if (!empty($request->file('image_ct_update'))) {
            if (isset($ct->image_ct_update)) {
                // unlink($path . $ct->image_ct);
            }
            $imgwidth = 1920;
            $filename = '_image_ct';
            $request_file = $request->file('image_ct_update');
            $name_pic = Tool::upload_picture($path, $imgwidth, $filename, $request_file);
            $ct->image_ct = $name_pic;
        }

        $ct->detail_ct = $request->detail_ct_update;
        $ct->detail_ct_all = $request->detail_ct_all_update;

        if (!empty($request->file('upload_video_ct2'))) {
            $image = $request->file('upload_video_ct2');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('/upload/ct/video/'), $imageName);
            $ct->upload_video_ct = $imageName;
            $ct->video_type_ct = 1;
        } elseif (!empty($request->link_video_ct2)) {
            $ct->link_video_ct = $request->link_video_ct2;
            $ct->video_type_ct = 2;
        }

        $ct->start_date_ct = date_format(new DateTime($request->start_date_ct2), "Y-m-d");
        $ct->end_date_ct = date_format(new DateTime($request->end_date_ct2), "Y-m-d");

        if (!empty($request->file('uploadfile_ct2'))) {
            $image = $request->file('uploadfile_ct2');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('/upload/ct/video/'), $imageName);
            $ct->uploadfile_ct = $imageName;
        }
        $ct->update();
        return redirect('admin/mdk_ct');
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
        $ct = Mdk_ct::find($id);
        $ct->delete();
        $path = public_path() . '/upload/ct/image/';
        unlink($path . $ct->image_ct);
        return back();
    }
}
