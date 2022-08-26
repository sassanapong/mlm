@extends('layouts.frontend.app')
@section('conten')
    <div class="bg-whiteLight page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
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
                                        <select naem="prefix_name"class="form-select" id="">
                                            <option selected disabled>เลือกคำนำหน้า</option>
                                            <option value="นาย">นาย</option>
                                            <option value="นาง">นาง</option>
                                            <option value="นางสาว">นางสาว</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xl-6">
                                        <label for="" class="form-label">ชื่อ - นามสกุล <span
                                                class="text-danger customers_name_err _err">*</span></label>
                                        <input name="customers_name" type="text" class="form-control" id="">
                                    </div>
                                    <div class="col-md-6 col-xl-3">
                                        <label for="" class="form-label">เพศ <span
                                                class="text-danger">*</span></label>
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
                                        <select class="form-select" id="">
                                            <option name="year" selected disabled>ปี</option>
                                            @foreach ($arr_year as $val)
                                                <option val="{{ $val }}">{{ $val }}</option>
                                            @endforeach


                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xl-2">
                                        <label for="" class="form-label">สัญชาติ <span
                                                class="text-danger nation_id_err _err">*</span></label>
                                        <select class="form-select" name="nation_id" id="">
                                            <option selected disabled>เลือกสัญชาติ</option>
                                            <option value="ไทย">ไทย</option>

                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xl-5">
                                        <label for="" class="form-label">เลขบัตรประชาชน <span
                                                class="text-danger id_card_err _err">*</span></label>
                                        <input name="id_card" type="text" class="form-control" id="">
                                    </div>
                                    <div class="col-md-6 col-xl-5">
                                        <label for="" class="form-label">โทรศัพท์ <span
                                                class="text-danger phone_err _err">*</span></label>
                                        <input name="phone" type="text" class="form-control" id="">
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="" class="form-label">E-mail</label>
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
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="card_address" value=""
                                                    class="form-control card_address" id="">
                                            </div>
                                            <div class="col-md-6 col-xl-3">
                                                <label for="" class="form-label">หมู่ที่ <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="card_moo" class="form-control card_address"
                                                    id="">
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label for="" class="form-label">ซอย <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="card_soi" class="form-control card_address"
                                                    id="">
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label for="" class="form-label">ถนน <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="card_road" class="form-control card_address"
                                                    id="">
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <label for="province" class="form-label">จังหวัด</label>
                                                <label class="form-label text-danger province_err _err"></label>
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
                                                <label class="form-label text-danger district_err _err"></label>
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
                                                        class="text-danger">*</span></label>
                                                <input id="zipcode" name="card_zipcode " type="text"
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
                                        <input class="form-check-input" id="same_address" type="checkbox" value=""
                                            id="flexCheckDefault">
                                        <label class="form-check-label" for="same_address">
                                            ใช้ที่อยู่เดียวกันบัตรประชาชน
                                        </label>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6 col-xl-5">
                                        <label for="" class="form-label">ที่อยู่ <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control address_same_card" id="">
                                    </div>
                                    <div class="col-md-6 col-xl-3">
                                        <label for="" class="form-label">หมู่ที่ <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control address_same_card" id="">
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="" class="form-label">ซอย <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control address_same_card" id="">
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="" class="form-label">ถนน <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control address_same_card" id="">
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="province" class="form-label">จังหวัด</label>
                                        <label class="form-label text-danger province_err _err"></label>
                                        <select class="form-select address_same_card" name="province" id="same_province">
                                            <option value="">--กรุณาเลือก--</option>
                                            @foreach ($province as $item)
                                                <option value="{{ $item->province_id }}">
                                                    {{ $item->province_name }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-md-6 col-xl-4">

                                        <label for="district" class="form-label">อำเภอ/เขต</label>
                                        <label class="form-label text-danger district_err _err"></label>
                                        <select class="form-select address_same_card" name="district" id="same_district">
                                            <option value="">--กรุณาเลือก--</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="tambon" class="form-label">ตำบล</label>
                                        <label class="form-label text-danger tambon_err _err"></label>
                                        <select class="form-select address_same_card" name="tambon" id="same_tambon">
                                            <option value="">--กรุณาเลือก--</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="" class="form-label">รหัสไปรษณีย์ <span
                                                class="text-danger">*</span></label>
                                        <input id="same_zipcode" type="text" class="form-control address_same_card"
                                            id="">
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
                                    <div class="col-md-12">
                                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                                            <i class='bx bxs-error me-2'></i>
                                            <div>
                                                สมาชิกจะใส่หรือไม่ใส่ก็ได้ หากไม่ได้ใส่จะมีผลกับการโอนเงินให้สมาชิก
                                            </div>
                                        </div>
                                        {{-- <div class="file-upload">
                                            <label for="upload" class="file-upload__label"><i class='bx bx-upload'></i>
                                                อัพโหลดเอกสาร</label>
                                            <input id="upload" class="file-upload__input" type="file"
                                                name="file-upload">
                                        </div> --}}
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="" class="form-label">ธนาคาร</label>
                                        <select class="form-select" id="">
                                            <option>เลือกธนาคาร</option>
                                            <option></option>
                                            <option></option>
                                            <option></option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="" class="form-label">สาขา</label>
                                        <input type="text" class="form-control" id="">
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="" class="form-label">เลขที่บัญชี <span
                                                class="text-danger small">* (ใส่เฉพาะตัวเลขเท่านั้น)</span></label>
                                        <input type="text" class="form-control" id="">
                                    </div>
                                    <div class="col-md-12 col-xl-12 mb-3">
                                        <label for="" class="form-label">ชื่อบัญชี</label>
                                        <input type="text" class="form-control" id="">
                                    </div>
                                </div>
                                <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">ผู้รีบผลประโยชน์</div>
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
                                        <label for="" class="form-label">ชื่อ</label>
                                        <input type="text" class="form-control" id="">
                                    </div>
                                    <div class="col-md-6 col-xl-4">
                                        <label for="" class="form-label">นามสกุล</label>
                                        <input type="text" class="form-control" id="">
                                    </div>
                                    <div class="col-md-6 col-xl-4 mb-3">
                                        <label for="" class="form-label">เกี่ยวข้องเป็น</label>
                                        <input type="text" class="form-control" id="">
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-12 text-center">
                                        <hr>
                                        <button type="submit" class="btn btn-success rounded-pill">บันทึกข้อมูล</button>
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
    <script>
        $('#linkMenuTop .nav-item').eq(0).addClass('active');
    </script>



    <script>
        function printErrorMsg(msg) {
            console.log(msg);
            $('._err').text('');
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(`*${value}*`);
            });
        }


        //BEGIN form_register
        $('#form_register').submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('store_register') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if ($.isEmptyObject(data.error) || data.status == "success") {
                        console.log(data.status);
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });
        //END form_register
    </script>

    {{-- BEGIN  Preview image --}}
    <script>
        file_card.onchange = evt => {
            const [file] = file_card.files
            if (file) {
                img_card.src = URL.createObjectURL(file)
            }
        }
    </script>

    {{-- END  Preview image --}}


    {{-- BEGIN Action same_address --}}
    <script>
        $('#same_address').click(function() {

            if (this.checked) {
                $('.card_address').each(function(key) {
                    console.log($(this));
                    $('.address_same_card').eq(key).val($(this).val()).attr('disabled', true);
                });
            } else {
                $('.address_same_card').val('').attr('disabled', false);
            }
        });
    </script>
    {{-- END Action same_address --}}

    {{-- --------------------- Address  --------------------- --}}
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
                    $("#same_zipcode").val(data.zipcode);
                },
                error: function() {}
            })
        });
        //  END tambon
    </script>
    {{-- --------------------- Address  --------------------- --}}
@endsection
