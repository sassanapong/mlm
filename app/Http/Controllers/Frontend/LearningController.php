<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mdk_ct;
use App\Mdk_lrn;

class LearningController extends Controller
{

    public function index()
    {
        $Lrn = Mdk_lrn::orderBy('id', 'DESC')->paginate(4);
        $data = array(
            'Lrn' => $Lrn
        );
        return view('frontend/learning', $data);
    }

    public function search_lrn(Request $request)
    {
        // Get the search value from the request
        $Lrn = Mdk_lrn::orderBy('id', 'DESC')->paginate(4);
        $data = array(
            'Lrn' => $Lrn
        );

        $search_lrn = $request->input('search_lrn');
        // Search in the title and body columns from the posts table
        $posts_lrn = Mdk_lrn::query()
            ->where('title_lrn', 'LIKE', "%{$search_lrn}%")
            ->whereRaw('DATE(end_date_lrn) >= DATE(NOW())')
            ->get();
        // dd( $posts_lrn);
        // Return the search view with the resluts compacted
        return view('frontend/search_lrn', compact('posts_lrn'), $data);
    }

    public function learning_detail($id)
    {
        $Lrn = Mdk_lrn::find($id);
        $data = array(
            'Lrn' => $Lrn
        );
        return view('frontend/learning-detail', $data);
    }


    public function ct()
    {
        $Ct = Mdk_ct::orderBy('id', 'DESC')->paginate(4);
        $data = array(
            'Ct' => $Ct
        );
        return view('frontend/ct', $data);
    }

    public function search_ct(Request $request)
    {
        // Get the search value from the request
        $Ct = Mdk_ct::orderBy('id', 'DESC')->paginate(4);
        $data = array(
            'Ct' => $Ct
        );

        $search_ct = $request->input('search_ct');

        // Search in the title and body columns from the posts table
        $posts_ct = Mdk_ct::query()
            ->where('title_ct', 'LIKE', "%{$search_ct}%")
            ->whereRaw('DATE(end_date_ct) >= DATE(NOW())')
            ->get();

        // Return the search view with the resluts compacted
        return view('frontend/search_ct', compact('posts_ct'), $data);
    }

    public function ct_detail($id)
    {
        $Ct = Mdk_ct::find($id);
        $data = array(
            'Ct' => $Ct
        );
        return view('frontend/ct-detail', $data);
    }
}
