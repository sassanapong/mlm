<?php

namespace App\Http\Controllers\Frontend;

use App\Customers;
use App\CustomersBank;
use App\eWallet;
use App\eWallet_tranfer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;
use PhpParser\Node\Expr\FuncCall;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Support\Facades\Http;
use Phattarachai\LineNotify\Facade\Line;

class eWalletController extends Controller
{

    public function eWallet_history()

    {
        return view('frontend/eWallet-history');
    }


    public function front_end_get_ewallet(Request $request)
    {
        $customer = Auth::guard('c_user')->user()->id;
        $recive = Customers::where('id', $customer)->first();
        $data =  eWallet::where(function ($query) use ($request) {
            if ($request->has('Where')) {
                foreach (request('Where') as $key => $val) {
                    if ($val) {
                        if (strpos($val, ',')) {
                            $query->whereIn($key, explode(',', $val));
                        } else {
                            $query->where($key, $val);
                        }
                    }
                }
            }
            if ($request->has('Like')) {
                foreach (request('Like') as $key => $val) {
                    if ($val) {
                        $query->where($key, 'like', '%' . $val . '%');
                    }
                }
            }
        })
            ->where('customers_id_fk', Auth::guard('c_user')->user()->id)
            // ->orwhere('customers_id_receive',$recive->id)
            ->OrderBy('id', 'DESC');
        // ->get();


        return DataTables::of($data)
            // ->setRowClass('intro-x py-4 h-24 zoom-in')



            ->editColumn('transaction_code', function ($query) {
                if ($query->type == 4) {
                    $data = '<a href="' . route('order_detail', ['code_order' => $query->transaction_code]) . '" class="btn btn-sm btn-outline-primary">' . $query->transaction_code . '</a>';
                    return $data;
                } else {
                    return $query->transaction_code;
                }
            })

            ->editColumn('customer_username', function ($query) {
                //$customers = Customers::select('user_name','name', 'last_name')->where('id', $query->customers_id_receive)->first();
                // if($customers){
                //     $test_customers = $customers['user_name'];
                // }else{
                //     $test_customers="-";
                // }

                if ($query->type == 2) {

                    if ($query->customers_username_tranfer == '0534768') {
                        $customers = Customers::select('user_name', 'name', 'last_name')->where('user_name', $query->customers_username_tranfer)->first();
                        $name_user = $customers->name . ' ' . $customers->last_name;
                    } else {
                        $name_user = $query->customers_username_tranfer;
                    }
                } else {


                    if ($query->customers_name_receive == '0534768') {
                        $customers = Customers::select('user_name', 'name', 'last_name')->where('user_name', $query->customers_name_receive)->first();
                        $name_user = $customers->name . ' ' . $customers->last_name;
                    } else {

                        $name_user = $query->customer_username;
                    }
                }

                return $name_user;
            })


            // ดึงข้อมูล created_at
            ->editColumn('created_at', function ($query) {
                $time = date('Y/m/d H:i:s', strtotime($query->created_at));

                return $time;
            })
            // ดึงข้อมูล lot_expired_date วันหมดอายุ
            ->editColumn('amt', function ($query) {
                if ($query->edit_amt > 0) {
                    $amt = number_format($query->edit_amt, 2);
                } else {
                    $amt = number_format($query->amt, 2);
                }
                $type = $query->type;
                if ($query->customers_id_receive == Auth::guard('c_user')->user()->id) {

                    if ($type  == 3 || $type  == 4) {
                        $text_type = "-";
                    } else {
                        $text_type = "";
                    }
                } else {

                    if ($type  == 2 || $type  == 3 || $type  == 4) {
                        $text_type = "-";
                    } else {
                        $text_type = "";
                    }
                }

                return $text_type . $amt;
            })
            ->editColumn('balance', function ($query) {
                // if($query->customers_id_receive == Auth::guard('c_user')->user()->id){
                //     $balance = number_format($query->balance_recive, 2) . " บาท";
                // }else{
                //     $balance = number_format($query->balance, 2) . " บาท";
                // }
                $balance = number_format($query->balance, 2);
                return $balance;
            })
            ->editColumn('edit_amt', function ($query) {
                $edit_amt = $query->edit_amt == 0 ? '' :  number_format($query->edit_amt, 2);
                return $edit_amt;
            })

            ->editColumn('customers_name_receive', function ($query) {
                // $customers = Customers::select('user_name','name', 'last_name')->where('id', $query->customers_id_receive)->first();

                if ($query->customers_name_receive == '0534768') {
                    $customers = Customers::select('user_name', 'name', 'last_name')->where('id', $query->customers_id_receive)->first();
                    return $customers->name . ' ' . $customers->last_name;
                } else {
                    $test_customers = $query->customers_name_receive;

                    return $test_customers;
                }
            })
            ->editColumn('note_orther', function ($query) {
                // $customers = Customers::select('user_name','name', 'last_name')->where('id', $query->customers_id_receive)->first();
                if ($query->note_orther) {
                    $html = $query->note_orther;
                } else {
                    $html = $query->type_note;
                }


                return $html;
            })

            // ->editColumn('customers_id_fk', function ($query) {
            //     $customers = Customers::select('user_name','name', 'last_name')->where('id', $query->customers_id_fk)->first();
            //     $test_customers = $customers['user_name'];
            //     return $test_customers;
            // })

            ->editColumn('type', function ($query) {
                $type = $query->type;
                $text_type = "";
                if ($query->customers_id_receive == Auth::guard('c_user')->user()->id) {
                    if ($type  == 1) {
                        $text_type = "ฝากเงิน";
                    }
                    if ($type  == 2) {
                        $text_type = "รับเงิน";
                    }
                    if ($type  == 3) {
                        $text_type = "ถอนเงิน";
                    }
                    if ($type  == 4) {
                        $text_type = "ซื้อสินค้า";
                    }
                    if ($type  == 5) {
                        $text_type = "ReCashback";
                    }
                    if ($type  == 6) {
                        $text_type = "บริหารทีม ReCashback";
                    }
                    if ($type  == 7) {
                        $text_type = "ยืนยันรับสิทธิ์";
                    }
                    if ($type  == 8) {
                        $text_type = "โบนัสบริหารทีม";
                    }
                    if ($type  == 9) {
                        $text_type = "โบนัสเจ้าของลิขสิทธิ์";
                    }

                    if ($type  == 10) {
                        $text_type = "โบนัสโบนัสขยายธุรกิจ";
                    }

                    if ($type  == 11) {
                        $text_type = "โบนัสสร้างทีม";
                    }
                    if ($type  == 12) {
                        $text_type = "โบนัสบาลานซ์ W/S";
                    }
                    if ($type  == 13) {
                        $text_type = "โบนัส MATCHING";
                    }

                    if ($type  == 14) {
                        $text_type = "โบนัส เงินล้านบริหารTEAM";
                    }
                    if ($type  == 15) {
                        $text_type = "โบนัส Easy";
                    }
                } else {

                    if ($type  == 1) {
                        $text_type = "ฝากเงิน";
                    }
                    if ($type  == 2) {
                        $text_type = "โอนเงิน";
                    }
                    if ($type  == 3) {
                        $text_type = "ถอนเงิน";
                    }
                    if ($type  == 4) {
                        $text_type = "ซื้อสินค้า";
                    }
                    if ($type  == 5) {
                        $text_type = "ReCashback";
                    }
                    if ($type  == 6) {
                        $text_type = "บริหารทีม ReCashback";
                    }
                    if ($type  == 7) {
                        $text_type = "ยืนยันรับสิทธิ์";
                    }
                    if ($type  == 8) {
                        $text_type = "โบนัสบริหารทีม";
                    }
                    if ($type  == 9) {
                        $text_type = "โบนัสเจ้าของลิขสิทธิ์";
                    }

                    if ($type  == 10) {
                        $text_type = "โบนัสโบนัสขยายธุรกิจ";
                    }

                    if ($type  == 11) {
                        $text_type = "โบนัสสร้างทีม";
                    }
                    if ($type  == 12) {
                        $text_type = "โบนัสบาลานซ์ W/S";
                    }
                    if ($type  == 13) {
                        $text_type = "โบนัส MATCHING";
                    }

                    if ($type  == 14) {
                        $text_type = "โบนัส เงินล้านบริหารTEAM";
                    }

                    if ($type  == 15) {
                        $text_type = "โบนัส Easy";
                    }
                }
                return $text_type;
            })
            ->editColumn('status', function ($query) {
                $status = $query->status;

                if ($status == 1) {
                    $status = "รออนุมัติ";
                    $status_bg = "warning";
                }
                if ($status == 2) {
                    $status = "อนุมัติ";
                    $status_bg = "success";
                }
                if ($status == 3) {
                    $status = "ไม่อนุมัติ";
                    $status_bg = "danger";
                }
                if ($status == 4) {
                    $status = "ยกเลิก";
                    $status_bg = "danger";
                }
                $html = '<span class="badge bg-' . $status_bg . '">' . $status . '</span>';

                return $html;
            })

            ->rawColumns(['transaction_code', 'status'])
            ->make(true);
    }



