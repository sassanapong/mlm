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
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
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
                                                                    <small class="text-danger doc_issue_err _err">*
                                                                        รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</small>
                                                                </label>
                                                                <input class="form-control" type="file"
                                                                    id="attachment" multiple name="doc_issue[]">
                                                            </div>
                                                        </div>
                                                        {{-- Input File Image --}}
                                                        <div id="files-area" class="mt-2 files-area">
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
                                <form id="form_promotion_help" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card card-box borderR10 mb-2 mb-md-0">
                                        <div class="card-body">

                                            <div class="row g-3">
                                                <div class="col-md-12 mb-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input radio_promotion" type="radio"
                                                            name="cPromotion" id="option1P" value="คลอดบุตร"
                                                            data-type="birth" checked>
                                                        <label class="form-check-label" for="option1P">คลอดบุตร</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input radio_promotion" type="radio"
                                                            name="cPromotion" id="option2P" value="นอนโรงพยาบาล"
                                                            data-type="hospital">
                                                        <label class="form-check-label"
                                                            for="option2P">นอนโรงพยาบาล</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input radio_promotion" type="radio"
                                                            name="cPromotion" id="option3P" value="เสียชีวิต"
                                                            data-type="death">
                                                        <label class="form-check-label" for="option3P">เสียชีวิต</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input radio_promotion" type="radio"
                                                            name="cPromotion" id="option4P" value="เพลิงไหม้"
                                                            data-type="fire">
                                                        <label class="form-check-label" for="option4P">เพลิงไหม้</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card p-3">
                                                <!--คลอดบุตร-->
                                                <div class="row g-3">
                                                    <div class="col-md-4 col-xl-3">
                                                        <label for="" class="form-label">รหัสสมาชิก <small
                                                                class="text-danger username_err _err">*</small></label>
                                                        <input type="text" name="username" class="form-control"
                                                            id="">
                                                    </div>
                                                    <div class="col-md-4 col-xl-3">
                                                        <label for="" class="form-label">ชื่อ
                                                            <small class="text-danger name_err _err">*</small></label>
                                                        <input type="text" name="name" class="form-control"
                                                            id="">
                                                    </div>
                                                    <div class="col-md-4 col-xl-3">
                                                        <label for="" class="form-label">นามสกุล
                                                            <small class="text-danger last_name_err _err">*</small></label>
                                                        <input type="text" name="last_name" class="form-control"
                                                            id="">
                                                    </div>
                                                    <div class="col-md-4 col-xl-3">
                                                        <label for="" class="form-label">เบอร์มือถือ <span
                                                                class="text-danger phone_err _err">*</span></label>
                                                        <input type="text" name="phone" class="form-control"
                                                            id="">
                                                    </div>
                                                    <div class="col-md-4 col-xl-3">
                                                        <label for="" class="form-label">ตำแหน่ง</label>
                                                        <input type="text" name="role" class="form-control"
                                                            id="">
                                                    </div>
                                                    <div class="col-9 col-md-6 col-xl-3">
                                                        <label for=""
                                                            class="form-label">รักษาสภาพสมาชิกมาแล้ว</label>
                                                        <input type="text" name="day_member" class="form-control"
                                                            id="">
                                                        <span id="maintain_member"
                                                            class="text-danger small">**ต้องรักษาสภาพสมาชิกมาแล้วไม่น้อยกว่า
                                                            180 วัน</span>
                                                    </div>

                                                    <div class="col-md-5 col-xl-5">
                                                        <label for="" class="form-label">วันที่ใช้สิทธิ์ <span
                                                                class="text-danger exercise_date_err _err">*</span></label>
                                                        <input type="date" name="exercise_date" class="form-control"
                                                            id="">
                                                    </div>
                                                </div>
                                                <div class="row g-3" id="birth">
                                                    <div class="col-md-12">
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">ใบสูติบัตร <span
                                                                        class="text-danger ">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="birth_certificate"
                                                                    data-type_file="birth_certificate"
                                                                    data-type_name="ใบสูติบัตร"
                                                                    name="doc_promotion[birth_certificate][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_birth_certificate" class="mx-auto">
                                                                    <div id="files-names_birth_certificate"
                                                                        class="row"></div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}

                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">บัตรประชาชนของบิดาหรือมารดา <span
                                                                        class="text-danger ">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="card_father_mother"
                                                                    data-type_file="card_father_mother"
                                                                    data-type_name="บัตรประชาชนของบิดาหรือมารดา"
                                                                    name="doc_promotion[card_father_mother][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_card_father_mother" class="mx-auto">
                                                                    <div id="files-names_card_father_mother"
                                                                        class="row"></div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">ใบเสร็จรับเงินจากสถานที่ทำคลอด <span
                                                                        class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="maternity_receipt"
                                                                    data-type_file="maternity_receipt"
                                                                    data-type_name="ใบเสร็จรับเงินจากสถานที่ทำคลอด"
                                                                    name="doc_promotion[maternity_receipt][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_maternity_receipt" class="mx-auto">
                                                                    <div id="files-names_maternity_receipt"
                                                                        class="row"></div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">บัญชีธนาคาร <span
                                                                        class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="bank_account_maternity"
                                                                    data-type_file="bank_account_maternity"
                                                                    data-type_name="บัญชีธนาคาร"
                                                                    name="doc_promotion[bank_account_maternity][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_bank_account_maternity"
                                                                    class="mx-auto">
                                                                    <div id="files-names_bank_account_maternity"
                                                                        class="row"></div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
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
                                                                        class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="medical_certificate"
                                                                    data-type_file="medical_certificate"
                                                                    data-type_name="ใบรับรองและความเห็นของแพทย์"
                                                                    name="doc_promotion[medical_certificate][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_medical_certificate" class="mx-auto">
                                                                    <div id="files-names_medical_certificate"
                                                                        class="row"></div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">บัตรประชาชน <span
                                                                        class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="card_hospital"
                                                                    data-type_file="card_hospital"
                                                                    data-type_name="บัตรประชาชน"
                                                                    name="doc_promotion[card_hospital][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_card_hospital" class="mx-auto">
                                                                    <div id="files-names_card_hospital" class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">ใบเสร็จรับเงินจากโรงพยาบาล <span
                                                                        class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="receipt_hospital"
                                                                    data-type_file="receipt_hospital"
                                                                    data-type_name="ใบเสร็จรับเงินจากโรงพยาบาล"
                                                                    name="doc_promotion[receipt_hospital][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_receipt_hospital" class="mx-auto">
                                                                    <div id="files-names_receipt_hospital" class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">บัญชีธนาคาร <span
                                                                        class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="bank_account_hospital"
                                                                    data-type_file="bank_account_hospital"
                                                                    data-type_name="บัญชีธนาคาร"
                                                                    name="doc_promotion[bank_account_hospital][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_bank_account_hospital" class="mx-auto">
                                                                    <div id="files-names_bank_account_hospital"
                                                                        class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                        </div>

                                                    </div>
                                                </div>
                                                <!--เสียชีวิต-->
                                                <div class="row g-3" id="death">
                                                    <div class="col-md-12">
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">ใบมรณะบัตร <span
                                                                        class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="death_certificate"
                                                                    data-type_file="death_certificate"
                                                                    data-type_name="ใบมรณะบัตร"
                                                                    name="doc_promotion[death_certificate][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_death_certificate" class="mx-auto">
                                                                    <div id="files-names_death_certificate"
                                                                        class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">บัตรประชาชนของผู้เสียชีวิต <span
                                                                        class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="death_card" data-type_file="death_card"
                                                                    data-type_name="บัตรประชาชนของผู้เสียชีวิต"
                                                                    name="doc_promotion[death_card][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_death_card" class="mx-auto">
                                                                    <div id="files-names_death_card" class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">ทะเบียนบ้านผู้เสียชีวิต <span
                                                                        class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="death_house_registration"
                                                                    data-type_file="death_house_registration"
                                                                    data-type_name="ทะเบียนบ้านผู้เสียชีวิต"
                                                                    name="doc_promotion[death_house_registration][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_death_house_registration"
                                                                    class="mx-auto">
                                                                    <div id="files-names_death_house_registration"
                                                                        class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">หนังสือรับรองการตาย
                                                                    <span class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="death_book" data-type_file="death_book"
                                                                    data-type_name="หนังสือรับรองการตาย"
                                                                    name="doc_promotion[death_book][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_death_book" class="mx-auto">
                                                                    <div id="files-names_death_book" class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">รายงานบันทึกประจำวันเกี่ยวกับคดี
                                                                    <span class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="case_report" data-type_file="case_report"
                                                                    data-type_name="รายงานบันทึกประจำวันเกี่ยวกับคดี"
                                                                    name="doc_promotion[case_report][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_case_report" class="mx-auto">
                                                                    <div id="files-names_case_report" class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">บัตรประชาชนและบัญชีธนาคารของผู้รับมรดกหรือผู้สืบตน
                                                                    <span class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="death_heir" data-type_file="death_heir"
                                                                    data-type_name="บัตรประชาชนและบัญชีธนาคารของผู้รับมรดกหรือผู้สืบตน"
                                                                    name="doc_promotion[]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_death_heir" class="mx-auto">
                                                                    <div id="files-names_death_heir" class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
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
                                                                    <span class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="damaged_housing"
                                                                    data-type_file="damaged_housing"
                                                                    data-type_name="ภาพถ่ายที่อยู่อาศัยที่เกิดความเสียหาย"
                                                                    name="doc_promotion[damaged_housing][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_damaged_housing" class="mx-auto">
                                                                    <div id="files-names_damaged_housing" class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">หนังสือรับรองจากบรรเทาสาธารณภัย
                                                                    <span class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="disaster_certificate"
                                                                    data-type_file="disaster_certificate"
                                                                    data-type_name="หนังสือรับรองจากบรรเทาสาธารณภัย"
                                                                    name="doc_promotion[disaster_certificate][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_disaster_certificate" class="mx-auto">
                                                                    <div id="files-names_disaster_certificate"
                                                                        class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div class="col-md-12">
                                                                <label for=""
                                                                    class="form-label">หลักฐานลงบันทึกประจำวันที่สถานีตำรวจ
                                                                    <span class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="daily_record"
                                                                    data-type_file="daily_record"
                                                                    data-type_name="หลักฐานลงบันทึกประจำวันที่สถานีตำรวจ"
                                                                    name="doc_promotion[daily_record][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_daily_record" class="mx-auto">
                                                                    <div id="files-names_daily_record" class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">บัตรประชาชน <span
                                                                        class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="card_fire" data-type_file="card_fire"
                                                                    data-type_name="บัตรประชาชน" name="doc_promotion[]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_card_fire" class="mx-auto">
                                                                    <div id="files-names_card_fire" class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">สำเนาทะเบียนบ้าน
                                                                    <span class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="house_registration_fire"
                                                                    data-type_file="house_registration_fire"
                                                                    data-type_name="สำเนาทะเบียนบ้าน"
                                                                    name="doc_promotion[house_registration_fire][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_house_registration_fire"
                                                                    class="mx-auto">
                                                                    <div id="files-names_house_registration_fire"
                                                                        class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">บัญชีธนาคาร <span
                                                                        class="text-danger">* รองรับไฟล์ .jpg, .jpg, png
                                                                        เท่านั้น</span></label>
                                                                <input class="form-control attachment" type="file"
                                                                    multiple id="bank_account_fire"
                                                                    data-type_file="bank_account_fire"
                                                                    data-type_name="บัญชีธนาคาร"
                                                                    name="doc_promotion[bank_account_fire][]">
                                                            </div>
                                                            {{-- Input File Image --}}
                                                            <div id="files-area" class="mt-2 files-area">
                                                                <div id="filesList_bank_account_fire" class="mx-auto">
                                                                    <div id="files-names_bank_account_fire"
                                                                        class="row">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Input File Image --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="" class="form-label">รายละเอียด <span
                                                                class="text-danger info_promotion_err _err">*</span></label>
                                                        <textarea rows="5" name="info_promotion" class="form-control" id=""></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-12 text-center">
                                                    <hr>
                                                    <button id="sumbmit_test" type="submit"
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
        <script src="{{ asset('frontend/js/inputfile_multi.js') }}"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                    let class_name = key.split(".").join("_");
                    console.log(class_name);
                    $('.' + class_name + '_err').text(`*${value}*`);
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

                            Swal.fire({
                                icon: 'warning',
                                title: 'กรุณากรอกข้อมูให้ครบถ้วน',
                            })
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




        {{-- BEGIN form_promotion_help --}}
        <script>
            // BEGIN รับ data type มาเช็คว่าอยู่ที่ tap ไหนอยู๋ เพื่อเช็ค Input file
            var type_radio = "birth";
            $('.radio_promotion').click(function() {
                type_radio = $(this).data('type');
            });
            // BEN รับ data type มาเช็คว่าอยู่ที่ tap ไหนอยู๋ เพื่อเช็ค Input file




            $('#form_promotion_help').submit(function(e) {

                var formData = new FormData($(this)[0]);
                e.preventDefault();
                // BEGIN หา input ที่อยู่ใน id ของ tap นั้นๆ

                $(`#${type_radio}`).find('input[type="file"].attachment').each(function() {

                    if ($(this).val() == '') {
                        $(`#${type_radio}`).find('span').each(function() {
                            if ($(this).text() == '*') {
                                $(this).text('กรุณาแนบเอกสาร');
                            }
                        })
                    }
                });
                // END หา input ที่อยู่ใน id ของ tap นั้นๆ

                $.ajax({
                    url: '{{ route('store_promotion_help') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if ($.isEmptyObject(data.error) || data.status == "success") {
                            Swal.fire({
                                title: 'ทำรายการสำเร็จ',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'ปิด',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "/mlm/home";
                                }
                            })
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'กรุณากรอกข้อมูให้ครบถ้วน',
                            })
                            printErrorMsg(data.error);
                        }
                    }
                });
            });
        </script>
        {{-- END form_promotion_help --}}
    @endsection
