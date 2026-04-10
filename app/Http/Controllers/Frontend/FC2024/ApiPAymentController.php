<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\eWallet;
use App\eWallet_tranfer;
use App\Customers;

class ApiPAymentController extends Controller
{

    // public function payment_complete_backend(Request $rs)
    // {

    //     Log::channel('payment')->info('เข้าทุกกรณี:', ['data' => $rs->all()]);

    //     $response = Http::withHeaders([
    //         'Content-Type' => 'application/json',
    //         'x-api-key' => 'skey_test_22092WvG4sPGBeXC3ZfuF0UXNymik10cg4vgC',
    //     ])->get('https://dev-kpaymentgateway-services.kasikornbank.com/qr/v2/qr/' . $rs->chargeId);

    //     if ($response->successful()) {
    //         try {
    //             DB::BeginTransaction();
    //             $data = $response->json();

    //             if ($data['status'] == 'success') {
    //                 Log::channel('payment')->info('order:', ['data' => $data]);
    //                 $eWallet_tranfer = eWallet_tranfer::where('qr_id', $data['order_id'])->first();

    //                 $customers = Customers::where('user_name', $eWallet_tranfer->customer_username)->first();
    //                 $query_ewallet = eWallet_tranfer::where('id', $eWallet_tranfer->id)->first();

    //                 $dataPrepare = [
    //                     'receive_date' =>  now(),
    //                     'receive_time' => now(),
    //                     'code_refer' => $data['order_id'],
    //                     'balance' =>  $customers->ewallet,
    //                     'edit_amt' => 0,
    //                     'ew_mark' => 0,
    //                     'date_mark' => date('Y-m-d H:i:s'),
    //                     'status' => 2,
    //                 ];

    //                 $query_ewallet->update($dataPrepare);

    //                 if ($query_ewallet) {
    //                     $dataPrepare_update = [
    //                         'old_balance' => $customers->ewallet,
    //                         'balance' =>  $customers->ewallet + $eWallet_tranfer->amt
    //                     ];
    //                     $query_ewallet->update($dataPrepare_update);
    //                     if ($query_ewallet) {

    //                         $dataPrepare_update_ewallet = [
    //                             'ewallet' =>  $customers->ewallet + $eWallet_tranfer->amt
    //                         ];


    //                         $create_data = [
    //                             'transaction_code' => $eWallet_tranfer->transaction_code,
    //                             'customers_id_fk' =>  $eWallet_tranfer->customers_id_fk,
    //                             'customer_username' => $eWallet_tranfer->customer_username,
    //                             'amt' => $eWallet_tranfer->amt,
    //                             'old_balance' => $customers->ewallet,
    //                             'balance' =>  $customers->ewallet +  $eWallet_tranfer->amt,
    //                             'edit_amt' => 0,
    //                             'ew_mark' => 0,
    //                             'date_mark' => date('Y-m-d H:i:s'),
    //                             'type' => $eWallet_tranfer->type,
    //                             'status' => 2,
    //                         ];


    //                         eWallet::create($create_data);
    //                         Customers::where('id', $customers->id)->update($dataPrepare_update_ewallet);
    //                         DB::commit();
    //                         return redirect('eWallet-TranferHistory/success')->withSuccess('ชำระเงินสำเร็จ');
    //                         // return response()->json([
    //                         //     'status' => 'success',
    //                         //     'message' => 'ชำระเงินสำเร็จ'
    //                         // ], 200);
    //                     }
    //                 } else {
    //                     return redirect('eWallet-TranferHistory/fail')->withError('ชำระเงินไม่สำเร็จ');
    //                     // return response()->json([
    //                     //     'status' => 'fail',
    //                     //     'message' => 'ชำระเงินไม่สำเร็จ'
    //                     // ], 200);
    //                 }

    //                 return redirect('eWallet-TranferHistory/success')->withSuccess('ชำระเงินสำเร็จ');
    //                 // return response()->json([
    //                 //     'status' => 'success',
    //                 //     'message' => 'ชำระเงินสำเร็จ'
    //                 // ], 200);
    //             } else {
    //                 return redirect('eWallet-TranferHistory/fail')->withError('ชำระเงินไม่สำเร็จ');
    //                 // return response()->json([
    //                 //     'status' => 'fail',
    //                 //     'message' => 'ชำระเงินไม่สำเร็จ'
    //                 // ], 200);
    //             }
    //         } catch (\Exception $e) {

