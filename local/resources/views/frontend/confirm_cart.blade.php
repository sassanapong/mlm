    <title>บริษัท มารวยด้วยกัน จำกัด</title>

    @extends('layouts.frontend.app')
    @section('conten')
        <div class="bg-whiteLight page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('Order') }}">รายการสินค้า</a></li>
                                <li class="breadcrumb-item active text-truncate" aria-current="page"> ยืนยันรายการสินค้า
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-box borderR10 mb-2 mb-md-0">

                            <div class="card-body">

                                <form action="{{ route('payment_submit') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-md-8 col-sm-12">


                                        <div class="card card-box borderR10 mb-2 mb-md-0">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                <h4 class="card-title">รูปแบบการจัดส่ง</h4>
                                                <div class="row g-3">
                                                    <div class="col-md-12 col-xl-12 mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input radio" type="radio"
                                                                name="sent_type_to_customer" id="sent_type_customer"
                                                                onclick="sent_type('customer')" value="sent_type_customer"
                                                                checked="checked">
                                                            <label class="form-check-label"
                                                                for="option1R">ซื้อให้ตัวเอง</label>
                                                        </div>
                                                        {{-- <div class="form-check form-check-inline">
                                                            <input class="form-check-input radio" type="radio"
                                                                name="sent_type_to_customer" value="sent_type_other"
                                                                id="sent_type_other" onclick="sent_type('other')">
                                                            <label class="form-check-label"
                                                                for="option2R">ซื้อให้ลูกทีม</label>
                                                        </div> --}}

                                                    </div>
                                                </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <h4 class="card-title">เลือกจัดส่งสินค้าโดย </h4>
                                                        <div class="row g-3">
                                                            <div class="col-md-12 col-xl-12 mb-3">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input radio" type="radio"
                                                                        name="tracking_type"
                                                                         value="Kerry"
                                                                        checked="checked">
                                                                    <label class="form-check-label"
                                                                        for="option1R">Kerry</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input radio" type="radio"
                                                                        name="tracking_type" value="Flash">
                                                                    <label class="form-check-label"
                                                                        for="option2R">Flash </label>
                                                                </div>
                                                                {{-- <div class="form-check form-check-inline">
                                                                    <input class="form-check-input radio" type="radio"
                                                                        name="tracking_type" value="Ems">
                                                                    <label class="form-check-label"
                                                                        for="option2R">Ems </label>
                                                                </div> --}}


                                                            </div>
                                                        </div>
                                                            </div>

                                            </div>




                                                <div class="row">
                                                    <div class="col-md-5 col-xl-5 mb-3" id="check_user"
                                                        style="display: none">
                                                        <div class="card">

                                                            <div class="card-body">
                                                                <h6>รหัสของลูกทีมซื้อสินค้า(PV เป็นของลูกทีม)</h6>
                                                                <div class="input-group mb-3">
                                                                    <input type="text" id="username"
                                                                        class="form-control" placeholder="รหัสสมาชิก"
                                                                        aria-label="Username"
                                                                        aria-describedby="button-addon2">
                                                                    <button class="btn btn-primary" type="button"
                                                                        onclick="check()">ยืนยันรหัสสมาชิก</button>
                                                                </div>
                                                                <span style="font-size: 12px"
                                                                    class="text-danger">*คะแนนการสั่งซื้อจะถูกใช้ให้กับลูกทีมที่เลือก</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-7 col-xl-7 mb-3" id="data_direct"
                                                        style="display: none">
                                                        <div class="card">

                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-4 col-xxl-3">
                                                                        <div class="ratio ratio-1x1">
                                                                            <div class="rounded-circle">
                                                                                <img src="{{ asset('/frontend/images/man.png') }}"
                                                                                    class="mw-100" alt="">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-8 col-xxl-9">

                                                                        <p id="text_username">รหัสสมาชิก : PS97 (ตำแหน่ง)
                                                                        </p>
                                                                        <p id="text_name"> จันทราวรรณ</p>
                                                                            {{-- <p class="fs-12">
                                                                                รักษาสภาพสมาชิกมาแล้ว
                                                                                <span class="badge rounded-pill bg-light text-dark fw-light">
                                                                                    56 วัน
                                                                                </span>
                                                                            </p> --}}
                                                                            <input type="hidden"
                                                                                name="customers_sent_id_fk"
                                                                                id="customers_sent_id_fk"
                                                                                value="">
                                                                            <input type="hidden"
                                                                                name="customers_sent_user_name"
                                                                                id="customers_sent_user_name"
                                                                                value="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>


                                                <h4 class="card-title">ที่อยู่ผู้รับสินค้า</h4>
                                                <div class="row g-3">
                                                        <div class="col-md-12 col-xl-12 mb-3">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input radio" type="radio" onchange="sent_address('sent_address')"
                                                                id="sent_address_check" name="receive" value="sent_address"
                                                                checked="checked">
                                                                <label class="form-check-label" for="option1R">จัดส่ง</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input radio" type="radio" id="sent_other"
                                                                onchange="sent_address('sent_other')" name="receive"
                                                                value="sent_address_other">
                                                                <label class="form-check-label" for="option2R">อื่นๆ</label>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    @if (@$address->province_id)
                                                    <div id="i_sent_address">
                                                        <div class="row g-3" >
                                                            <div class="col-md-4 col-xl-4">
                                                                <label for="" class="form-label">ชื่อผู้รับ <span
                                                                        class="text-danger same_address_err _err">*</span></label>
                                                                <input type="text" name="name" class="form-control "
                                                                    id="" value="{{ $customer->prefix_name }} {{ $customer->name }} {{ $customer->last_name }}" required>
                                                            </div>

                                                            <div class="col-md-4 col-xl-4">
                                                                <label for="" class="form-label">โทรศัพท์ <span class="text-danger phone_err _err">*</span></label>
                                                                <input name="phone" type="text" class="form-control" name="phone" value="{{ @$address->phone }}">
                                                            </div>

                                                            <div class="col-md-4 col-xl-4">
                                                            </div>

                                                            <div class="col-md-3 col-xl-3">
                                                                <label for="" class="form-label">ที่อยู่ <span
                                                                        class="text-danger same_address_err _err">*</span></label>
                                                                <input type="text" name="house_no" class="form-control"
                                                                value="{{ @$address->address }}" readonly="">
                                                            </div>
                                                            <div class="col-md-2 col-xl-2">
                                                                <label for="" class="form-label">หมู่ที่ <span
                                                                        class="text-danger same_moo_err _err">*</span></label>
                                                                <input type="text" name="moo" class="form-control"
                                                                value="{{ @$address->moo }}" readonly="">
                                                            </div>
                                                            <div class="col-md-3 col-xl-3">
                                                                <label for="" class="form-label">ซอย <span
                                                                        class="text-danger same_soi_err _err">*</span></label>
                                                                <input type="text" name="soi" value="{{ @$address->soi }}" class="form-control"
                                                                readonly="">
                                                            </div>
                                                            <div class="col-md-4 col-xl-4">
                                                                <label for="" class="form-label">ถนน <span
                                                                        class="text-danger same_road_err _err">*</span></label>
                                                                <input type="text" name="road" class="form-control"
                                                                value="{{ @$address->road }}" readonly="">
                                                            </div>
                                                            <div class="col-md-6 col-xl-4">
                                                                <label for="province" class="form-label">จังหวัด</label>
                                                                <label class="form-label text-danger same_province_err _err"></label>


                                                                <input name="province_id" type="hidden" class="form-control"
                                                                value="{{ @$address->province_id }}" >
                                                                <input type="text"  class="form-control"
                                                                value="{{@$address->province_name}}" readonly="">


                                                            </div>
                                                            <div class="col-md-6 col-xl-4">

                                                                <label for="district" class="form-label">อำเภอ/เขต</label>
                                                                <label class="form-label text-danger same_district_err _err"></label>


                                                                <input name="district_id" type="hidden" class="form-control"
                                                                value="{{ @$address->district_id }}">
                                                                <input type="text"  class="form-control"
                                                                value="{{@$address->district_name}}" readonly="">
                                                            </div>
                                                            <div class="col-md-6 col-xl-4">
                                                                <label for="tambon" class="form-label">ตำบล</label>
                                                                <label class="form-label text-danger same_tambon_err _err"></label>

                                                                <input name="tambon_id" type="hidden" class="form-control"
                                                                value="{{ @$address->tambon_id }}" >
                                                                <input type="text"  class="form-control"
                                                                value="{{@$address->tambon_name}}" readonly="">
                                                            </div>
                                                            <div class="col-md-6 col-xl-4">
                                                                <label for="" class="form-label">รหัสไปรษณีย์ <span
                                                                        class="text-danger same_zipcode_err _err ">*</span></label>
                                                                <input id="zipcode" name="zipcode" type="text"
                                                                    class="form-control"  value="{{ @$address->zipcode }}"  readonly>
                                                            </div>

                                                        </div>

                                                    </div>

                                                    @else
                                                    <div id="i_sent_address">

                                                        <div class="alert alert-warning icons-alert">

                                                            <p><strong>Warning!</strong> <code>ไม่มีข้อมูลที่อยู่การจัดส่งสินค้า
                                                                    กรุณาตั้งค่าก่อนชำระเงิน</code> <a
                                                                    href="{{ route('editprofile') }}"
                                                                    class="pcoded-badge label label-warning ">ตั้งค่า คลิ๊ก!!</a></p>
                                                        </div>

                                                    </div>

                                                @endif
                                                <div id="i_sent_other" style="display: none;">


                                                    <div class="row g-3" >
                                                        <div class="col-md-4 col-xl-4">
                                                            <label for="" class="form-label">ชื่อผู้รับ <span
                                                                    class="text-danger same_address_err _err">*</span></label>
                                                            <input type="text" name="sam_name" class="form-control "
                                                                id="" >
                                                        </div>


                                                        <div class="col-md-6 col-xl-4 mb-3">
                                                            <label for="" class="form-label">โทรศัพท์ </label>
                                                            <input type="text" name="same_phone" class="form-control address_same_card"
                                                                id="">
                                                        </div>
                                                        <div class="col-md-6 col-xl-5">
                                                            <label for="" class="form-label">ที่อยู่ <span
                                                                    class="text-danger same_address_err _err">*</span></label>
                                                            <input type="text" name="same_address" class="form-control address_same_card"
                                                                id="">
                                                        </div>
                                                        <div class="col-md-6 col-xl-3">
                                                            <label for="" class="form-label">หมู่ที่ <span
                                                                    class="text-danger same_moo_err _err">*</span></label>
                                                            <input type="text" name="same_moo" class="form-control address_same_card"
                                                                id="">
                                                        </div>
                                                        <div class="col-md-6 col-xl-4">
                                                            <label for="" class="form-label">ซอย <span
                                                                    class="text-danger same_soi_err _err">*</span></label>
                                                            <input type="text" name="same_soi" class="form-control address_same_card"
                                                                id="">
                                                        </div>
                                                        <div class="col-md-6 col-xl-4">
                                                            <label for="" class="form-label">ถนน <span
                                                                    class="text-danger same_road_err _err">*</span></label>
                                                            <input type="text" name="same_road" class="form-control address_same_card"
                                                                id="">
                                                        </div>
                                                        <div class="col-md-6 col-xl-4">
                                                            <label for="province" class="form-label">จังหวัด</label>
                                                            <label class="form-label text-danger same_province_err _err"></label>
                                                            <select class="form-select address_same_card select_same" name="same_province"
                                                                id="same_province">
                                                                <option value="">--กรุณาเลือก--</option>
                                                                @foreach ($province as $item)
                                                                    <option value="{{ $item->province_id }}">
                                                                        {{ $item->province_name }}</option>
                                                                @endforeach
                                                            </select>


                                                        </div>
                                                        <div class="col-md-6 col-xl-4">

                                                            <label for="district" class="form-label">อำเภอ/เขต</label>
                                                            <label class="form-label text-danger same_district_err _err"></label>
                                                            <select class="form-select address_same_card select_same" name="same_district"
                                                                id="same_district" disabled readonly>
                                                                <option value="">--กรุณาเลือก--</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 col-xl-4">
                                                            <label for="tambon" class="form-label">ตำบล</label>
                                                            <label class="form-label text-danger same_tambon_err _err"></label>
                                                            <select class="form-select address_same_card select_same" name="same_tambon"
                                                                id="same_tambon" disabled readonly>
                                                                <option value="">--กรุณาเลือก--</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 col-xl-4">
                                                            <label for="" class="form-label">รหัสไปรษณีย์ <span
                                                                    class="text-danger same_zipcode_err _err ">*</span></label>
                                                            <input id="same_zipcode" name="same_zipcode" type="text"
                                                                class="form-control address_same_card" id="">
                                                        </div>

                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">

                                        <div class="card card-box borderR10 mb-2 mb-md-0">
                                            <div class="card-body">
                                                <div class="row">

                                                        <h4>วิธีการชำระเงิน</h4>
                                                        <div class="col-12 col-md-12">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="type_pay"
                                                                id="flexRadioDefault1" value="e-wallet" checked>
                                                            <label class="form-check-label" for="flexRadioDefault1">
                                                                หักเงิน eWallet
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-8 col-lg-8">

                                                            <div class="card cardL card-body borderR10 bg-warning bg-opacity-20 mb-2 mb-md-3">
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0">
                                                                        <div class="bg-warning borderR8 iconFlex">
                                                                            <i class="bx bxs-wallet"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h5>eWallet  คงเหลือ</h5>
                                                                        <h5 class="text-p1  mb-0 fw-bold">{{number_format(Auth::guard('c_user')->user()->ewallet,2)}}</h5>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                    </div>
                                                    </div>

                                                <h4>สรุปรายการสั่งซื้อ</h4>
                                                <hr>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-2">มูลค่าสินค้า
                                                            ({{ Cart::session(1)->getTotalQuantity() }}) ชิ้น</p>
                                                    </div>
                                                    <div class="col-md-6 text-md-end">
                                                        <p class="mb-2">
                                                            {{ number_format(Cart::session(1)->getTotal() / 1.07, 2) }} บาท</p>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <p class="mb-2">Vat 7%</p>
                                                    </div>
                                                    <div class="col-md-6 text-md-end">
                                                        <p class="mb-2">
                                                            {{ number_format(Cart::session(1)->getTotal() - Cart::session(1)->getTotal() / 1.07, 2) }}
                                                            บาท</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-2">มูลค่าสินค้า + Vat</p>
                                                    </div>
                                                    <div class="col-md-6 text-md-end">
                                                        <p class="mb-2">{{ number_format(Cart::session(1)->getTotal()) }}
                                                            บาท</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-2">PV รวม</p>
                                                    </div>
                                                    <div class="col-md-6 text-md-end">
                                                        <?php
                                                        $cartCollection = Cart::session(1)->getContent();
                                                        $data = $cartCollection->toArray();
                                                        if ($data) {
                                                            foreach ($data as $value) {
                                                                $pv[] = $value['quantity'] * $value['attributes']['pv'];
                                                            }
                                                            $pv_total = array_sum($pv);
                                                        } else {
                                                            $pv_total = 0;
                                                        }

                                                        ?>
                                                        <p class="mb-2">{{ number_format($pv_total) }} PV</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-2">ค่าส่ง</p>
                                                    </div>
                                                    <div class="col-md-6 text-md-end">
                                                        <p class="mb-2" id="total_shipping">{{$bill['shipping']}} บาท</p>
                                                    </div>
                                                    @if($shipping_zipcode['status'] == 'success')
                                                    <div class="col-md-12 text-end" id="shipping_text">

                                                        <span class="badge rounded-pill bg-danger bg-opacity-20 text-danger fw-light ps-3">
                                                            พื้นที่หางไกลเพิ่ม 50 บาท
                                                        </span>
                                                    </div>
                                                    @else
                                                    <div class="col-md-12  text-end" id="shipping_text">
                                                        <span class="badge rounded-pill bg-primary bg-opacity-20 text-primary fw-light ps-3">
                                                            จัดส่งทั่วไป
                                                        </span>


                                                    </div>
                                                    @endif