    public function deposit(Request $request)
    {
        $rule = [

            'upload' => 'required|image',  // เพิ่มการตรวจสอบว่าเป็นไฟล์รูปภาพ
        ];

        $message_err = [

            'upload.required' => 'กรุณาแนบสลิป การโอนเงิน',
            'upload.image' => 'กรุณาแนบไฟล์ภาพที่ถูกต้อง',
        ];

        $validator = Validator::make(
            $request->all(),
            $rule,
            $message_err
        );

        if (!$validator->fails()) {
            $customers_id_fk = Auth::guard('c_user')->user()->id;
            $customers = Customers::where('id', $customers_id_fk)->first();

            $count_eWallet = \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_wallet();

            // Upload the file
            if ($request->hasFile('upload')) {
                $url = 'local/public/images/eWllet/deposit/' . date('Ym');
                $imageName = $request->upload->extension();
                $filenametostore = date("YmdHis") . $customers_id_fk . "." . $imageName;

                // Save the file to the server
                $request->upload->move($url, $filenametostore);

                $response = $this->checkSlip($url . '/' . $filenametostore);



                if ($response->successful()) {
                    // Process the deposit transaction if the slip is correct

                    $response = json_decode($response);
                    $response = $response->data;

                    $cutoffDate = '2024-11-12';

                    if (strtotime($cutoffDate) >= strtotime($response->transDate)) {
                        return $data = ['status' => 'fail', 'message' => 'ต้องใช้สลิปที่เป็นปัจจุบันเท่านั้น กรุณาติดต่อ Admin'];
                    }


                    if ($response->success == true) {
                        $dataPrepare = [
                            'transaction_code' => $count_eWallet,
                            'customers_id_fk' => $customers_id_fk,
                            'customer_username' => $customers->user_name,
                            'url' => $url,
                            'file_ewllet' => $filenametostore,
                            'amt' => $response->amount,
                            'type' => 1,
                            'status' => 1,

                        ];
                        try {
                            DB::beginTransaction();
                            $lastRecord =  eWallet_tranfer::create($dataPrepare);
                            DB::commit();
                            $response_eWallet = eWalletController::approve_update_ewallet($lastRecord->id, $response);
                            if ($response_eWallet['status'] == 'success') {
                                return $data = ['status' => 'success'];
                            } else {
                                return $data = ['status' => 'fail', 'message' => 'เกิดข้อผิดพลาดกรุณาทำรายการใหม่'];
                            }
                        } catch (Exception $e) {
                            DB::rollback();
                            return $data = ['status' => 'fail', 'message' => 'เกิดข้อผิดพลาดกรุณาทำรายการใหม่'];
                        }
                    } else {
                        DB::rollback();
                        return $data = ['status' => 'fail', 'message' => $response->message];
                    }
                } else {
                    $response = json_decode($response);

                    if ($response->code == 1007) { //ไม่ใช่ slip

                        $dataPrepare = [
                            'transaction_code' => $count_eWallet,
                            'customers_id_fk' => $customers_id_fk,
                            'customer_username' => $customers->user_name,
                            'url' => $url,
                            'file_ewllet' => $filenametostore,
                            'amt' => 0,
                            'type' => 1,
                            'status' => 1,

                        ];

                        try {
                            DB::beginTransaction();
                            $lastRecord =  eWallet_tranfer::create($dataPrepare);
                            DB::commit();


                            $message = "\n" . "รหัส : " . $customers->user_name . "\n";

                            $message .= "ฝากเงินรออนุมัติ \n";
                            $img_url = asset($url . '/' . $filenametostore);

                            Line::imageUrl($img_url)
                                ->send($message);

                            return $data = ['status' => 'success', 'message' => 'ฝากสำเร็จกรุณารอ Admin ตรวจสอบ'];
                        } catch (Exception $e) {
                            DB::rollback();
                            return $data = ['status' => 'fail', 'message' => 'เกิดข้อผิดพลาดกรุณาทำรายการใหม่'];
                        }
                    }

                    return $data = ['status' => 'fail', 'message' => $response->message];
                }
            }
            return $data = ['status' => 'fail', 'message' => 'กรุณาแนบสลิปการโอนเงิน'];
        }

        return $data = ['status' => 'fail', 'message' => $validator->errors()];
    }


