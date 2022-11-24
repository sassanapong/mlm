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
                    <form id="form_register">
                        @csrf
                        <div class="card card-box borderR10 mb-2 mb-md-0">
                            <div class="card-body">
                                <h4 class="card-title">สมัครสมาชิก</h4>
                                <hr>

                                <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">ข้อมูลส่วนตัว</div>
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-4 col-xxl-3">
                                        <label for="" class="form-label">รหัสผู้แนะนำ <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="sponser"
                                            value="{{ Auth::guard('c_user')->user()->user_name }}" disabled>
                                            <input type="hidden" class="form-control"   name="sponser"
                                            value="{{ Auth::guard('c_user')->user()->user_name }}" id="">
                                    </div>
                                    <div class="col-md-6 col-lg-2 col-xxl-1">
                                        <label for="" class="form-label d-none d-md-block">&nbsp;</label>
                                        {{-- <button class="btn btn-p1 rounded-pill">ตรวจ</button> --}}
                                        {{-- <a class="btn btn-outline-dark rounded-circle btn-icon" onclick="clear_sponser()"><i
                                                class="bx bx-x"></i></a> --}}
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-xxl-8 mb-3">
                                        <label for="" class="form-label">ชื่อผู้แนะนำ <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="sponser_name"
                                            value="{{ Auth::guard('c_user')->user()->name }} {{ Auth::guard('c_user')->user()->last_name }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">ข้อมูลส่วนตัว</div>
                                <div class="row g-3">
                                    <div class="col-md-6 col-xl-6">
                                        <label for="" class="form-label">ขนาดธุรกิจ <span
                                                class="text-danger sizebusiness_err _err">*</span></label>
                                        <select name="sizebusiness" class="form-select" id="sizebusiness">
                                            <option selected disabled>เลือกขนาดธุรกิจ</option>
                                            <option value="MB">MB</option>
                                            <option value="MO">MO</option>
                                            <option value="VIP">VIP</option>
                                            <option value="VVIP">VVIP</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xl-6">
                                        <label for="" class="form-label">PV <span
                                                class="text-danger pv_err _err">*</span></label>
                                        <input name="pv" readonly type="text" class="form-control" id="pv">
                                    </div>
                                    <div class="col-md-6 col-xl-3">
                                        <label for="" class="form-label">คำนำหน้า <span
                                                class="text-danger prefix_name_err _err">*</span></label>
                                        <select name="prefix_name" class="form-select" id="">
                                            <option selected disabled>เลือกคำนำหน้า</option>
                                            <option value="นาย">นาย</option>
                                            <option value="นาง">นาง</option>
                                            <option value="นางสาว">นางสาว</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-xl-3">
                                        <label for="" class="form-label">ชื่อ <span
                                                class="text-danger name_err _err">*</span></label>
                                        <input name="name" type="text" class="form-control" id="">
                                    </div>
                                    <div class="col-md-3 col-xl-3">
                                        <label for="" class="form-label">นามสกุล <span
                                                class="text-danger last_name_err _err">*</span></label>
                                        <input name="last_name" type="text" class="form-control" id="">
                                    </div>
                                    <div class="col-md-6 col-xl-3">
                                        <label for="" class="form-label">เพศ <span
                                                class="text-danger gender_err _err">*</span></label>
                                        <select name="gender" class="form-select" id="">
                                            <option selected disabled>เลือกเพศ</option>
                                            <option value="ชาย">ชาย</option>
                                            <option value="หญิง">หญิง</option>
                                            <option vlaue="ไม่ระบุ">ไม่ระบุ</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xl-6">
                                        <label for="" class="form-label">ชื่อทางธุรกิจ <span
                                                class="text-danger business_name_err _err">*</span></label>
                                        <input name="business_name" type="text" class="form-control" id="">
                                    </div>
                                    <div class="col-md-6 col-xl-2">
                                        <label for="" class="form-label">วันเกิด <span
                                                class="text-danger day_err _err">*</span></label>
                                        <select name="day" class="form-select" id="">
                                            <option selected disabled>วัน</option>

                                            @foreach ($day as $val)
                                                <option val="{{ $val }}">{{ $val }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xl-2">

                                        <label for=""
                                            class=" text-danger form-label d-none d-md-block month_err _err">&nbsp;</label>
                                        <select name="month" class="form-select" id="">
                                            <option selected disabled>เดือน</option>
                                            <option value="01">มกราคม</option>
                                            <option value="02">กุมภาพันธ์</option>
                                            <option value="03">มีนาคม</option>
                                            <option value="04">เมษายน</option>
                                            <option value="05">พฤษภาคม</option>
                                            <option value="06">มิถุนายน</option>
                                            <option value="07">กรกฎาคม</option>
                                            <option value="08">สิงหาคม</option>
                                            <option value="09">กันยายน</option>
                                            <option value="10">ตุลาคม</option>
                                            <option value="11">พฤศจิกายน</option>
                                            <option value="12">ธันวาคม</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xl-2">
                                        <label for=""
                                            class="text-danger form-label d-none d-md-block year_err _err">&nbsp;</label>
                                        <select class="form-select" id="" name="year">
                                            <option selected disabled>ปี</option>
                                            @foreach ($arr_year as $val)
                                                <option val="{{ $val }}">{{ $val }}</option>
                                            @endforeach


                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xl-2">
                                        <label for="" class="form-label">สัญชาติ <span
                                                class="text-danger nation_id_err _err">*</span></label>
                                        <select class="form-select" name="nation_id" id="nation_id">
                                            {{-- <option selected disabled>เลือกสัญชาติ</option> --}}
                                            @php $region = DB::table('dataset_business_location')->get(); @endphp
                                            @foreach (@$region as $r)
                                                <option value="{{ @$r->id }}">{{ @$r->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xl-5">
                                        <label for="" class="form-label">เลขบัตรประชาชน <span
                                                class="text-danger id_card_err _err">*</span></label>
                                        <input name="id_card" type="text" class="form-control" maxlength="13"
                                            id="id_card" >
                                            <span class="error text-danger"></span>
                                    </div>
                                    <div class="col-md-6 col-xl-5">
                                        <label for="" class="form-label">โทรศัพท์ <span
                                                class="text-danger phone_err _err">*</span></label>
                                        <input name="phone" type="text" class="form-control" id="" maxlength="10" minlength="10" onkeyup="isThaichar(this.value,this)">
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="" class="form-label">E-mail <span
                                                class="text-danger email_err _err"></span></label>
                                        <input name="email" type="text" class="form-control" id="">
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="" class="form-label">Line ID</label>
                                        <input name="line_id" type="text" class="form-control" id="">
                                    </div>
                                    <div class="col-md-6 col-xl-4 mb-3">
                                        <label for="" class="form-label">Facebook</label>
                                        <input name="fackbook" type="text" class="form-control" id="">
                                    </div>
                                </div>
                                <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">ที่อยู่ตามบัตรประชาชน
                                </div>
                                <div class="row g-3">
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
                                                src="https://via.placeholder.com/250x300.png?text=card" />
                                        </div>

                                    </div>
                                    <div class="col-md-8 my-auto">
                                        <div id="group_data_card_address" class="row ">

                                            <div class="col-md-6 col-xl-5 ">
                                                <label for="" class="form-label">ที่อยู่ <span
                                                        class="text-danger card_address_err _err">*</span></label>
                                                <input type="text" name="card_address" value=""
                                                    class="form-control card_address" id="">
                                            </div>
                                            <div class="col-md-6 col-xl-3">
                                                <label for="" class="form-label">หมู่ที่ <span
                                                        class="text-danger card_moo_err _err">*</span></label>
                                                <input type="text" name="card_moo" class="form-control card_address"
                                                    id="">
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label for="" class="form-label">ซอย <span
                                                        class="text-danger card_soi_err _err">*</span></label>
                                                <input type="text" name="card_soi" class="form-control card_address"
                                                    id="">
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label for="" class="form-label">ถนน <span
                                                        class="text-danger card_road_err _err">*</span></label>
                                                <input type="text" name="card_road" class="form-control card_address"
                                                    id="">
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label for="province" class="form-label">จังหวัด</label>
                                                <label class="form-label text-danger card_province_err _err"></label>
                                                <select class="form-select card_address" name="card_province"
                                                    id="province">
                                                    <option value="">--กรุณาเลือก--</option>
                                                    @foreach ($province as $item)
                                                        <option value="{{ $item->province_id }}">
                                                            {{ $item->province_name }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                            <div class="col-md-6 col-xl-4">

                                                <label for="district" class="form-label">อำเภอ/เขต</label>
                                                <label class="form-label text-danger card_district_err _err"></label>
                                                <select class="form-select card_address" name="card_district"
                                                    id="district" disabled>
                                                    <option value="">--กรุณาเลือก--</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label for="tambon" class="form-label">ตำบล</label>
                                                <label class="form-label text-danger tambon_err _err"></label>
                                                <select class="form-select card_address" name="card_tambon"
                                                    id="tambon" disabled>
                                                    <option value="">--กรุณาเลือก--</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label for="" class="form-label">รหัสไปรษณีย์ <span
                                                        class="text-danger card_zipcode_err _err">*</span></label>
                                                <input id="zipcode" name="card_zipcode" type="text"
                                                    class="form-control card_address" id="">
                                            </div>
                                            <div class="col-md-6 col-xl-4 mb-3">
                                                <label for="" class="form-label">เบอร์มือถือ</label>
                                                <input type="text" name="card_phone" maxlength="10" class="form-control card_address"
                                                    id="">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">
                                    ที่อยู่จัดส่ง
                                    <div class="form-check form-check-inline h6 fw-normal">
                                        <input class="form-check-input" id="status_address" type="checkbox"
                                            name="status_address" value="1" id="flexCheckDefault">
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
                                    <div class="col-md-6 col-xl-4 mb-3">
                                        <label for="" class="form-label">เบอร์มือถือ</label>
                                        <input type="text" name="same_phone" class="form-control address_same_card"
                                            id="" maxlength="10">
                                    </div>
                                </div>
                                <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">
                                    ข้อมูลบัญชีธนาคารเพื่อรับรายได้</div>
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



                                </div>
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
                                        <input type="text" name="name_benefit" class="form-control" id="">
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="" class="form-label">นามสกุล <span
                                                class="text-danger last_name_benefit_err _err "></span></label>
                                        <input type="text" name="last_name_benefit" class="form-control"
                                            id="">
                                    </div>
                                    <div class="col-md-6 col-xl-4 mb-3">
                                        <label for="" class="form-label">เกี่ยวข้องเป็น <span
                                                class="text-danger involved_err _err "></span></label>
                                        <input type="text" name="involved" class="form-control" id="">
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div  class="col-md-12 text-center">

                                        <hr>
                                        <p onclick="alert_summit()" class="btn btn-success rounded-pill">บันทึกข้อมูล</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




@section('script')
    {{-- sweetalert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $('#linkMenuTop .nav-item').eq(0).addClass('active');
    </script>
    <script>
        function isThaichar(str,obj){
                var orgi_text="1234567890";
                var str_length=str.length;
                var str_length_end=str_length-1;
                var isThai=true;
                var Char_At="";
                for(i=0;i<str_length;i++){
                    Char_At=str.charAt(i);
                    if(orgi_text.indexOf(Char_At)==-1){
                        isThai=false;
                    }
                }
                if(str_length>=1){
                    if(isThai==false){
                        obj.value=str.substr(0,str_length_end);
                    }
                }
            }
    </script>



    <script>
        function printErrorMsg(msg) {
            console.log(msg);
            $('._err').text('');
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(`*${value}*`);
            });
        }

        function clear_sponser() {
            $('#sponser').val('');
            $('#sponser_name').val('');
        }

        $('#sponser').change(function() {
            sponser = $(this).val();
            if (sponser == '') {
                return;
            }
            $.ajax({
                    url: '{{ route('check_sponser') }}',
                    type: 'GET',
                    data: {
                        sponser: sponser,
                        user_name: '{{ Auth::guard('c_user')->user()->user_name }}'
                    },
                })
                .done(function(data) {

                    if (data['status'] == 'fail') {

                        Swal.fire({
                            icon: 'error',
                            title: data['message'],
                        })

                        $('#sponser').val('');
                        $('#sponser_name').val('');


                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: data['data']['name'] + data['data']['last_name'] + ' (' + data[
                                'data'][
                                'user_name'
                            ] + ')',
                            text: data['message'],
                        })

                        sponser_name = data['data']['name'] + data['data']['last_name'];
                        $('#sponser_name').val(sponser_name);

                    }

                    console.log(data);
                })
                .fail(function() {
                    console.log("error");
                })
        })

        function alert_summit() {


            Swal.fire({
                title: 'เงื่อนไขและข้อตกลงใน',
                html: `    <div class="row info_alert">
        <div class="col-12 overflow-auto">
            <p style="font-size: 12px;" >
ข้อตกลงระหว่างสมาชิกของบริษัทมารวยด้วยกัน จำกัด กับสมาชิก มีรายละเอียดดังนี้
<br>
บริษัทมารวยด้วยกัน จำกัด ทำธุรกิจจำหน่ายสินค้าเพื่อสุขภาพ ฯลฯ ซึ่งต่อไปนี้จะเรียกว่า “บริษัท” สมาชิกบริษัทมารวยด้วยกัน จำกัด คือผู้สั่งซื้อสินค้าของทางบริษัทและสมัครเป็นสมาชิกเครือข่ายของบริษัท เพื่อช่วยกันจำหน่ายสินค้าหรือกระจายสินค้า และ สามารถสั่งซื้อสินค้าได้ในราคาที่บริษัทกำหนด
 และยังได้รับค่าตอบแทนที่ท่านแนะนำท่านอื่นมาซื้อสินค้าและเป็นสมาชิกอีกด้วย ซึ่งต่อไปนี้จะเรียกว่า “สมาชิก”
 <br>

1.ข้าพเจ้ามีอายุไม่น้อยกว่า 20 ปีบริบูรณ์ในวันที่ทำการสมัคร  และมีเอกสารรับรองเพื่อใช้ในการสมัครสมาชิก หากข้อความดังกล่าวไม่เป็นจริง ข้าพเจ้ายินยอมให้ทางบริษัทฯ เพิกถอนสมาชิก และดำเนินคดีจนถึงที่สุด
<br>
2.ผู้ประกอบธุรกิจขายตรงที่จะจ่ายผลตอบแทนให้กับผู้จำหน่ายอิสระตามแผนการจ่ายผลตอบแทนที่ได้จดทะเบียนไว้ต่อนายทะเบียน ณ สำนักงานคณะกรรมการคุ้มครองผู้บริโภค สคบ. ตามที่ปรากฏในแผนการจ่ายผลตอบแทนของผู้ประกอบธุรกิจขายตรงที่มอบแก่ผู้จำหน่ายอิสระ
<br>
3.ผู้ประกอบธุรกิจขายตรงยินยอมที่จะรับซื้อคืนสินค้า วัสดุอุปกรณ์ส่งเสริมการขาย ชุดคู่มือ หรืออุปกรณ์ส่งเสริมธุรกิจจากผู้จำหน่ายอิสระ ในกรณีที่ผู้จำหน่ายอิสระประสงค์จะใช้สิทธิ์คืนสินค้าดังกล่าว โดยที่ผู้จำหน่ายอิสระจะต้องแสดงความจำนงใช้สิทธิ์ดังกล่าวต่อผู้ประกอบธุรกิจขายตรงภายในเวลา
 30 นับแต่วันที่ได้สินค้า
 <br>
4.เมื่อผู้จำหน่ายอิสระใช้สิทธิ์คืนสินค้า วัสดุอุปกรณ์ส่งเสริมการขาย  ชุดคู่มือ หรืออุปกรณ์ส่งเสริมธุรกิจ ที่ได้ซื้อไปจากผู้ประกอบธุรกิจขายตรง  ให้ผู้ประกอบธุรกิจขายตรงซื้อคืนตามราคาที่ผู้จำหน่ายอิสระได้จ่ายไป ภายใน 15 วัน  นับตั้งแต่วันที่ผู้จำหน่ายอิสระได้ใช้สิทธิ์คืน
 แต่ในการใช้สิทธิ์ดังกล่าว กรณีที่สิ้นสุดระยะเวลาลงตามข้อ 3 , ผู้ประกอบธุรกิจขายตรงมีสิทธิ์หักค่าดำเนินการต่างๆได้ และมีสิทธิ์หักลบกลบหนี้ใดๆอันเกี่ยวกับสัญญาข้อ 1 ที่ผู้จำหน่ายอิสระต้องชำระได้
 <br>
5.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า การสั่งซื้อสินค้าและสมัครเป็นสมาชิกเป็นการสั่งซื้อสินค้าล่วงหน้าตามเงื่อนไขและแผนการตลาดของทางบริษัท และจะได้รับสินค้าจากบริษัทภายใน 60 วัน นับจากวันที่สั่งซื้อสินค้าและสมัครเป็นสมาชิกสินค้าที่สั่งซื้อแล้ว และได้มีการคิดคำนวณผลตอบแทนไปแล้วนั้น
 จะไม่สามารถคืนได้ไม่ว่ากรณีใดๆ นอกจากนี้เงินค่าสินค้าที่ผู้สั่งซื้อสินค้าตามเงื่อนไขและแผนการตลาดของบริษัทฯ  บริษัทฯไม่สามารถคืนให้แก่ลูกค้าได้เนื่องจากตามเงื่อนไขและแผนการตลาด มีค่าบริหารจัดการ ค่าการตลาด ค่าภาษีอากร และค่าใช้จ่ายอื่น ๆซึ่งมีการคำนวณผลประโยชน์ตอบแทนหรือโบนัสให้แก่สมาชิกจากผลประกอบการและผลกำไรจากการขายสินค้า
 เป็นระบบทั้งระบบ ดังนั้น จึงไม่สามารถคืนให้แก่ลูกค้าได้
 <br>
6.ข้อผู้พันอื่นๆที่ผู้ประกอบธุรกิจขายตรงสามารถกำหนดได้ให้อยู่ภายใต้กรอบของกฎหมายว่าด้วยการขายตรงและตลาดแบบตรง  และข้อผูกพันนั้นต้องไม่ขัดต่อความสงบเรียบร้อยของศีลธรรมอันดีของประชาชน
<br>
7.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า การสั่งซื้อสินค้าและการเป็นสมาชิกของข้าพเจ้ามิได้มีส่วนเกี่ยวข้องกับบริษัทในฐานะผู้ถือหุ้น, กรรมการ, ผู้บริหาร, พนักงาน, เจ้าหน้าที่, ลูกจ้าง,หรือตัวแทนบริษัทดังนั้น ข้าพเจ้าไม่มีอำนาจก่อให้เกิดนิติกรรมใดๆในนามบริษัทกับผู้อื่น ฯลฯ
<br>
8.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่าการสั่งซื้อสินค้าและสมัครเป็นสมาชิกของข้าพเจ้า บริษัทมิได้เป็นผู้เชิญชวนหรือชักชวน แต่ข้าพเจ้าได้รับการแนะนำต่อ  จากผู้ที่เคยใช้สินค้ามาก่อนและมีสถานภาพการเป็นสมาชิกของ บริษัทมารวยด้วยกัน จำกัดเท่านั้นและมีวัตถุประสงค์ในการร่วมกันบอกต่อเพื่อจำหน่ายสินค้าและกระจายสินค้าสู่ผู้บริโภคเพื่อให้เกิดผลกำไร
<br>
9.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่าสมาชิก สิ้นสุดสถานภาพการเป็นสมาชิกลงเมื่อ
<br>
9.1 เสียชีวิตและไม่มีทายาทผู้สืบทอดมรดกเข้ามารับโอนสถานภาพสมาชิกภายในกำหนด 1 ปีนับตั้งแต่วันที่สมาชิกเสียชีวิต
<br>
9.2 ขาดการรักษาสถานะภาพสมาชิกนานติดต่อกัน90วัน
<br>
9.3 ถูกเพิกถอนจากการเป็นสมาชิก
<br>
10.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า กรณีที่สถานภาพของสมาชิก ได้สิ้นสุดลงด้วยการเหตุใดก็ตามสมาชิกผู้ที่สถานภาพได้สิ้นสุดลงไปแล้วนั้น สามารถสมัครเป็นสมาชิกภายหลังจาก  6 เดือน (180วัน) นับตั้งแต่วันที่สถานภาพได้สิ้นสุดลง
<br>
11.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า กรณีที่สมาชิก ไม่อาจดำเนินการโอนสถานภาพการเป็นสมาชิกด้วยตนเองเพราะเหตุใดเหตุหนึ่งตามที่ระบุไว้ดังต่อไปนี้บริษัทจะโอนสถานภาพการเป็นสมาชิกให้แก่ผู้สืบทอดมรดกตามที่ระบุไว้ในใบสมัคร
<br>
11.1 กรณีสมาชิกเสียชีวิต
<br>
11.2 เมื่อแพทย์ลงความเห็นว่าวิกลจริต
<br>
11.3 เมื่อศาลมีคำสั่งให้เป็นคนไร้ความสามารถหรือเสมือนไร้ความสามารถ
<br>
12.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า ผู้สั่งซื้อสินค้าและสมาชิกผู้ร่วมธุรกิจอิสระ ต้องปฏิบัติตนให้เหมาะสมกับการมีอาชีพต่อไปนี้
<br>
12.1 ให้บริการที่ดีอย่างสม่ำเสมอ
<br>
12.2 มีความซื่อสัตย์ สุจริต จริงใจต่อลูกค้า บริษัทและเพื่อนร่วมอาชีพโดยจะไม่แย่งสายงานจากเพื่อนร่วมอาชีพ ไม่ทำให้ลูกค้าบริษัทฯ และเพื่อนร่วมอาชีพสูญเสียผลประโยชน์หากสมาชิกประพฤติผิดเกี่ยวกับการแย่งสายงาน หรือลูกค้า
ทางบริษัทจะตัดหรือเพิกถอนการเป็นสมาชิกออกจากบริษัททันที โดยไม่ต้องจ่ายเงินคืนหรือผลประโยชน์ใดๆ ให้แก่สมาชิกที่ประพฤติผิดนั้น
<br>
12.3 ไม่ลดหรือเสนอที่จะลดผลประโยชน์เพื่อจูงใจลูกค้า
<br>
12.4 ไม่กล่าวเปรียบเทียบให้ร้ายหรือพูดลบที่อาจก่อให้เกิดความเสียหายต่อบริษัทฯ ผู้บริหารและเพื่อนร่วมอาชีพอย่างไม่ยุติธรรมทางตรงและทางอ้อม
<br>
12.5 หมั่นศึกษาหาความรู้ในวิชาชีพเพื่อเพิ่มเติมอย่างสม่ำเสมอหมั่นเข้าฝึกอบรมหรือเข้าประชุมตามที่ทางบริษัทจัดขึ้นทุกครั้ง
<br>
12.6 พัฒนาบุคลิก การแต่งกาย ตรงต่อเวลานัด ให้ความร่วมมือในกิจกรรมกับบริษัท เป็นอย่างดี
<br>
12.7 ประพฤติตนอยู่ในศีลธรรม ประเพณีอันดีงาม ทั้งดำรงไว้ซึ่งเกียรติ และศักดิ์ศรีแห่งคุณธรรม
<br>
13.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่าบริษัทตกลงที่จะจ่ายผลตอบแทนให้กับผู้สั่งซื้อสินค้าและสมาชิกตามผลประกอบการและแผนการตลาดหรือจากผลกำไรจากการจำหน่ายสินค้าของบริษัท
<br>
14.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่านอกเหนือจากแผนงานหรือนโยบายของบริษัท และมิได้เกิดจากความยินยอมหรือรับรองจากบริษัทหรือการกระทำใดๆ
 ที่กล่าวอ้างบริษัท โดยก่อให้เกิดความเสียหายถือเป็นความรับผิดชอบเฉพาะตัวของข้าพเจ้าแต่เพียงผู้เดียวไม่เกี่ยวข้องกับบริษัท บริษัทจะไม่รับผิดชอบใดๆ และข้าพเจ้ายินยอมรับผิดชอบเป็นส่วนตัวในทางกฎหมาย ทั้งในทางแพ่งและทางอาญา
 <br>
15.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า ข้าพเจ้าจะช่วยบริษัทจำหน่ายสินค้าโดยการบอกต่อ และจะดูแลให้บริการลูกค้าที่ข้าพเจ้าแนะนำด้วยค่าใช้จ่ายของข้าพเจ้าเอง
<br>
16.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่าทางบริษัทห้ามสมาชิกทอดทิ้งลูกค้าและสายงานโดยจะต้องให้บริการที่ดีและมีความสม่ำเสมอแก่ลูกค้าเมื่อลูกค้าสมัครเข้าเป็นสมาชิก ในสายงานของตนก็ต้องให้คำแนะนำถึงวิธีดำเนินธุรกิจ ของบริษัทมารวยด้วยกัน จำกัด
ที่ถูกต้องโดยให้ช่วยกันขายสินค้า ของบริษัท บอกต่อคุณภาพของสินค้า ของบริษัทให้กำลังใจ ให้ข้อมูลและ แนะนำเข้าสู่แผนงานการฝึกอบรมต่างๆ ตามที่บริษัทได้จัดเสนอให้อย่างถูกต้องสม่ำเสมอ
<br>
17.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่าสมาชิกไม่มีสิทธิเรียกร้อง เงินเดือน, สวัสดิการ, เงินช่วยเหลือและ/หรือ รายได้อื่นใดจากบริษัท ยกเว้นรายได้, ผลตอบแทนและสวัสดิการเฉพาะส่วนที่สมาชิกพึงได้รับจากผลประกอบการหรือผลกำไรจากการขายสินค้า ของบริษัท ตามที่ระบุไว้ในแผนงานของบริษัท
<br>
18.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า หากสมาชิกกระทำการใดๆที่ก่อให้เกิดหนี้สินกับบริษัทไม่ว่าด้วยเหตุใด และจำนวนเงินเท่าใดบริษัทมีสิทธินำเอาผลประโยชน์ของสมาชิกมาหักหนี้นั้นๆได้
<br>
19.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่าจะไม่ยอมรับรูปแบบการทุจริตใดๆที่มาจากบุคคลอื่นในการที่จะสนับสนุนการหาสมาชิกใหม่หรือสนับสนุนสินค้าของทางบริษัทที่จะส่งผลต่อสถานภาพการเป็นผู้จำหน่ายสินค้า ของบริษัท ของข้าพเจ้าไม่ว่าจะเป็นในลักษณะใดก็ตาม
<br>
20.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า การติดต่อกับหน่วยงานราชการใดหรือองค์กรเอกชนใดเพื่อเป็นการดำเนินธุรกิจจะต้องได้รับอนุมัติจากบริษัทเป็นลายลักษณ์อักษร
<br>
21.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า ผลิตภัณฑ์ ของบริษัท อุปกรณ์ส่งเสริมการขาย สื่อสิ่งพิมพ์เครื่องหมายการค้า ชื่อ และอุปกรณ์ต่างๆที่เป็นกรรมสิทธิ์ของบริษัทฯ ห้ามมิให้นำไปใช้ ลอกเลียนแบบหรือทำซ้ำ หรือดัดแปลง เพื่อการใดๆทั้งสิ้น
<br>
22.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า ห้ามสมาชิกใช้สิทธิใดๆ เหนือเครื่องหมายการค้า เครื่องหมายบริการ
 โลโก้ รูปรอยประดิษฐ์ รวมตลอดถึงทรัพย์สินทางปัญญาทั้งหลายทั้งปวงของบริษัทหากไม่ได้รับความยินยอมเป็นหนังสือจากกรรมการหรือตัวแทนผู้มีอำนาจของบริษัทฯ
 <br>
23.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า จะนำเสนอผลิตภัณฑ์ ของบริษัท แผนงานการดำเนินธุรกิจและการฝึกอบรมต่างๆ โดยจะไม่โฆษณาชักชวน โดยกล่าวคำอวดอ้างหรือคำยืนยันที่ไม่เป็นจริงนอกเหนือจากที่บริษัทได้ประกาศรับรองไว้ในเอกสารหรือแผนงานของบริษัท
<br>
24.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่าห้ามสมาชิกบังคับซื้อสินค้าตลอดจนถึงการกักตุนสินค้าหรือบังคับเกินความจำเป็นในการใช้สินค้าหรือการขยายธุรกิจ
<br>
25.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า ห้ามสมาชิกจำหน่าย หรือ จัดวางสินค้าของบริษัทฯ ในสถานที่ใด ๆ เช่นขายปลีกร้านค้าสหกรณ์ ตลาดขนาดเล็กซุปเปอร์มาร์เก็ต ห้างสรรพสินค้า หรือสถานที่อื่น หรือ โฆษณาเพื่อเสนอขายสินค้าดังกล่าวทางสื่อใดๆโดยไม่ได้รับอนุมัติจากบริษัทเป็นลายลักษณ์อักษร โดยเด็ดขาดเพื่อไม่ก่อให้เกิดการได้เปรียบเหนือสมาชิกอื่นๆ
<br>
26.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า ห้ามสมาชิก จำหน่ายหรือเสนอผลิตภัณฑ์ ของบริษัท แก่สายงานอื่น รวมทั้งห้ามชักชวนให้สมาชิกผู้อื่นเปลี่ยนสายงานโดยเด็ดขาดเพื่อไม่ให้เกิดความสับสน แตกความสามัคคี และส่งผลกระทบต่อการบริหารจัดการการตลาดของสมาชิกในสายงานอื่นๆ
<br>
27.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า จะทำความเข้าใจที่ถูกต้องเกี่ยวกับบริษัท ไม่บิดเบือนข้อมูลอวดอ้างหรือนำเสนอข้อมูลที่อาจก่อให้เกิดความเข้าใจผิดเกี่ยวกับการดำเนินงาน ผลิตภัณฑ์หรือระบบตอบแทนของบริษัท
 โดยจะต้องให้ข้อเท็จจริงแก่ลูกค้าอย่างชัดเจนว่า ธุรกิจ บริษัทมารวยด้วยกัน จำกัด จำกัด คือโอกาสที่จะสร้างผลงานการขายสินค้า ของบริษัท ที่นำมาซึ่งรายได้จากระบบแผนการตลาด โดนคิดคำนวณจากยอดซื้อ ยอดขายทั้งส่วนตัวและทีมงานมิใช่นำมาจากยอดการหาจำนวนคนเข้าสู่เครือข่ายสายงาน
 <br>
28.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า จะไม่จำหน่ายผลิตภัณฑ์ของบริษัท  ของบริษัทต่ำกว่าราคาสมาชิก และสมาชิกบริษัทไม่มีสิทธิเป็นผู้กำกับดูแล กำหนดราคานอกเหนือจากราคาของบริษัท เพื่อให้สอดคล้องกับความต้องการของตลาดเพื่อให้เกิดประโยชน์สูงสุดแก่สมาชิกอื่น
<br>
29.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่าห้ามสมาชิกจำหน่ายหรือเสนอบริการอื่นที่ไม่ใช่ผลิตภัณฑ์ของบริษัทอันเป็นคู่แข่งของสินค้าของบริษัทโดยเด็ดขาด
<br>
30.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า หากเกิดกรณีพิพาทขึ้นระหว่างสมาชิกกับสมาชิก หรือสมาชิกกับลูกค้า หรือสมาชิกกับบริษัท ก็ตามให้ยึดเอาคำตัดสินของกรรมการบริษัท เป็นที่สุด
<br>
31.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่าหากมีกรณีที่ รายได้ และ/หรือผลตอบแทนใดๆที่บริษัทจ่ายให้เกินจากสิทธิที่ข้าพเจ้าพึงได้รับข้าพเจ้าจะต้องคืนให้บริษัททันทีที่ข้าพเจ้าทราบหรือได้รับแจ้งจากบริษัทโดยชำระคืนเป็นเงินสดหรือยินยอมให้บริษัทหักจากรายได้ตามที่ได้เกินมา
<br>
32.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า ข้าพเจ้าขอมอบอำนาจให้บริษัทเก็บ และถ่ายทอดข้อมูล(ส่วนตัวข้าพเจ้า, ข้อมูลเกี่ยวกับลูกค้า, ข้อมูลเกี่ยวกับการดำเนินธุรกิจขายตรงและข้อมูลอื่นๆที่เกี่ยวข้องทั้งหมด) ได้โดยวิธีการทางอิเล็กทรอนิกส์
<br>
33.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า ข้าพเจ้าเต็มใจที่จะยอมรับกฎระเบียบ ข้อบังคับต่างๆและจะปฏิบัติตามกฎหมาย ซึ่งรวมถึงพระราชบัญญัติการขายตรงและตลาดแบบขายตรงพ.ศ.2545ทั้งหมดด้วย
<br>
34.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่าการกระทำของสมาชิก อันจะนำไปสู่มาตรการลงโทษ มีดังนี้
<br>
34.1 สมาชิกฝ่าฝืนกฎระเบียบข้อบังคับ จรรยาบรรณของบริษัท
<br>
34.2 สมาชิกได้กระทำการอันเป็นการเสื่อมเสียต่อบริษัท และผลิตภัณฑ์ของบริษัท
<br>
34.3 สมาชิก กระทำการอันเป็นเหตุให้บริษัท เสียหายในการประกอบกิจการ
<br>
34.4 สมาชิก ได้แจ้งหรือแสดงข้อมูลเกี่ยวกับสมาชิก หรือข้อมูลเกี่ยวกับบริษัทไม่ตรงกับข้อเท็จจริง
<br>
35.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่ามาตรการ การลงโทษของบริษัทเรียงลำดับตามความรุนแรงของการกระทำดังต่อไปนี้
<br>
35.1 ตักเตือนเป็นลายลักษณ์อักษร
<br>
35.2 งดรับสิทธิประโยชน์ต่างๆ อันพึงจะได้ในรอบการจ่ายนั้นๆ
<br>
35.3 เพิกถอนสถานภาพออกจากการเป็นสมาชิก
<br>
36.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่า หากข้าพเจ้าประพฤติผิดไปจากเงื่อนไข, ข้อตกลง ตลอดจนแผนงานและกติกาที่บริษัทกำหนด
 รวมถึงจรรยาบรรณ กฎและระเบียบของกฎหมายขายตรงบริษัทมีสิทธิในการพิจารณาบอกเลิกการเป็นสมาชิก รวมถึงการตัด, ลด,งด,ระงับและหรือยกเลิกการจ่ายผลประโยชน์ของข้าพเจ้า ทั้งในส่วนที่เกิดขึ้นและจะเกิดขึ้นต่อไปในอนาคตโดยข้าพเจ้าไม่มีสิทธิเรียกร้องใดๆ จากบริษัททั้งสิ้น
 <br>
37.ข้าพเจ้าตกลงยินยอมและเข้าใจดีว่าบริษัทสงวนสิทธิในการพิจารณาเปลี่ยนแปลง, แก้ไข และปรับปรุงเงื่อนไข หรือข้อกำหนดต่างๆ ตามที่บริษัทเห็นสมควร โดยไม่ต้องแจ้งให้ทราบล่วงหน้าทั้งนี้บริษัทจะทำการแจ้งเป็นลายลักษณ์อักษรให้ทราบภายหลัง
<br>
38.ข้าพเจ้าได้อ่านและเข้าใจข้อความทั้งหมดแล้วและยอมรับเงื่อนไขต่าง ๆ ตามที่บริษัทกำหนดทุกประการด้วยความสมัครใจของข้าพเจ้าเอง
<br>
<b>***หากสมาชิกท่านใดที่มีอายุน้อยกว่า 20 ปีต้องมีเอกสารรับรองจากทางผู้ปกครองเพื่อใช้ในการสมัครสมาชิก มิฉะนั้นหากทางบริษัททราบภายหลังรหัสดังกล่าวจะถือว่าเป็นโมฆะทันที
</p>

        </div>
    </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยอมรับข้อตกลง',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form_register').submit();
                }
            })
        }


        //BEGIN form_register
        $('#form_register').submit(function(e) {
            Swal.fire({
            title: 'รอสักครู่...',
            html: 'ระบบกำลังทำรายการกรุณาอย่าปิดหน้านี้จนกว่าระบบจะทำรายการเสร็จ...',
            didOpen: () => {
                Swal.showLoading()
            },
        })

            e.preventDefault();
            var formData = new FormData($(this)[0]);
            // console.log(formData);



            $.ajax({
                url: '{{ route('store_register') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    swal.close();
                    if (data.pvalert) {
                        Swal.fire({
                            icon: 'warning',
                            title: data['pvalert'],
                        })
                    }
                    if ($.isEmptyObject(data.error) || data.status == "success") {
                        alert_result(data.data_result);
                    }

                    if(data.ms){

                        Swal.fire({
                            icon: 'warning',
                            title: data.ms,
                        })
                        printErrorMsg(data.error);
                    }
                }
            });
        });
        //END form_register

        $('#sizebusiness').change(function() {
            val = $(this).val();
            $.ajax({
                url: '{{ route('pv') }}',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    val: val
                },
                success: function(data) {
                    $('#pv').val(data)
                }
            });
        })
    </script>

    {{-- BEGIN create --}}
    <script>
        function alert_result(data) {
            if (data) {
                var html = `
            <div class="overflow-hidden " >
            <div class="row">
                <div class="col-12 text-right">ชื่อ-สกุล : ${data.prefix_name}${data.name} ${data.last_name}</div>
                <div class="col-12 text-right">ชื่อทางธุรกิจ : ${data.business_name} </div>
                <hr class="mt-3">
                <div class="col-12 text-right">UserName : ${data.user_name} </div>
                <div class="col-12 text-right">password : ${data.password}</div>
            </div>
        </div>
            `
                Swal.fire({
                    title: 'สมัครมาชิกสำเร็จ',
                    html: html,
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ปิด',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('home') }}";
                    }
                })
            }
        }
    </script>
    {{-- END create --}}

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

    {{-- BEGIN Action same_address --}}
    <script>
        $('#status_address').click(function() {

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
        $("#province").change(function() {
            let province_id = $(this).val();
            $.ajax({
                url: '{{ route('getDistrict') }}',
                type: 'GET',
                dataType: 'json',
                data: {
                    province_id: province_id,
                },
                success: function(data) {
                    $("#district").children().remove();
                    $("#tambon").children().remove();
                    $("#district").append(` <option value="">--กรุณาเลือก--</option>`);
                    $("#tambon").append(` <option value="">--กรุณาเลือก--</option>`);
                    $("#zipcode").val("");
                    data.forEach((item) => {
                        $("#district").append(
                            `<option value="${item.district_id}">${item.district_name}</option>`
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
                data: {
                    district_id: district_id,
                },
                success: function(data) {
                    $("#tambon").children().remove();
                    $("#tambon").append(` <option value="">--กรุณาเลือก--</option>`);
                    $("#zipcode").val("");
                    data.forEach((item) => {
                        $("#tambon").append(
                            `<option value="${item.tambon_id}">${item.tambon_name}</option>`
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
                },
                error: function() {}
            })
        });
        //  END tambon
    </script>

    {{-- --------------------- Address shipping --------------------- --}}

    <script>
        $('#nation_id').change(function() {
            value = $(this).val();
            if (value != "1") {
                $('#id_card').attr('maxlength', '15');
                $('#id_card').val("");
            } else {
                $('#id_card').attr('maxlength', '13');
                $('#id_card').val("");
            }
        })


$(document).ready(function(){


  $('#id_card').on('keyup',function(){
    nation_id = $('#nation_id').val();
    if(nation_id == 1){
        if($.trim($(this).val()) != '' && $(this).val().length == 13){
      id = $(this).val().replace(/-/g,"");
      var result = Script_checkID(id);
      if(result === false){
         id_card = $('#id_card').val();
        $('span.error').removeClass('true').text('เลขบัตร'+id_card+' ไม่ถูกต้อง');
        $('#id_card').val('');
      }else{
        // $('span.error').addClass('true').text('เลขบัตรถูกต้อง');
      }
    }else{
      $('span.error').removeClass('true').text('');

    }

    }

  })
});

function Script_checkID(id){
    if(! IsNumeric(id)) return false;
    if(id.substring(0,1)== 0) return false;
    if(id.length != 13) return false;
    for(i=0, sum=0; i < 12; i++)
        sum += parseFloat(id.charAt(i))*(13-i);
    if((11-sum%11)%10!=parseFloat(id.charAt(12))) return false;
    return true;
}
function IsNumeric(input){
    var RE = /^-?(0|INF|(0[1-7][0-7]*)|(0x[0-9a-fA-F]+)|((0|[1-9][0-9]*|(?=[\.,]))([\.,][0-9]+)?([eE]-?\d+)?))$/;
    return (RE.test(input));
}
    </script>
@endsection
