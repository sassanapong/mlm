@extends('layouts.frontend.app')

@section('css')
    <style>
        .info_alert {
            text-align: left;
            height: 15rem;
            width: 100%;
        }

        .disabled_select {
            pointer-events: none;
            background: #E9ECEF;
        }
    </style>
@endsection

@section('conten')
    <div class="bg-whiteLight page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                            <li class="breadcrumb-item active" aria-current="page">สมัครสมาชิก</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <div class="card card-box borderR10 mb-2 mb-md-0">
                        <div class="card-body">
                            <h4 class="card-title">สมัครสมาชิก</h4>
                            <hr>
                            <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">ผู้แนะนำ</div>
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <label for="" class="form-label">รหัสผู้แนะนำ <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-lg-2 col-xxl-1">
                                    <label for="" class="form-label d-none d-md-block">&nbsp;</label>
                                    <button class="btn btn-p1 rounded-pill">ตรวจ</button>
                                    <button class="btn btn-outline-dark rounded-circle btn-icon"><i
                                            class="bx bx-x"></i></button>
                                </div>
                                <div class="col-md-6 col-lg-6 col-xxl-8 mb-3">
                                    <label for="" class="form-label">ชื่อผู้แนะนำ <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="" disabled>
                                </div>
                            </div>
                            <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">ข้อมูลส่วนตัว</div>
                            <div class="row g-3">
                                <div class="col-md-6 col-xl-3">
                                    <label for="" class="form-label">คำนำหน้า <span
                                            class="text-danger prefix_name_err _err">*</span></label>
                                    <select name="prefix_name" class="form-select disabled_select" id="">
                                        <option disabled>เลือกคำนำหน้า</option>
                                        <option {{ $customers_info->prefix_name == 'นาย' ? 'selected' : '' }}
                                            value="นาย">นาย</option>
                                        <option {{ $customers_info->prefix_name == 'นาง' ? 'selected' : '' }}
                                            value="นาง">นาง</option>
                                        <option {{ $customers_info->prefix_name == 'นางสาว' ? 'selected' : '' }}
                                            value="นางสาว">นางสาว</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-xl-3">
                                    <label for="" class="form-label">ชื่อ <span
                                            class="text-danger name_err _err">*</span></label>
                                    <input name="name" type="text" class="form-control" id=""
                                        value="{{ $customers_info->name }}" readonly>
                                </div>
                                <div class="col-md-3 col-xl-3">
                                    <label for="" class="form-label">นามสกุล <span
                                            class="text-danger last_name_err _err">*</span></label>
                                    <input name="last_name" type="text" class="form-control" id=""
                                        value="{{ $customers_info->last_name }}" readonly>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <label for="" class="form-label">เพศ <span
                                            class="text-danger gender_err _err">*</span></label>
                                    <select name="gender" class="form-select disabled_select" id="">
                                        <option selected disabled>เลือกเพศ</option>
                                        <option {{ $customers_info->gender == 'ชาย' ? 'selected' : '' }} value="ชาย">
                                            ชาย</option>
                                        <option {{ $customers_info->gender == 'หญิง' ? 'selected' : '' }} value="หญิง">
                                            หญิง</option>
                                        <option {{ $customers_info->gender == 'ไม่ระบุ' ? 'selected' : '' }}
                                            vlaue="ไม่ระบุ">ไม่ระบุ</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <label for="" class="form-label">ชื่อทางธุรกิจ <span
                                            class="text-danger business_name_err _err">*</span></label>
                                    <input name="business_name" type="text" class="form-control" id=""
                                        value="{{ $customers_info->business_name }}" readonly>
                                </div>
                                <div class="col-md-6 col-xl-2">
                                    <label for="" class="form-label">วันเกิด <span
                                            class="text-danger day_err _err">*</span></label>
                                    <input name="day" type="text" class="form-control" id=""
                                        value="{{ $customers_day }}" readonly>

                                </div>
                                <div class="col-md-6 col-xl-2">

                                    <label for=""
                                        class=" text-danger form-label d-none d-md-block month_err _err">&nbsp;</label>
                                    <select name="month" class="form-select disabled_select" id="">
                                        <option disabled>เดือน</option>
                                        <option {{ $customers_month == '01' ? 'selected' : '' }} value="01">มกราคม
                                        </option>
                                        <option {{ $customers_month == '02' ? 'selected' : '' }} value="02">
                                            กุมภาพันธ์</option>
                                        <option {{ $customers_month == '03' ? 'selected' : '' }} value="03">มีนาคม
                                        </option>
                                        <option {{ $customers_month == '04' ? 'selected' : '' }} value="04">เมษายน
                                        </option>
                                        <option {{ $customers_month == '05' ? 'selected' : '' }} value="05">
                                            พฤษภาคม
                                        </option>
                                        <option {{ $customers_month == '06' ? 'selected' : '' }} value="06">
                                            มิถุนายน
                                        </option>
                                        <option {{ $customers_month == '07' ? 'selected' : '' }} value="07">
                                            กรกฎาคม
                                        </option>
                                        <option {{ $customers_month == '08' ? 'selected' : '' }} value="08">
                                            สิงหาคม
                                        </option>
                                        <option {{ $customers_month == '09' ? 'selected' : '' }} value="09">
                                            กันยายน
                                        </option>
                                        <option {{ $customers_month == '10' ? 'selected' : '' }} value="10">ตุลาคม
                                        </option>
                                        <option {{ $customers_month == '11' ? 'selected' : '' }} value="11">
                                            พฤศจิกายน</option>
                                        <option {{ $customers_month == '12' ? 'selected' : '' }} value="12">
                                            ธันวาคม
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-xl-2">
                                    <label for=""
                                        class="text-danger form-label d-none d-md-block year_err _err">&nbsp;</label>
                                    <input name="year" type="text" class="form-control" id=""
                                        value="{{ $customers_year }}" readonly>
                                </div>
                                <div class="col-md-6 col-xl-2">
                                    <label for="" class="form-label">สัญชาติ <span
                                            class="text-danger nation_id_err _err">*</span></label>
                                    <select class="form-select disabled_select" name="nation_id" id="">
                                        <option disabled>เลือกสัญชาติ</option>
                                        <option {{ $customers_info->nation_id == 'ไทย' ? 'selected' : '' }}
                                            value="ไทย">ไทย</option>

                                    </select>
                                </div>
                                <div class="col-md-6 col-xl-5">
                                    <label for="" class="form-label">เลขบัตรประชาชน <span
                                            class="text-danger id_card_err _err">*</span></label>
                                    <input name="id_card" type="text" class="form-control" maxlength="13"
                                        id="" value="{{ $customers_info->id_card }}" readonly>
                                </div>
                                <div class="col-md-6 col-xl-5">
                                    <label for="" class="form-label">โทรศัพท์ <span
                                            class="text-danger phone_err _err">*</span></label>
                                    <input name="phone" type="text" class="form-control" id=""
                                        value="{{ $customers_info->phone }}" readonly>
                                </div>
                                <form id="form_customers_info" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-xl-4">
                                            <label for="" class="form-label">E-mail <span
                                                    class="text-danger email_err _err"></span></label>
                                            <input name="email" type="text" class="form-control" id=""
                                                value="{{ $customers_info->email }}">
                                        </div>
                                        <div class="col-md-6 col-xl-4">
                                            <label for="" class="form-label">Line ID</label>
                                            <input name="line_id" type="text" class="form-control" id=""
                                                value="{{ $customers_info->line_id }}">
                                        </div>
                                        <div class="col-md-6 col-xl-4 mb-3">
                                            <label for="" class="form-label">Facebook</label>
                                            <input name="facebook" type="text" class="form-control" id=""
                                                value="{{ $customers_info->facebook }}">
                                        </div>
                                    </div>

                                    <div class="row text-center mb-2">
                                        <div class="col-md-12 col-xl-12">
                                            <button type="submit"
                                                class="btn btn-success rounded-pill">บันทึกข้อมูล</button>
                                            <button class="btn btn-danger rounded-pill">ยกเลิก</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">ที่อยู่ตามบัตรประชาชน
                            </div>

                            <form id="form_update_info_card">
                                @csrf
                                <div class="row g-3">

                                    <input type="hidden" name="customers_id"
                                        value="{{ Auth::guard('c_user')->user()->id }}">
                                    <div class="col-md-4">
                                        <div class="col-md-12 text-center">
                                            <div class="file-upload">
                                                <span class="text-danger file_card_err _err"></span>
                                                <label for="file_card" class="file-upload__label"><i
                                                        class='bx bx-upload'></i>
                                                    อัพโหลดเอกสาร</label>
                                                <input id="file_card" class="file-upload__input" type="file"
                                                    accept="image/*" name="file_card">
                                            </div>


                                        </div>
                                        <div class="mt-1 mb-2 d-flex justify-content-center">

                                            <img width="250" height="300" id="img_card"
                                                src="{{ @$address_card->url != null ? @$address_card->url . '/' . @$address_card->img_card : 'https://via.placeholder.com/250x300.png?text=card' }} " />
                                        </div>
                                    </div>
                                    <div class="col-md-8 my-auto">
                                        <div id="group_data_card_address" class="row ">

                                            <div class="col-md-6 col-xl-5 ">
                                                <label for="" class="form-label">ที่อยู่ <span
                                                        class="text-danger card_address_err _err">*</span></label>
                                                <input type="text" name="card_address"
                                                    value="{{ @$address_card->address }}"
                                                    class="form-control card_address" id=""
                                                    @if ($address_card != null) readonly @endif>
                                            </div>
                                            <div class="col-md-6 col-xl-3">
                                                <label for="" class="form-label">หมู่ที่ <span
                                                        class="text-danger card_moo_err _err">*</span></label>
                                                <input type="text" name="card_moo" class="form-control card_address"
                                                    id=""
                                                    value="{{ @$address_card->moo }}"@if ($address_card != null) readonly @endif>
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label for="" class="form-label">ซอย <span
                                                        class="text-danger card_soi_err _err">*</span></label>
                                                <input type="text" name="card_soi" class="form-control card_address"
                                                    id=""
                                                    value="{{ @$address_card->soi }}"@if ($address_card != null) readonly @endif>
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label for="" class="form-label">ถนน <span
                                                        class="text-danger card_road_err _err">*</span></label>
                                                <input type="text" name="card_road" class="form-control card_address"
                                                    id=""
                                                    value="{{ @$address_card->road }}"@if ($address_card != null) readonly @endif>
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label for="province" class="form-label">จังหวัด</label>
                                                <label class="form-label text-danger card_province_err _err"></label>
                                                <select
                                                    class="form-select card_address @if ($address_card != null) disabled_select @endif "
                                                    name="card_province" id="province">
                                                    <option value="">--กรุณาเลือก--</option>
                                                    @foreach ($province as $item)
                                                        <option
                                                            {{ @$address_card->province == $item->province_id ? 'selected' : '' }}
                                                            value="{{ $item->province_id }}">
                                                            {{ $item->province_name }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                            <div class="col-md-6 col-xl-4">

                                                <label for="district" class="form-label">อำเภอ/เขต</label>
                                                <label class="form-label text-danger card_district_err _err"></label>
                                                <select
                                                    class="form-select card_address @if ($address_card != null) disabled_select @endif"
                                                    name="card_district" id="district" disabled>
                                                    <option value="">--กรุณาเลือก--</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label for="tambon" class="form-label">ตำบล</label>
                                                <label class="form-label text-danger tambon_err _err"></label>
                                                <select
                                                    class="form-select card_address @if ($address_card != null) disabled_select @endif"
                                                    name="card_tambon" id="tambon" disabled>
                                                    <option value="">--กรุณาเลือก--</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label for="" class="form-label">รหัสไปรษณีย์ <span
                                                        class="text-danger card_zipcode_err _err">*</span></label>
                                                <input id="zipcode" name="card_zipcode" type="text"
                                                    class="form-control card_address" id="" value=""
                                                    @if ($address_card != null) readonly @endif>
                                            </div>
                                            <div class="col-md-6 col-xl-4 mb-3">
                                                <label for="" class="form-label">เบอร์มือถือ</label>
                                                <input type="text" name="card_phone" class="form-control card_address"
                                                    id="" value="{{ @$address_card->phone }}"
                                                    @if ($address_card != null) readonly @endif>
                                            </div>
                                        </div>
                                    </div>

                                </div>



                                <div class="row text-center mb-2">
                                    <div class="col-md-12 col-xl-12">
                                        <button type="submit" class="btn btn-success rounded-pill">บันทึกข้อมูล</button>
                                        <button class="btn btn-danger rounded-pill">ยกเลิก</button>
                                    </div>

                                </div>


                            </form>
                            <form id="form_update_same_address" method="post">

                                @csrf

                                <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">
                                    ที่อยู่จัดส่ง
                                    <div class="form-check form-check-inline h6 fw-normal">
                                        <input class="form-check-input" name="status_address" id="status_address"
                                            type="checkbox" value="1" id="flexCheckDefault">
                                        <label class="form-check-label" for="status_address">
                                            ใช้ที่อยู่เดียวกันบัตรประชาชน
                                        </label>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6 col-xl-5">
                                        <label for="" class="form-label">ที่อยู่ <span
                                                class="text-danger same_address_err _err">*</span></label>
                                        <input type="text" name="same_address" class="form-control address_same_card"
                                            id="" value="{{ @$address_delivery->address }}">
                                    </div>
                                    <div class="col-md-6 col-xl-3">
                                        <label for="" class="form-label">หมู่ที่ <span
                                                class="text-danger same_moo_err _err">*</span></label>
                                        <input type="text" name="same_moo" class="form-control address_same_card"
                                            id="" value="{{ @$address_delivery->moo }}">
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="" class="form-label">ซอย <span
                                                class="text-danger same_soi_err _err">*</span></label>
                                        <input type="text" name="same_soi" class="form-control address_same_card"
                                            id="" value="{{ @$address_delivery->soi }}">
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="" class="form-label">ถนน <span
                                                class="text-danger same_road_err _err">*</span></label>
                                        <input type="text" name="same_road" class="form-control address_same_card"
                                            id="" value="{{ @$address_delivery->road }}">
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="province" class="form-label">จังหวัด</label>
                                        <label class="form-label text-danger same_province_err _err"></label>
                                        <select class="form-select address_same_card select_same" name="same_province"
                                            id="same_province">
                                            <option selected disabled value="">--กรุณาเลือก--</option>
                                            @foreach ($province as $item)
                                                <option
                                                    {{ @$address_delivery->province == $item->province_id ? 'selected' : '' }}
                                                    value="{{ $item->province_id }}">
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
                                    <div class="col-md-6 col-xl-4 mb-3">
                                        <label for="" class="form-label">เบอร์มือถือ</label>
                                        <input type="text" name="same_phone" class="form-control address_same_card"
                                            id="" value="{{ @$address_delivery->phone }}">
                                    </div>
                                </div>

                                <div class="row text-center mb-2">
                                    <div class="col-md-12 col-xl-12">
                                        <button type="submit" class="btn btn-success rounded-pill">บันทึกข้อมูล</button>
                                        <button class="btn btn-danger rounded-pill">ยกเลิก</button>
                                    </div>

                                </div>
                            </form>


                            <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">
                                ข้อมูลบัญชีธนาคารเพื่อรับรายได้</div>
                            @if ($info_bank != null)
                                <div class="row g-3">
                                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                                        <i class='bx bxs-error me-2'></i>
                                        <div>
                                            สมาชิกจะใส่หรือไม่ใส่ก็ได้ หากไม่ได้ใส่จะมีผลกับการโอนเงินให้สมาชิก
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-danger file_bank_err _err"></span>

                                        <div class=" mt-1 mb-1 d-flex justify-content-center">
                                            <img width="250" height="300" id="img_bank"
                                                src="{{ @$info_bank->url . '/' . @$info_bank->img_bank }}" />
                                        </div>

                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6 col-xl-4">
                                                <label for="" class="form-label">ธนาคาร <span
                                                        class="text-danger bank_name_err _err "></span></label>
                                                <select name="bank_name" class="form-select disabled_select"
                                                    id="">
                                                    <option disabled>เลือกธนาคาร</option>
                                                    @foreach ($bank as $value_bank)
                                                        <option
                                                            {{ $info_bank->bank_id_fk == $value_bank->id ? 'selected' : '' }}
                                                            value="{{ $value_bank->id }}">
                                                            {{ $value_bank->name }}</option>
                                                    @endforeach


                                                </select>
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label for="" class="form-label">สาขา <span
                                                        class="text-danger bank_branch_err _err "></span></label>
                                                <input type="text" name="bank_branch" class="form-control"
                                                    id="" value="{{ $info_bank->bank_branch }}" readonly>
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label for="" class="form-label">เลขที่บัญชี </label>
                                                <input type="text" name="bank_no" class="form-control" id=""
                                                    value="{{ $info_bank->bank_no }}" readonly>
                                            </div>
                                            <div class="col-md-6 col-xl-12 mb-3">
                                                <label for="" class="form-label">ชื่อบัญชี <span
                                                        class="text-danger account_name_err _err "></span></label>
                                                <input type="text" name="account_name" class="form-control"
                                                    id="" value="{{ $info_bank->account_name }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($info_bank == null)
                                <form id="form_cerate_info_bank_last" method="post">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                                            <i class='bx bxs-error me-2'></i>
                                            <div>
                                                สมาชิกจะใส่หรือไม่ใส่ก็ได้ หากไม่ได้ใส่จะมีผลกับการโอนเงินให้สมาชิก
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-danger file_bank_err _err"></span>
                                            <div class="col-md-12 text-center">
                                                <div class="file-upload">
                                                    <label for="file_bank" class="file-upload__label"><i
                                                            class='bx bx-upload'></i>
                                                        อัพโหลดเอกสาร</label>
                                                    <input id="file_bank" class="file-upload__input" type="file"
                                                        name="file_bank">
                                                </div>
                                            </div>
                                            <div class=" mt-1 mb-1 d-flex justify-content-center">
                                                <img width="250" height="300" id="img_bank"accept="image/*"
                                                    src="https://via.placeholder.com/250x300.png?text=Bank" />
                                            </div>

                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6 col-xl-4">
                                                    <label for="" class="form-label">ธนาคาร <span
                                                            class="text-danger bank_name_err _err "></span></label>
                                                    <select name="bank_name" class="form-select" id="">
                                                        <option selected disabled>เลือกธนาคาร</option>

                                                        @foreach ($bank as $value_bank)
                                                            <option value="{{ $value_bank->id }}">
                                                                {{ $value_bank->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 col-xl-4">
                                                    <label for="" class="form-label">สาขา <span
                                                            class="text-danger bank_branch_err _err "></span></label>
                                                    <input type="text" name="bank_branch" class="form-control"
                                                        id="">
                                                </div>
                                                <div class="col-md-6 col-xl-4">
                                                    <label for="" class="form-label">เลขที่บัญชี <span
                                                            class="text-danger small bank_no_err _err">*
                                                            (ใส่เฉพาะตัวเลขเท่านั้น)</span></label>
                                                    <input type="text" name="bank_no" minlength="10" maxlength="12"
                                                        class="form-control" id="">
                                                </div>
                                                <div class="col-md-6 col-xl-12 mb-3">
                                                    <label for="" class="form-label">ชื่อบัญชี <span
                                                            class="text-danger account_name_err _err "></span></label>
                                                    <input type="text" name="account_name" class="form-control"
                                                        id="">
                                                </div>
                                            </div>

                                        </div>


                                        <div class="row text-center mb-2">
                                            <div class="col-md-12 col-xl-12">
                                                <button type="submit"
                                                    class="btn btn-success rounded-pill">บันทึกข้อมูล</button>
                                                <button class="btn btn-danger rounded-pill">ยกเลิก</button>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            @endif



                            <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">ผู้รับผลประโยชน์</div>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="alert alert-danger d-flex align-items-center mb-0" role="alert">
                                        <i class='bx bxs-error me-2'></i>
                                        <div>
                                            ถ้าไม่กรอกถือว่าผู้รับผลประโยชน์จะเป็นผู้รับผลประโยชน์ตามกฎหมาย
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">ชื่อ <span
                                            class="text-danger name_benefit_err _err "></span></label>
                                    <input type="text" name="name_benefit" class="form-control" id=""
                                        value="{{ @$info_benefit->name }}" readonly>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">นามสกุล <span
                                            class="text-danger last_name_benefit_err _err "></span></label>
                                    <input type="text" name="last_name_benefit" class="form-control" id=""
                                        value="{{ @$info_benefit->last_name }}" readonly>
                                </div>
                                <div class="col-md-6 col-xl-4 mb-3">
                                    <label for="" class="form-label">เกี่ยวข้องเป็น <span
                                            class="text-danger involved_err _err "></span></label>
                                    <input type="text" name="involved" class="form-control" id=""
                                        value="{{ @$info_benefit->involved }}" readonly>
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
    {{-- sweetalert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('#linkMenuTop .nav-item').eq(0).addClass('active');
    </script>


    {{-- FORM --}}
    <script>
        function printErrorMsg(msg) {

            $('._err').text('');
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(`*${value}*`);
            });
        }

        //BEGIN form_customers_info
        $('#form_customers_info').submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('update_customers_info') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if ($.isEmptyObject(data.error) || data.status == "success") {
                        Swal.fire({

                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'ปิด',

                        }).then((result) => {
                            location.reload();
                        })
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });
        //END form_customers_info

        //BEGIN form_customers_info
        $('#form_update_same_address').submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('update_same_address') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if ($.isEmptyObject(data.error) || data.status == "success") {
                        Swal.fire({

                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'ปิด',

                        }).then((result) => {
                            location.reload();
                        })
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });
        //END form_customers_info

        //BEGIN form_cerate_info_bank_last
        $('#form_cerate_info_bank_last').submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('cerate_info_bank_last') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if ($.isEmptyObject(data.error) || data.status == "success") {
                        Swal.fire({

                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'ปิด',

                        }).then((result) => {
                            location.reload();
                        })
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });
        //END form_cerate_info_bank_last


        //BEGIN form_update_info_card
        $('#form_update_info_card').submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('form_update_info_card') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if ($.isEmptyObject(data.error) || data.status == "success") {
                        Swal.fire({

                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'ปิด',

                        }).then((result) => {
                            location.reload();
                        })
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });
        //END form_update_info_card
    </script>
    {{-- FORM --}}


    {{-- BEGIN Action same_address --}}
    <script>
        $('#same_address').click(function() {

            if (this.checked) {
                $('.card_address').each(function(key) {
                    $('.address_same_card').eq(key).val($(this).val()).attr('readonly', true);
                    $("#same_district").attr('disabled', false);
                    $("#same_tambon").attr('disabled', false);
                    $('#same_district,#same_tambon,#same_province').addClass('disabled_select')
                });
            } else {
                $('.address_same_card').val('').attr('readonly', false);
                $("#same_district").attr('disabled', true);
                $("#same_tambon").attr('disabled', true);
                $('#same_district,#same_tambon,#same_province').removeClass('disabled_select')

            }
        });
    </script>
    {{-- END Action same_address --}}



    {{-- --------------------- Address Card  --------------------- --}}
    <script>
        // BEGIN province

        $(document).ready(function() {

            $('#province').change();
            $('#district').change();
            $('#tambon').change();

            $('#same_province').change();
            $('#same_district').change();
            $('#same_tambon').change();


            let same_addredd = ` {{ @$address_delivery->status }}`;
            if (same_addredd == 1) {
                $('#status_address').click();
            }
        });

        $("#province").change(function() {

            let province_id = $(this).val();

            $.ajax({
                url: '{{ route('getDistrict') }}',
                type: 'GET',
                dataType: 'json',
                async: false,
                data: {
                    province_id: province_id,
                },
                success: function(data) {
                    $("#district").children().remove();
                    $("#tambon").children().remove();
                    $("#district").append(` <option value="">--กรุณาเลือก--</option>`);
                    $("#tambon").append(` <option value="">--กรุณาเลือก--</option>`);
                    $("#zipcode").val("");

                    let district = ` {{ @$address_card->district }}`;
                    data.forEach((item) => {
                        $("#district").append(
                            `<option  ${district == item.district_id ? 'selected' : ''} value="${item.district_id}">${item.district_name}</option>`
                        );
                        $("#same_district").append(
                            `<option value="${item.district_id}">${item.district_name}</option>`
                        );
                    });
                    $("#district").attr('disabled', false);
                    $("#tambon").attr('disabled', true);

                },
                error: function() {}
            })
        });
        // END province

        // BEGIN district
        $("#district").change(function() {
            let district_id = $(this).val();
            $.ajax({
                url: '{{ route('getTambon') }}',
                type: 'GET',
                dataType: 'json',
                async: false,
                data: {
                    district_id: district_id,
                },
                success: function(data) {
                    $("#tambon").children().remove();
                    $("#tambon").append(` <option value="">--กรุณาเลือก--</option>`);
                    $("#zipcode").val("");

                    let tambon = ` {{ @$address_card->tambon }}`;
                    data.forEach((item) => {
                        $("#tambon").append(
                            `<option ${tambon == item.tambon_id ? 'selected' : ''} value="${item.tambon_id}">${item.tambon_name}</option>`
                        );
                        $("#same_tambon").append(
                            `<option value="${item.tambon_id}">${item.tambon_name}</option>`
                        );
                    });
                    $("#tambon").attr('disabled', false);
                },
                error: function() {}
            })
        });
        // BEGIN district

        //  BEGIN tambon
        $("#tambon").change(function() {
            let tambon_id = $(this).val();
            $.ajax({
                url: '{{ route('getZipcode') }}',
                type: 'GET',
                dataType: 'json',
                async: false,
                data: {
                    tambon_id: tambon_id,
                },
                success: function(data) {
                    $("#zipcode").val(data.zipcode);
                },
                error: function() {}
            })
        });
        //  END tambon
    </script>
    {{-- --------------------- Address Card --------------------- --}}

    {{-- --------------------- Address shipping  --------------------- --}}
    <script>
        // BEGIN province
        $("#same_province").change(function() {
            let province_id = $(this).val();
            $.ajax({
                url: '{{ route('getDistrict') }}',
                type: 'GET',
                dataType: 'json',
                async: false,
                data: {
                    province_id: province_id,
                },
                success: function(data) {
                    let district = ` {{ @$address_delivery->district }}`;
                    $("#same_district").children().remove();
                    $("#same_tambon").children().remove();
                    $("#same_district").append(` <option value="">--กรุณาเลือก--</option>`);
                    $("#same_tambon").append(` <option value="">--กรุณาเลือก--</option>`);
                    $("#same_zipcode").val("");
                    data.forEach((item) => {
                        $("#same_district").append(
                            `<option ${district == item.district_id ? 'selected' : ''}   value="${item.district_id}">${item.district_name}</option>`
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
                async: false,
                data: {
                    district_id: district_id,
                },
                success: function(data) {

                    $("#same_tambon").children().remove();
                    $("#same_tambon").append(` <option value="">--กรุณาเลือก--</option>`);
                    $("#same_zipcode").val("");
                    let tambon = ` {{ @$address_delivery->tambon }}`;
                    data.forEach((item) => {
                        $("#same_tambon").append(
                            `<option ${tambon == item.tambon_id ? 'selected' : ''}  value="${item.tambon_id}">${item.tambon_name}</option>`
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
                async: false,
                data: {
                    tambon_id: tambon_id,
                },
                success: function(data) {
                    $("#same_zipcode").val(data.zipcode);
                },
                error: function() {}
            })
        });
        //  END tambon
    </script>
    {{-- --------------------- Address shipping --------------------- --}}





    {{-- BEGIN Action same_address --}}
    <script>
        $('#status_address').click(function() {

            if (this.checked) {

                $('#province').change();
                $('#district').change();
                $('#tambon').change();
                $('.card_address').each(function(key) {
                    $('.address_same_card').eq(key).val($(this).val()).attr('readonly', true);
                    $("#same_district").attr('disabled', false);
                    $("#same_tambon").attr('disabled', false);
                    $('#same_district,#same_tambon,#same_province').addClass('disabled_select')
                });
            } else {

                $('.address_same_card').val('').attr('readonly', false);
                $("#same_district").attr('disabled', true);
                $("#same_tambon").attr('disabled', true);
                $('#same_district,#same_tambon,#same_province').removeClass('disabled_select')

            }
        });
    </script>
    {{-- END Action same_address --}}




    {{-- BEGIN  Preview image --}}
    <script>
        file_card.onchange = evt => {
            const [file] = file_card.files
            if (file) {
                img_card.src = URL.createObjectURL(file)
            }
        }

        file_bank.onchange = evt => {
            const [file] = file_bank.files
            if (file) {
                img_bank.src = URL.createObjectURL(file)
            }
        }
    </script>

    {{-- END  Preview image --}}
@endsection