    public static function approve_update_ewallet($eWallet_tranfer_id, $json_data)
    {

        $ewallet_id = $eWallet_tranfer_id;

        $check = eWallet_tranfer::where('id', $ewallet_id)->first();
        // $query = eWallet_tranfer::where('code_refer', $code_refer)->first();
        $customers = Customers::where('id', $check->customers_id_fk)->first();
        $amt = $check->amt;

        $query_ewallet = eWallet_tranfer::where('id', $ewallet_id);

        try {
            DB::BeginTransaction();

            if ($check) {
                $dataPrepare = [
                    'receive_date' => date('Y-m-d', strtotime($json_data->transDate)),
                    'receive_time' => $json_data->transTime,
                    'code_refer' => $json_data->transRef,
                    'balance' =>  $customers->ewallet,
                    'date_mark' => date('Y-m-d H:i:s'),
                    'status' => 2,
                ];

                $query_ewallet->update($dataPrepare);

                // อัพเดท old_balance กับ  balance ของ table ewallet

                if ($check->type == "3") {
                } else {
                    if ($query_ewallet) {
                        $dataPrepare_update = [
                            'old_balance' => $customers->ewallet,
                            'balance' =>  $customers->ewallet + $amt
                        ];
                        $query_ewallet->update($dataPrepare_update);
                        if ($query_ewallet) {

                            $dataPrepare_update_ewallet = [
                                'ewallet' =>  $customers->ewallet + $amt,
                                'ewallet_tranfer' =>  $customers->ewallet_tranfer + $amt
                            ];

                            $create_data = [
                                'transaction_code' => $check->transaction_code,
                                'customers_id_fk' =>  $check->customers_id_fk,
                                'customer_username' => $check->customer_username,
                                'url' => $check->url,
                                'file_ewllet' => $check->file_ewllet,
                                'amt' => $check->amt,
                                'receive_date' => date('Y-m-d', strtotime($json_data->transDate)),
                                'receive_time' => $json_data->transTime,
                                'code_refer' => $json_data->transRef,
                                'old_balance' => $customers->ewallet,
                                'balance' =>  $customers->ewallet + $amt,
                                'edit_amt' => 0,
                                'date_mark' => date('Y-m-d H:i:s'),
                                'type' => $check->type,
                                'status' => 2,
                            ];

                            eWallet::create($create_data);
                            Customers::where('id', $check->customers_id_fk)->update($dataPrepare_update_ewallet);
                            DB::commit();

                            $data = ['status' => 'success', 'ms' => 'success'];
                            return $data;
                        }
                    }
                }
            } else {
                DB::rollback();
                $data = ['status' => 'fail', 'ms' => 'ไม่พบข้อมูลการฝากเงิน'];
                return $data;
            }
        } catch (Exception $e) {
            DB::rollback();
            $data = ['status' => 'fail', 'ms' => 'error'];
            return $data;
        }

        $data = ['status' => 'fail', 'ms' => 'error'];

        return $data;
    }

    public function checkSlip($file)
    {


        // Prepare headers

        $headers = [

            'x-authorization' => 'SLIPOKCCY5JZ4',
            'Content-Type' => 'application/json',
        ];
        $file = url($file);
        // Send the API request to verify the slip
        $response = Http::withHeaders($headers)
            ->post('https://api.slipok.com/api/line/apikey/33195', [
                'url' => $file,
                'log' => true, //true false
            ]);

        return $response;  // Return the API response
    }