    //             Log::channel('payment')->info('Fail:', ['data' => $rs->all(), 'error' => $e->getMessage()]);
    //             return redirect('eWallet-TranferHistory/fail')->withError('ชำระเงินไม่สำเร็จ');
    //             // return response()->json([
    //             //     'status' => 'fail',
    //             //     'message' =>  $e->getMessage()
    //             // ], 200);
    //         }
    //     } else {
    //         return redirect('eWallet-TranferHistory/fail')->withError('ชำระเงินไม่สำเร็จ');
    //         // return response()->json([
    //         //     'status' => 'fail',
    //         //     'message' => 'ชำระเงินไม่สำเร็จ'
    //         // ], 200);
    //     }
    // }


    public function payment_complete_backend(Request $rs)
    {
        // Log การแจ้งเตือน
        Log::channel('payment')->info('เข้าทุกกรณี:', ['data' => $rs->all()]);

        // ส่ง Request ไปยัง KBank เพื่อดึงข้อมูลการชำระเงิน
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key' => 'skey_test_22092WvG4sPGBeXC3ZfuF0UXNymik10cg4vgC',
        ])->get('https://dev-kpaymentgateway-services.kasikornbank.com/qr/v2/qr/' . $rs->chargeId);

        // ตรวจสอบว่าส่ง request สำเร็จหรือไม่
        if ($response->successful()) {
            try {
                DB::BeginTransaction();
                $data = $response->json();

                if ($data['status'] == 'success') {
                    Log::channel('payment')->info('order:', ['data' => $data]);

                    // ค้นหา eWallet_tranfer โดยใช้ qr_id
                    $eWallet_tranfer = eWallet_tranfer::where('qr_id', $data['order_id'])->first();
                    // ค้นหาข้อมูลลูกค้า
                    $customers = Customers::where('user_name', $eWallet_tranfer->customer_username)->first();
                    // เตรียมข้อมูลสำหรับอัพเดท eWallet_tranfer
                    $dataPrepare = [
                        'receive_date' => now(),
                        'receive_time' => now(),
                        'code_refer' => $data['order_id'],
                        'balance' => $customers->ewallet,
                        'edit_amt' => 0,
                        'ew_mark' => 0,
                        'date_mark' => date('Y-m-d H:i:s'),
                        'status' => 2,
                    ];

                    // อัพเดท eWallet_tranfer
                    $query_ewallet = eWallet_tranfer::where('id', $eWallet_tranfer->id)->first();
                    $query_ewallet->update($dataPrepare);

                    // ถ้าอัพเดทสำเร็จ
                    if ($query_ewallet) {
                        $dataPrepare_update = [
                            'old_balance' => $customers->ewallet,
                            'balance' => $customers->ewallet + $eWallet_tranfer->amt
                        ];
                        $query_ewallet->update($dataPrepare_update);

                        if ($query_ewallet) {
                            // อัพเดทยอด eWallet ของลูกค้า
                            $dataPrepare_update_ewallet = [
                                'ewallet' => $customers->ewallet + $eWallet_tranfer->amt
                            ];
                            Customers::where('id', $customers->id)->update($dataPrepare_update_ewallet);

                            // สร้างรายการใน eWallet
                            $create_data = [
                                'transaction_code' => $eWallet_tranfer->transaction_code,
                                'customers_id_fk' => $eWallet_tranfer->customers_id_fk,
                                'customer_username' => $eWallet_tranfer->customer_username,
                                'amt' => $eWallet_tranfer->amt,
                                'old_balance' => $customers->ewallet,
                                'balance' => $customers->ewallet + $eWallet_tranfer->amt,
                                'edit_amt' => 0,
                                'ew_mark' => 0,
                                'date_mark' => date('Y-m-d H:i:s'),
                                'type' => $eWallet_tranfer->type,
                                'status' => 2,
                            ];
                            eWallet::create($create_data);
                            DB::commit();
                            // ตอบกลับไปยัง KBank ด้วยสถานะ 200
                            return response()->json([
                                'status' => 'success',
                                'message' => 'ชำระเงินสำเร็จ'
                            ], 200);
                        }
                    } else {
                        // ตอบกลับไปยัง KBank ด้วยสถานะล้มเหลว (แต่ยังส่ง status 200)
                        return response()->json([
                            'status' => 'fail',
                            'message' => 'ชำระเงินไม่สำเร็จ'
                        ], 200);
                    }
                } else {
                    // กรณีการชำระเงินไม่สำเร็จ
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'ชำระเงินไม่สำเร็จ'
                    ], 200);
                }
            } catch (\Exception $e) {
                Log::channel('payment')->info('Fail:', ['data' => $rs->all(), 'error' => $e->getMessage()]);
                return response()->json([
                    'status' => 'fail',
                    'message' => 'ชำระเงินไม่สำเร็จ'
                ], 200);
            }
        } else {
            // กรณีการเชื่อมต่อกับ KBank ไม่สำเร็จ
            return response()->json([
                'status' => 'fail',
                'message' => 'ชำระเงินไม่สำเร็จ'
            ], 200);
        }
    }



    public function api_payment_card(Request $rs)
    {

        //[2024-10-11 16:53:35] local.INFO: เข้าทุกกรณี: {"data":{"token":"tokn_test_22092355a4919b2833c6c8e2c44e98a7e5411","mid":"401618714478001","paymentMethods":"card"}} 


        Log::channel('payment')->info('เข้าทุกกรณี:', ['data' => $rs->all()]);
        $eWallet_tranfer = eWallet_tranfer::where('transaction_code', $rs->transaction_code)->first();
        if ($eWallet_tranfer) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => 'skey_test_22092WvG4sPGBeXC3ZfuF0UXNymik10cg4vgC',
            ])->post('https://dev-kpaymentgateway-services.kasikornbank.com/card/v2/charge', [
                "amount" =>  $eWallet_tranfer->amt,
                "currency" => "THB",
                "description" => "เติมเงินเข้า eWallet Credit",
                "source_type" => "card",
                "mode" => "token",
                "token" => $rs->token,

                // "customer_id" =>  $customers->user_name,
                "reference_order" => $eWallet_tranfer->transaction_code
            ]);

            if ($response->successful()) {
                $data = $response->json();

                try {
                    if ($data['status'] == 'success') {
                        Log::channel('payment')->info('order:', ['data' => $data]);

                        $customers = Customers::where('user_name', $eWallet_tranfer->customer_username)->first();
                        $query_ewallet = eWallet_tranfer::where('id', $eWallet_tranfer->id)->first();

                        $dataPrepare = [
                            'receive_date' =>  now(),
                            'receive_time' => now(),
                            'code_refer' => $data['reference_order'],
                            'balance' =>  $customers->ewallet,
                            'edit_amt' => 0,
                            'ew_mark' => 0,
                            'date_mark' => date('Y-m-d H:i:s'),
                            'status' => 2,
                        ];

                        $query_ewallet->update($dataPrepare);

                        if ($query_ewallet) {
                            $dataPrepare_update = [
                                'old_balance' => $customers->ewallet,
                                'balance' =>  $customers->ewallet + $eWallet_tranfer->amt
                            ];
                            $query_ewallet->update($dataPrepare_update);
                            if ($query_ewallet) {

                                $dataPrepare_update_ewallet = [
                                    'ewallet' =>  $customers->ewallet + $eWallet_tranfer->amt
                                ];


                                $create_data = [
                                    'transaction_code' => $eWallet_tranfer->transaction_code,
                                    'customers_id_fk' =>  $eWallet_tranfer->customers_id_fk,
                                    'customer_username' => $eWallet_tranfer->customer_username,
                                    'amt' => $eWallet_tranfer->amt,
                                    'old_balance' => $customers->ewallet,
                                    'balance' =>  $customers->ewallet +  $eWallet_tranfer->amt,
                                    'edit_amt' => 0,
                                    'ew_mark' => 0,
                                    'date_mark' => date('Y-m-d H:i:s'),
                                    'type' => $eWallet_tranfer->type,
                                    'status' => 2,
                                ];


                                eWallet::create($create_data);
                                Customers::where('id', $customers->id)->update($dataPrepare_update_ewallet);
                                DB::commit();
                                return redirect('eWallet-TranferHistory/success')->withSuccess('ชำระเงินสำเร็จ');
                                // return response()->json([
                                //     'status' => 'success',
                                //     'message' => 'ชำระเงินสำเร็จ'
                                // ], 200);
                            }
                        } else {
                            Log::channel('payment')->info('order:', ['data' => 'ชำระเงินไม่สำเร็จ COD03']);

                            return redirect('eWallet-TranferHistory/fail')->withError('ชำระเงินไม่สำเร็จ COD03');
                            // return response()->json([
                            //     'status' => 'fail',
                            //     'message' => 'ชำระเงินไม่สำเร็จ'
                            // ], 200);
                        }
                    } else {
                        Log::channel('payment')->info('order:', ['data' => 'ชำระเงินไม่สำเร็จ COD01']);
                        return redirect('eWallet-TranferHistory/fail')->withError('ชำระเงินไม่สำเร็จ COD01');
                    }
                } catch (Exception $e) {
                    Log::channel('payment')->info('order:', ['data' => $e->getMessage()]);
                    DB::rollback();
                    $data = $response->json();
                    return response()->json(['status' => 'fail', 'ms' => $e->getMessage()], 200);
                }
            }
        } else {
            Log::channel('payment')->info('order:', ['data' => 'ชำระเงินไม่สำเร็จ COD02']);
            return redirect('eWallet-TranferHistory/fail')->withError('ชำระเงินไม่สำเร็จ COD02');
        }
    }
}
