<title>บริษัท มารวยด้วยกัน จำกัด</title>

@extends('layouts.frontend.app')
@section('conten')
    <div class="bg-whiteLight page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">หน้าแรก</a></li>
                            <li class="breadcrumb-item"><a href="{{route('order_history')}}">ประวัติการสั่งซื้อ</a></li>
                            <li class="breadcrumb-item active text-truncate" aria-current="page">เลขที่ออเดอร์
                                {{$orders_detail[0]->code_order}}</li>

                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a href="{{route('order_history')}}" class="btn btn-sm rounded-pill btn-outline-dark mb-1"><i
                            class="fas fa-angle-left me-2"></i> ย้อนกลับ</a>
                    <div class="card card-box borderR10 mb-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="card-title mb-0"><i class="fas fa-truck"></i> สถานะ <span class="badge bg-{{$orders_detail[0]->css_class}} fw-light">{{$orders_detail[0]->detail}}</span></h5>
                                </div>
                                {{-- <div class="col-12">
                                    <p class="text-muted mb-0">Kerry Express - kerry123456</p>
                                    <button type="button" class="btn btn-secondary btn-sm mb-1" data-bs-toggle="modal"
                                        data-bs-target="#trackModal">ดูรายละเอียด</button>
                                    <ul class="timeline">
                                        <li class="timeline-item">
                                            <div class="timeline-marker"></div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title text-p1 mb-0">พัสดุถึงศูนย์คัดแยก</h6>
                                                <p class="text-muted small">22-06-2565 12:14</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div> --}}
                                <hr>
                                <div class="col-12">{{$orders_detail[0]->code_order}}
                                    <h5 class="card-title mb-2"><i class="fas fa-map-marker-alt"></i>
                                        ที่อยู่ในการจัดส่ง</h5>

                                @if ($orders_detail[0]->address)
                                <?php $address = $orders_detail[0]->address[0];   ?>
                                <p><b>{{ $orders_detail[0]->name }}</b><br>
                                    @if ($address->tel) Tel: {{ $address->tel }} <br>@endif

                                    @if ($address->house_no) {{ $address->house_no }} @endif
                                    @if ($address->moo != '-' and $address->moo != '') หมู่.{{ $address->moo }} @endif
                                    @if ($address->house_name != '-' and $address->house_name != '') บ.{{ $address->house_name }} @endif
                                    @if ($address->soi != '-' and $address->soi != '') ซอย.{{ $address->soi }} @endif
                                    @if ($address->road != '-' and $address->road != '') ถนน.{{ $address->road }} @endif
                                    @if ($address->district != '-' and $address->district != '') ต.{{ $address->district }} @endif
                                    @if ($address->tambon != '-' and $address->tambon != '') อ.{{ $address->tambon }} @endif
                                    {{-- @if ($address->province != '-' and $address->province != '') จ.{{ $address->province }} @endif --}}
                                    @if ($address->zipcode) {{ $address->zipcode }}@endif
                                </p>
                            @else
                                <p><b> Address Is Null</b>
                            @endif


                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-box borderR10 mb-2">
                        <div class="card-body">
                            <div class="cardL-cart">
                                <div class="row">
                                    <div class="col-3 col-lg-2">
                                        <img src="{{ asset('frontend/images/product_sample1.jpg') }}" class="mw-100 mb-2">
                                    </div>
                                    <div class="col-9 col-lg-10">
                                        <p class="fs-12 text-muted mb-0">Size XL (20+1), Pure</p>
                                        <h6>XL1</h6>
                                        <div class="text-end">
                                            <button type="button" class="btn btn-outline-secondary px-2 py-1">x1</button>
                                            <p class="mb-0">5000.00 บาท</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-4">
                                    <p class="mb-2">ราคา</p>
                                </div>
                                <div class="col-8 text-end">
                                    <p class="mb-2">5000.00 บาท</p>
                                </div>
                                <div class="col-4">
                                    <p class="mb-2">Pont รวม</p>
                                </div>
                                <div class="col-8 text-end">
                                    <p class="mb-2">250</p>
                                </div>
                                <div class="col-4">
                                    <p class="mb-2">ค่าส่ง</p>
                                </div>
                                <div class="col-8 text-end">
                                    <p class="mb-2">0.00 บาท</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-4">
                                    <p class="mb-2">ราคาสุทธิ</p>
                                </div>
                                <div class="col-8 text-end">
                                    <p class="mb-2 text-purple1"><span class="text-p1 h5">5000.00</span> บาท</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-box borderR10 mb-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h5>วิธีการชำระเงิน</h5>
                                    <p class="text-muted mb-0">หักเงิน eWallet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-box borderR10 mb-2">
                        <div class="card-body">
                            <div class="row gx-2">
                                <div class="col-5">
                                    <dt>หมายเลขคำสั่งซื้อ</dt>
                                </div>
                                <div class="col-7 text-end">
                                    <dd>mdk65-060001</dd>
                                </div>
                                <div class="col-5">
                                    <dt>เวลาที่สั่งซื้อ</dt>
                                </div>
                                <div class="col-7 text-end">
                                    <dd>21/6/2565 15:00</dd>
                                </div>
                                <div class="col-5">
                                    <dt>เวลาชำระเงิน</dt>
                                </div>
                                <div class="col-7 text-end">
                                    <dd>21/6/2565 15:10</dd>
                                </div>
                                <div class="col-5">
                                    <dt>เวลาส่งสินค้า</dt>
                                </div>
                                <div class="col-7 text-end">
                                    <dd>22/6/2565 9:00</dd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="trackModal" tabindex="-1" aria-labelledby="trackModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="trackModalLabel">สถานะการจัดส่ง</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title text-p1 mb-0">พัสดุถึงศูนย์คัดแยก</h6>
                                <p class="text-muted small">22-06-2565 12:14</p>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title text-p1 mb-0">พัสดุถึงศูนย์คัดแยก</h6>
                                <p class="text-muted small">22-06-2565 12:14</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#linkMenuTop .nav-item').eq(1).addClass('active');
    </script>
    <script>
        $('.page-content').css({
            'min-height': $(window).height() - $('.navbar').height()
        });
    </script>
@endsection