    public function transfer(Request $request)
    {


        if ($request->amt <  300) {
            $data = ['status' => 'fail', 'ms' => 'ต้องมียอดขั้นต่ำในการโอน 300 บาท'];
            return response()->json(['status' => 'fail', 'ms' => 'ต้องมียอดขั้นต่ำในการโอน 300 บาท'], 200);
        }


        $customers_id_fk =  Auth::guard('c_user')->user()->id;
        // $count_eWallet = eWallet::get()->count() + 1;


        $transaction_code =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_wallet();

        $customer_receive = Customers::lockForUpdate()
            ->where('user_name', $request->customers_id_receive)->first();


        $old_balance_receive =  $customer_receive->ewallet;
        // dd($customer_receive);
        $customer_transfer = Customers::lockForUpdate()->where('id', $customers_id_fk)->first();

        if (empty($customer_transfer->ewallet_use)) {
            $ewallet_use = 0;
        } else {

            $ewallet_use = $customer_transfer->ewallet_use;
        }


        $ewallet = Auth::guard('c_user')->user()->ewallet;
        $ewallet_tranfer = Auth::guard('c_user')->user()->ewallet_tranfer;

        if (($ewallet_use + $ewallet_tranfer)  > $ewallet) {
            $price_ewallet = Auth::guard('c_user')->user()->ewallet;
        } else {
            if ($ewallet_use >= 300) {
                $price_ewallet = $ewallet_use + $ewallet_tranfer;
            } else {
                if ($ewallet_tranfer >= 300) {
                    $price_ewallet = $ewallet_tranfer;
                } else {
                    $price_ewallet = 0;
                }
            }
        }

        if ($request->amt > $price_ewallet) {
            return response()->json(['status' => 'fail', 'ms' => 'ไม่สามารถโอนยอดเกิน' . $price_ewallet . 'บาท'], 200);
        }


        $expire_date_1 = $customer_transfer->expire_date;
        $expire_date_2 = $customer_transfer->expire_date_bonus;

        if (strtotime($expire_date_1) >  strtotime($expire_date_2)) {
            $expire_date = $expire_date_1;
        } else {
            $expire_date = $expire_date_2;
        }

        if (empty($expire_date) || strtotime($expire_date) < strtotime(date('Ymd'))) {
            $data = ['status' => 'fail', 'ms' => 'รหัสของคุณไม่มีการ Active ไม่สามารถแจงให้รหัสอื่นได้'];
            return response()->json(['status' => 'fail', 'ms' => 'รหัสของคุณไม่มีการ Active ไม่สามารถทำราการโอนได้'], 200);
        }

        $old_balance_user =  $customer_transfer->ewallet;


        if ($customer_transfer->ewallet_use >= 300 || $customer_transfer->ewallet_tranfer >= 300) {

            if ($customer_transfer->ewallet >= $request->amt) {


                if ($customer_transfer->ewallet_use >= 300) {
                    $ewallet_use =  $customer_transfer->ewallet_use - $request->amt;

                    if ($ewallet_use < 0) {
                        $customer_transfer->ewallet_use = 0;
                        $ewallet_tranfer = $customer_transfer->ewallet_tranfer +  $ewallet_use;
                        if ($ewallet_tranfer < 0) {
                            return response()->json(['status' => 'fail', 'ms' => 'ยอดเงินฝากและโบนัสของคุณไม่เพียงต่อการโอนเงิน'], 200);
                        } else {

                            $customer_transfer->ewallet_tranfer = $ewallet_tranfer;
                        }
                    } else {
                        $customer_transfer->ewallet_use = $ewallet_use;
                    }
                } else {

                    $ewallet_tranfer =  $customer_transfer->ewallet_tranfer - $request->amt;
                    if ($ewallet_tranfer < 0) {
                        return response()->json(['status' => 'fail', 'ms' => 'สามารถโอนได้ที่ ' . $customer_transfer->ewallet_tranfer . ' บาทเท่านั้น'], 200);
                    } else {
                        $customer_transfer->ewallet_tranfer = $ewallet_tranfer;
                    }
                }



                $ewallet = $customer_transfer->ewallet - $request->amt;
                $customer_transfer->ewallet = $ewallet;


                if ($ewallet <= 0) {
                    $customer_transfer->ewallet_use = 0;
                    $customer_transfer->ewallet_tranfer = 0;
                }


                $customer_receive->ewallet = $customer_receive->ewallet + $request->amt;
                $customer_receive->ewallet_tranfer = $customer_receive->ewallet_tranfer + $request->amt;

                $dataPrepare = [ //ผู้โอน
                    'transaction_code' => $transaction_code,
                    'customers_id_fk' => $customers_id_fk,
                    'customer_username' => Auth::guard('c_user')->user()->user_name,
                    'customers_id_receive' => $customer_receive->id,
                    'customers_id_tranfer' => $customers_id_fk,
                    'customers_username_tranfer' => Auth::guard('c_user')->user()->user_name,
                    'customers_name_receive' => $customer_receive->user_name,
                    'old_balance' => $old_balance_user,
                    'balance' => $customer_transfer->ewallet,
                    // 'balance_recive'=>$customer_receive->ewallet,
                    'note_orther' => 'โอนให้รหัส ' . $customer_receive->user_name,
                    'type_tranfer' => 'tranfer',
                    'receive_date' => date('Y-m-d'),
                    'receive_time' => date('H:i:s'),
                    'amt' => $request->amt,
                    'type' => 2,
                    'status' => 2,
                ];
                $query =  eWallet::create($dataPrepare);

                $dataPrepare_receive = [ //ผู้รับ
                    'transaction_code' => $transaction_code,
                    'customers_id_fk' =>  $customer_receive->id,
                    'customer_username' => $customer_receive->user_name,
                    'customers_id_tranfer' => $customers_id_fk,
                    'customers_username_tranfer' => Auth::guard('c_user')->user()->user_name,
                    'customers_id_receive' => $customer_receive->id,
                    'customers_name_receive' => $customer_receive->user_name,
                    'old_balance' => $old_balance_receive,
                    'balance' => $customer_receive->ewallet,
                    'note_orther' => 'ได้รับยอดโอนจาก ' . Auth::guard('c_user')->user()->user_name,

                    // 'balance_recive'=>$customer_receive->ewallet,
                    'type_tranfer' => 'receive',
                    'receive_date' => date('Y-m-d'),
                    'receive_time' => date('H:i:s'),
                    'amt' => $request->amt,
                    'type' => 2,
                    'status' => 2,
                ];

                $query_receive =  eWallet::create($dataPrepare_receive);
                try {
                    DB::BeginTransaction();
                    $customer_transfer->save();
                    $customer_receive->save();
                    DB::commit();
                    return response()->json(['status' => 'success'], 200);
                } catch (Exception $e) {
                    DB::rollback();
                    return response()->json(['status' => 'fail', 'ms' => 'เกิดข้อผิดพลาดกรุณาทำรายการไหม่'], 200);
                }
            } else {
                return response()->json(['status' => 'fail', 'ms' => 'eWallet ของท่านไม่เพียงพอ'], 200);
                // return redirect('home')->withError('eWallet ของท่านไม่เพียงพอ');
            }
        } else {
            return response()->json(['status' => 'fail', 'ms' => 'ยอดเงินฝากและโบนัสของคุณไม่เพียงต่อการโอนเงิน'], 200);
        }
    }