{{--
                                                    <div class="col-md-8">
                                                        <p class="mb-2">ส่วนลดประจำตำแหน่ง( {{$bill['position']}} {{$bill['bonus']}} %)</p>
                                                    </div>
                                                    <div class="col-md-4 text-md-end">
                                                        <p class="mb-2">{{number_format($bill['discount'])}} บาท</p>
                                                    </div> --}}
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-2">ราคาสุทธิ</p>
                                                    </div>
                                                    <div class="col-md-6 text-md-end">
                                                        <p class="mb-2 text-purple1"><span
                                                                class="text-p1 h5" id="price_total">{{ number_format($bill['price_total']) }}</span>
                                                            บาท</p>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    @if($pv_total < 100)

                                                    <a href="{{ route('Order') }}" type="button"
                                                        class="btn btn-warning rounded-pill w-100 mb-2 justify-content-center text-danger"> ขั้นต่ำการทำรายการสั่งซื้อ 100 PV.</a>
                                                    @else
                                                    <button type="submit"
                                                    class="btn btn-p1 rounded-pill w-100 mb-2 justify-content-center">ยืนยันคำสั่งซื้อ</button>
                                                <a href="{{ route('cancel_order') }}" type="button"
                                                    class="btn btn-outline-dark rounded-pill w-100 mb-2 justify-content-center">ยกเลิก</a>
                                                    @endif


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>



        <div class="modal fade" id="modal_check" tabindex="-1" aria-labelledby="depositModal3Label"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content borderR25">
                    <div class="modal-header justify-content-center">
                        <h5 class="modal-title" id="depositModal3Label">ซื้อให้ลูกทีม</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4 col-xxl-3">
                                <div class="ratio ratio-1x1">
                                    <div class="rounded-circle">
                                        <img src="{{ asset('/frontend/images/man.png') }}" class="mw-100"
                                            alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-8 col-xxl-9">



                                <p id="modal_text_username">รหัสสมาชิก : PS97 (ตำแหน่ง)</p>
                                <p id="modal_name"> จันทราวรรณ
                                </p>
                                {{-- <p class="fs-12">
                                รักษาสภาพสมาชิกมาแล้ว
                                <span class="badge rounded-pill bg-light text-dark fw-light">
                                    56 วัน
                                </span>
                            </p> --}}
                            </div>
                        </div>



                    </div>
                    <div class="modal-footer justify-content-center border-0">


                        <div id="confirm_pay_sent_customer"></div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
        <script>
            function sent_type(type) {
                if (type == 'other') {

                    document.getElementById("check_user").style.display = "block";

                } else {
                    document.getElementById("check_user").style.display = "none";
                    document.getElementById("data_direct").style.display = "none";

                }
            }


            function check() {

                var username = $('#username').val();

                $.ajax({
                        url: '{{ route('check_custome_unline') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            'user_name': username
                        }
                    })
                    .done(function(data) {

                        if (data['status'] == 'success') {

                            document.getElementById("modal_text_username").innerHTML = 'รหัสสมาชิก : ' + data['data'][
                                'user_name'
                            ] + ' (' + data['data']['qualification_name'] + ')';
                            document.getElementById("modal_name").innerHTML = 'คุณ ' + data['data']['name'] + ' ' + data[
                                'data']['last_name'];


                            var sent_to_customer_username = data['data']['user_name'];
                            document.getElementById("confirm_pay_sent_customer").innerHTML =
                                '<button type="button" class="btn btn-p1 rounded-pill"  onclick="data_direct_confirm(\'' +
                                sent_to_customer_username + '\')">ยืนยันรหัสสมาชิก</button>';


                            $("#modal_check").modal('show');
                            //  alert(data['status']);

                        } else {

                            //console.log(data);
                            Swal.fire({
                                icon: 'error',
                                text: data['ms'],
                            })
                        }
                    })
                    .fail(function() {
                        console.log("error");
                    })
            }

            function data_direct_confirm(sent_to_customer_username) {

                var username = $('#username').val();

                $.ajax({
                        url: '{{ route('check_custome_unline') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            'user_name': username
                        }
                    })
                    .done(function(data) {

                        if (data['status'] == 'success') {

                            document.getElementById("text_username").innerHTML = 'รหัสสมาชิก : ' + data['data'][
                                'user_name'] + ' (' + data['data']['qualification_name'] + ')';
                            document.getElementById("text_name").innerHTML = 'คุณ ' + data['data']['name'] + ' ' + data[
                                'data']['last_name'];

                            $('#customers_sent_user_name').val(data['data']['user_name']);
                            $('#customers_sent_id_fk').val(data['data']['id']);

                            $('#modal_check').modal('hide');
                            document.getElementById("data_direct").style.display = "block";

                            //  alert(data['status']);
                        } else {

                            //console.log(data);
                            Swal.fire({
                                icon: 'error',
                                text: data['ms'],
                            })
                        }
                    })
                    .fail(function() {
                        console.log("error");
                    })
            }

            function sent_address(type){

                if(type =='sent_other'){

                    document.getElementById("i_sent_address").style.display = "none";
                    document.getElementById("i_sent_other").style.display = "block";
                    var same_zipcode = $("#same_zipcode").val();
                    if(same_zipcode!=''){

                            $.ajax({
                            url: '{{ route('fc_shipping_zip_code_js') }}',
                            type: 'GET',
                            data: {
                                _token: '{{ csrf_token() }}',
                                zip_code: same_zipcode,
                                price_shipping_pv:"{{$bill['price_shipping_pv']}}",
                                price_discount:"{{$bill['price_discount']}}",
                            }
                        })
                        .done(function(data) {

                           $("#total_shipping").html(data['total_shipping']);
                           $("#price_total").html(data['price_total']);


                            if(data['status'] == 'success'){
                                        document.getElementById("shipping_text").innerHTML = '<span class="badge rounded-pill bg-danger bg-opacity-20 text-danger fw-light ps-3">พื้นที่หางไกลเพิ่ม 50 บาท</span>';
                                    }else{
                                        document.getElementById("shipping_text").innerHTML = '<span class="badge rounded-pill bg-primary bg-opacity-20 text-primary fw-light ps-3">จัดส่งทั่วไป</span>';

                                    }
                        })


                    }else{
                        document.getElementById("shipping_text").innerHTML = '';
                    }

                }else{
                    var zipcode = $("#zipcode").val();

                    if(zipcode!=''){

                            $.ajax({
                            url: '{{ route('fc_shipping_zip_code_js') }}',
                            type: 'GET',
                            data: {
                                _token: '{{ csrf_token() }}',
                                zip_code: zipcode,
                                price_shipping_pv:"{{$bill['price_shipping_pv']}}",
                                price_discount:"{{$bill['price_discount']}}",
                            }
                        })
                        .done(function(data) {
                            $("#total_shipping").html(data['total_shipping']);
                            $("#price_total").html(data['price_total']);
                            if(data['status'] == 'success'){
                                        document.getElementById("shipping_text").innerHTML = '<span class="badge rounded-pill bg-danger bg-opacity-20 text-danger fw-light ps-3">พื้นที่หางไกลเพิ่ม 50 บาท</span>';
                                    }else{
                                        document.getElementById("shipping_text").innerHTML = '<span class="badge rounded-pill bg-primary bg-opacity-20 text-primary fw-light ps-3">จัดส่งทั่วไป</span>';

                                    }
                        })


                    }else{
                        document.getElementById("shipping_text").innerHTML = '';
                    }
                    document.getElementById("i_sent_address").style.display = "block";
                    document.getElementById("i_sent_other").style.display = "none";

                }

            }
        </script>

