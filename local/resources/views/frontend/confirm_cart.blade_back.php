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
                                <li class="breadcrumb-item"><a href="{{route('Order')}}">รายการสินค้า</a></li>
                                <li class="breadcrumb-item active text-truncate" aria-current="page"> ยืนยันรายการสินค้า </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-box borderR10 mb-2 mb-md-0">

                            <div class="card-body">

                                <div class="row">

                                        <div class="col-md-8 col-sm-12">
                                            <h4 class="card-title">ยืนยันรายการสินค้า</h4>


                                            <div class="card card-box borderR10 mb-2 mb-md-0">
                                                <div class="card-body">

                                                    <div class="card-block payment-tabs">
                                                        <ul class="nav nav-tabs md-tabs" role="tablist">

                                                            <li class="nav-item">
                                                                <a class="nav-link active" id="nav_address" data-toggle="tab" href="#address"
                                                                    role="tab">ที่อยู่การจัดส่ง</a>
                                                                <div class="slide"></div>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="nav_card">ชำระเงิน</a>
                                                                <div class="slide"></div>
                                                            </li>


                                                        </ul>
                                                        <div class="tab-content m-t-15">

                                                            <div class="tab-pane active" id="address" role="tabpanel">

                                                                <div class="card-block p-b-0" style="padding:10px">

                                                                  <div class="row">
                                                                    <div class="col-sm-12 col-md-12 col-xl-12 m-b-30">
                                                                        <h4 class="sub-title" style="font-size: 17px">รูปแบบการจัดส่ง</h4>
                                                                        <div class="form-radio">
                                                                            <div class="radio radio-inline">
                                                                                <label>
                                                                                    <input type="radio" name="sent_type_to_customer"
                                                                                        value="sent_type_customer" id="sent_type_customer"
                                                                                        onclick="sent_type('customer')" checked="checked">
                                                                                    <i class="helper"></i><b>ซื้อให้ตัวเอง</b>
                                                                                </label>
                                                                            </div>
                                                                            <div class="radio radio-inline">
                                                                                <label>
                                                                                    <input type="radio" name="sent_type_to_customer"
                                                                                        value="sent_type_other" id="sent_type_other"
                                                                                        onclick="sent_type('other')">
                                                                                    <i class="helper"></i><b>ซื้อให้ลูกทีม</b>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row" id="check_user" style="display: none">
                                                                  <div class="col-md-12">
                                                                      <div class="row">
                                                                          <div class="col-md-6 col-lg-6 col-xl-6">
                                                                              <div class="card">
                                                                                  <div class="card-block" style="padding: 10px;">
                                                                                      <h6>รหัสของลูกทีมซื้อสินค้า(PV เป็นของลูกทีม)</h6>
                                                                                      <div class="form-group row">
                                                                                          <div class="col-md-12">
                                                                                              <div class="input-group input-group-button">
                                                                                                  <input type="text" id="username" class="form-control"
                                                                                                      placeholder="รหัสสมาชิก">
                                                                                                  <span class="input-group-addon btn btn-primary"
                                                                                                      onclick="check()">
                                                                                                      <span class="">ทำรายการ</span>
                                                                                                  </span>
                                                                                              </div>
                                                                                              <span
                                                                                                  style="font-size: 12px" class="text-danger">*คะแนนการสั่งซื้อจะถูกใช้ให้กับลูกทีมที่เลือก</span>
                                                                                          </div>

                                                                                      </div>

                                                                                  </div>
                                                                              </div>
                                                                          </div>
                                                                          <div class="col-md-6 col-lg-6 col-xl-6" id="data_direct" style="display: none">
                                                                              <div class="card bg-c-yellow order-card m-b-0">
                                                                                  <div class="card-block" style="padding: 10px;">
                                                                                      <div class="row">
                                                                                          <div class="col-md-12">
                                                                                              <h5 id="c_text_username"
                                                                                                  style="color: #000;font-size: 16px"></h5>
                                                                                              <h6 class="m-b-0" id="c_name" style="color: #000"></h6>
                                                                                              <input type="hidden" name="address_sent_id_fk"
                                                                                                  id="address_sent_id_fk" value="">
                                                                                                  <input type="hidden" name="customers_sent_user_name"
                                                                                                  id="customers_sent_user_name" value="">

                                                                                          </div>

                                                                                      </div>
                                                                                      <div class="row">
                                                                                          <div class="col-md-12">
                                                                                              <h6 class="m-b-0" style="color: #000"><i
                                                                                                      class="fa fa-star p-2 m-b-0"></i>
                                                                                                  <b id="c_qualification_name"></b>
                                                                                              </h6>
                                                                                          </div>
                                                                                      </div>
                                                                                      <hr class="m-b-5 m-t-5">
                                                                                      <div class="row">
                                                                                          <div class="col-md-6">
                                                                                              <h5 class="m-b-0" style="color: #000">ซื้อปกติ</h5>
                                                                                          </div>
                                                                                          <div class="col-md-6">
                                                                                              <h5 class="m-b-0 text-right" id="c_text_pv"
                                                                                                  style="color: #000"></h5>
                                                                                          </div>
                                                                                      </div>
                                                                                      <hr class="m-b-5 m-t-5">
                                                                                      <div class="row">
                                                                                          <div class="col-md-6">
                                                                                              <p class="m-b-0" style="color: #000">
                                                                                                  <b>รักษาสิทธิ์</b>
                                                                                              </p>
                                                                                          </div>
                                                                                          <div class="col-md-6 text-right">
                                                                                              <div id="c_pv_mt_active"></div>
                                                                                          </div>
                                                                                      </div>
                                                                                  </div>
                                                                              </div>
                                                                          </div>
                                                                      </div>
                                                                  </div>
                                                              </div>


                                                                    <div class="row">
                                                                        <div class="col-sm-12 col-md-12 col-xl-12">
                                                                            <h4 class="sub-title" style="font-size: 17px">ที่อยู่ผู้รับสินค้า</h4>
                                                                            <div class="form-radio">
                                                                                <div class="radio radio-inline" id="i_sent_address">
                                                                                    <label>
                                                                                        <input type="radio"
                                                                                            onchange="sent_address('sent_address',{{ @$address->province_id_fk }})"
                                                                                            id="sent_address_check" name="receive" value="sent_address"
                                                                                            checked="checked">
                                                                                        <i class="helper"></i><b>จัดส่ง</b>
                                                                                    </label>
                                                                                </div>


                                                                                {{-- <div class="radio radio-inline">
                                                                                    <label>
                                                                                        <input type="radio" id="sent_office_check"
                                                                                            onchange="sent_address('sent_office')" name="receive"
                                                                                            value="sent_office">
                                                                                        <i class="helper"></i><b>รับที่สาขา</b>
                                                                                    </label>
                                                                                </div> --}}

                                                                                <div class="radio radio-inline">
                                                                                    <label>
                                                                                        <input type="radio" id="sent_other"
                                                                                            onchange="sent_address('sent_other')" name="receive"
                                                                                            value="sent_address_other">
                                                                                        <i class="helper"></i><b>อื่นๆ</b>
                                                                                    </label>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                    @if (@$address->province_id_fk)
                                                                        <div id="sent_address">
                                                                            <div class="row m-t-5">
                                                                                <div class="col-md-6 col-sm-6 col-6">
                                                                                    <label>ชื่อผู้รับ <b class="text-danger">*</b></label>
                                                                                    <input type="text" class="form-control form-control-bold"
                                                                                        placeholder="ชื่อผู้รับ" name="name"
                                                                                        value="{{ $customer->prefix_name }} {{ $customer->first_name }} {{ $customer->last_name }}"
                                                                                        readonly="">
                                                                                </div>
                                                                                <div class="col-md-6 col-sm-6 col-6">
                                                                                    <label>เบอร์โทรศัพท์ <b class="text-danger">*</b></label>
                                                                                    <input type="text" class="form-control form-control-bold"
                                                                                        placeholder="เบอร์โทรศัพท์" name="tel_mobile"
                                                                                        value="{{ $address->tel_mobile }}" readonly="">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row m-t-5">
                                                                                <div class="col-md-6 col-sm-6 col-6">
                                                                                    <label>Email </label>
                                                                                    <input type="email" class="form-control form-control-bold"
                                                                                        placeholder="Email" name="email" value="{{ $customer->email }}"
                                                                                        readonly="">
                                                                                </div>
                                                                            </div>

                                                                            <div class="row m-t-5">
                                                                                <div class="col-md-3 col-sm-4 col-4">
                                                                                    <label>บ้านเลขที่ <b class="text-danger">*</b></label>
                                                                                    <input type="text" class="form-control form-control-bold"
                                                                                        placeholder="บ้านเลขที่" name="house_no"
                                                                                        value="{{ @$address->house_no }}" readonly="">
                                                                                </div>
                                                                                <div class="col-md-5 col-sm-8 col-8">
                                                                                    <label>หมู่บ้าน/อาคาร <b class="text-danger">*</b></label>
                                                                                    <input type="text" class="form-control form-control-bold"
                                                                                        placeholder="หมู่บ้าน/อาคาร" name="house_name"
                                                                                        value="{{ $address->house_name }}" readonly="">
                                                                                </div>
                                                                                <div class="col-md-3 col-sm-12 col-12">
                                                                                    <label>หมู่ที่ <b class="text-danger">*</b></label>
                                                                                    <input type="text" class="form-control form-control-bold"
                                                                                        placeholder="หมู่ที่" name="moo" value="{{ $address->moo }}"
                                                                                        readonly="">
                                                                                </div>

                                                                            </div>
                                                                            <div class="row m-t-5">
                                                                                <div class="col-sm-4">
                                                                                    <label>ถนน</label>
                                                                                    <input type="text" class="form-control form-control-bold"
                                                                                        placeholder="ถนน" name="road" value="{{ $address->road }}"
                                                                                        readonly="">
                                                                                </div>

                                                                                <div class="col-sm-4">
                                                                                    <label>ตรอก/ซอย </label>
                                                                                    <input type="text" class="form-control form-control-bold"
                                                                                        placeholder="ตรอก/ซอย" name="soi" value="{{ $address->soi }}"
                                                                                        readonly="">
                                                                                </div>

                                                                                <div class="col-sm-4">
                                                                                    <label>จังหวัด <b class="text-danger">*</b></label>
                                                                                    <input type="text" id="provinces_address"
                                                                                        class="form-control form-control-bold" placeholder="จังหวัด"
                                                                                        value="{{ $address->provinces_name }}" readonly="">
                                                                                    <input type="hidden" name="province"
                                                                                        value="{{ $address->province_id_fk }}">
                                                                                </div>

                                                                                <div class="col-sm-4">
                                                                                    <label>เขต/อำเภอ <b class="text-danger">*</b></label>
                                                                                    <input type="text" class="form-control form-control-bold"
                                                                                        placeholder="เขต/อำเภอ" value="{{ $address->amphures_name }}"
                                                                                        readonly="">
                                                                                    <input type="hidden" name="amphures"
                                                                                        value="{{ $address->amphures_id_fk }}">
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <label>แขวง/ตำบล <b class="text-danger">*</b> </label>
                                                                                    <input type="text" class="form-control form-control-bold"
                                                                                        placeholder="แขวง/ตำบล" value="{{ $address->district_name }}"
                                                                                        readonly="">
                                                                                    <input type="hidden" name="district"
                                                                                        value="{{ $address->district_id_fk }}">
                                                                                </div>

                                                                                <div class="col-sm-4">
                                                                                    <label>รหัสไปษณีย์ <b class="text-danger">*</b></label>
                                                                                    <input type="text" class="form-control form-control-bold"
                                                                                        placeholder="รหัสไปษณีย์" name="zipcode"
                                                                                        value="{{ $address->zipcode }}" readonly="">
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    @else
                                                                        <div id="sent_address">

                                                                            <div class="alert alert-warning icons-alert">

                                                                                <p><strong>Warning!</strong> <code>ไม่มีข้อมูลที่อยู่การจัดส่งสินค้า
                                                                                        กรุณาตั้งค่าก่อนชำระเงิน</code> <a
                                                                                        href="{{ route('editprofile') }}"
                                                                                        class="pcoded-badge label label-warning ">ตั้งค่า คลิ๊ก!!</a></p>
                                                                            </div>

                                                                        </div>

                                                                    @endif