    public static function  checkcustomer(Request $request)
    {
        //Request $request

        $rs_user_name_active = trim($request->id);
        $rs_user_use  =  Auth::guard('c_user')->user()->user_name;


        if ($rs_user_use == '0534768' || $rs_user_name_active == '0534768') {

            $user_name_active =  DB::table('customers')
                ->select(
                    'customers.pv',
                    'customers.id',
                    'customers.name',
                    'customers.last_name',
                    'customers.user_name',
                    'customers.qualification_id',
                    'customers.expire_date',
                    'customers.expire_date_bonus',
                    'dataset_qualification.pv_active',
                    'customers.introduce_id',
                    'customers.status_customer'

                )
                ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                ->where('user_name', '=', $rs_user_name_active)
                ->first();


            if ($user_name_active) {
                $data = [
                    'status' => 'success',
                    'user_name' => $user_name_active->user_name,
                    'name' => $user_name_active->name,
                    'position' => $user_name_active->qualification_id,
                    'pv_active' => $user_name_active->pv_active,
                    'ms' => 'Success'
                ];
                return $data;
            } else {
                $data = ['status' => 'fail', 'ms' => 'ไม่พบรหัส'];
                return $data;
            }
        }
        if ($rs_user_name_active ==  $rs_user_use) {
            $data = ['status' => 'fail', 'ms' => 'ไม่สามารถทำรายการโอนให้ตัวเองได้'];
            return $data;
        }

        $user =  DB::table('customers')
            ->select(
                'customers.pv',
                'customers.id',
                'customers.name',
                'customers.last_name',
                'customers.user_name',
                'customers.qualification_id',
                'customers.expire_date',
                'customers.expire_date_bonus',
                'dataset_qualification.pv_active',
                'customers.introduce_id'
            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $rs_user_use)
            ->first();
        $expire_date_1 = $user->expire_date;
        $expire_date_2 = $user->expire_date_bonus;

        if (strtotime($expire_date_1) >  strtotime($expire_date_2)) {
            $expire_date = $expire_date_1;
        } else {
            $expire_date = $expire_date_2;
        }


        if (empty($expire_date) || strtotime($expire_date) < strtotime(date('Ymd'))) {
            $data = ['status' => 'fail', 'ms' => 'รหัสของคุณไม่มีการ Active ไม่สามารถโอนให้รหัสอื่นได้'];
            return $data;
        }

        // $rs_user_use  = '7492038';
        // $rs_user_name_active = '9519863';
        $rs = array();

        $user_name_active =  DB::table('customers')
            ->select(
                'customers.pv',
                'customers.id',
                'customers.name',
                'customers.last_name',
                'customers.user_name',
                'customers.qualification_id',
                'customers.expire_date',
                'customers.expire_date_bonus',
                'dataset_qualification.pv_active',
                'customers.introduce_id',
                'customers.status_customer'

            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $rs_user_name_active)
            ->first();

        //9519863

        if (!empty($user_name_active)) {

            if ($user_name_active->status_customer != 'normal') {
                $data = ['status' => 'fail', 'ms' => 'รหัส ' . $rs_user_name_active . ' ถูกตัดออกจากระบบเเล้ว'];
                return $data;
            }
            $user_name_active->status_customer;
            $name = $user_name_active->name . ' ' . $user_name_active->last_name;
            if (empty($user_name_active->expire_date) || strtotime($user_name_active->expire_date) < strtotime(date('Ymd'))) {
                if (empty($user_name_active->expire_date)) {
                    $date_mt_active = 'Not Active';
                } else {
                    //$date_mt_active= date('d/m/Y',strtotime(Auth::guard('c_user')->user()->expire_date));
                    $date_mt_active = 'Not Active';
                }
                $status = 'danger';
            } else {
                $date_mt_active = 'Active ' . date('d/m/Y', strtotime(Auth::guard('c_user')->user()->expire_date));
                $status = 'success';
            }
            //ไหม่ ไม่ต้องตามสายงาน
            // $data = ['user_name' => $user_name_active->user_name, 'name' => $name, 'position' => $user_name_active->qualification_id, 'pv_active' => $user_name_active->pv_active, 'date_active' => $date_mt_active, 'ms' => 'Success'];
            // return $data;


            if ($user_name_active->user_name == $rs_user_use || $user_name_active->introduce_id == $rs_user_use) {

                $data = ['user_name' => $user_name_active->user_name, 'name' => $name, 'position' => $user_name_active->qualification_id, 'pv_active' => $user_name_active->pv_active, 'date_active' => $date_mt_active, 'ms' => 'Success'];
                return $data;
            } else {
                $i = 1;
                $user_name = $rs_user_use;

                while ($i <= 10) { //ค้นหาด้านบน 5 ชั้น
                    $up =  DB::table('customers')
                        ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id')
                        // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                        ->where('user_name', '=', $user_name)
                        ->first();

                    if (empty($up)) {
                        $status = 'fail';

                        //1240175
                        @$user_name = $up->introduce_id;
                        $rs = '';
                        $i = 10;
                        break;
                    }

                    if ($up->user_name ==  $rs_user_name_active || $up->introduce_id ==  $rs_user_name_active) {
                        $i = 10;
                        // dd($up->user_name);
                        //$rs[] = ['user_name' => $up->user_name, 'name' => $up->name, 'sponser' => $up->introduce_id, 'type' => 'up', 'status' => 'success'];
                        $status = 'success';
                        break;
                    } else {
                        @$user_name = $up->introduce_id;
                        // $rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'up','status'=>'fail'];
                        $rs = '';
                        $status = 'fail';
                        if (!empty($up->name)) {
                            $i++;
                        }
                    }
                }


                if ($status == 'fail') {
                    $j = 1;
                    $user_name = $user_name_active->introduce_id;
                    while ($j <= 10) { //ค้นหาด้านล่าง 5 ชั้น

                        $up =  DB::table('customers')
                            ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id')
                            // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                            ->where('user_name', '=', $user_name)
                            ->first();
                        // if($i==3){
                        //     dd($up,$i);
                        // }

                        if (empty($up)) {
                            $status = 'fail';
                            @$user_name = $up->introduce_id;
                            $rs = '';
                            //$rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'dow','status'=>'fail'];
                            $i = 10;
                            break;
                        }

                        if ($up->user_name == $rs_user_use || $up->introduce_id == $rs_user_use) {
                            $i = 10;
                            //$rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'dow','status'=>'success'];
                            $rs = '';
                            $status = 'success';
                            break;
                        } else {
                            @$user_name = $up->introduce_id;
                            //$rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'dow','status'=>'fail'];
                            $rs = '';
                            $status = 'fail';
                            if (!empty($up->name)) {
                                $j++;
                            }
                        }
                    }
                }
                // dd($rs);

                if ($status == 'fail') {
                    $data = ['status' => 'fail', 'rs' => $rs, 'ms' => 'รหัสสมาชิกไม่อยู่ในสายงานแนะนำ'];
                    return $data;
                } else {
                    $data = ['status' => 'success', 'user_name' => $user_name_active->user_name, 'name' => $name, 'position' => $user_name_active->qualification_id, 'pv_active' => $user_name_active->pv_active, 'date_active' => $date_mt_active, 'rs' => $rs, 'ms' => 'Success'];
                    return $data;
                }
            }



            // for($i=1;$i<=5;$i++){
            //     if()

            // }
        } else {
            $data = ['status' => 'fail', 'ms' => 'รหัสสมาชิกไม่ถูกต้อง'];
            return $data;
        }
    }


    public function check_customerbank(Request $request)
    {
        $customer_bank = CustomersBank::where('customers_id', $request->id)->first();
        if (!empty($customer_bank)) {
            $return = "true";
        } else {
            $return = "fail";
        }
        return $return;
    }



