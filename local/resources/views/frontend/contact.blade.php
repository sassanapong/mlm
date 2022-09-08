    <title>บริษัท มารวยด้วยกัน จำกัด</title>

    @extends('layouts.frontend.app')
    <link rel="stylesheet" href="{{ asset('frontend/css/inputfile.css') }}">


    @section('conten')
        <div class="bg-whiteLight page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                                <li class="breadcrumb-item active" aria-current="page">ติดต่อบริษัท</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-1-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-1" type="button" role="tab" aria-controls="pills-1"
                                    aria-selected="true">รับเรื่อง - ปัญหาต่างๆ</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-2-tab" data-bs-toggle="pill" data-bs-target="#pills-2"
                                    type="button" role="tab" aria-controls="pills-2"
                                    aria-selected="false">โปรโมชั่นเพื่อนช่วยเพื่อน</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-1" role="tabpanel"
                                aria-labelledby="pills-1-tab">
                                <form id="form_report_issue" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card card-box borderR10 mb-2 mb-md-0">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-12 col-xl-12 mb-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input radio" type="radio" name="cReport"
                                                            id="option1R" value="การเงิน" checked>
                                                        <label class="form-check-label" for="option1R">การเงิน</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input radio" type="radio" name="cReport"
                                                            id="option2R" value="ปัญหาระบบ">
                                                        <label class="form-check-label" for="option2R">ปัญหาระบบ</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input radio" type="radio" name="cReport"
                                                            id="option3R" value="แก้ไขข้อมูลพื้นฐาน">
                                                        <label class="form-check-label"
                                                            for="option3R">แก้ไขข้อมูลพื้นฐาน</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input radio" type="radio" name="cReport"
                                                            id="option4R" value="สินค้า+การจัดส่ง">
                                                        <label class="form-check-label"
                                                            for="option4R">สินค้า+การจัดส่ง</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input radio" type="radio" name="cReport"
                                                            id="radio_other" value="อื่นๆ">
                                                        <label class="form-check-label" for="radio_other">อื่นๆ</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <small class="text-danger text_other_err _err"></small>
                                                        <input class="form-control" type="text" id="text_other"
                                                            name="text_other" id="">
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <div class="card p-3">
                                                        <div class="row g-3">
                                                            <div class="col-md-6 col-xl-3">
                                                                <label for="" class="form-label">รหัสสมาชิก <small
                                                                        class="text-danger username_err _err">*</small></label>
                                                                <input type="text" class="form-control" id=""
                                                                    name="username"
                                                                    value="{{ Auth::guard('c_user')->user()->user_name }}">
                                                            </div>
                                                            <div class="col-md-6 col-xl-3">
                                                                <label for="" class="form-label">ชื่อ<small
                                                                        class="text-danger name_err _err">*</small></label>
                                                                <input type="text" class="form-control" id=""
                                                                    name="name"
                                                                    value="{{ Auth::guard('c_user')->user()->name }}">
                                                            </div>
                                                            <div class="col-md-6 col-xl-3">
                                                                <label for="" class="form-label">นามสกุล <small
                                                                        class="text-danger last_name_err _err">*</small></label>
                                                                <input type="text" class="form-control" id=""
                                                                    name="last_name"
                                                                    value="{{ Auth::guard('c_user')->user()->last_name }}">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">รายละเอียด <small
                                                                        class="text-danger info_issue_err _err">*</small></label>
                                                                <textarea rows="5" class="form-control" id="" name="info_issue"></textarea>
                                                            </div>
                                                            <div class="col-md-12">

                                                                <label for="" class="form-label">อัพโหลดเอกสาร
                                                                    <small class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</small>
                                                                </label>
                                                                <input class="form-control" type="file"
                                                                    id="attachment" multiple name="doc_issue[]">
                                                            </div>
                                                        </div>
                                                        {{-- Input File Image --}}

                                                        <div id="files-area" class="mt-2">
                                                            <div id="filesList" class="mx-auto">
                                                                <div id="files-names" class="row"></div>
                                                            </div>
                                                        </div>
                                                        <div id="uploadPreview" class="col-3 "></div>
                                                        {{-- Input File Image --}}
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-12 text-center">
                                                    <hr>
                                                    <button type="submit"
                                                        class="btn btn-success rounded-pill me-2">ส่งเรื่อง</button>
                                                    <button type="button"
                                                        class="btn btn-danger rounded-pill me-2">ยกเลิก</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="pills-2" role="tabpanel" aria-labelledby="pills-2-tab">
                                <form>
                                    <div class="card card-box borderR10 mb-2 mb-md-0">
                                        <div class="card-body">

                                            <div class="row g-3">
                                                <div class="col-md-12 mb-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cPromotion"
                                                            id="option1P" value="option1" checked>
                                                        <label class="form-check-label" for="option1P">คลอดบุตร</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cPromotion"
                                                            id="option2P" value="option2">
                                                        <label class="form-check-label"
                                                            for="option2P">นอนโรงพยาบาล</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cPromotion"
                                                            id="option3P" value="option3">
                                                        <label class="form-check-label" for="option3P">เสียชีวิต</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cPromotion"
                                                            id="option4P" value="option4">
                                                        <label class="form-check-label" for="option4P">เพลิงไหม้</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card p-3">
                                                <!--คลอดบุตร-->
                                                <div class="row g-3">
                                                    <div class="col-md-4 col-xl-3">
                                                        <label for="" class="form-label">รหัสสมาชิก <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="">
                                                    </div>
                                                    <div class="col-md-4 col-xl-3">
                                                        <label for="" class="form-label">ชื่อ
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="">
                                                    </div>
                                                    <div class="col-md-4 col-xl-3">
                                                        <label for="" class="form-label">นามสกุล
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="">
                                                    </div>
                                                    <div class="col-md-4 col-xl-3">
                                                        <label for="" class="form-label">เบอร์มือถือ <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="">
                                                    </div>
                                                    <div class="col-md-4 col-xl-3">
                                                        <label for="" class="form-label">ตำแหน่ง</label>
                                                        <input type="text" class="form-control" id="">
                                                    </div>
                                                    <div class="col-9 col-md-6 col-xl-3">
                                                        <label for=""
                                                            class="form-label">รักษาสภาพสมาชิกมาแล้ว</label>
                                                        <input type="text" class="form-control" id="">
                                                        <span id="maintain_member"
                                                            class="text-danger small">**ต้องรักษาสภาพสมาชิกมาแล้วไม่น้อยกว่า
                                                            180 วัน</span>
                                                    </div>
                                                    <div class="col-3 col-md-1 col-xl-1">
                                                        <label for="" class="form-label d-block">&nbsp;</label>
                                                        <input type="text" readonly class="form-control-plaintext"
                                                            id="" value="วัน">
                                                    </div>
                                                    <div class="col-md-5 col-xl-5">
                                                        <label for="" class="form-label">วันที่ใช้สิทธิ์</label>
                                                        <input type="date" class="form-control" id="">
                                                    </div>
                                                </div>
                                                <div class="row g-3" id="birth">
                                                    <div class="col-md-12">
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">ใบสูติบัตร <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">บัตรประชาชนของบิดาหรือมารดา <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">ใบเสร็จรับเงินจากสถานที่ทำคลอด <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">บัญชีธนาคาร <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                                <!--นอนโรงพยาบาล-->
                                                <div class="row g-3" id="hospital">
                                                    <div class="col-md-12">
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">ใบรับรองและความเห็นของแพทย์ <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">บัตรประชาชน <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">ใบเสร็จรับเงินจากโรงพยาบาล <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">บัญชีธนาคาร <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!--เสียชีวิต-->
                                                <div class="row g-3" id="death">
                                                    <div class="col-md-12">
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">ใบมรณะบัตร <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">บัตรประชาชนของผู้เสียชีวิต <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">ทะเบียนบ้านผู้เสียชีวิต <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">หนังสือรับรองการตาย
                                                                    <span class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">รายงานบันทึกประจำวันเกี่ยวกับคดี
                                                                    <span class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">บัตรประชาชนและบัญชีธนาคารของผู้รับมรดกหรือผู้สืบตน
                                                                    <span class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!--เพลิงไหม้-->
                                                <div class="row g-3" id="fire">
                                                    <div class="col-md-12">
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">ภาพถ่ายที่อยู่อาศัยที่เกิดความเสียหาย
                                                                    <span class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">หนังสือรับรองจากบรรเทาสาธารณภัย
                                                                    <span class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">หลักฐานลงบันทึกประจำวันที่สถานีตำรวจ
                                                                    <span class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">บัตรประชาชน <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">สำเนาทะเบียนบ้าน
                                                                    <span class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">บัญชีธนาคาร <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="file"
                                                                    id="formFile">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="" class="form-label">รายละเอียด <span
                                                                class="text-danger">*</span></label>
                                                        <textarea rows="5" class="form-control" id=""></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-12 text-center">
                                                    <hr>
                                                    <button type="button"
                                                        class="btn btn-success rounded-pill me-2">ส่งเรื่อง</button>
                                                    <button type="button"
                                                        class="btn btn-danger rounded-pill me-2">ยกเลิก</button>
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
        <!-- Modal -->
        <div class="modal fade" id="successRModal" tabindex="-1" aria-labelledby="successRModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content borderR25">
                    <div class="modal-header text-center justify-content-center">
                        <h5 class="modal-title" id="successRModalLabel">รับเรื่อง-ปัญหาต่างๆ</h5>
                    </div>
                    <div class="modal-body text-center">
                        <i class='far fa-check-circle text-success fa-5x mb-3'></i>
                        <p><b>รหัสสมาชิก: </b> <span id="modal_username"></span> <b>ชื่อ-นามสกุล: </b>
                            <span id="modal_name_lastname">................</span>
                        </p>
                        <p><b>ส่งเรื่อง: </b> <span id="modal_head_info">..............</span></p>
                        <h5 class="text-danger">*** ทางบริษัทจำดำเนินการแก้ไขภายใน ... วัน ***</h5>
                    </div>
                    <div class="modal-footer justify-content-center border-0">
                        <button type="button" class="btn btn-p1 rounded-pill px-4" onclick="dismiss_modal()"
                            data-bs-dismiss="modal">ตกลง</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
        {{-- inoutfile --}}
        <script src="{{ asset('frontend/js/inputfile.js') }}"></script>

        <script>
            $('#linkMenuTop .nav-item').eq(4).addClass('active');
        </script>
        <script type="text/javascript">
            $('#hospital, #death, #fire').addClass('d-none')
            $(document).ready(function() {
                $('#option1P').click(function() {
                    $('#birth').addClass('d-block').removeClass('d-none');
                    $('#hospital').addClass('d-none').removeClass('d-block');
                    $('#death').addClass('d-none').removeClass('d-block');
                    $('#fire').addClass('d-none').removeClass('d-block');
                    $('#maintain_member').text('**ต้องรักษาสภาพสมาชิกมาแล้วไม่น้อยกว่า 180 วัน');
                });
                $('#option2P').click(function() {
                    $('#birth').addClass('d-none').removeClass('d-block');
                    $('#hospital').addClass('d-block').removeClass('d-none');
                    $('#death').addClass('d-none').removeClass('d-block');
                    $('#fire').addClass('d-none').removeClass('d-block');
                    $('#maintain_member').text('**ต้องรักษาสภาพสมาชิกมาแล้วไม่น้อยกว่า 300 วัน');
                });
                $('#option3P').click(function() {
                    $('#birth').addClass('d-none').removeClass('d-block');
                    $('#hospital').addClass('d-none').removeClass('d-block');
                    $('#death').addClass('d-block').removeClass('d-none');
                    $('#fire').addClass('d-none').removeClass('d-block');
                    $('#maintain_member').text('**ต้องรักษาสภาพสมาชิกมาแล้วไม่น้อยกว่า 300 วัน');
                });
                $('#option4P').click(function() {
                    $('#birth').addClass('d-none').removeClass('d-block');
                    $('#hospital').addClass('d-none').removeClass('d-block');
                    $('#death').addClass('d-none').removeClass('d-block');
                    $('#fire').addClass('d-block').removeClass('d-none');
                    $('#maintain_member').text('**ต้องรักษาสภาพสมาชิกมาแล้วไม่น้อยกว่า 300 วัน');
                });
            });
        </script>
        <script>
            $('.page-content').css({
                'min-height': $(window).height() - $('.navbar').height()
            });
        </script>



        {{-- BEGIN ถ้ายังไม่เลือกหัวข้อเป็น อื่นๆ จะยังไม่ขึ้น input text --}}
        <script>
            $(document).ready(function() {

                $('#text_other').hide();

                $('.radio').change(function() {
                    let radio_val = $(this).val();

                    if (radio_val == "อื่นๆ") {
                        $('#text_other').show();
                    } else {
                        $('#text_other').hide();
                    }
                });
            });
        </script>
        {{-- END  ถ้ายังไม่เลือกหัวข้อเป็น อื่นๆ จะยังไม่ขึ้น input text --}}


        {{-- BEGIN printErrorMsg --}}
        <script>
            function printErrorMsg(msg) {

                $('._err').text('');
                $.each(msg, function(key, value) {
                    $('.' + key + '_err').text(`*${value}*`);
                });
            }
        </script>
        {{-- END printErrorMsg --}}

        {{-- BEGIN form_change_password --}}
        <script>
            $('#form_report_issue').submit(function(e) {
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: '{{ route('store_report_issue') }}',
                    method: 'POST',
                    data: formData,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if ($.isEmptyObject(data.error) || data.status == "success") {


                            $('#modal_username').text(data.data.username);
                            $('#modal_name_lastname').text(data.data.name + " " + data.data.last_name);
                            $('#modal_head_info').text(data.data.head_info);

                            var myModal = new bootstrap.Modal(document.getElementById('successRModal'));
                            myModal.show();
                        } else {
                            printErrorMsg(data.error);
                        }
                    }
                });
            });
        </script>
        {{-- END form_change_password --}}


        {{-- BEGIN กดปิด Modal ตอนทำรายการสำเร็จ --}}
        <script>
            function dismiss_modal() {
                window.location.href = "/mlm/home";
            }
        </script>
        {{-- END กดปิด Modal ตอนทำรายการสำเร็จ --}}
    @endsection
