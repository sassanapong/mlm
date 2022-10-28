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
                                                <input type="text" name="card_phone" class="form-control card_address"
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
                                            id="">
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
                                    <div class="col-md-12 text-center">

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
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Obcaecati repellat animi temporibus enim est
            accusantium nesciunt necessitatibus et quisquam exercitationem! Ea mollitia ipsum excepturi doloribus unde
            explicabo, ex culpa maxime! Nesciunt perspiciatis maxime totam ratione eveniet sapiente corrupti obcaecati
            veniam, deleniti fugit, sequi cupiditate voluptas odit eligendi veritatis consequatur! Fugit sapiente laudantium
            ex ut quaerat vitae harum quas vero cupiditate officiis itaque perferendis distinctio ab repellat nisi optio
            voluptatem enim, maxime culpa iusto illo. Porro consequuntur non, voluptate quod fugit asperiores id magnam
            eveniet iusto ratione adipisci natus, odit eius? Maxime, dolore? Officiis quo voluptates tenetur ullam fuga
            sunt, cupiditate, dignissimos rem laboriosam beatae labore atque ratione, est eaque? Quasi nulla aspernatur
            quas. Laborum architecto odio harum? Quidem fugit ratione aut, voluptate expedita quaerat accusamus recusandae
            ex cupiditate eligendi esse suscipit nostrum, voluptatem id! Praesentium recusandae perferendis eos quam quos
            facere et quasi, quibusdam provident a blanditiis architecto sunt fugiat eveniet tempora nam. Nihil consectetur
            quam facilis natus, porro voluptates similique consequatur ut, necessitatibus dicta sequi. Delectus et dolore
            obcaecati quos eum reiciendis molestias veniam possimus praesentium excepturi beatae error dolor quo in
            consequatur vel quaerat, consequuntur nostrum, iusto vero quidem vitae quisquam quis fugit. Consequuntur
            sapiente officiis accusamus impedit.
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
                    if (data.pvalert) {
                        Swal.fire({
                            icon: 'warning',
                            title: data['pvalert'],
                        })
                    }
                    if ($.isEmptyObject(data.error) || data.status == "success") {
                        alert_result(data.data_result);
                    } else {
                        printErrorMsg(data.error);
                        Swal.fire({
                            icon: 'warning',
                            title: data['ms'],
                        })

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
    </script>
@endsection