    public static function  checkcustomer_upline(Request $request)
    {
        //Request $request
        $rs_user_use  = trim($request->user_use); //ผู้โอน
        $rs_user_name_active = trim($request->user_name_active); //ผู้รับ



        if ($rs_user_use == '0534768' ||  $rs_user_name_active == '0534768') {

            $user_name_active =  DB::table('customers')
                ->select(
                    'customers.pv',
                    'customers.id',
                    'customers.name',
                    'customers.last_name',
                    'customers.user_name',
                    'customers.qualification_id',
                    'customers.expire_date',
                    'customers.expire_date_bonus',
                    'dataset_qualification.pv_active',
                    'customers.introduce_id',
                    'customers.status_customer'

                )
                ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                ->where('user_name', '=', $rs_user_name_active)
                ->first();


            if ($user_name_active) {
                $data = [
                    'status' => 'success',
                    'user_name' => $user_name_active->user_name,
                    'name' => $user_name_active->name,
                    'position' => $user_name_active->qualification_id,
                    'pv_active' => $user_name_active->pv_active,
                    'ms' => 'Success'
                ];
                return $data;
            } else {
                $data = ['status' => 'fail', 'ms' => 'ไม่พบรหัส'];
                return $data;
            }
        }

        $user =  DB::table('customers')
            ->select(
                'customers.pv',
                'customers.id',
                'customers.name',
                'customers.last_name',
                'customers.user_name',
                'customers.qualification_id',
                'customers.expire_date',
                'customers.expire_date_bonus',
                'dataset_qualification.pv_active',
                'customers.introduce_id',
                'customers.status_customer',
            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $rs_user_use)
            ->first();

        $expire_date_1 = $user->expire_date;
        $expire_date_2 = $user->expire_date_bonus;

        if (strtotime($expire_date_1) >  strtotime($expire_date_2)) {
            $expire_date = $expire_date_1;
        } else {
            $expire_date = $expire_date_2;
        }

        if (empty($expire_date) || strtotime($expire_date) < strtotime(date('Ymd'))) {
            $data = ['status' => 'fail', 'ms' => 'รหัสของคุณไม่มีการ Active ไม่สามารถแจงให้รหัสอื่นได้'];
            return response()->json(['status' => 'fail', 'ms' => 'รหัสของคุณไม่มีการ Active ไม่สามารถแจงให้รหัสอื่นได้'], 200);
        }

        // $rs_user_use  = '7492038';
        // $rs_user_name_active = '9519863';
        $rs = array();

        $user_name_active =  DB::table('customers')
            ->select(
                'customers.pv',
                'customers.id',
                'customers.name',
                'customers.last_name',
                'customers.user_name',
                'customers.qualification_id',
                'customers.expire_date',
                'customers.expire_date_bonus',
                'dataset_qualification.pv_active',
                'customers.introduce_id',
                'customers.status_customer',

            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $rs_user_name_active)
            ->first();
        //9519863



        if (!empty($user_name_active)) {

            if ($user_name_active->status_customer != 'normal') {
                $data = ['status' => 'fail', 'ms' => 'รหัส ' . $rs_user_name_active . ' ถูกตัดออกจากระบบเเล้ว'];
                return $data;
            }


            $name = $user_name_active->name . ' ' . $user_name_active->last_name;
            if (empty($user_name_active->expire_date) || strtotime($user_name_active->expire_date) < strtotime(date('Ymd'))) {
                if (empty($user_name_active->expire_date)) {
                    $date_mt_active = 'Not Active';
                } else {
                    //$date_mt_active= date('d/m/Y',strtotime(Auth::guard('c_user')->user()->expire_date));
                    $date_mt_active = 'Not Active';
                }
                $status = 'danger';
            } else {
                $date_mt_active = 'Active ' . date('d/m/Y', strtotime($user_name_active->expire_date));
                $status = 'success';
            }


            if (empty($user_name_active->expire_date_bonus) || strtotime($user_name_active->expire_date_bonus) < strtotime(date('Ymd'))) {
                if (empty($user_name_active->expire_date_bonus)) {
                    $date_mt_active_bonus = 'Not Active';
                } else {
                    //$date_mt_active_bonus= date('d/m/Y',strtotime(Auth::guard('c_user')->user()->expire_date_bonus));
                    $date_mt_active_bonus = 'Not Active';
                }
                $status = 'danger';
            } else {
                $date_mt_active_bonus = 'Active ' . date('d/m/Y', strtotime($user_name_active->expire_date_bonus));
                $status = 'success';
            }

            /////////////ไหม่ไม่ต้องตามสางาน

            // $data = ['user_name' => $user_name_active->user_name, 'name' => $name, 'position' => $user_name_active->qualification_id, 'pv_active' => $user_name_active->pv_active, 'date_active' => $date_mt_active, 'date_active_bonus' => $date_mt_active_bonus, 'ms' => 'Success'];
            // return $data;

            ///////////////////
            if ($user_name_active->user_name == $rs_user_use || $user_name_active->introduce_id == $rs_user_use) {

                $data = ['user_name' => $user_name_active->user_name, 'name' => $name, 'position' => $user_name_active->qualification_id, 'pv_active' => $user_name_active->pv_active, 'date_active' => $date_mt_active, 'ms' => 'Success'];
                return $data;
            } else {
                $i = 1;
                $user_name = $rs_user_use;

                while ($i <= 10) { //ค้นหาด้านบน 5 ชั้น
                    $up =  DB::table('customers')
                        ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id')
                        // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                        ->where('user_name', '=', $user_name)
                        ->first();

                    if (empty($up)) {
                        $status = 'fail';

                        //1240175
                        @$user_name = $up->introduce_id;
                        $rs = '';
                        $i = 10;
                        break;
                    }

                    if ($up->user_name ==  $rs_user_name_active || $up->introduce_id ==  $rs_user_name_active) {
                        $i = 10;
                        // dd($up->user_name);
                        //$rs[] = ['user_name' => $up->user_name, 'name' => $up->name, 'sponser' => $up->introduce_id, 'type' => 'up', 'status' => 'success'];
                        $status = 'success';
                        break;
                    } else {
                        @$user_name = $up->introduce_id;
                        // $rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'up','status'=>'fail'];
                        $rs = '';
                        $status = 'fail';
                        if (!empty($up->name)) {
                            $i++;
                        }
                    }
                }


                if ($status == 'fail') {
                    $j = 1;
                    $user_name = $user_name_active->introduce_id;
                    while ($j <= 10) { //ค้นหาด้านล่าง 5 ชั้น

                        $up =  DB::table('customers')
                            ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id')
                            // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                            ->where('user_name', '=', $user_name)
                            ->first();
                        // if($i==3){
                        //     dd($up,$i);
                        // }



                        if (empty($up)) {
                            $status = 'fail';
                            @$user_name = $up->introduce_id;
                            $rs = '';
                            //$rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'dow','status'=>'fail'];
                            $i = 10;
                            break;
                        }

                        if ($up->user_name == $rs_user_use || $up->introduce_id == $rs_user_use) {
                            $i = 10;
                            //$rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'dow','status'=>'success'];
                            $rs = '';
                            $status = 'success';
                            break;
                        } else {
                            @$user_name = $up->introduce_id;
                            //$rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'dow','status'=>'fail'];
                            $rs = '';
                            $status = 'fail';
                            if (!empty($up->name)) {
                                $j++;
                            }
                        }
                    }
                }
                // dd($rs);

                if ($status == 'fail') {
                    $data = ['status' => 'fail', 'rs' => $rs, 'ms' => 'รหัสสมาชิกไม่อยู่ในสายงานแนะนำ'];
                    return $data;
                } else {
                    $data = ['status' => 'success', 'user_name' => $user_name_active->user_name, 'name' => $name, 'position' => $user_name_active->qualification_id, 'pv_active' => $user_name_active->pv_active, 'date_active' => $date_mt_active, 'rs' => $rs, 'ms' => 'Success'];
                    return $data;
                }
            }



            // for($i=1;$i<=5;$i++){
            //     if()

            // }
        } else {
            $data = ['status' => 'fail', 'ms' => 'รหัสสมาชิกไม่ถูกต้อง'];
            return $data;
        }
    }

