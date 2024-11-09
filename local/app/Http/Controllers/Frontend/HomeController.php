<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DB;
use App\News;
use App;
//use App\Http\Controllers\Session;
class HomeController extends Controller
{

  public function __construct()
  {
    $this->middleware('customer');
  }

  public function index()
  {

    $News = News::paginate(6);
    $data = array(
      'News' => $News
    );
    return view('frontend/home', $data);
  }

  public function change(Request $request)
  {
    App::setLocale($request->lang);
    session()->put('locale', $request->lang);

    return redirect()->back();
  }
  public function acceptTerms(Request $request)
  {
    $customer_id = Auth::guard('c_user')->user()->id;
    $terms_accepted =  DB::table('customers')
      ->where('id', $customer_id)
      ->update([
        'terms_accepted' => 'yes',
        'terms_accepted_date' => now()
      ]);
    // รีไดเร็กไปยังหน้าต่อไป
    return redirect()->route('home')->withSuccess('ยอมรับข้อตกลงสำเร็จ');
  }
}
