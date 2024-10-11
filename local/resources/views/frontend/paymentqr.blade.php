<title></title>

@extends('layouts.frontend.app')
@section('conten')
    <div class="bg-whiteLight page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ชำระเงิน</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <div class="card card-box borderR10 mb-2 mb-md-0">
                        <div class="card-body">
                            {{-- <h4 class="card-title">Register Url</h4>
                            <hr> --}}

                            <div class="col-lg-6">
                                <div class="card card-box borderR10 mb-2 mb-lg-0">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4 col-xxl-3 text-center">

                                                <div class="rounded-circle">


                                                    <img src="{{ asset('frontend/images/ThaiQR.jpg') }}" class="mw-100"
                                                        alt="" />

                                                </div>


                                            </div>
                                            <div class="col-8 col-xxl-9">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <?php
                                                        
                                                        $status = $data->status;
                                                        
                                                        if ($status == 1) {
                                                            $status = 'รอชำระเงิน';
                                                            $status_bg = 'primary';
                                                        }
                                                        if ($status == 2) {
                                                            $status = 'อนุมัติ';
                                                            $status_bg = 'success';
                                                        }
                                                        if ($status == 3) {
                                                            $status = 'ไม่อนุมัติ';
                                                            $status_bg = 'danger';
                                                        }
                                                        if ($status == 4) {
                                                            $status = 'ยกเลิก';
                                                            $status_bg = 'danger';
                                                        }
                                                        
                                                        
                                                        ?>
                                                        <span
                                                            class="badge rounded-pill bg-{{$status_bg}} bg-opacity-20 text-{{$status_bg}} fw-light ps-1">
                                                            <i class="fas fa-circle text-{{$status_bg}}"></i> {{$status}}
                                                        </span>

                                                    </div>
                                                    <div class="col-6 text-end">
                                                        {{-- <a type="button" class="btn btn-warning px-2"
                                                            href="http://localhost/vrich/editprofile"><i
                                                                class="bx bxs-edit"></i></a> --}}
                                                    </div>
                                                </div>


                                                <h5>USERNAME :
                                                    {{ Auth::guard('c_user')->user()->user_name }}

                                                    ({{ Auth::guard('c_user')->user()->qualification_id }})</h5>
                                                <h5> {{ Auth::guard('c_user')->user()->name }}
                                                    {{ Auth::guard('c_user')->user()->last_name }}</h5>
                                                <hr>
                                                <p>
                                                    <span class="label-xs">Transaction:</span> <span class="label-xs">{{$data->transaction_code}}</span>

                                                    <br>
                                                    <span class="label-xs">บิลวันที่:</span> <span class="label-xs">{{date('Y/m/d H:i:s',strtotime($data->created_at))}}</span>
                                                    <br>
                                                    <br>
                                                    <span class="label-xs">ยอดชำระ:</span> <span class="label-xs">{{$data->amt}}</span>
                                                    
                                                </p>
                                                       <form method="POST" action="{{url('api_payment_test')}}">
                                                        <script type="text/javascript"
                                                            src="https://dev-kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js"
                                                            data-apikey="pkey_test_22092qPHuA2b43plEIhcAwtNzIvQ2FPwEs7zC"
                                                            data-amount="{{$data->amt}}"
                                                            data-payment-methods="qr"
                                                            data-order-id="{{$data->qr_id}}">
                                                        </script>
                                                        </form>  
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <span class="label-xs"> วันที่ทำรายการ </span>
 
                                        <span class="badge bg-light text-dark fw-light"> {{date('Y/m/d H:i:s')}}</span>

 
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