    public static function  checkcustomer_upline_tranfer_pv(Request $request)
    {
        //Request $request

        $rs_user_name_active = trim($request->user_name_active);
        $rs_user_use  = trim($request->user_use);
        if ($rs_user_name_active ==  $rs_user_use) {
            $data = ['status' => 'fail', 'ms' => 'ไม่สามารถทำรายการโอน PV ให้ตัวเองได้'];
            return $data;
        }


        if ($rs_user_use == '0534768' ||  $rs_user_name_active == '0534768') {

            $user_name_active =  DB::table('customers')
                ->select(
                    'customers.pv',
                    'customers.id',
                    'customers.name',
                    'customers.last_name',
                    'customers.user_name',
                    'customers.qualification_id',
                    'customers.expire_date',
                    'customers.expire_date_bonus',
                    'dataset_qualification.pv_active',
                    'customers.introduce_id',
                    'customers.status_customer'

                )
                ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                ->where('user_name', '=', $rs_user_name_active)
                ->first();


            if ($user_name_active) {
                $data = [
                    'status' => 'success',
                    'user_name' => $user_name_active->user_name,
                    'name' => $user_name_active->name,
                    'position' => $user_name_active->qualification_id,
                    'pv_active' => $user_name_active->pv_active,
                    'ms' => 'Success'
                ];
                return $data;
            } else {
                $data = ['status' => 'fail', 'ms' => 'ไม่พบรหัส'];
                return $data;
            }
        }

        $user =  DB::table('customers')
            ->select(
                'customers.pv',
                'customers.id',
                'customers.name',
                'customers.last_name',
                'customers.user_name',
                'customers.qualification_id',
                'customers.expire_date',
                'customers.expire_date_bonus',
                'dataset_qualification.pv_active',
                'customers.introduce_id'
            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $rs_user_use)
            ->first();
        $expire_date_1 = $user->expire_date;
        $expire_date_2 = $user->expire_date_bonus;

        if (strtotime($expire_date_1) >  strtotime($expire_date_2)) {
            $expire_date = $expire_date_1;
        } else {
            $expire_date = $expire_date_2;
        }


        if (empty($expire_date) || strtotime($expire_date) < strtotime(date('Ymd'))) {
            $data = ['status' => 'fail', 'ms' => 'รหัสของคุณไม่มีการ Active ไม่สามารถแจงให้รหัสอื่นได้'];
            return $data;
        }

        // $rs_user_use  = '7492038';
        // $rs_user_name_active = '9519863';
        $rs = array();

        $user_name_active =  DB::table('customers')
            ->select(
                'customers.pv',
                'customers.id',
                'customers.name',
                'customers.last_name',
                'customers.user_name',
                'customers.qualification_id',
                'customers.expire_date',
                'customers.expire_date_bonus',
                'dataset_qualification.pv_active',
                'customers.introduce_id',
                'customers.status_customer'

            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $rs_user_name_active)
            ->first();

        //9519863

        if (!empty($user_name_active)) {

            if ($user_name_active->status_customer != 'normal') {
                $data = ['status' => 'fail', 'ms' => 'รหัส ' . $rs_user_name_active . ' ถูกตัดออกจากระบบเเล้ว'];
                return $data;
            }
            $user_name_active->status_customer;
            $name = $user_name_active->name . ' ' . $user_name_active->last_name;
            if (empty($user_name_active->expire_date) || strtotime($user_name_active->expire_date) < strtotime(date('Ymd'))) {
                if (empty($user_name_active->expire_date)) {
                    $date_mt_active = 'Not Active';
                } else {
                    //$date_mt_active= date('d/m/Y',strtotime(Auth::guard('c_user')->user()->expire_date));
                    $date_mt_active = 'Not Active';
                }
                $status = 'danger';
            } else {
                $date_mt_active = 'Active ' . date('d/m/Y', strtotime(Auth::guard('c_user')->user()->expire_date));
                $status = 'success';
            }
            //ไหม่ ไม่ต้องตามสายงาน
            // $data = ['user_name' => $user_name_active->user_name, 'name' => $name, 'position' => $user_name_active->qualification_id, 'pv_active' => $user_name_active->pv_active, 'date_active' => $date_mt_active, 'ms' => 'Success'];
            // return $data;


            if ($user_name_active->user_name == $rs_user_use || $user_name_active->introduce_id == $rs_user_use) {

                $data = ['user_name' => $user_name_active->user_name, 'name' => $name, 'position' => $user_name_active->qualification_id, 'pv_active' => $user_name_active->pv_active, 'date_active' => $date_mt_active, 'ms' => 'Success'];
                return $data;
            } else {
                $i = 1;
                $user_name = $rs_user_use;

                while ($i <= 10) { //ค้นหาด้านบน 5 ชั้น
                    $up =  DB::table('customers')
                        ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id')
                        // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                        ->where('user_name', '=', $user_name)
                        ->first();

                    if (empty($up)) {
                        $status = 'fail';

                        //1240175
                        @$user_name = $up->introduce_id;
                        $rs = '';
                        $i = 10;
                        break;
                    }

                    if ($up->user_name ==  $rs_user_name_active || $up->introduce_id ==  $rs_user_name_active) {
                        $i = 10;
                        // dd($up->user_name);
                        //$rs[] = ['user_name' => $up->user_name, 'name' => $up->name, 'sponser' => $up->introduce_id, 'type' => 'up', 'status' => 'success'];
                        $status = 'success';
                        break;
                    } else {
                        @$user_name = $up->introduce_id;
                        // $rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'up','status'=>'fail'];
                        $rs = '';
                        $status = 'fail';
                        if (!empty($up->name)) {
                            $i++;
                        }
                    }
                }


                if ($status == 'fail') {
                    $j = 1;
                    $user_name = $user_name_active->introduce_id;
                    while ($j <= 10) { //ค้นหาด้านล่าง 5 ชั้น

                        $up =  DB::table('customers')
                            ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id')
                            // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                            ->where('user_name', '=', $user_name)
                            ->first();
                        // if($i==3){
                        //     dd($up,$i);
                        // }



                        if (empty($up)) {
                            $status = 'fail';
                            @$user_name = $up->introduce_id;
                            $rs = '';
                            //$rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'dow','status'=>'fail'];
                            $i = 10;
                            break;
                        }

                        if ($up->user_name == $rs_user_use || $up->introduce_id == $rs_user_use) {
                            $i = 10;
                            //$rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'dow','status'=>'success'];
                            $rs = '';
                            $status = 'success';
                            break;
                        } else {
                            @$user_name = $up->introduce_id;
                            //$rs[]=['user_name'=>$up->user_name,'name'=>$up->name,'sponser'=>$up->introduce_id,'type'=>'dow','status'=>'fail'];
                            $rs = '';
                            $status = 'fail';
                            if (!empty($up->name)) {
                                $j++;
                            }
                        }
                    }
                }
                // dd($rs);

                if ($status == 'fail') {
                    $data = ['status' => 'fail', 'rs' => $rs, 'ms' => 'รหัสสมาชิกไม่อยู่ในสายงานแนะนำ'];
                    return $data;
                } else {
                    $data = ['status' => 'success', 'user_name' => $user_name_active->user_name, 'name' => $name, 'position' => $user_name_active->qualification_id, 'pv_active' => $user_name_active->pv_active, 'date_active' => $date_mt_active, 'rs' => $rs, 'ms' => 'Success'];
                    return $data;
                }
            }



            // for($i=1;$i<=5;$i++){
            //     if()

            // }
        } else {
            $data = ['status' => 'fail', 'ms' => 'รหัสสมาชิกไม่ถูกต้อง'];
            return $data;
        }
    }






