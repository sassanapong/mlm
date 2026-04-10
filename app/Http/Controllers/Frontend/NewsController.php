<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\News;

class NewsController extends Controller
{
    public function news_detail($id)
    {
        $News = News::find($id);
        $data = array(
            'News' => $News
        );
        return view('frontend/news-detail', $data);
    }
}