<script>
    // BEGIN province
    $("#same_province").change(function() {
        let province_id = $(this).val();
        $.ajax({
            url: '{{ route('getDistrict') }}',
            type: 'GET',
            dataType: 'json',
            data: {
                province_id: province_id,
            },
            success: function(data) {
                $("#same_district").children().remove();
                $("#same_tambon").children().remove();
                $("#same_district").append(` <option value="">--กรุณาเลือก--</option>`);
                $("#same_tambon").append(` <option value="">--กรุณาเลือก--</option>`);
                $("#same_zipcode").val("");
                data.forEach((item) => {
                    $("#same_district").append(
                        `<option value="${item.district_id}">${item.district_name}</option>`
                    );
                });
                $("#same_district").attr('disabled', false);
                $("#same_tambon").attr('disabled', true);

            },
            error: function() {}
        })
    });
    // END province

    // BEGIN district
    $("#same_district").change(function() {

        let district_id = $(this).val();
        $.ajax({
            url: '{{ route('getTambon') }}',
            type: 'GET',
            dataType: 'json',
            data: {
                district_id: district_id,
            },
            success: function(data) {

                $("#same_tambon").children().remove();
                $("#same_tambon").append(` <option value="">--กรุณาเลือก--</option>`);
                $("#same_zipcode").val("");
                data.forEach((item) => {
                    $("#same_tambon").append(
                        `<option value="${item.tambon_id}">${item.tambon_name}</option>`
                    );
                });
                $("#same_tambon").attr('disabled', false);
            },
            error: function() {}
        })
    });
    // BEGIN district

    //  BEGIN tambon
    $("#same_tambon").change(function() {
        let tambon_id = $(this).val();
        $.ajax({
            url: '{{ route('getZipcode') }}',
            type: 'GET',
            dataType: 'json',
            data: {
                tambon_id: tambon_id,
            },
            success: function(data) {
                $("#same_zipcode").val(data.zipcode);
                $.ajax({
                            url: '{{ route('fc_shipping_zip_code_js') }}',
                            type: 'GET',
                            data: {
                                _token: '{{ csrf_token() }}',
                                zip_code: data.zipcode,
                                price_shipping_pv:"{{$bill['price_shipping_pv']}}",
                                price_discount:"{{$bill['price_discount']}}",

                            }
                        })
                        .done(function(data) {
                            $("#total_shipping").html(data['total_shipping']);
                            $("#price_total").html(data['price_total']);
                            if(data['status'] == 'success'){
                                        document.getElementById("shipping_text").innerHTML = '<span class="badge rounded-pill bg-danger bg-opacity-20 text-danger fw-light ps-3">พื้นที่หางไกลเพิ่ม 50 บาท</span>';
                                    }else{
                                        document.getElementById("shipping_text").innerHTML = '<span class="badge rounded-pill bg-primary bg-opacity-20 text-primary fw-light ps-3">จัดส่งทั่วไป</span>';

                                    }
                        })
            },
            error: function() {}
        })
    });
    //  END tambon
</script>
    @endsection
