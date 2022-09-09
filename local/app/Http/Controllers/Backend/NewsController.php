<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\News;
use App\External\Tool;
use DateTime;

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
            $imgwidth = 200;
            $filename = '_image_news';
            $request_file = $request->file('image_news');
            $name_pic = Tool::upload_picture($path, $imgwidth, $filename, $request_file);
            $news->image_news = $name_pic;
        }

        $news->detail_news = $request->detail_news;

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

        $news->date_news = date_format(new DateTime($request->date_news),"Y-m-d");
        $news->save();
        return redirect('admin/news_manage');
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
    public function edit($id)
    {
        //
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
        //
    }
}
