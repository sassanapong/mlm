<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\News;
use App\External\Tool;
use DateTime;
use DB;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $News = News::all();
        $data = array(
            'News' => $News
        );
        // dd(77);
        return view('backend/news/news_manage', $data);
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
        $news = new News;
        $news->title_news = $request->title_news;
        $path = public_path() . '/upload/news/image/';
        if (!empty($request->file('image_news'))) {
            if (isset($news->image_news)) {
                // unlink($path . $news->image_news);
            }
            $imgwidth = 1920;
            $filename = '_image_news';
            $request_file = $request->file('image_news');
            $name_pic = Tool::upload_picture($path, $imgwidth, $filename, $request_file);
            $news->image_news = $name_pic;
        }

        $news->detail_news = $request->detail_news;
        $news->detail_news_all = $request->detail_news_all;

        if (!empty($request->file('upload_video'))) {
            $image = $request->file('upload_video');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('/upload/news/video/'), $imageName);
            $news->upload_video = $imageName;
            $news->video_type = 1;
        } elseif (!empty($request->link_news)) {
            $news->link_video = $request->link_news;
            $news->video_type = 2;
        }

        $news->start_date_news = date_format(new DateTime($request->start_date_news), "Y-m-d");
        $news->end_date_news = date_format(new DateTime($request->end_date_news), "Y-m-d");
        $news->save();
        return redirect('admin/news_manage');
    }

    public function Pulldata(Request $request)
    {
        $id_news = $request->id;
        $sql_news = DB::table('new')
            ->select(
                'new.*',
            )->where('id', $id_news)
            ->first();

        return response()->json($sql_news, 200);
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
        $news = News::find($request->id);
        $news->title_news = $request->title_news_update;
        $path = public_path() . '/upload/news/image/';
        if (!empty($request->file('image_news_update'))) {
            if (isset($news->image_news_update)) {
                // unlink($path . $news->image_news);
            }
            $imgwidth = 1920;
            $filename = '_image_news';
            $request_file = $request->file('image_news_update');
            $name_pic = Tool::upload_picture($path, $imgwidth, $filename, $request_file);
            $news->image_news = $name_pic;
        }

        $news->detail_news = $request->detail_news_update;
        $news->detail_news_all = $request->detail_news_all_update;

        if (!empty($request->file('upload_video2'))) {
            $image = $request->file('upload_video2');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('/upload/news/video/'), $imageName);
            $news->upload_video = $imageName;
            $news->video_type = 1;
        } elseif (!empty($request->link_news2)) {
            $news->link_video = $request->link_news2;
            $news->video_type = 2;
        }

        $news->start_date_news = date_format(new DateTime($request->start_date_news2), "Y-m-d");
        $news->end_date_news = date_format(new DateTime($request->end_date_news2), "Y-m-d");
        $news->update();
        return redirect('admin/news_manage');
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
        $news = News::find($id);
        $news->delete();
        $path = public_path() . '/upload/news/image/';
        unlink($path . $news->image_news);
        $max1 = DB::table('new')->max('id') + 1;
        DB::statement("ALTER TABLE new AUTO_INCREMENT =  $max1");
        return back();
    }
}