    public function withdraw(Request $request)
    {


        if ($request->amt <  300) {
            return redirect('home')->withError('ต้องมียอดขั้นต่ำในการถอน 300 บาท');
        }


        $customers_id_fk =  Auth::guard('c_user')->user()->id;
        $customer_withdraw = Customers::lockForUpdate()->where('id', $customers_id_fk)->first();

        $ewallet_use = Auth::guard('c_user')->user()->ewallet_use;
        $ewallet = Auth::guard('c_user')->user()->ewallet;
        $ewallet_tranfer = Auth::guard('c_user')->user()->ewallet_tranfer;

        if (($ewallet_use + $ewallet_tranfer)  > $ewallet) {
            $price_ewallet = Auth::guard('c_user')->user()->ewallet;
        } else {
            if ($ewallet_use >= 300) {
                $price_ewallet = $ewallet_use + $ewallet_tranfer;
            } else {
                if ($ewallet_tranfer >= 300) {
                    $price_ewallet = $ewallet_tranfer;
                } else {
                    $price_ewallet = 0;
                }
            }
        }

        if ($request->amt > $price_ewallet) {
            return response()->json(['status' => 'fail', 'ms' => 'ไม่สามารถถอนยอดเกิน' . $price_ewallet . 'บาท'], 200);
        }


        $expire_date_1 = $customer_withdraw->expire_date;
        $expire_date_2 = $customer_withdraw->expire_date_bonus;

        if (strtotime($expire_date_1) >  strtotime($expire_date_2)) {
            $expire_date = $expire_date_1;
        } else {
            $expire_date = $expire_date_2;
        }



        if (empty($expire_date) || strtotime($expire_date) < strtotime(date('Ymd'))) {
            return redirect('home')->withError('วันที่รักษายอดไม่เพียงพอ');
        } else {
            $y = date('Y') + 543;
            $y = substr($y, -2);


            if ($customer_withdraw->ewallet_use >= 300 || $customer_withdraw->ewallet_tranfer >= 300) {

                if ($customer_withdraw->ewallet_use >= 300) {
                    $ewallet_use =  $customer_withdraw->ewallet_use - $request->amt;

                    if ($ewallet_use < 0) {
                        $customer_withdraw->ewallet_use = 0;
                        $ewallet_tranfer = $customer_withdraw->ewallet_tranfer +  $ewallet_use;
                        if ($ewallet_tranfer < 0) {
                            return response()->json(['status' => 'fail', 'ms' => 'ยอดเงินฝากและโบนัสของคุณไม่เพียงต่อการถอนเงิน'], 200);
                        } else {

                            $customer_withdraw->ewallet_tranfer = $ewallet_tranfer;
                        }
                    } else {
                        $customer_withdraw->ewallet_use = $ewallet_use;
                    }
                } else {

                    $ewallet_tranfer =  $customer_withdraw->ewallet_tranfer - $request->amt;
                    if ($ewallet_tranfer < 0) {
                        $customer_withdraw->ewallet_tranfer = 0;
                        $ewallet_use = $customer_withdraw->ewallet_use +  $ewallet_tranfer;
                        if ($ewallet_use < 0) {
                            return response()->json(['status' => 'fail', 'ms' => 'ยอดเงินฝากและโบนัสของคุณไม่เพียงต่อการถอนเงิน'], 200);
                        } else {

                            $customer_withdraw->ewallet_use =  $ewallet_use;
                        }
                    } else {
                        $customer_withdraw->ewallet_tranfer = $ewallet_tranfer;
                    }
                }


                $ewallet =  $customer_withdraw->ewallet - $request->amt;

                $customer_withdraw->ewallet = $ewallet;


                if ($ewallet <= 0) {
                    $customer_withdraw->ewallet_use = 0;
                    $customer_withdraw->ewallet_tranfer = 0;
                }



                $transaction_code =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_bonus(2);


                $dataPrepare = [
                    'transaction_code' => $transaction_code,
                    'customers_id_fk' => $customers_id_fk,
                    'customer_username' => $customer_withdraw->user_name,
                    'old_balance' => $customer_withdraw->ewallet + $request->amt,
                    'balance' => $customer_withdraw->ewallet,
                    'amt' => $request->amt,
                    'type' => 3,
                    'status' => 1,
                ];

                try {
                    DB::BeginTransaction();
                    $customer_withdraw->save();
                    $query =  eWallet::create($dataPrepare);
                    DB::commit();
                    return redirect('home')->withSuccess('ทำรายการถอดสำเร็จ');
                } catch (\Exception $e) {
                    DB::rollback();
                    return redirect('home')->withSuccess('ทำรายการถอดไม่สำเร็จกรุณาทำรายการไหม่');
                }
            } else {
                DB::rollback();
                return redirect('home')->withSuccess('ยอดเงินฝากและโบนัสของคุณไม่เพียงต่อการถอนเงิน');
            }
        }
    }
}
