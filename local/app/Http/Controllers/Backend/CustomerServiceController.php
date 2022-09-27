<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CUser;
use Illuminate\Http\Request;
use Auth;

class CustomerServiceController extends Controller
{

    public function admin_login_user($id)
    {

        $users = CUser::where('id', $id)->first();
        Auth::guard('c_user')->logout();
        Auth::guard('c_user')->login($users);

        return redirect('editprofile');
    }
}
