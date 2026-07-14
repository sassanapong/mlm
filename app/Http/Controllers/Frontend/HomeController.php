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
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
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
    $allSalePreviewTotalPv = $this->allSalePreviewTotalPv(Auth::guard('c_user')->user()->user_name);
    $data = array(
      'News' => $News,
      'allSalePreviewTotalPv' => $allSalePreviewTotalPv,
    );
    return view('frontend/home', $data);
  }

  protected function allSalePreviewTotalPv($userName)
  {
    if (!Schema::hasTable('bonus_all_sale_preview_details')) {
      return 0;
    }

    $period = $this->currentAllSalePeriod();

    return (float) DB::table('bonus_all_sale_preview_details')
      ->where('year', $period['year'])
      ->where('month', $period['month'])
      ->where('route', $period['route'])
      ->where('user_name', $userName)
      ->sum('organization_pv');
  }

  protected function currentAllSalePeriod()
  {
    $today = Carbon::now();
    $day = (int) $today->format('d');

    return [
      'year' => (int) $today->format('Y'),
      'month' => (int) $today->format('m'),
      'route' => $day <= 15 ? 1 : 2,
    ];
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
