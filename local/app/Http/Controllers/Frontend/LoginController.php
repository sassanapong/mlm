<?php
namespace App\Http\Controllers\Frontend;
use Auth;
use Session;
use Illuminate\Http\Request;
use App\Models\CUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
//use App\Http\Controllers\Session;
class LoginController extends Controller
{
    public function login(Request $req){

      $get_users = CUser::where('user_name','=',$req->username)
        ->where('password','=',md5($req->password))
        ->first();

 if($get_users){
          session()->forget('access_from_admin');
          Auth::guard('c_user')->login($get_users);

            return redirect('home');
        }else{
           return redirect('/')->withError('Pless check username and password !.');

       }
   }

   public function forceLogin($username)
   {
     if ($username) {
        $username = Crypt::decryptString($username);
        $user = CUser::where('user_name', $username)->first();

        if ($user) {
          Auth::guard('c_user')->login($user);
          session()->put('access_from_admin', 1);
        }

        return redirect('profile');
     }

     return redirect('/')->withError('Cannot access with this user.');
   }

}