{{--
                                                                    <div id="sent_office" style="display: none;">
                                                                        <div class="row m-t-5">
                                                                            <div class="col-sm-12">
                                                                                <select name="receive_location" class="form-control" readonly="">

                                                                                    @foreach ($location as $value)

                                                                                        <option value="{{ $value->id }}">{{ $value->b_name }}
                                                                                            ({{ $value->b_details }})</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row m-t-5">
                                                                            <div class="col-md-6 col-sm-6 col-6">
                                                                                <label>ชื่อผู้รับ <b class="text-danger">*</b></label>
                                                                                <input type="text" class="form-control form-control-bold"
                                                                                    placeholder="ชื่อผู้รับ" name="office_name" id="office_name" value="">
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-6 col-6">
                                                                                <label>เบอร์โทรศัพท์ <b class="text-danger">*</b></label>
                                                                                <input type="text" class="form-control form-control-bold"
                                                                                    placeholder="เบอร์โทรศัพท์" id="office_tel_mobile"
                                                                                    name="receive_tel_mobile" value="">
                                                                            </div>
                                                                        </div>

                                                                        <div class="row m-t-5">
                                                                            <div class="col-md-6 col-sm-6 col-6">
                                                                                <label>Email </label>
                                                                                <input type="email" class="form-control form-control-bold"
                                                                                    placeholder="Email" name="office_email"
                                                                                    value="{{ $customer->email }}">
                                                                            </div>

                                                                        </div>
                                                                    </div> --}}

                                                                    <div id="sent_address_other" style="display: none;">
                                                                        <div class="row m-t-5">
                                                                            <div class="col-md-6 col-sm-6 col-6">
                                                                                <label>ชื่อผู้รับ <b class="text-danger">*</b></label>
                                                                                <input type="text"
                                                                                    class="form-control form-control-bold sent_address_other"
                                                                                    placeholder="ชื่อผู้รับ" name="other_name" id="other_name">

                                                                            </div>
                                                                            <div class="col-md-6 col-sm-6 col-6">
                                                                                <label>เบอร์โทรศัพท์ <b class="text-danger">*</b></label>
                                                                                <input type="text"
                                                                                    class="form-control form-control-bold sent_address_other"
                                                                                    placeholder="เบอร์โทรศัพท์" name="other_tel_mobile"
                                                                                    id="other_tel_mobile">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row m-t-5">
                                                                            <div class="col-md-6 col-sm-6 col-6">
                                                                                <label>Email </label>
                                                                                <input type="email" class="form-control form-control-bold"
                                                                                    placeholder="Email" name="other_email" id="other_email">
                                                                            </div>

                                                                        </div>

                                                                        <div class="row m-t-5">
                                                                            <div class="col-md-3 col-sm-4 col-4">
                                                                                <label>บ้านเลขที่ <b class="text-danger">*</b></label>
                                                                                <input type="text"
                                                                                    class="form-control form-control-bold sent_address_other"
                                                                                    placeholder="บ้านเลขที่" name="other_house_no" id="other_house_no">
                                                                            </div>
                                                                            <div class="col-md-5 col-sm-8 col-8">
                                                                                <label>หมู่บ้าน/อาคาร <b class="text-danger">*</b></label>
                                                                                <input type="text"
                                                                                    class="form-control form-control-bold sent_address_other"
                                                                                    placeholder="หมู่บ้าน/อาคาร" name="other_house_name"
                                                                                    id="other_house_name">
                                                                            </div>
                                                                            <div class="col-md-3 col-sm-12 col-12">
                                                                                <label>หมู่ที่ <b class="text-danger">*</b></label>
                                                                                <input type="text"
                                                                                    class="form-control form-control-bold sent_address_other"
                                                                                    placeholder="หมู่ที่" name="other_moo" id="other_moo">
                                                                            </div>
                                                                        </div>

                                                                        <div class="row m-t-5">
                                                                            <div class="col-sm-4">
                                                                                <label>ถนน</label>
                                                                                <input type="text" class="form-control form-control-bold" placeholder="ถนน"
                                                                                    name="other_road">
                                                                            </div>

                                                                            <div class="col-sm-4">
                                                                                <label>ตรอก/ซอย </label>
                                                                                <input type="text" class="form-control form-control-bold"
                                                                                    placeholder="ตรอก/ซอย" name="other_soi">
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <label>จังหวัด <font class="text-danger">*</font></label>
                                                                                <select class="js-example-basic-single col-sm-12 sent_address_other"
                                                                                    id="province" name="other_province">
                                                                                    <option value=""> Select </option>
                                                                                    @foreach ($provinces as $value_provinces)
                                                                                        <option value="{{ $value_provinces->province_id }}">
                                                                                            {{ $value_provinces->province_name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>


                                                                        </div>

                                                                        <div class="form-group row m-t-5">

                                                                            <div class="col-sm-4">
                                                                                <label>เขต/อำเภอ <font class="text-danger">*</font></label>
                                                                                <select class="js-example-basic-single col-sm-12 sent_address_other"
                                                                                    name="other_amphures" id="amphures">
                                                                                    <option value=""> Select </option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="col-sm-4">
                                                                                <label>แขวง/ตำบล <font class="text-danger">*</font></label>
                                                                                <select class="js-example-basic-single col-sm-12 sent_address_other"
                                                                                    name="other_district" id="district">
                                                                                      <option value=""> Select </option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <label>รหัสไปษณีย์</label>
                                                                                <input type="text" class="form-control sent_address_other"
                                                                                    placeholder="รหัสไปษณีย์" id="other_zipcode" name="other_zipcode"
                                                                                    value="">
                                                                            </div>

                                                                        </div>

                                                                    </div>


                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- ////////////////////////////// --}}


                                        <div class="col-md-4 col-sm-12">

                                            <div class="card card-box borderR10 mb-2 mb-md-0">
                                                <div class="card-body">
                                                    <h4>สรุปรายการสั่งซื้อ</h4>
                                                    <hr>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-2">มูลค่าสินค้า ({{Cart::session(1)->getTotalQuantity()}}) ชิ้น</p>
                                                        </div>
                                                        <div class="col-md-6 text-md-end">
                                                            <p class="mb-2">{{ number_format(Cart::session(1)->getTotal()/1.07,2) }} บาท</p>
                                                        </div>




                                                        <div class="col-md-6">
                                                            <p class="mb-2">Vat 7%</p>
                                                        </div>
                                                        <div class="col-md-6 text-md-end">
                                                            <p class="mb-2">{{ number_format(Cart::session(1)->getTotal() - Cart::session(1)->getTotal()/1.07,2) }} บาท</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-2">มูลค่าสินค้า + Vat</p>
                                                        </div>
                                                        <div class="col-md-6 text-md-end">
                                                            <p class="mb-2">{{ number_format(Cart::session(1)->getTotal()) }} บาท</p>
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
                                                            <p class="mb-2">{{number_format($pv_total)}} PV</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-2">ค่าส่ง</p>
                                                        </div>
                                                        <div class="col-md-6 text-md-end">
                                                            <p class="mb-2">0 บาท</p>
                                                        </div>


                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-2">ราคาสุทธิ</p>
                                                        </div>
                                                        <div class="col-md-6 text-md-end">
                                                            <p class="mb-2 text-purple1"><span class="text-p1 h5">{{ number_format(Cart::session(1)->getTotal()) }}</span> บาท</p>
                                                        </div>
                                                    </div>
                                                    <div class="text-center">
                                                        <button type="button"
                                                            class="btn btn-p1 rounded-pill w-100 mb-2 justify-content-center">ยืนยันคำสั่งซื้อ</button>
                                                        <a href="{{route('cancel_order')}}" type="button"
                                                            class="btn btn-outline-dark rounded-pill w-100 mb-2 justify-content-center">ยกเลิก</a>
                                                    </div>
                                                </div>
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
    <script>


    </script>


    @endsection
