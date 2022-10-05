<?php

namespace App\Http\Controllers\Frontend;

use App\eWallet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;
use PhpParser\Node\Expr\FuncCall;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class eWalletController extends Controller
{

    public function eWallet_history()

    {
        return view('frontend/eWallet-history');
    }

    public function deposit(Request $request)
    {

        $customers_id_fk =  Auth::guard('c_user')->user()->id;


        $count_eWallet =  IdGenerator::generate([
            'table' => 'ewallet',
            'field' => 'transaction_code',
            'length' => 15,
            'prefix' => 'ew-' . date("Ymd"),
            'reset_on_prefix_change' => true
        ]);



        if ($request->upload[0]) {
            $url = 'local/public/images/eWllet/deposit/' . date('Ym');
            $imageName = $request->upload[0]->extension();
            $filenametostore =  date("YmdHis")  . $customers_id_fk . "." . $imageName;
            $request->upload[0]->move($url,  $filenametostore);


            $dataPrepare = [
                'transaction_code' => $count_eWallet,
                'customers_id_fk' => $customers_id_fk,
                'url' => $url,
                'file_ewllet' => $filenametostore,
                'amt' => $request->amt,
                'type' => 1,
                'status' => 1,
            ];

            $query =  eWallet::create($dataPrepare);

            return redirect('eWallet_history')->withSuccess(' Success');
        }
    }


    public function transfer(Request $request)
    {

        $customers_id_fk =  Auth::guard('c_user')->user()->id;
        $count_eWallet = eWallet::get()->count() + 1;
        $transaction_code = "ew" . date('Ymd') . date('Hi') . "-" . $count_eWallet;

        $dataPrepare = [
            'transaction_code' => $transaction_code,
            'customers_id_fk' => $customers_id_fk,
            'customers_id_receive' => $request->customers_id_receive,
            'customers_name_receive' => $request->customers_name_receive,
            'amt' => $request->amt,
            'type' => 2,
            'status' => 1,
        ];

        $query =  eWallet::create($dataPrepare);
        return back();
    }


    public function withdraw(Request $request)
    {
        $customers_id_fk =  Auth::guard('c_user')->user()->id;
        $count_eWallet = eWallet::get()->count() + 1;
        $transaction_code = "ew" . date('Ymd') . date('Hi') . "-" . $count_eWallet;

        $dataPrepare = [
            'transaction_code' => $transaction_code,
            'customers_id_fk' => $customers_id_fk,
            'amt' => $request->amt,
            'type' => 3,
            'status' => 1,
        ];



        $query =  eWallet::create($dataPrepare);
        return back();
    }
}
